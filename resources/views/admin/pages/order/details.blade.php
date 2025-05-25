@extends('admin.layout.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">
  <div class="aiz-main-content">
    <div class="">
      <div class="card">
        <div class="card-header">
          <h1 class="h2 fs-16 mb-0">Order Details of <span style="color:blue">#{{ $order->order_number }}</span></h1>
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
          <div class="row ">            
            <!--Assign Delivery Boy-->
           
            @if ($order->is_paid == '0')
              <div class="col-md-6">
                <label for="update_payment_status">Payment Status</label>      
                <div class="d-flex align-items-center">       
                  <select class="form-control w-50" data-minimum-results-for-search="Infinity" id="update_payment_status" tabindex="-98">
                    <option {{ $order->is_paid == '0'?'selected':'' }} value="0" selected=""> Unpaid </option>
                    <option {{ $order->is_paid == '1'?'selected':'' }} value="1"> Paid </option>
                  </select> 
                  <input style="margin-left: 10px" name="sendMail" id="sendMail" type="checkbox">Send mail?
                  <button style="margin-left: 10px" class="btn btn-primary"  onclick="handlePaymentStatusSave('{{ $order->order_number }}')">Save</button>   
                </div>                         
              </div>
            @endif
            
            <div class="col-md-6">              
              <label for="update_delivery_status">Order Status</label>             
              <div class="d-flex align-items-center">
                <select class="form-control w-50" data-minimum-results-for-search="Infinity" id="update_delivery_status" tabindex="-98">                 
                    @php
                        // Define the order statuses based on the user's role
                        $userRole = auth()->check() ? checkRole() : null;                  
                        if ($userRole == 'Salesman') {
                            $statuses = [
                                '2' => 'Processing',
                                '4' => 'Out for Delivery',
                                '6' => 'Canceled',
                            ];
                        } elseif ($userRole == 'Delivery Boy') {
                            $statuses = [
                                '4' => 'Out for Delivery',
                                '5' => 'Delivered',
                            ];
                        } else {
                            $statuses = orderStatuses();
                        }
                    @endphp
                    <option value="" selected disabled>--Status--</option>
                    @foreach ($statuses as $value => $text)                                             
                        <option {{ $value == $order->order_status ? 'selected' : '' }} value="{{ $value }}">{{ $text }}</option> 
                    @endforeach   
                </select>
                <input style="margin-left: 10px" name="sendMail" id="sendMail" type="checkbox">Send mail?
                <button style="margin-left: 10px" class="btn btn-primary"  onclick="handleSave('{{ $order->order_number }}')">Save</button>   
              </div>                  
            </div> 
          </div>  
          
          @if ($order->payment_method != '1')
          <hr>
            <div class="row">
                <div class="col-12 col-md-6">
                  <label for="update_payment_status">Account Number</label>      
                  <div class="d-flex align-items-center mr-3" style="margin-right: 5px;">       
                    <input class="form-control " type="text" id="account_number" value="{{$order->account_number}}">
                  </div>                         
                </div>
                <div class="col-12 col-md-6">
                  <label for="update_payment_status">Transaction Number</label>      
                  <div class="d-flex align-items-center">       
                    <input class="form-control" type="text" id="transaction_id" value="{{$order->transaction_id}}">
                    <button style="margin-left: 10px" class="btn btn-primary"  onclick="referenceNumberSave('{{ $order->order_number }}')">Save</button>   
                  </div>                         
                </div>
              </div> 
              <hr> 
            @endif
                
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
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center" style="display: table-cell;">Qty</th>                    
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center" style="display: table-cell;">Price</th> 
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center footable-last-visible" style="display: table-cell;">Total Price</th>
                    <th  data-breakpoints="lg" class="min-col text-uppercase text-center footable-last-visible d-none" style="display: table-cell;">Action</th>
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
                    <td class="text-center" style="display: table-cell;"> {{ $item->quantity }} </td>                    
                    <td class="text-center footable-last-visible" style="display: table-cell;"> {{ $item->sale_price }} </td>                  
                    <td class="text-center footable-last-visible" style="display: table-cell;"> {{ $item->sale_price*$item->quantity }} </td>
                    <td class="d-none">
                      <div class="d-flex justify-content-center " >
                        <a href="{{route('edit-order',$order->order_number)}}" class="btn btn-sm  btn-primary">Edit Order</a>
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




