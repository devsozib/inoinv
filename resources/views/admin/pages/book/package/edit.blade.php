@extends('admin.layout.app')

@section('content')

<style>
    .preview-image {
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
    }
</style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Edit Package</h4>
                      <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{ route('books.package') }}" class="btn btn-info">Package List</a>
                        </div>
                      </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                      <div class="live-preview">
                        <div class="row gy-4">
                            <form action="{{ route('books.package_update', $product->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="name" class="form-label">Package Name</label>
                                        <input type="text" class="form-control" value="{{ $product->name }}" id="name" name="name" placeholder="Enter product name" required>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="name" class="form-label">Books</label>
                                        <select class="form-select mb-3 select2" name="books[]" multiple>
                                            <option value="">--Select Books--</option>
                                            @foreach ($products as $item)
                                                <option {{ in_array($item->id , explode(',', $product->package_item_ids)) ? 'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Price</label>
                                        <input type="number"  class="form-control" name="price" step="0.01" value="{{$product->price}}" required>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Offer Price</label>
                                        <input type="number"  class="form-control" name="offer_price" value="{{$product->offer_price}}" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Offer From</label>
                                        <input type="date"  class="form-control" name="offer_from" value="{{$product->offer_from}}" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Offer To</label>
                                        <input type="date"  class="form-control" name="offer_to" value="{{$product->offer_to}}" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="image" class="form-label">Image(366x366)</label>
                                        <input type="file" class="form-control" id="image" name="images" onchange="previewImages(event)">
                                        <div class="mt-2" id="image-preview-container"></div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select mb-3" name="status" required>
                                            @foreach(getStatus() as $key => $status)
                                                <option value="{{$key}}" {{$product->status == $key ? 'selected' : ''}}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="editor" name="description" placeholder="Enter product description" rows="3">{{$product->description}}</textarea>
                                    <div>    
                                    
                                   

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

    $(document).ready(function() {
            $('.select2').select2();
    });

</script>
<script>
    function previewImages(event) {
        var previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = '';

        var files = event.target.files;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();

            reader.onload = function(e) {
                var imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.classList.add('preview-image');
                previewContainer.appendChild(imgElement);
            };

            reader.readAsDataURL(file);
        }
    }
</script>
<script>
    var G_ITEM_NUMBER = 1;
    function addNewItem(){
        G_ITEM_NUMBER++;
    
        var html = `
            <div class="row mt-2" id="item${G_ITEM_NUMBER}" data-item="${G_ITEM_NUMBER}">
                <div class="col-6">
                    <input type="text" name="tags[]" id="itemSize${G_ITEM_NUMBER}" placeholder="Tag name" class="form-control itemSize">
                </div>
                <div class="col-4">
                    <input type="hidden" name="removeable[${G_ITEM_NUMBER-1}]" value="0">
                    <input type="checkbox" name="removeable[${G_ITEM_NUMBER-1}]" id="itemPrice${G_ITEM_NUMBER}" value="1" class="form-check-input itemPrice">
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
        // rearrangeSize();
    }
    // function rearrangeSize(){
    //     return;
    //     var eles = document.getElementsByClassName("itemSize");
    //     for(i=0; i<eles.length; i++){

    //     }
    // }
    var O_ITEM_NUMBER = 1;

    function removeOptionItem(id) {
        document.getElementById(id).remove();
        // No need to rearrange as we're using unique IDs
    }
    $(document).ready(function() {
            $('.select2').select2();
    });

    function changedCategory(id){
        var eles = document.getElementsByClassName('subcategories');
        for(var i=0; i<eles.length; i++){
            if(eles[i].classList.contains('categoryWise'+id)){
                eles[i].classList.remove('d-none');
            }else{
                eles[i].classList.add('d-none');
            }
        }
        document.getElementById('sub_category').value = "";
    }
</script>
@endsection
@endsection
