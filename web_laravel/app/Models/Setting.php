<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name', 
        'site_logo', 
        'site_favicon', 
        'login_background', 
        'primary_color',
        'qris_image',
        'bank_name',
        'account_number',
        'account_name'
    ];
}
