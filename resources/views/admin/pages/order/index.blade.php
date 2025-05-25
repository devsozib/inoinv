@extends('admin.layout.app')

@section('content')
<style>
  #toaster-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    max-width: 300px;
    background-color: #333;
    color: #fff;
    padding: 15px;
    border-radius: 5px;
    display: none;
  }
</style>
    <div id="toaster-container"></div>
    <div class="page-content">
        <div class="container-fluid">
          
            <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    @if(session('sweet_alert'))
                        <script>
                            Swal.fire({
                                icon: '{{ session('sweet_alert.type') }}',
                                title: '{{ session('sweet_alert.title') }}',
                                text: '{{ session('sweet_alert.text') }}',
                            });
                        </script>
                    @endif
                    @php
                       $orderStatuses = orderStatuses();
                    @endphp
                    <div class="card-header align-items-center">
                      <div class="">
                          <div class="row">
                            <div class="col-12 col-md-4 col-lg-2 " style="padding: 0px 5px;">
                              <label for="">From Date</label>
                              <input type="date" id="from_date" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 col-lg-2" style="padding: 0px 5px;">
                              <label for="">To Date</label>
                              <input type="date" id="to_date" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 col-lg-2" style="padding: 0px 5px;">
                              <label for="">Order Number</label>
                              <input type="text" id="order_number" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 col-lg-2" style="padding: 0px 5px;">
                              <label for="">Order Status</label>
                              <select id="order_status" class="form-select">
                                <option value="">--Select--</option>
                                @foreach ($orderStatuses as $key => $status)
                                  <option value="{{$key}}">{{$status}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2" style="padding: 0px 5px;">
                              <label for="">Payment Status</label>
                              <select id="is_paid" class="form-select">
                                <option value="">--Select--</option>
                                <option value="0">Unpaid</option>
                                <option value="1">Paid</option>
                              </select>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2">
                              <div class="d-flex justify-content-end">
                                <button onclick="filterOrders()" type="button" class="from-input btn btn-primary " style="margin-top: 30px;">Search</button>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                      <div class="live-preview">
                        <div class="row gy-4">
                            <table class="table" id="dataTbl">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>                                                                    
                                    <th>Phone</th>                                                                    
                                    <th>Total amount</th>                                                                  
                                    <th>Payable Amount</th>                                                                                                     
                                    <th>Payment Status</th>   
                                    <th>Status</th>                                   
                                    <th>Action</th>                                   
                                  </tr>
                                </thead>
                                <tbody id="tbodyContainer">
                                  @foreach ($orders as $item)
                                  <tr>
                                      <th class="orderSl">{{ $loop->index+1 }}</th>                                    
                                      <td>{{ $item->order_number }}</td>                                   
                                      <td>{{ getUser($item->customer_id) }}</td>                                                   
                                      <td>{{ getUserPhone($item->customer_id) }}</td>                                                   
                                      <td>{{ $item->total_amount }}</td>                                  
                                      <td>{{ $item->paid_amount }}</td>                                                                
                                      <td>{{ $item->is_paid == '0'?'Unpaid':'Paid' }}</td>                                                                
                                      {{-- <td>{{ $item->delivery_address_id }}</td>                                   --}}
                                      <td>
                                        @php
                                            $status = $orderStatuses[$item->order_status] ?? 'Unknown Status';
                                        @endphp
                                        {{ $status }}
                                      </td> 
                                      <td>
                                          <a class="btn btn-info" href="{{ route('order.details',$item->order_number) }}">Details</a>    
                                      </td>                                                                                                                                                                                                 
                                    </tr>
                                  @endforeach
                                </tbody>
                              </table>                                               
                        </div>
                        <!--end row-->
                      </div>
                    </div>
                  </div>
                </div>
                <!--end col-->
              </div>

        </div>
        <!-- container-fluid -->
    </div>

@section('script')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
  var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
      cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
      encrypted: true
  });
  var channel = pusher.subscribe('order');
  channel.bind('place-order', function(data) {
      var oldItem = document.getElementById('tbodyContainer').innerHTML;
      document.getElementById('tbodyContainer').innerHTML = data.order+oldItem;

      var elements = document.getElementsByClassName("orderSl");
      for (var i = 0; i < elements.length; i++) {
        elements[i].innerHTML = i+1;
      }
      showToaster('New order added.');
  });


  function filterOrders(){
    var from_date = document.getElementById("from_date").value;
    var to_date = document.getElementById("to_date").value;
    var order_number = document.getElementById("order_number").value;
    var order_status = document.getElementById("order_status").value;
    var is_paid = document.getElementById("is_paid").value;

    $.post('{{route('filter_orders')}}', {from_date:from_date,to_date:to_date,order_number:order_number,order_status:order_status,is_paid:is_paid,_token:'{{csrf_token()}}'}, function(data){
      document.getElementById('tbodyContainer').innerHTML = data;
    })

  }

</script>


  <script>
    function updateStatus(orderId, newStatus){
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
                newStatus: newStatus
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

<script>
  // Function to show the toaster
  function showToaster(message) {
    var toaster = document.createElement('div');
    toaster.id = 'toaster';
    toaster.innerHTML = message;
    document.getElementById('toaster-container').appendChild(toaster);
    document.getElementById('toaster-container').style.display = 'block';
    setTimeout(function () {
      hideToaster();
    }, 3000);
  }
  showToaster('New order added.');
  function hideToaster() {
    var toaster = document.getElementById('toaster');
    toaster.parentNode.removeChild(toaster);
    document.getElementById('toaster-container').style.display = 'none';
  }
</script>
@endsection
@endsection





