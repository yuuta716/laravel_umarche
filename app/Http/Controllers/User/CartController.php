<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
//ユーザーidを取得するために
use App\Models\User;

class CartController extends Controller {

    public function index() {
        $user = User::findOrFail( Auth::id() );
        //ログインしてるユーザー情報を取得
        $products = $user->products;
        $totalPrice = 0;
        foreach ( $products as $product ) {
            $totalPrice += $product->price * $product->pivot->quantity;
            //⾦額と数量を掛けたものをtotalPriceに
        }
        return view( 'user.cart', compact( 'products', 'totalPrice' ) );
    }

    public function add( Request $request ) {

        $itemInCart = Cart::where( 'product_id', $request->product_id )//渡ってくるproductを取得
        ->where( 'user_id', Auth::id() )->first();
        //違うユーザーかもしれないのでuserでログインしてるユーザーとする
        if ( $itemInCart ) {
            $itemInCart->quantity += $request->quantity;
            //カートに1⼊ってるとして更に追加されると合計される
            $itemInCart->save();
            //保存
        } else {
            Cart::create( [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ] );
        }
        return redirect()->route("user.cart.index");

    }

}
