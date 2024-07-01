{{-- components/shop-thumbnailからthumbnailに変えて<x-thumbnailの要素typeがshopsなら
storage/shops/にproductsならstorage/products/に、これを変数に置き換えている! --}}
@php
    if ($type === 'shops') {
        $path = 'storage/shops/';
    }
    if ($type === 'products') {
        $path = 'storage/products/';
    }
@endphp
{{-- この下のコードは空ならNO_imgの画像を、ソレ以外ならstorage/productsに⼊れるとなってますが上に
$pathという変数に置き換えているのでassetのstorage/productsを$pathに変えてあげると画像の保存する
ファイルを変えれる。 --}}

<div>
    @if (empty($filename))
        <img src="{{ asset('images/noimage.jpg') }}" class="w-full">
    @else
        <img src="{{ asset('$path' . $filename) }}">
    @endif
</div>
