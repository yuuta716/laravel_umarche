<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class ItemController extends Controller
{
    public function __construct()
    {
        //ユーザーかどうかの確認
        $this->middleware('auth:users');
    }

    public function index()
    {
        $products = Product::availableItems()->get();
        return view('user.index', compact('products'));
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
