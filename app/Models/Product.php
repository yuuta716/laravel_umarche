<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;

class Product extends Model {
    use HasFactory;

    public function shop() {
        return $this->belongsTo( shop::class );
    }

    public function category() {
        return $this->belongsTo( SecondaryCategory::class, 'secondary_category_id' );
    }

    public function imageFirst() {
        return $this->belongsTo( Image::class, 'image1', 'id' );
    }

    public function stock() {
        return $this->hasMany( stock::class );
    }

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

}
