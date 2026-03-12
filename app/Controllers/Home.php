<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\CategoryModel;
use App\Models\BannersModel;

class Home extends BaseController
{
    /**
     * హోమ్ పేజీ - వార్తలు, బ్రేకింగ్ న్యూస్ మరియు ప్రకటనలను లోడ్ చేస్తుంది
     */
    public function index()
    {
        $newsModel = new NewsModel();
        $categoryModel = new CategoryModel();
        $bannerModel = new BannersModel();
        
        $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;

        // 1. అన్ని యాక్టివ్ మెయిన్ కేటగిరీలను తెచ్చుకోవడం
        $data['categories'] = $categoryModel->groupStart()
                                            ->where('type', 'main')
                                            ->orWhere('type', 'state')
                                        ->groupEnd()
                                        ->where('IsActice', 1) 
                                        ->findAll();

        // 2. బ్రేకింగ్ న్యూస్ (Ticker కోసం)
        $data['breaking_news'] = $newsModel->where(['is_breaking_news' => 1, 'status' => 1])
                                           ->orderBy('published_at', 'DESC')
                                           ->limit(10)
                                           ->findAll();

        // 3. తాజా వార్తలు (Latest 15)
        $data['latest_news'] = $newsModel->where('status', 1) 
                                         ->orderBy('published_at', 'DESC')
                                         ->findAll(15);

        // 4. ట్రెండింగ్ వార్తలు (Top 7)
        $data['trending_news'] = $newsModel->where('status', 1)
                                           ->orderBy('view_count', 'DESC')
                                           ->findAll(7);

        // 5. ప్రకటనల డేటా (Enhanced Banners Logic)
        // Top Banner: తాజాది ఒకటి
        $data['top_banner']    = $bannerModel->where(['position' => 'top_banner', 'status' => 1])
                                             ->orderBy('id', 'DESC')
                                             ->first();

        // Slide Ads: స్లైడర్ లో వార్తల మధ్య వచ్చేవి (Randomize for variety)
        $data['slide_ads']     = $bannerModel->where(['position' => 'slide_ad', 'status' => 1])
                                             ->orderBy('id', 'RANDOM') // ప్రతిసారి కొత్త అడ్స్ కనిపించడానికి
                                             ->findAll();

        // Sidebar Ads: సైడ్ బార్ లో వరుసగా వచ్చేవి
        $data['sidebar_ads']   = $bannerModel->where(['position' => 'sidebar_ad', 'status' => 1])
                                             ->orderBy('id', 'DESC')
                                             ->findAll();

        // Category Ads: సెక్షన్స్ మధ్యలో వచ్చేవి
        $data['category_ads']  = $bannerModel->where(['position' => 'category_ad', 'status' => 1])
                                             ->orderBy('id', 'DESC')
                                             ->findAll();

        // 6. యూట్యూబ్ వీడియోల డేటా
        $data['youtube_videos'] = $this->getYouTubeVideos();

        // 7. వివిధ కేటగిరీల వార్తలు (Dynamic)
        $data['politics_news'] = $newsModel->where(['category_id' => 1, 'status' => 1])->orderBy('published_at', 'DESC')->findAll(4);
        $data['cinema_news']   = $newsModel->where(['category_id' => 2, 'status' => 1])->orderBy('published_at', 'DESC')->findAll(4);
        $data['sports_news']   = $newsModel->where(['category_id' => 3, 'status' => 1])->orderBy('published_at', 'DESC')->findAll(4);

        return view('frontend/home', $data);
    }

    /**
     * యూట్యూబ్ RSS ఫీడ్ మెథడ్
     */
    private function getYouTubeVideos() {
        $channelId = 'UCti6SpfXgaHXbmNHKyX2GJA';
        $rssUrl = "https://www.youtube.com/feeds/videos.xml?channel_id={$channelId}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $rssUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);

        $videos = [];
        if ($response) {
            $xml = @simplexml_load_string($response);
            if ($xml) {
                $ns = $xml->getNamespaces(true);
                foreach ($xml->entry as $entry) {
                    $yt = $entry->children($ns['yt']);
                    $video_id = (string)$yt->videoId;
                    if ($video_id) {
                        $videos[] = [
                            'title'     => (string)$entry->title,
                            'link'      => "https://www.youtube.com/watch?v={$video_id}",
                            'video_id'  => $video_id,
                            'thumbnail' => "https://img.youtube.com/vi/{$video_id}/mqdefault.jpg"
                        ];
                    }
                    if (count($videos) >= 8) break;
                }
            }
        }
        return $videos;
    }

