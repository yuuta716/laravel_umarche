<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    public function shop()
    {
        return $this->belongsTo(shop::class);
    }

    public function category()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    public function imageFirst()
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }

    public function imageSecond()
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    public function imageThird()
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    public function imageFourth()
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    public function stock()
    {
        return $this->hasMany(stock::class);
    }

    protected $fillable = ['shop_id', 'name', 'information', 'price', 'is_selling', 'sort_order', 'secondary_category_id', 'image1', 'image2', 'image3', 'image4'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts')->withPivot(['id', 'quantity']);
    }

    public function scopeAvailableItems($query)
    {
        $stocks = DB::table('t_stocks')->select('product_id', DB::raw('sum(quantity)as quantity'))->groupBy('product_id')->having('quantity', '>', 1);
        return $query
            ->joinSub($stocks, 'stock', function ($join) {
                $join->on('products.id', '=', 'stock.product_id');
            })
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->join('images as image2', 'products.image2', '=', 'image2.id')
            ->join('images as image3', 'products.image3', '=', 'image3.id')
            ->join('images as image4', 'products.image4', '=', 'image4.id')
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->select('products.id as id', 'products.name as name', 'products.price', 'products.sort_order as sort_order', 'products.information', 'secondary_categories.name as category', 'image1.filename as filename');
    }

    public function scopeSortOrder($query, $sortOrder)
    {
        // 特定のソート順が指定されていない場合や、推奨されるソート順が選択された場合のデフォルト動作の条件。
        if ($sortOrder === null || $sortOrder === \Constant::SORT_ORDER['recommend']) {
            return $query->orderBy('sort_order', 'asc');
        }
        // ⾼い価格から低い価格へ並び替えるための条件(desc)
        if ($sortOrder === \Constant::SORT_ORDER['higherPrice']) {
            return $query->orderBy('price', 'desc');
        }
        // 低い価格から⾼い価格へ並び替えるための条件(asc)
        if ($sortOrder === \Constant::SORT_ORDER['lowerPrice']) {
            return $query->orderBy('price', 'asc');
        }
        // 商品が追加された⽇付が新しい順に並び替えるための条件(desc)
        if ($sortOrder === \Constant::SORT_ORDER['later']) {
            return $query->orderBy('products.created_at', 'desc');
        }
        // 商品が追加された⽇付が古い順に並び替えるための条件(asc)
        if ($sortOrder === \Constant::SORT_ORDER['older']) {
            return $query->orderBy('products.created_at', 'asc');
        }
    }

    public function scopeSelectCategory($query, $categoryId)
    {
        if ($categoryId !== '0') {
            return $query->where('secondary_category_id', $categoryId);
        } else {
            return;
        }
    }

    public function scopeSearchKeyword($query, $keyword)
    {
        if (!is_null($keyword)) {
            //与えられたキーワードがnullでないことを確認します
            $spaceConvert = mb_convert_kana($keyword, 's'); //キーワード内の全⾓スペースを半⾓スペースに変換にして検索キーワードの⼀貫性を保証
            $keywords = preg_split('/[\s]+/', $spaceConvert, -1, PREG_SPLIT_NO_EMPTY); //変換されたキーワードを空⽩で区切って配列にPREG_SPLIT_NO_EMPTYフラグは、空の⽂字列を結果から除外するために使⽤されます。
            foreach (
                $keywords
                as $word //単語をループで回す
            ) {
                $query->where('products.name', 'like', '%' . $word . '%');
            }
            return $query;
        } else {
            return;
        }
    }
}
