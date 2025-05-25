@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
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
            <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Create Toping</h4>
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
                            <form action="{{ route('topings.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="name" class="form-label">Toping Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Toping name" required>
                                    </div>                                                                    
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="price" name="price" placeholder="Enter Toping price"  step="0.00001" required>
                                    </div>                                                                    
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select mb-3" name="status">
                                            <option selected="" value="1">Actve</option>                                            
                                            <option value="0">InActve</option>                                            
                                        </select>
                                    </div>                                                                                                                                        
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" multiple class="form-control" id="image" name="images">
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
                                                <div class="row mt-2" id="item1" data-item="1">
                                                    <div class="col-6">
                                                        <select onchange="rearrangeSize()" name="sizeId[]" class="form-select itemSize" id="itemSize1">
                                                            <option value="" >--Select Size--</option>
                                                            @foreach ($sizes as $size)
                                                                <option value="{{$size->id}}">{{$size->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="number" name="prices[]" id="itemPrice1" class="form-control itemPrice" placeholder="Price"  step="0.00001">
                                                    </div>
                                                    <div class="col-2">
                                                        <button class="btn btn-danger" onclick="removeItem('item1')">X</button>
                                                    </div>
                                                </div>
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
    var G_ITEM_NUMBER = 1;
    function addNewItem(){
        G_ITEM_NUMBER++;
        var html = `
            <div class="row mt-2" id="item${G_ITEM_NUMBER}" data-item="${G_ITEM_NUMBER}">
                <div class="col-6">
                    <select onchange="rearrangeSize()" name="sizeId[]" class="form-select itemSize" id="itemSize${G_ITEM_NUMBER}">
                        <option value="" >--Select Size--</option>
                        @foreach ($sizes as $size)
                            <option value="{{$size->id}}">{{$size->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" name="prices[]" id="itemPrice${G_ITEM_NUMBER}" class="form-control itemPrice" placeholder="Price" step="0.00001">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger" onclick="removeItem('item${G_ITEM_NUMBER}')">X</button>
                </div>
            </div>
        `;

        document.getElementById("itemContainer").insertAdjacentHTML('beforeend', html);
    }
    function removeItem(id){
        document.getElementById(id).remove();
        rearrangeSize();
    }
    function rearrangeSize(){
        return;
        var eles = document.getElementsByClassName("itemSize");
        for(i=0; i<eles.length; i++){

        }
    }
</script>
@endsection
@endsection