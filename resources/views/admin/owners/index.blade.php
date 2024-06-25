<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            オーナー一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="md:p-6 bg-white border-b border-gray-200">
                    {{-- エロクワント
                    @foreach ($e_all as $e_owner)
                    {{ $e_owner->name }}
                    {{ $e_owner->created_at->diffForHumans() }}
                    @endforeach
                    <br>
                    クエリビルダ
                    @foreach ($q_get as $q_owner)
                    {{ $q_owner->name }}
                    {{ Carbon\Carbon::parse($q_owner->created_at)->diffForHumans() }}
                    @endforeach --}}
                    <section class="text-gray-600 body-font">
                        <div class="container md:md:px-5 py-24 mx-auto">
                          <x-flash-message status="session('status')"/>
                          <div class="flex justify-end mb-4">
                            <button onclick="location.href='{{route('admin.owners.create')}}'" class=" text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規作成</button>
                          </div>
                          <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-center whitespace-no-wrap">
                              <thead>
                                <tr>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">作成日</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($owners as $owner)
                                        <tr>
                                            <td class="md:px-4 py-3"> {{ $owner->name }}</td>
                                            <td class="md:px-4 py-3">{{ $owner->email }}</td>
                                            <td class="md:px-4 py-3">{{ $owner->created_at->diffForHumans() }}</td>
                                            <td class="md:px-4 py-3">
                                                <button onclick="location.href='{{route('admin.owners.edit',['owner'=>$owner->id])}}'" class="mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded">編集する</button>
                                            </td>
                                        <form id="delete_{{$owner->id}}" method="post" action="{{ route('admin.owners.destroy',["owner"=>$owner->id])}}">
                                            @csrf
                                            @method("delete")
                                            <td class="md:px-4 py-3">
                                                <a href="#" data-id="{{ $owner->id }}" onclick="deletePost(this)"
                                                class=" mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red600 rounded " style="background: red">削除する</a>
                                            </td>
                                        </form>
                                        </tr>
                                @endforeach
                              </tbody>
                            </table>
                            {{$owners->links()}}
                          </div>
                        </div>
                      </section>
                </div>
            </div>
        </div>
    </div>
    <script>
        function deletePost(e){
        "use strict"; if(confirm("本当に削除しても良いですか？")){
        document.getElementById("delete_" + e.dataset.id).submit();}}
    </script>
</x-app-layout>
