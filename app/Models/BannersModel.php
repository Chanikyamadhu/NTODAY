<?php

namespace App\Models;

use CodeIgniter\Model;

class BannersModel extends Model
{
    protected $table            = 'banners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // మీరు కోరిన విధంగా summary కాలమ్‌తో కలిపి పూర్తి Allowed Fields
    protected $allowedFields = [
        'title', 
        'summary', 
        'image_path', 
        'link_url', 
        'position', 
        'status'
    ];

    // Dates - టేబుల్‌లో created_at ఉంది కాబట్టి దీనిని ఎనేబుల్ చేస్తున్నాను
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // మీ టేబుల్‌లో updated_at లేకపోతే ఖాళీగా ఉంచండి

    // Validation - డేటా సేవ్ చేసేటప్పుడు పొరపాట్లు జరగకుండా
    protected $validationRules = [
        'title'      => 'required|min_length[3]|max_length[255]',
        'summary'    => 'permit_empty|max_length[255]',
        'image_path' => 'required',
        'position'   => 'required'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'బానర్ టైటిల్ తప్పనిసరి.'
        ],
        'image_path' => [
            'required' => 'బానర్ ఇమేజ్ అప్‌లోడ్ చేయాలి.'
        ]
    ];

    /**
     * యాక్టివ్ బానర్లను పొజిషన్ ప్రకారం పొందడానికి ఫంక్షన్
     */
    public function getActiveBanners($position = null)
    {
        $builder = $this->where('status', 1);
        if ($position) {
            $builder->where('position', $position);
        }
        return $builder->orderBy('id', 'DESC')->findAll();
    }
}