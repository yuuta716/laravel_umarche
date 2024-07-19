<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\PrimaryCategory;

class ItemController extends Controller
{
    public function __construct()
    {
        //ユーザーかどうかの確認
        $this->middleware('auth:users');
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter("item"); //itemのidを取得
           if (!is_null($id)) { //itemのidが存在するなら↓
            $itemId = Product::availableItems()->where('products.id',$id)->exists(); //productのidが⼊ってきた値idと⼀致してるか。⼊ってきた値が存在するかどうか確認。
           //↓itemIdが存在していなかったら
           if (!$itemId) {
           abort(404); //404の画⾯表⽰
            }
            }
           return $next($request);
            });

    }

    public function index(Request $request)
    {
        $categories = PrimaryCategory::with("secondary")->get();
        $products = Product::availableItems()
        ->selectCategory($request->category ?? "0")//選んでいない場合初期値０に！
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? '20');
        return view('user.index', compact('products','categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity'); //⼀つの商品の在庫情報を取るた
        // めに
        if ($quantity > 9) {
            $quantity = 9;
        } //9より⼤きかったら９
        return view('user.show', compact('product','quantity'));
    }
}
