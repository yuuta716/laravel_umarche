<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Shop;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        //オーナーかどうかの確認
        $this->middleware('auth:owners');
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('product'); //productのidを取得
            if (!is_null($id)) {
                //productのidが存在するなら↓
                $productOwnerId = Product::findOrFail($id)->shop->owner->id; //Productの中にownerがないのでshop-
                // >owner->idにする。
                $productId = (int) $productOwnerId; //⽂字列=>数値に
                //認証⽤のid↓
                if ($productId !== Auth::id()) {
                    //同じではなかったら
                    abort(404); //404の画⾯表⽰
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        //Owner::findOrFail(Auth::$id())でログインしているオーナーの情報を取得している。
        //->shop->product更にこのコードで取得した情報からリレーションで繋がっているshopからproductを取得
        // して変数で置き換える。
        // $products = Owner::findOrFail(Auth::id())->shop->product;
        $ownerInfo = Owner::with('shop.product.imageFirst')->where('id', Auth::id())->get();
        // dd($ownerInfo);
        //return view("owner.products.index"に$products（変数)に置き換えたものを使いたいので
        // compact("products")で使⽤可能に！
        return view('owner.products.index', compact('ownerInfo'));
        //このview("owner.products.index"を制作する
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // shops処理
        // whereで条件指定 → "owner_id",Auth::id()オーナーでログインしたID
        $shops = Shop::where('owner_id', Auth::id())
            ->select('id', 'name') //idとnameを表⽰
            ->get(); //id,nameを取得
        // images処理
        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc') //新しい順番
            ->get();
        // category処理
        // リレーションで取得する際n+1問題が起こるのでwithで！
        $categories = PrimaryCategory::with('secondary')->get();
        return view('owner.products.create', compact('shops', 'images', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
