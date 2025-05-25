
<tr>
    <td class="orderSl"></td>                                    
    <td>{{ $item->order_number }}</td>                                   
    <td>{{ getUser($item->customer_id) }}</td>                                  
    <td>{{ $item->type =='2'?'Pickup/Dine in':'Delivery' }}</td>                                  
    <td>{{ $item->total_amount }}</td>                                  
    <td>{{ $item->paid_amount }}</td>                                                                
    <td>{{ $item->delivery_address_id }}</td>                                  
    <td>
        <select class="form-select rounded-pill mb-3" onchange="updateStatus('{{ $item->order_number }}',this.value)">
            @foreach (orderStatuses() as $value => $text)
                <option {{ $value == $item->order_status ? 'selected' : '' }} value="{{ $value }}">{{ $text }}</option> 
            @endforeach                                                                                 
        </select>
    </td> 
    <td>
        <a class="btn btn-info" href="{{ route('order.details',$item->order_number) }}">Details</a>    
    </td>                                                                                                                                                                                              
  </tr>