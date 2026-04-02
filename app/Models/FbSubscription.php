<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbSubscription extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'page_access_token',
        'page_id',
        'page_name',
        'client_id',
    ];
}
