<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\API\ResponseTrait;

class CategoryController extends BaseController
{
    use ResponseTrait;

    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        // Helpers ని ఇక్కడే లోడ్ చేయడం వల్ల అన్ని మెథడ్స్ లో అందుబాటులో ఉంటాయి
        helper(['url', 'form', 'text']); 
    }

    /**
     * మెయిన్ పేజీని ప్రదర్శించడం
     */
    public function index()
    {
        $data = [
            'title'           => "Manage Categories & Locations",
            // ఎన్‌హెచ్‌మెంట్: మెయిన్ కేటగిరీలలో యాక్టివ్/ఇన్-యాక్టివ్ రెండూ కనిపించాలి
            'main_categories' => $this->categoryModel->where(['type' => 'main'])->orderBy('name', 'ASC')->findAll(),
            // ఎన్‌హెచ్‌మెంట్: 'IsActice' 1 ఫిల్టర్ తొలగించాను, తద్వారా Hidden States కూడా కనిపిస్తాయి
            'states'          => $this->categoryModel->where(['type' => 'state'])->orderBy('name', 'ASC')->findAll()
        ];

        return view('admin/categories/index', $data);
    }

    /**
     * కొత్త డేటాను సేవ్ చేయడం
     */
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'type' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'సరైన వివరాలు నమోదు చేయండి.');
        }

        $name = trim($this->request->getPost('name'));
        $parentId = $this->request->getPost('parent_id') ?: 0;
        
        // ఎన్‌హెచ్‌మెంట్: వ్యూ నుండి వచ్చే డైనమిక్ టైప్ ని తీసుకోవడం
        $type = $this->request->getPost('type');

        // 1. డూప్లికేట్ చెక్
        $exists = $this->categoryModel->where([
            'name'      => $name,
            'parent_id' => $parentId
        ])->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', "ఈ పేరుతో ఇప్పటికే డేటా ఉంది!");
        }

        // 2. డేటా ప్రిపరేషన్
        $insertData = [
            'parent_id' => $parentId,
            'name'      => $name,
            'slug'      => url_title(convert_accented_characters($name), '-', true),
            'type'      => $type, // వ్యూ లోని JS ద్వారా పంపిన ఆటో-టైప్ ఇక్కడ సేవ్ అవుతుంది
            'status'    => 1,
            'IsActice'  => 1 
        ];

        try {
            if ($this->categoryModel->insert($insertData)) {
                // ఎన్‌హెచ్‌మెంట్: మెయిన్ కేటగిరీ అయితే parent_id = id చేయడం (మీ పాత రిక్వెస్ట్ ప్రకారం)
                if ($type === 'main' && $parentId == 0) {
                    $newId = $this->categoryModel->insertID();
                    $this->categoryModel->update($newId, ['parent_id' => $newId]);
                }
                return redirect()->to('admin/categories')->with('success', 'విజయవంతంగా జోడించబడింది!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'డేటాబేస్ లోపం: ' . $e->getMessage());
        }

        return redirect()->back()->with('error', 'సేవ్ చేయడంలో లోపం తలెత్తింది.');
    }

    /**
     * డేటాను అప్‌డేట్ చేయడం (POST)
     */
    public function update()
    {
        $id = $this->request->getPost('id');
        $name = trim($this->request->getPost('name'));

        if (!$id || !$name) {
            return redirect()->back()->with('error', 'తప్పుడు డేటా పంపబడింది.');
        }

        $data = [
            'name' => $name,
            'slug' => url_title(convert_accented_characters($name), '-', true)
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('admin/categories')->with('success', 'సవరణలు విజయవంతంగా సేవ్ చేయబడ్డాయి!');
        }

        return redirect()->back()->with('error', 'అప్‌డేట్ చేయడం విఫలమైంది.');
    }

    /**
     * AJAX: డ్రిల్ డౌన్ కోసం చిల్డ్రన్ వివరాలు (GET)
     */
    public function getLocationDetails($id = null)
    {
        if ($id === null || $id === "0") {
            return $this->response->setJSON(['children' => []]);
        }

        // ఎన్‌హెచ్‌మెంట్: మేనేజ్మెంట్ పేజీలో Hidden ఐటమ్స్ కూడా కనిపించాలి కాబట్టి 'IsActice' 1 ఫిల్టర్ తొలగించాను
        $data = $this->categoryModel->where(['parent_id' => $id])
                                    ->orderBy('name', 'ASC')
                                    ->findAll();

        return $this->response->setJSON([
            'status'   => true,
            'children' => $data ?: []
        ]);
    }

    /**
     * AJAX: API/Bulk Fetching కోసం (Add/Edit News పేజీలలో వాడేది)
     */
    public function getChildren($parentId)
    {
        // ఇక్కడ కేవలం లైవ్ వార్తల కోసం కాబట్టి IsActice = 1 ఉంచాలి
        $data = $this->categoryModel
            ->where(['parent_id' => $parentId, 'IsActice' => 1])
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON($data ?: []);
    }

    /**
     * డేటాను తొలగించడం
     */
    public function delete($id)
    {
        $record = $this->categoryModel->find($id);
        if (!$record) {
            return redirect()->to('admin/categories')->with('error', 'రికార్డు దొరకలేదు.');
        }

        $hasChildren = $this->categoryModel->where('parent_id', $id)->where('id !=', $id)->first();
        
        if ($hasChildren) {
            return redirect()->to('admin/categories')->with('error', 'దీని కింద ఉప-వివరాలు ఉన్నాయి. ముందుగా వాటిని తొలగించండి!');
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to('admin/categories')->with('success', 'విజయవంతంగా తొలగించబడింది.');
        }

        return redirect()->back()->with('error', 'తొలగించడం సాధ్యపడలేదు.');
    }

    public function toggleStatus($id, $status)
    {
        if ($this->categoryModel->find($id)) {
            $this->categoryModel->update($id, ['IsActice' => (int)$status]);
            $message = ($status == 1) ? 'కేటగిరీ ఇప్పుడు కనిపిస్తుంది.' : 'కేటగిరీ దాచబడింది.';
            return redirect()->back()->with('success', $message);
        }
        return redirect()->back()->with('error', 'కేటగిరీ లభించలేదు!');
    }
}