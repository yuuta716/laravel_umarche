<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\shop;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('image'); //Imageのidを取得
            if (!is_null($id)) {
                //imageのidが存在するなら↓
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $imageId = (int) $imagesOwnerId; //⽂字列=>数値に
                //認証⽤のid↓
                if ($imageId !== Auth::id()) {
                    //同じではなかったら
                    abort(404); //404の画⾯表⽰
                }
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())->orderBy('updated_at', 'desc')->paginate(20);
        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        $imageFiles = $request->file('files'); //filesとすることで複数画像を取得できる
        //⼀応IF分で書いておきます！
        if (!is_null($imageFiles)) {
            foreach ($imageFiles as $imageFile) {
                $fileNameToStore = ImageService::upload($imageFile, 'products'); //第⼆引数はフォルダー名
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore,
                ]); //ファイルが帰ってきたら保存処理をする。
            }
        }
        //レダイレクションでindex画⾯に戻しフラッシュメッセージを表⽰させる。
        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像登録を実施しました',
                'status' => 'info',
            ]);
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
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
        $request->validate([
            'title' => ['string', 'max:50'],
        ]);
        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();
        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像情報を更新しました',
                'status' => 'info',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // storageフォルダーの中の選択されたIDの画像を消さないといけないので
        $image = Image::findOrFail($id);
        //ストレージフォルダのありかを⽰さないといけない
        $filePath = 'public/products/' . $image->filename;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        Image::findOrFail($id)->delete();
        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像を削除しました',
                'status' => 'alert',
            ]);
    }
}
