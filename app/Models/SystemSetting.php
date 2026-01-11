<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_logo',
        'site_favicon',
        'contact_email',
        'contact_phone',
        'address',
        'footer_text',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'header_ad_script',
        'sidebar_ad_script',
        'footer_ad_script',
        'article_middle_ad_script',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'contact_page_image',
        'contact_page_title',
        'contact_page_content',
        'tip_us_page_image',
        'tip_us_page_title',
        'tip_us_page_content',
        'contact_form_title',
        'tip_us_form_title',
    ];
}
