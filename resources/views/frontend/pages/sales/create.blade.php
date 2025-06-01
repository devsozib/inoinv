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
select, input{
	border-color: #000 !important;
}
label{
	color: #000 !important;
}
</style>
<form action="{{route('sales.store')}}" method="post">
    @csrf
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
										





										<div class="group-item" data-itemnumber="1" id="form-group-item1" style="background:#198754; color:#fff !important; padding: 10px 5px;">
											<div class="row align-items-end">
												<div class="col-md-3">
													<label style="color:#fff !important;">Product Name</label>
													<select onchange="selectProduct(1)" id="product1" class="form-control js-example-basic-single" style="height: 30px;" required>
														<option value=""></option>
														@foreach ($products as $product)
															<option value="{{ $product->id }}" data-price="{{ $product->lastPurchasePrice }}">
																{{ $product->name }}({{$product->model}})
															</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;"> PUrchase Price</label>
													<input type="number" id="purchase_price1" style="height: 30px;" class="form-control" readonly>
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;"> Unit Price</label>
													<input onchange="calculateTotal()" type="number" id="unit_price1" style="height: 30px;" class="form-control unit-price" >
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;">Qty</label>
													<input onchange="calculateTotal()" type="number" id="qty1" style="height: 30px;" class="form-control qty" min="0">
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;">Total</label>
													<input type="number" id="total1" style="height: 30px;" class="form-control total" readonly>
												</div>
												<div class="col-md-1 text-end btn-holder">
													<button onclick="addItem()"  type="button" class=" btn btn-primary addItemBtn">Add</button>
												</div>
											</div>
										</div>

										<hr>

									<div class=""  style="color:#000 !important;">
										<div class="row align-items-end">
											<div class="col-md-4">
												<label style="color:#000 !important;">Product Name</label>
											</div>
											<div class="col-md-2">
												<label style="color:#000 !important;"> Unit Price</label>
											</div>
											<div class="col-md-2">
												<label style="color:#000 !important;">Qty</label>
											</div>
											<div class="col-md-2">
												<label style="color:#000 !important;">Total</label>
											</div>
											<div class="col-md-1 text-end btn-holder">
											</div>
										</div>
									</div>
									<div id="item_container">
										
									</div>
									<hr>

									<br>
									<div class="row d-flef justify-content-end align-items-end">
										<div class="col-md-4"></div>
										<div class="col-md-2">
											<label>Sub Total</label>
											<input onchange="calculateTotal()" type="number" id="subTotal" name="subTotal" style="height: 30px;" class="form-control total" readonly>
										</div>
										<div class="col-md-2">
											<label>Discount</label>
											<input onchange="calculateTotal()" type="number" id="discount" name="discount" style="height: 30px;" class="form-control total" >
										</div>
										<div class="col-md-2">
											<label>Grand Total</label>
											<input type="number" id="grandTotal" name="grandTotal" style="height: 30px;" class="form-control total" readonly>
										</div>
										<div class="col-md-2 text-end btn-holder">
											
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

</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
  var itemNumber = 2;

  $(document).ready(function() {
    $('.js-example-basic-single').select2({
      tags: true
    });


  });

    function addItem(){

		var product = document.getElementById('product1').value;
		var qty = document.getElementById('qty1').value;
		if(product==""){
			document.getElementById('product1').setCustomValidity("Time is required");
        	document.getElementById('product1').reportValidity();
			return;
		}

		if(qty.trim() === ""){
			document.getElementById('qty1').setCustomValidity("Time is required");
        	document.getElementById('qty1').reportValidity();
			return;
		}

		const price = document.getElementById('unit_price1').value;
		if(price.trim() === ""){
			document.getElementById('unit_price1').setCustomValidity("Time is required");
        	document.getElementById('unit_price1').reportValidity();
			return;
		}


		var eles = document.getElementsByClassName('item'+product);
		if(eles.length){
			var qEles = document.getElementsByClassName('qty'+product);
			if(qEles.length){
				var old_qty = qEles[0].value;
				qEles[0].value = parseInt(old_qty) + parseInt(qty);
			}

		}else{

			

			var html = `
				<div class="item${product} group-item mt-2" data-itemnumber="${itemNumber}" id="form-group-item${itemNumber}">
					<div class="row align-items-end">
						<div class="col-md-4">
							<input  type="hidden" name="product[]" value="${product}">
							<select onchange="selectProduct(${itemNumber})" style="height: 30px;"  id="product${itemNumber}" class="product${product} form-control product-select js-example-basic-single" required disabled>
								<option value=""></option>
								@foreach ($products as $product)`;

								var select = (product == {{ $product->id }} ? 'selected' : '');
								
								html +=`
									<option value="{{ $product->id }}" data-price="{{ $product->lastPurchasePrice }}" ${select}>
										{{ $product->name }}({{$product->model}})
									</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<input onchange="calculateTotal()" type="number" name="unit_price[]" id="unit_price${itemNumber}" style="height: 30px;" class="form-control unit-price" value="${price}" >
						</div>
						<div class="col-md-2">
							<input onchange="calculateTotal()" type="number" name="qty[]" id="qty${itemNumber}" style="height: 30px;" class="qty${product} form-control qty" min="0" value="${qty}">
						</div>
						<div class="col-md-2">
							<input type="number" name="total" id="total${itemNumber}" style="height: 30px;" class="form-control total" readonly>
						</div>
						<div class="col-md-2 text-end btn-holder">
							<button onclick="removeItem(${itemNumber})" type="button" class="btn btn-danger remove-item me-1 ">×</button>
						</div>
					</div>
				</div>
			`;
			$('#item_container').append(html);
			itemNumber++;
		}

		calculateTotal();
      	
	}

	function removeItem(item){
		document.getElementById('form-group-item' + item).remove();
		calculateTotal();
	}

  function selectProduct(item){
	
	var selectedPrice = $('#product'+item+' option:selected').data('price');
	if(document.getElementById('purchase_price' + item))
		document.getElementById('purchase_price' + item).value = selectedPrice;
	calculateTotal()
  }

  function calculateTotal(){
	var eles = document.getElementsByClassName('group-item');

	var subTotal = 0;
	for(var i=0; i<eles.length; i++){
		var itemNumber = eles[i].dataset.itemnumber;
		
		var unit_price = document.getElementById('unit_price'+itemNumber).value;
		var qty = document.getElementById('qty'+itemNumber).value;
		var totalEle = document.getElementById('total'+itemNumber);
		
		if(parseInt(qty) >= 0 &&  parseFloat(unit_price) >= 0){
			var total = (parseInt(qty) * parseFloat(unit_price));
			totalEle.value = total;
			if(i>0)subTotal += total;
		}
		

	}
	var discount = document.getElementById('discount').value;
	discount = (parseFloat(discount) >= 0 ? parseFloat(discount) : 0);

	 document.getElementById('subTotal').value = subTotal;
	 document.getElementById('grandTotal').value = subTotal - discount;
	

  }
</script>











@endsection