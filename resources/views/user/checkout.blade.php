<p>決済ページヘリダイレクトします</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const publicKey = "{{ $publicKey }}" //公開キー
    const stripe = Stripe(publicKey) //Stripe(publicKey)で初期化してstripe変数に
    // 画⾯読み込み処理
    window.onload = function() {
        stripe.redirectToCheckout({ //Checkoutに⾶ばす
            sessionId: '{{ $checkout_session -> id }}' //stripeで作成した情報(idは作成時、⾃動で作られている)
        }).then(function(result) {
            window.location.href = "{{ route('user.cart.index') }}" //NGだったらuser.cart.indexに戻す
        });
    }
</script>
