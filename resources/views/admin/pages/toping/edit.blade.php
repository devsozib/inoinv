@extends('admin.layout.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">          
            <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Edit Toping</h4>
                      <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{ route('topings.index') }}" class="btn btn-info">Toping List</a>
                        </div>
                      </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                      <div class="live-preview">
                        <div class="row gy-4">
                            @if ($errors->any())
                                <div class="alert alert-danger" id="validation-error-alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            
                                <script>
                                    // Set a timeout to hide the alert after 2000 milliseconds (2 seconds)
                                    setTimeout(function () {
                                        document.getElementById('validation-error-alert').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif
                            <form action="{{ route('topings.update',$toping->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="name" class="form-label">Toping Name</label>
                                        <input type="text" class="form-control" id="name" value="{{ $toping->name }}" name="name" placeholder="Enter Toping name" required>
                                    </div>                                                                       
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="price" name="price" placeholder="Enter Toping price"  step="0.00001" value="{{ $toping->price  }}" required>
                                    </div>                                                                      
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select mb-3" name="status">
                                            <option  {{ $toping->status=='1'?'selected':''  }} selected="" value="1">Actve</option>                                            
                                            <option  {{ $toping->status=='0'?'selected':''  }} value="0">InActve</option>                                            
                                        </select>
                                    </div>                                                                                                                                         
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="image" class="form-label">Image URL</label>
                                        <input type="file" multiple class="form-control" id="image" name="images">
                                        <img width="60px" height="60px" src="{{ asset('frontend/toping_images/' . $toping->image) }}" alt="Product Image">
                                    </div>                                                                   
                                </div>
                                <div class="row">
                                    <h4>Size Wise Topping Price</h4><br><br>
                                    <div style="max-width: 500px;">
                                        <div class="row">
                                            <div class="col-6"><h5>Size</h5></div>
                                            <div class="col-6"><h5>Price</h5></div>
                                        </div>
                                        <div id="itemContainer">
                                            @foreach ($sizeVsToppings as $storedSize)
                                            <div class="row mt-2" id="item{{$storedSize['sizeId']}}" data-item="{{$storedSize['sizeId']}}">
                                                <div class="col-6">
                                                    <select onchange="rearrangeSize()" name="sizeId[]" class="form-select itemSize" id="itemSize{{$storedSize['sizeId']}}">
                                                        <option value="">--Select Size--</option>
                                                        <option value="{{$storedSize['sizeId']}}" selected>
                                                            {{$storedSize['name']}}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" name="prices[]" id="itemPrice{{$storedSize['sizeId']}}" class="form-control itemPrice" placeholder="Price"  step="0.00001" value="{{ $storedSize['price'] }}">
                                                </div>
                                                <div class="col-2">
                                                    <button class="btn btn-danger" onclick="removeItem('item{{$storedSize['sizeId']}}')">X</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 item-end">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="addNewItem()">Add One</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </form>
                                               
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
<script>
    ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });

</script>
<script>
    var G_ITEM_NUMBER = {{ count($sizeVsToppings) }} + 1;

    function addNewItem(){
    G_ITEM_NUMBER++;
    
    var addedSizeIds = Array.from(document.querySelectorAll('.itemSize')).map(select => select.value);
    
    var newSizes = @json($sizes).filter(size => !addedSizeIds.includes(String(size.id)));

    var sizeOptions = newSizes.map(size => `<option value="${size.id}">${size.name}</option>`).join('');

    var html = `
        <div class="row mt-2" id="item${G_ITEM_NUMBER}" data-item="${G_ITEM_NUMBER}">
            <div class="col-6">
                <select onchange="rearrangeSize()" name="sizeId[]" class="form-select itemSize" id="itemSize${G_ITEM_NUMBER}">
                    <option value="" >--Select Size--</option>
                    ${sizeOptions}
                </select>
            </div>
            <div class="col-4">
                <input type="number" name="prices[]" id="itemPrice${G_ITEM_NUMBER}"  step="0.00001" placeholder="Price" class="form-control itemPrice">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger" onclick="removeItem('item${G_ITEM_NUMBER}')">X</button>
            </div>
        </div>
    `;

    document.getElementById("itemContainer").insertAdjacentHTML('beforeend', html);
}

function removeItem(id){
    var removedElement = document.getElementById(id);
    removedElement.remove();
    rearrangeSize();
}

function rearrangeSize(){
    var items = document.querySelectorAll('.itemSize');
    
    items.forEach((item, index) => {
        var itemId = `itemSize${index + 1}`;
        var priceId = `itemPrice${index + 1}`;
        var removeButton = `removeItem('item${index + 1}')`;

        item.id = itemId;
        item.parentNode.parentNode.id = `item${index + 1}`;
        item.nextElementSibling.childNodes[1].id = priceId;
        item.parentNode.nextElementSibling.childNodes[1].setAttribute('onclick', removeButton);
    });
}

</script>
@endsection
@endsection