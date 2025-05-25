@extends('admin.layout.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">
  <div class="aiz-main-content">
    <div class="">
      <div class="card">
        <div class="card-header">
          <h1 class="h2 fs-16 mb-0">Edit Order for <span style="color:blue">#{{ $order->order_number }}</span></h1>
        </div>
        @if(session('sweet_alert'))
            <script>
                Swal.fire({
                    icon: '{{ session('sweet_alert.type') }}',
                    title: '{{ session('sweet_alert.title') }}',
                    text: '{{ session('sweet_alert.text') }}',
                });
            </script>
        @endif
        <div class="card-body">

        @php
        $statuses = orderStatuses();
        @endphp
           
          <div class="row mt-3">
            <div class="col-12 col-md-4">
              <strong>Order Info</strong>
              <hr>
              <address>          
                <br> Order Status: {{getArrayData($statuses,$order->order_status)}}<br>
                Payment Status: {{ $order->is_paid == '1' ? 'Paid' : 'Unpaid' }}<br> 
                Transaction Number: {{$order->transaction_id}}<br> 
                Payment Method: {{getPaymentMethods()[$order->payment_method]['name']}}<br> 
                Delivery Method: {{getArrayData(lib_deliveryCharge(), $order->delivery_method)}}<br> 
              </address>
            </div>
            <div class="col-12 col-md-4">
              <strong>Billing Address</strong>
              <hr>
              <address>                                 
                  <div class="d-flex justify-content-between">
                    <div>
                      <button class="btn btn-info d-none"  data-bs-toggle="modal" data-bs-target="#myModal"><i class="ri-ball-pen-line" style="color: #fff"></i></button>
                      <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <form action="{{route('address.update')}}" method="post">
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                      <input type="hidden" name="addressId" value="{{ $order->AddId }}">
                                        <h5 class="modal-title" id="myModalLabel">Edit Address</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <label for="basiInput" class="form-label">Selected Address:</label>
                                            <input type="text" class="form-control" value="{{ $order->selectedAddress }}" id="selectedAddress" name="selectedAddress" placeholder="Selected Address" required>
                                        </div>                                        
                                        <div>
                                          <label for="basiInput" class="form-label">Entrance:</label>
                                          <input type="text" class="form-control" value="{{ $order->entrance }}" id="entrance" name="entrance" placeholder="Entrance" required>
                                        </div>
                                        <div>
                                          <label for="basiInput" class="form-label">Door Code:</label>
                                          <input type="text" class="form-control" value="{{ $order->door_code }}" id="door_code" name="door_code" placeholder="Door Code" required>
                                        </div>
                                        <div>
                                          <label for="basiInput" class="form-label">Floor:</label>
                                          <input type="text" class="form-control" value="{{ $order->floor }}" id="floor" name="floor" placeholder="Floor" required>
                                        </div>
                                        <div>
                                          <label for="basiInput" class="form-label">Apartment:</label>
                                          <input type="text" class="form-control" value="{{ $order->apartment }}" id="apartment" name="apartment" placeholder="Apartment" required>
                                        </div>
                                        <div>
                                          <label for="basiInput" class="form-label">Comments:</label>
                                          <input type="text" class="form-control" value="{{ $order->comment }}" id="Comment" name="comment" placeholder="Comment" required>
                                        </div>                                     
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary ">Update</button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                  </div>                
                <br> Name: {{$billing->name}}<br>
                Phone: {{ $shipping->phone }}<br>  
                Email: {{ $billing->email }}<br> 
                Address: {{$billing->address_details}}<br> 
                Area: {{ getArrayData($lib_areas, $billing->area)}}<br> 
                State : {{getArrayData($lib_districts, $billing->state)}}<br>
              </address>
            </div>
            <div class="col-12 col-md-4">
              <strong>Shipping Info</strong>
              <hr>

              <br> Name: {{$shipping->name}}<br> 
                Phone: {{ $shipping->phone }}<br> 
                Email: {{ $shipping->email }}<br> 
                Address: {{$shipping->address_details}}<br> 
                Area: {{ getArrayData($lib_areas, $shipping->area)}}<br> 
                State : {{getArrayData($lib_districts, $shipping->state)}}<br>
              
            </div>
          </div>
          <hr class="new-section-sm bord-no">
          <div class="row">
          </div>
          <div class="row">
            <div class="col-lg-12 table-responsive">
              <table class="table-bordered aiz-table invoice-summary table footable footable-1 breakpoint-xl" style="">
                <thead>
                  <tr  class="bg-trans-dark footable-header">
                    <th  data-breakpoints="lg" class="min-col footable-first-visible" style="display: table-cell;">#</th>
                    <th  width="10%" style="display:none;">Photo</th>
                    <th  class="text-uppercase" style="display: table-cell;">Name</th>
                    <th  class="text-uppercase" style="display: table-cell;">Size</th>
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center" style="display: table-cell;">Qty</th>                    
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center" style="display: table-cell;">Price</th> 
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center footable-last-visible" style="display: table-cell;">Total Price</th>
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center footable-last-visible" style="display: table-cell;">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($orderItems as $item)
                <tr>
                    <td class="footable-first-visible" style="display: table-cell;">{{ $loop->index+1 }}</td>
                    <td style="display: none;">                     
                        <img height="50" src="{{ asset('frontend/product_images/' . $item->image) }}">                     
                    </td>
                    <td style="display: table-cell; font-size:12px;">{{ $item->product_name }} </td>                   
                    <td style="display: table-cell; font-size:12px;">{{ $item->size_name }} </td>                   
                    <td class="text-center" style="display: table-cell;"> {{ $item->quantity }} </td>                    
                    <td class="text-center footable-last-visible" style="display: table-cell;"> {{ $item->sale_price }} </td>                  
                    <td class="text-center footable-last-visible" style="display: table-cell;"> {{ $item->sale_price*$item->quantity }} </td>
                    <td>
                      <div class="d-flex justify-content-center">
                        <a href="{{route('edit-order',$order->order_number)}}" class="btn btn-sm  btn-primary">Delete</a>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $item->id }}" class="btn btn-sm btn-info waves-effect waves-light d-none"><i class="ri-ball-pen-line"></i></button>
                      </div>
                    </td> 
                       <!-- Default Modals -->
                       <div id="myModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                </div>
                                <div class="modal-body">
                                  Update Qty of
                                  <strong
                                      style="color: darkorange">{{ $item->proName }}({{ $item->sizeName }})</strong>
                                  ?
                                </div>
                                <div class="modal-footer">

                                    <form
                                        action="{{ route('orders.qty_update') }}"
                                        method="post">
                                        @csrf                                    
                                        <input type="hidden" value="{{ $item->order_number }}" name="order_id">                
                                        <input type="hidden" value="{{ $item->product_id }}" name="product_id">                
                                        <input type="text" name="qty" class="form-control" value="{{ $item->quantity }}" id="">
                                        <button type="submit" class="btn btn-info">Update</button>

                                    </form>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal --> 
                  </tr>
                @endforeach

                <tr>
                    <form action="{{route('add_item_to_order')}}" method="post">
                        @csrf
                        <input type="text" name="order_number" value="{{ $order->order_number }}" hidden>
                        <td class="footable-first-visible" style="display: table-cell;">??</td>
                        <td style="display: table-cell; font-size:12px;">
                            <select onchange="getProductSizes(this.value)" name="product_id" id="" class="form-select" required style="max-width:200px;">
                                <option value="">--Select--</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </td>                   
                        <td style="display: table-cell; font-size:12px;" id="sizeHolder">
                            <select name="size_id" id="size_id" class="form-select" required>
                                <option value="">--Select--</option>
                            </select>
                        </td>                   
                        <td class="text-center" style="display: table-cell;" colspan="2"> 
                            <input type="number" name="quantity" class="form-control" style=" " required>
                        </td>
                        <td class="text-center" style="display: table-cell;" colspan="2"> 
                            <button type="submit" class="btn btn-sm btn-success" style="width:80%;">Add</button>
                        </td>
                    </form>
                </tr>
                 
                </tbody>
              </table>
            </div>
          </div>
          <div class="clearfix float-right" style="width: 300px; float:right">
            <table class="table">
              <tbody>
                <tr>
                  <td>
                    <strong class="text-muted">Sub Total :</strong>
                  </td>
                  <td> {{ $order->total_amount }} </td>
                </tr>
                @if($order->delivery_charge>0)
                <tr>
                  <td>
                    <strong class="text-muted">Shipping :</strong>
                  </td>
                  <td> {{ $order->delivery_charge }} </td>
                </tr>
                @endif
                @if($order->discount)
                <tr>
                  <td>
                    <strong class="text-muted">Discount :</strong>
                  </td>
                  <td> {{ $order->discount }} </td>
                </tr>
                @endif
                <tr>
                  <td>
                    <strong class="text-muted">Total :</strong>
                  </td>
                  <td class="text-muted h5"> {{ $order->paid_amount }} </td>
                </tr>
              </tbody>
            </table>
            <div class="no-print text-right d-none">
              <a href="https://demo.activeitzone.com/ecommerce/invoice/79" type="button" class="btn btn-icon btn-light">
                <i class="las la-print"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>  
  </div>
  </div>

