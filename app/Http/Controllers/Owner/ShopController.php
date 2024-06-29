<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\shop;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;


class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter("shop"));
            // dd(Auth::id());
            $id = $request->route()->parameter("shop"); //shopのidを取得
            if (!is_null($id)) { //shopのidが存在するなら↓
                $shopsOwnerId = shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; //⽂字列=>数値に
                $ownerId = Auth::id(); //認証⽤のid
                if ($shopId !== $ownerId) { //同じではなかったら
                    abort(404); //404の画⾯表⽰
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $shops = shop::where("owner_id", Auth::id())->get();
        return view("owner.shops.index", compact("shops"));
        // phpinfo();
    }

    public function edit($id)
    {
        // dd(shop::findOrFail($id));
        $shop = shop::findOrFail($id);
        return view("owner.shops.edit", compact("shop"));
    }

    public function update(UploadImageRequest $request)
    {
        $imageFile = $request->image; //imgを受け取り変数へ
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileNameToStore = ImageService::upload($imageFile,"shops");
             //nullではないかアップロードできてるか確認
            // Storage::putFile("public/shops", $imageFile); //保存先と保存したいファイル
            // $fileName = uniqid(rand()."_");//ランダムなファイルを作成
            // $extension = $imageFile->extension();//extensionで受け取った画像の拡張⼦をつけて代⼊
            // $fileNameToStore = $fileName. "." .$extension;
            // $resizedImage = InterventionImage::make($imageFile)->resize(1920,1080)->encode();//resizeでサイズ設定,encodeで画像として扱える
            // Storage::put("public/shops/" .$fileNameToStore,$resizedImage);//ファイルからのファイル
            // // 名,リサイズした画像

        }
        return redirect()->route("owner.shops.index");
    }
}
