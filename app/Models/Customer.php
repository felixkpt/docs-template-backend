<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'merchant_id',
        'email',
        'customer_type_id',
        'phone',
        'facebook_thread_id',
        'facebook_id',
        'alternate_phone',
        'user_id',
        'display_name',
        'message_time',
        'fb_message_time',
        'tweet_message_time',
        'country_id',
        'city_id',
        'business_name',
        'facebook_page_id',
        'company_id',
        'chat_tag_id',
        'account_number',
        'line_business_id',
        'organization_type_id',
        'order_frequency_id',
        'order_day_id',
        'contact_person',
        'status',
    ];

}