@section('script')
  <script>


  function getProductSizes(id){
    $.get('{{route('product_size_by_ajax')}}', {id:id}, function(data){
        var product = data.product;
        var sizes = data.productSizes;
        var html =`<option value="">--Select--</option>`;
        for(i=0; i<sizes.length; i++){
            html +=`<option value="${sizes[i].id}">${sizes[i].name}</option>`;
        }

        document.getElementById("size_id").innerHTML = html;

        if((product != null && product.is_size=='1')){
            document.getElementById('size_id').required = true;
        }else{
            document.getElementById('size_id').required = false;
        }
    });
  }


    function handleSave(orderNumber) {
        var selectedValue = $('#update_delivery_status').val();
        var sendMail = $('#sendMail').is(':checked');
        

        // Show confirmation dialog
        var userConfirmed = confirm("Are you sure you want to update the order status?");

        if (userConfirmed) {
            updateStatus(orderNumber, selectedValue,sendMail);
        } else {
            // Handle case where user does not confirm
            console.log("User canceled the status update.");
        }
    }

    function updateStatus(orderId, selectedValue,sendMail) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Set CSRF token in the request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        $.ajax({
            url: '/admin/update-status',
            method: 'POST',
            data: {
                orderId: orderId,
                newStatus: selectedValue,
                sendMail:sendMail
            },
            success: function(response) {
                // Handle success response
                // console.log(response);
                alert('Order status updated successfully.');
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error('Error updating status:', error);
                alert('There was an error updating the order status.');
            }
        });     
        }
    function handlePaymentStatusSave(orderNumber) {
            var selectedValue = $('#update_payment_status').val();
            var sendMail = $('#sendMail').is(':checked');
            

            // Show confirmation dialog
            var userConfirmed = confirm("Are you sure you want to update the payment status?");

            if (userConfirmed) {
            updatePaymentStatus(orderNumber, selectedValue,sendMail);
            } else {
                // Handle case where user does not confirm
                console.log("User canceled the status update.");
            }
    }

    function updatePaymentStatus(orderId, selectedValue,sendMail) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Set CSRF token in the request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            $.ajax({
                url: '/admin/update-payment-status',
                method: 'POST',
                data: {
                    orderId: orderId,
                    newStatus: selectedValue,
                    sendMail:sendMail
                },
                success: function(response) {
                    // Handle success response
                    window.location.reload();                
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating status:', error);
                    alert('There was an error updating the order status.');
                }
            });     
        }

        function referenceNumberSave(orderId){
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var ref = document.getElementById("transaction_id").value.trim();
        var ac_no = document.getElementById("account_number").value.trim();
        if(ref != "" && ref != null && ac_no != "" && ac_no != null){
            if(!confirm("Save transaction Info?"))return;
            $.post('{{route('save_reference_number')}}', {order_number:orderId,ac_no:ac_no, ref:ref, _token:csrfToken}, function(data){
                if(data==true){
                alert("Transaction Info Updated");
                }
            });
        }else{
            alert("Enter account number and transaction ID.");
        }
        
        }
    function assignDeliverBoy(orderId, value) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            // Set CSRF token in the request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            $.ajax({
                url: '/admin/assign-delivery-boy',
                method: 'POST',
                data: {
                    orderId: orderId,
                    value: value
                },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating status:', error);
                }
            });
    }
  </script>
@endsection
@endsection




