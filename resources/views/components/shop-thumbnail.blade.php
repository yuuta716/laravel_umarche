<div>
    @if (empty($filename))
        <img src="{{ asset('images/noimage.jpg') }}" class="w-full">
    @else
        <img src="{{ asset('storage/shops/' . $filename) }}">
    @endif
</div>
