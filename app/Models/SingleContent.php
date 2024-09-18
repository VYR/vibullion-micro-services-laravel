<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SingleContent extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'main_page',
        'products',
        'credit_assessment',
        'blogs',
        'webinars',
        'faqs',
        'about_us',
        'careers',
        'contact_us',
        'refer_earn',
        'become_partner',
        'after_login',
        'consultation',
        'complete_kyc',
        'schemes',
        'scheme_details1',
        'scheme_details2',
        'dashboard',
        'profile'
    ];

    protected function casts(): array
    {
        return [
            'main_page' => 'array',
            'products' => 'array'
        ];
    }
}
