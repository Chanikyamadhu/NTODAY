<?php
namespace App\Models;
use CodeIgniter\Model;

class CategoryModel extends Model {
    protected $table = 'categories';
    
    // ఇక్కడ స్పెల్లింగ్ సరిచేశాను: IsActice (మీరు కంట్రోలర్/వ్యూలో వాడుతున్న దానికి అనుగుణంగా)
    // ఒకవేళ డేటాబేస్ లో 'IsActive' అని ఉంటే, కంట్రోలర్ లో కూడా 'IsActive' అని మార్చుకోవాలి.
    protected $allowedFields = ['id', 'parent_id', 'name', 'slug', 'type', 'status', 'IsActice','created_at'];

    // మెయిన్ కేటగిరీలను మరియు వాటి సబ్-కేటగిరీలను ఒకేసారి పొందడానికి
    public function getHierarchy() {
        // ఇక్కడ కూడా స్పెల్లింగ్ సరిచేశాను
        $all = $this->where('IsActice', 1)->findAll();
        return $all;
    }
}