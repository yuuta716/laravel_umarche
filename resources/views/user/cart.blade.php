<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (count($products) > 0)
                        @foreach ($products as $product)
                            <div class="md:flex md:items-center mb-2">
                                <div class="md:w-3/12">{{-- 画像 --}}
                                    @if ($product->imageThird->filename !== null)
                                        <img src="{{ asset('storage/products/' . $product->imageThird->filename) }}">
                                    @else
                                        <img src="">
                                    @endif
                                </div>
                                <div class="md:w-4/12 md:ml-2 ml-4">{{ $product->name }}</div>{{-- 商品名 --}}
                                <div class="md:w-3/12 flex justify-around">
                                    <div>{{ $product->pivot->quantity }}個</div>{{-- 数 --}}
                                    <div>{{ number_format($product->pivot->quantity * $product->price) }}<span
                                            class="text-sm text-gray-700">円（税込）</span> </div>{{-- ⾦額 --}}
                                </div>
                                <div class="md:w-2/12 bg-red-500 p-2 rounded">削除ボタン</div>
                            </div>
                        @endforeach
                    @else
                        カートに商品が⼊っていません。
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
