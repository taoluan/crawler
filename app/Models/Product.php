<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'storeId', 'name', 'price', 'nid', 'image', 'ratingScore', 'originalPrice', 'itemSoldCntShow', 'location', 'discount'
    ];
}
