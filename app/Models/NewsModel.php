<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table      = 'news'; 
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title', 'slug', 'content', 'summary', 'meta_keywords', 'meta_description', 
        'category_id', 'sub_category_id', 'state_id', 'district_id', 'mandal_id', 
        'village_id', 'author_id', 'featured_image', 'status', 'is_breaking_news', 
        'view_count', 'published_at', 'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title'   => 'required|min_length[10]',
        'content' => 'required',
        'slug'    => 'required|is_unique[news.slug,id,{id}]'
    ];

    /**
     * వార్తలతో పాటు కేటగిరీ, లొకేషన్ మరియు రిపోర్టర్ (Author) వివరాలను పొందడానికి
     */
    public function getNewsWithDetails($id = null)
    {
        $builder = $this->db->table($this->table);
        
        // సెలెక్ట్ స్టేట్‌మెంట్ - రిపోర్టర్ వివరాలను ఇక్కడ చేర్చాను
        $builder->select('
            news.*, 
            categories.name as category_name, 
            districts.name as district_name,
            users.display_name as reporter_name, 
            users.profile_pic as reporter_image, 
            users.area_coverage as reporter_area,
            COALESCE(NULLIF(news.published_at, "0000-00-00 00:00:00"), news.created_at) as display_date
        ');
        
        // జాయిన్స్ (Joins)
        $builder->join('categories', 'categories.id = news.category_id', 'left');
        $builder->join('categories as districts', 'districts.id = news.district_id', 'left');
        
        // రిపోర్టర్ వివరాల కోసం users టేబుల్‌తో జాయిన్
        $builder->join('users', 'users.id = news.author_id', 'left');
        
        if ($id) {
            $result = $builder->where('news.id', $id)->get()->getRowArray();
            
            if ($result) {
                // తేదీ సమస్య లేకుండా ప్రచురణ తేదీని సెట్ చేయడం
                $result['published_at'] = $result['display_date'];
            }
            return $result;
        }

        return $builder->orderBy('display_date', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Slug ఆధారంగా వార్తను ఫెచ్ చేయడానికి (Frontend Single News Page కోసం)
     */
    public function getNewsBySlug($slug)
    {
        return $this->db->table($this->table)
            ->select('news.*, categories.name as category_name, users.display_name as reporter_name, users.profile_pic as reporter_image, users.area_coverage')
            ->join('categories', 'categories.id = news.category_id', 'left')
            ->join('users', 'users.id = news.author_id', 'left')
            ->where('news.slug', $slug)
            ->where('news.status', 1)
            ->get()
            ->getRowArray();
    }
}