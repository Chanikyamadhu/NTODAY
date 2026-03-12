<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'full_name', 'email', 'phone', 
        'gender', 'dob', 'role', 'profile_pic', 'display_name', 
        'address', 'area_coverage', 'news_tags', 'pincode', 'bio', 
        'is_active', 'status', 'last_login', 'deleted_at'];

        protected $useTimestamps = true; // created_at, updated_at ఆటోమేటిక్ గా మేనేజ్ అవుతాయి
}