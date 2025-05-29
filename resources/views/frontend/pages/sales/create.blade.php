@extends('frontend.layouts.app') 
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
	.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
  border-color: transparent transparent #888 transparent;
  border-width: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 0 !important;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}
</style>

<div class="content container-fluid pt-0">
					<div class="card mb-3">
						<div class="card-body">
							<!-- Page Header -->
							<div class="page-header mb-3">
								<div class="content-page-header mb-3">
									<h6>Customer Info</h5>
								</div>	
							</div>
							<!-- /Page Header -->				
							<div class="row">
								<div class="col-md-12">
									<form action="{{route('sales.store')}}" method="post">
                                        @csrf
										<div class="form-group-item mb-0 pb-0">
											<h5 class="form-title d-none">Basic Details</h5>
											<div class="row">
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Name <span class="text-danger">*</span></label> 
														<input type="text" name="name" class="form-control p-2" placeholder="Enter Name" value="{{ old('name') }}" required autocomplete="off">
													</div>
												</div>
												

                                            
                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Phone <span class="text-danger">*</span></label>
                                                       <input type="tel" class="form-control p-2" name="phone" id="phone" pattern="[0-9]{11}" maxlength="11" placeholder="Enter phone number" required>
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Address <span class="text-danger">*</span></label>
                                                        <input type="text"  class="form-control p-2" placeholder="Enter Address" id="address" name="address" value="{{ old('address') }}" required autocomplete="off">
													</div>
												</div>

											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="card mb-0">
						<div class="card-body">
							<!-- Page Header -->
							<div class="page-header mb-3">
								<div class="content-page-header mb-3">
									<h6>Cart Info</h6>
								</div>	
							</div>
							<!-- /Page Header -->				
							<div class="row">
								<div class="col-md-12">
									<form action="{{route('sales.store')}}" method="post">
                                        @csrf
										<div class="form-group-item">
											
											<div class="row">
												 <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Product Name <span class="text-danger">*</span></label>
														<!-- <input type="text"  class="form-control" placeholder="Product Name" name="product_name" value="{{ old('product_name') }}" required> -->
														 <select name="product_name" id="" class="form-control js-example-basic-single" required>
														 	<option value=""></option>
															@foreach ($products as $product)
																<option value="{{$product->id}}" {{ old('product_name') ==  $product->id ? 'selected' : ''}}>{{$product->name}}</option>
															@endforeach
														 </select>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Name <span class="text-danger">*</span></label> 
														<input type="text" name="name" class="form-control p-2" placeholder="Enter Name" value="{{ old('name') }}" required autocomplete="off">
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3 " >
														<label>Email </label>
														<input type="email" name="email" class="form-control p-2" placeholder="Enter Email Address" value="{{ old('email') }}" autocomplete="off">
													</div>											
												</div>

                                               

                                               

                                            
                                               

												




											</div>
										</div>
																	
										<div class="add-customer-btns text-left">
											<button type="submit" class="btn customer-btn-save">Submit</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
	
    $('.js-example-basic-single').select2({
		tags: true,
	});
	$('.js-example-basic-single-no-new-value').select2({
		tags: true,
	});
  });

  function getTotal(){
    var price = document.getElementById("price").value.trim();
    var qty = document.getElementById("qty").value.trim();
    if(price<0){
        document.getElementById("price").value = 0;
        price = 0;
    }
    if(qty<0){
        document.getElementById("qty").value = 0;
        qty = 0;
    }
    document.getElementById("total").value = price * qty;
	calculateDue();
  }

  function calculateDue(){
	var bill = (document.getElementById("total").value.trim() * 1)??0;
	var paid_amount = (document.getElementById("paid_amount").value.trim() * 1)??0;
	document.getElementById("due_amount").value = Math.max(0, bill-paid_amount);
  }

  function makeDiscount(){
	getTotal();
	var total = (document.getElementById("total").value.trim() * 1)??0;
	var discount = (document.getElementById("discount").value.trim() * 1)??0;
	document.getElementById("total").value = Math.max(0, total-discount);
	calculateDue();
  }

</script>

<script>
  const selectElements = document.querySelectorAll('.phoneCode');
  selectElements.forEach(selectElement => {
    selectElement.addEventListener('focus', function() {
		// console.log('open');
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.showdefault;
		});
    });
    selectElement.addEventListener('blur', function() {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
    });

	selectElement.addEventListener('change', function() {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
		selectElement.blur();
    });

	selectElement.addEventListener('mousedown', function(event) {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
		selectElement.blur();
  	});

	// Handling touchend event for touch devices
    selectElement.addEventListener('touchend', function(event) {
        // console.log('close');
        const options = selectElement.options;
        Array.from(options).forEach(option => {
            option.text = option.dataset.show;
        });
        selectElement.blur();
    });
  });
</script>

@endsection