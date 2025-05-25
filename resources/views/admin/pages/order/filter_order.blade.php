@foreach ($orders as $item)
<tr>
    <th class="orderSl">{{ $loop->index+1 }}</th>                                    
    <td>{{ $item->order_number }}</td>                                   
    <td>{{ getUser($item->customer_id) }}</td>                                                   
    <td>{{ getUserPhone($item->customer_id) }}</td>                                                   
    <td>{{ $item->total_amount }}</td>                                  
    <td>{{ $item->paid_amount }}</td>                                                                
    <td>{{ $item->is_paid == '0'?'Unpaid':'Paid' }}</td>
    <td>
      @php                                    
          $orderStatuses = orderStatuses();
          $status = $orderStatuses[$item->order_status] ?? 'Unknown Status';
      @endphp
      {{ $status }}
    </td> 
    <td>
        <a class="btn btn-info" href="{{ route('order.details',$item->order_number) }}">Details</a>    
    </td>                                                                                                                                                                                                 
  </tr>
@endforeach