    /**
     * వార్త పూర్తి వివరాల పేజీ (Enhanced with Sidebar Ads)
     */
    public function view($slug)
{
    $newsModel = new NewsModel();
    $bannerModel = new BannersModel();
    $categoryModel = new \App\Models\CategoryModel();

    $news = $newsModel->select('
        news.*, 
        categories.name as category_name, 
        categories.type as category_type, 
        sub_cats.name as sub_category_name,
        districts.name as district_name,
        mandals.name as mandal_name,
        villages.name as village_name,
        users.display_name as reporter_name,
        users.profile_pic as reporter_image,
        users.area_coverage as reporter_area
    ')
    ->join('categories', 'categories.id = news.category_id', 'left')
    ->join('categories as sub_cats', 'sub_cats.id = news.sub_category_id', 'left')
    ->join('categories as districts', 'districts.id = news.district_id', 'left')
    ->join('categories as mandals', 'mandals.id = news.mandal_id', 'left')
    ->join('categories as villages', 'villages.id = news.village_id', 'left')
    ->join('users', 'users.id = news.author_id', 'left')
    ->where('news.slug', $slug)
    ->where('news.status', 1) 
    ->first();

    if (!$news) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("వార్త దొరకలేదు.");
    }

    // --- START: Unique View Count Logic ---
    $session = session();
    // ఇప్పటికే చూసిన వార్తల ఐడిలను సెషన్ నుండి పొందండి
    $viewed_news = $session->get('viewed_news_ids') ?? [];

    // ఈ వార్త ఐడి సెషన్‌లో లేకపోతేనే అప్‌డేట్ చేయండి
    if (!in_array($news['id'], $viewed_news)) {
        $newsModel->where('id', $news['id'])
                  ->set('view_count', 'view_count + 1', FALSE)
                  ->update();
        
        // ఈ వార్త ఐడిని సెషన్ అరేకి జోడించండి
        $viewed_news[] = $news['id'];
        $session->set('viewed_news_ids', $viewed_news);
    }
    // --- END: Unique View Count Logic ---

    $data = [
        'news'           => $news,
        'title'          => $news['title'],
        'meta_desc'      => $news['meta_description'], 
        'meta_keys'      => $news['meta_keywords'],    
        'latest_news'    => $newsModel->where('status', 1)->orderBy('published_at', 'DESC')->findAll(6),
        'trending_news'  => $newsModel->where('status', 1)->orderBy('view_count', 'DESC')->findAll(6),
        'related_news'   => $newsModel->where('category_id', $news['category_id'])->where('id !=', $news['id'])->where('status', 1)->orderBy('published_at', 'DESC')->findAll(4),
        'sidebar_ads'    => $bannerModel->where(['position' => 'sidebar_ad', 'status' => 1])->orderBy('id', 'RANDOM')->findAll(3),
        'main_categories'  => $categoryModel->where(['type' => 'main', 'IsActice' => 1])->findAll(),
        'state_categories' => $categoryModel->where(['type' => 'state', 'IsActice' => 1])->findAll(),
        'total_views'      => $newsModel->selectSum('view_count')->first()['view_count'] ?? 0
    ];

    return view('frontend/single_news', $data); 
}

public function videos()
{
    $newsModel = new \App\Models\NewsModel();
    
    // మీరు హోమ్ కంట్రోలర్‌లో ఇప్పటికే ఉన్న getYouTubeVideos() వాడుకోవచ్చు
    $data['youtube_videos'] = $this->getYouTubeVideos();
    $data['title'] = "వీడియో వార్తలు - NToday";
    $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;
    
    // ఒకవేళ కేటగిరీల లిస్ట్ అవసరమైతే
    $categoryModel = new \App\Models\CategoryModel();
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll();

    return view('frontend/videos_view', $data);
}

// app/Controllers/Home.php

public function privacyPolicy()
{
    // కేటగిరీల లిస్ట్ అవసరమైతే (హెడర్ మెనూ కోసం)
    $categoryModel = new \App\Models\CategoryModel();
    $newsModel = new NewsModel();
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll();
    
    $data['title'] = "Privacy Policy - NToday";
    $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;
    
    return view('frontend/privacy_policy', $data);
}

// app/Controllers/Home.php లో యాడ్ చేయండి

public function termsConditions()
{
    $categoryModel = new \App\Models\CategoryModel();
    $newsModel = new NewsModel();
    
    // హెడర్ మెనూ కోసం కేటగిరీలు
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll();
    $data['title'] = "Terms & Conditions - NToday";
    $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;
    
    return view('frontend/terms_conditions', $data);
}

// app/Controllers/Home.php లో యాడ్ చేయండి

public function editorsPolicy()
{
    $categoryModel = new \App\Models\CategoryModel();
    $newsModel = new NewsModel();
    
    // హెడర్ మరియు మెనూ కోసం కేటగిరీలు
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll();
    $data['title'] = "Editorial Policy - NToday";
    $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;
    
    return view('frontend/editors_policy', $data);
}

// app/Controllers/Home.php

public function reportersPolicy()
{
    $categoryModel = new \App\Models\CategoryModel();
    $newsModel = new NewsModel();
    
    // హెడర్ మెనూ కోసం కేటగిరీలు
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll();
    $data['title'] = "Reporters Policy - NToday";
    $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;
    
    return view('frontend/reporters_policy', $data);
}

// app/Controllers/Home.php

public function contactUs()
{
    $categoryModel = new \App\Models\CategoryModel();
    $newsModel = new NewsModel();
    
    // హెడర్ మెనూ కోసం కేటగిరీలు
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll();
    $data['title'] = "Contact Us - NToday";
    $data['total_views'] = $newsModel->selectSum('view_count')->first()['view_count'] ?? 0;
    
    return view('frontend/contact_us', $data);
}

public function live_tv()
{
    $data = [
        'title' => 'లైవ్ టీవీ - NToday News',
        // మీ YouTube Live Video ID ఇక్కడ ఇవ్వండి
        'live_video_id' => 'YOUR_YOUTUBE_LIVE_ID' 
    ];
    return view('frontend/live_tv', $data);
}

public function live_radio()
{
    $data = [
        'title' => 'లైవ్ రేడియో - NToday News',
        // రేడియో స్ట్రీమింగ్ URL ఇక్కడ ఇవ్వండి
        'radio_stream_url' => 'https://your-radio-stream-link.com/live' 
    ];
    return view('frontend/live_radio', $data);
}
}