@extends('frontend.layouts.app')
@section('content')

<div id="container" class="page-template default-template wd_wide">
    {{cartView()}}
</div>


<script>
    function qtyMinus(id){
        var value = document.getElementById(id).value.trim();

        var rules = {value:'numaric',}
        var data = {value:value}
        var validator = Validator(data,rules);
        if(!validator.isValid) value = 1;

        value--;
        if(value < 1) value = 1;
        document.getElementById(id).value = value;
    }
    function qtyChange(id){
        var value = document.getElementById(id).value.trim();

        var rules = {value:'numaric',}
        var data = {value:value}
        var validator = Validator(data,rules);
        if(!validator.isValid) value = 1;

        if(value < 1) value = 1;
        document.getElementById(id).value = value;
    }

    function qtyPlus(id){
        var value = document.getElementById(id).value.trim();

        var rules = {value:'numaric',}
        var data = {value:value}
        var validator = Validator(data,rules);
        if(!validator.isValid) value = 1;

        value++;
        document.getElementById(id).value = value;
    }

    function updateCartQty(key){
        var value = document.getElementById("qty_"+key).value.trim();

        var rules = {value:'require|numaric',}
        var data = {value:value}
        var validator = Validator(data,rules);
        if(!validator.isValid) value = 1;


        fetch('{{ route('update_cart_qty') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key, value })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            document.getElementById('container').innerHTML = data.html;
            document.getElementById("mini_cart_value").innerHTML = `${data.currectPrice} ৳`;
            document.getElementById("mini_cart_view").innerHTML = data.miniCartHtml;
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });

    }

    function addDeliveryChargeTocart(){
        const deliveryCharge = document.querySelector('input[name="delivery_charge"]:checked').value;

        fetch('{{ route('update_cart_delivery_charge') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ deliveryCharge })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            document.getElementById('container').innerHTML = data.html;
            document.getElementById("mini_cart_value").innerHTML = `${data.currectPrice} ৳`;
            document.getElementById("mini_cart_view").innerHTML = data.miniCartHtml;
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });
    }

    function getCart(){
        fetch('{{ route('get_cart') }}')
        .then(res =>{
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
        }
        )
        .then(data => {
            document.getElementById('container').innerHTML = data.html;
            document.getElementById("mini_cart_value").innerHTML = `${data.currectPrice} ৳`;
            document.getElementById("mini_cart_view").innerHTML = data.miniCartHtml;
        })
    }

    function showPromoSection(){
        document.getElementByid("promo_form").style.display = "block";
    }

    function applyCoupon(){
        var couponCode = document.getElementById("coupon_code").value;
        fetch('{{ route('apply_coupon_to_cart') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ couponCode })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log(data);
            document.getElementById('container').innerHTML = data.html;
            document.getElementById("mini_cart_value").innerHTML = `${data.currectPrice} ৳`;
            document.getElementById("mini_cart_view").innerHTML = data.miniCartHtml;
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });

    }
</script>
@endsection