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
										






										<!-- Laravel Blade -->
									<div id="item_container">
										<div class="group-item" data-itemnumber="1" id="form-group-item1">
											<div class="row align-items-end">
												<div class="col-md-4">
													<label>Product Name</label>
													<select onchange="selectProduct(1)" id="product1" name="product[]" class="form-control js-example-basic-single" required>
														<option value=""></option>
														@foreach ($products as $product)
															<option value="{{ $product->id }}" data-price="{{ $product->price }}">
																{{ $product->name }}
															</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-2">
													<label>Unit Price</label>
													<input type="number" name="unit_price" id="unit_price1" style="height: 30px;" class="form-control unit-price" readonly>
												</div>
												<div class="col-md-2">
													<label>Qty</label>
													<input onchange="calculateTotal()" type="number" name="qty[]" id="qty1" style="height: 30px;" class="form-control qty" min="0">
												</div>
												<div class="col-md-2">
													<label>Total</label>
													<input type="number" name="total" id="total1" style="height: 30px;" class="form-control total" readonly>
												</div>
												<div class="col-md-2 text-end btn-holder">
													<button onclick="removeItem(1)" type="button" class="btn btn-danger remove-item me-1 d-none">×</button>
													<button onclick="addItem()"  type="button" class=" btn btn-success addItemBtn">+</button>
												</div>
											</div>
										</div>
									</div>

									<br>
									<div class="row d-flef justify-content-end align-items-end">
												<div class="col-md-4"></div>
												<div class="col-md-4"></div>
												<div class="col-md-4"></div>
												<div class="col-md-2">
													<label>Sub Total</label>
													<input type="number" id="subTotal" style="height: 30px;" class="form-control total" readonly>
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
      var html = `
			<div class="group-item" data-itemnumber="${itemNumber}" id="form-group-item${itemNumber}">
				<div class="row align-items-end">
					<div class="col-md-4">
						<label>Product Name</label>
						<select onchange="selectProduct(${itemNumber})"  id="product${itemNumber}" name="product[]" class="form-control product-select js-example-basic-single" required>
							<option value=""></option>
							@foreach ($products as $product)
								<option value="{{ $product->id }}" data-price="{{ $product->price }}">
									{{ $product->name }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2">
						<label>Unit Price</label>
						<input type="number" name="unit_price" id="unit_price${itemNumber}" style="height: 30px;" class="form-control unit-price" readonly>
					</div>
					<div class="col-md-2">
						<label>Qty</label>
						<input onchange="calculateTotal()" type="number" name="qty[]" id="qty${itemNumber}" style="height: 30px;" class="form-control qty" min="0">
					</div>
					<div class="col-md-2">
						<label>Total</label>
						<input type="number" name="total" id="total${itemNumber}" style="height: 30px;" class="form-control total" readonly>
					</div>
					<div class="col-md-2 text-end btn-holder">
						<button onclick="removeItem(${itemNumber})" type="button" class="btn btn-danger remove-item me-1 ">×</button>
						<button onclick="addItem()" type="button" class=" btn btn-success addItemBtn">+</button>
					</div>
				</div>
			</div>
		`;

      $('#item_container').append(html);

      itemNumber++;
	}

  function selectProduct(item){
	
	var selectedPrice = $('#product'+item+' option:selected').data('price');
	document.getElementById('unit_price' + item).value = selectedPrice;
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
		if(parseInt(qty) +  parseFloat(unit_price)){
			var total = (parseInt(qty) * parseFloat(unit_price));
			totalEle.value = total;
			subTotal += total;
		}
		

	}
	 document.getElementById('subTotal').value = subTotal;
	

  }
</script>











@endsection