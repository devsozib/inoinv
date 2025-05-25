@extends('frontend.layouts.app')
@section('content')

 
  <div id="container" class="page-template default-template wd_wide">
    {{generateCheckOutPage()}}
  </div>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // getCheckoutPage();
    function getCheckoutPage(){
        fetch('{{ route('get_check_out_page') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            document.getElementById('container').innerHTML = data.checkout_page;
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
            document.getElementById('container').innerHTML = data.checkout_page;
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });
    }

    function createNewAccount(ele){
        document.getElementById('create_account').style.display = (ele.checked ? 'block': 'none');
        document.getElementById("account_password").required = ele.checked ? true : false;
    }

    function deliveryOnOtherAddress(ele){
        document.getElementById('shipping_address_section').style.display = (ele.checked ? '': 'none');
        document.getElementById("shipping_first_name").required = ele.checked ? true : false;
        document.getElementById("shipping_phone").required = ele.checked ? true : false;
        document.getElementById("shipping_phone").required = ele.checked ? true : false;
        document.getElementById("shipping_state").required = ele.checked ? true : false;
        document.getElementById("shipping_area").required = ele.checked ? true : false;
        document.getElementById("shipping_address_1").required = ele.checked ? true : false;
    }

    function openLoginPopup(){
        $('#extra-add-product-popup-login').modal('show');
    }

    function selectPaymentMethod(id){
        var eles = document.getElementsByClassName("payment_method");
        for(var i=0; i<eles.length; i++){
            eles[i].style.display = 'none';
        }
        document.getElementById('payment_method_'+id).style.display = 'block';
    }

    function changeDistrict(district_id, prefix="", event){
        event.preventDefault();
        var eles = document.getElementsByClassName(prefix+"dist");
        for(var i=0; i<eles.length; i++){
            eles[i].style.display = "none";
        }

        var eles = document.getElementsByClassName(prefix+"dist_"+district_id);
        for(var i=0; i<eles.length; i++){
            eles[i].style.display = "block";
        }

        // if(prefix=="bill_") document.getElementById("billing_area_field").value = "";
        // if(prefix=="ship_") document.getElementById("shipping_area").value = "";
    }

  </script>

@endsection