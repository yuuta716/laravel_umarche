<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\shop;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Throwable;
use Illuminate\Support\Facades\Log;

class OwnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data_now = Carbon::now();
        echo $data_now->year;
        $e_all = Owner::all();
        $q_get = DB::table("owners")->select("name","created_at")->get();
        $q_first = DB::table("owners")->select("name")->first();
        $c_test = collect(["name" => "test"]);
        // dd($e_all, $q_get, $q_first, $c_test);
        $owners = Owner::select("id","name","email","created_at")->paginate(3);
        return view("admin.owners.index",compact("owners"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.owners.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try{
            DB::transaction(function() use($request) {
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                shop::create([
                    "owner_id"=>$owner->id,
                    "name"=>"店名を入力してください",
                    "information"=>"",
                    "filename"=>"",
                    "is_selling"=>true
                ]);

            });
        }
        catch(Throwable $e) {
            Log::error($e);
            throw $e;
        }
        return redirect()
        ->route('admin.owners.index')
        ->with(["message"=>"オーナー登録を実施しました。",
        "status"=>"info"]);
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
        $owner = Owner::findOrFail($id);
        // dd($owner);
        return view("admin.owners.edit",compact("owner"));
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
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with(["message"=>"オーナー情報を更新しました。",
        "status"=>"info"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Owner::findOrFail($id)->delete();
        return redirect()
        ->route("admin.owners.index")
        ->with(["message"=>"オーナー情報を削除しました",
        "status"=>"alert"]);
    }

    public function expiredOwnerIndex()
    {
        $expiredOwners = Owner::onlyTrashed()->get();
        return view("admin.expired-owners",compact("expiredOwners"));
    }
    public function expiredOwnerDestroy($id)
    {
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route("admin.expired-owners.index");
    }

}
