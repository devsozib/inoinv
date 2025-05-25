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
                      <h4 class="card-title mb-0 flex-grow-1">Add Book</h4>
                      <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{ route('books.index') }}" class="btn btn-info">Book List</a>
                        </div>
                      </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                      <div class="live-preview">
                        <div class="row gy-4">
                            <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="name" class="form-label">Book Name</label>
                                        <input type="text" class="form-control" value="{{ old('name') }}" id="name" name="name" placeholder="Enter product name" required>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="name" class="form-label">Category</label>
                                        <select class="form-select mb-3 select2" name="category[]" multiple >
                                            <option value="">--Select Category--</option>
                                            @foreach ($categories as $item)
                                                <option {{ old('category') == $item->id ? 'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3 d-none">
                                        <label for="name" class="form-label">Sub Category</label>
                                        <select id="sub_category" class="form-select mb-3" name="sub_category">
                                            <option value="" selected>--Select Category--</option>
                                            @foreach ($subCategories as $catId => $items)
                                                @foreach($items as $item)
                                                    <option class="{{ old('category') == $catId ? '' : 'd-none'}} subcategories categoryWise{{$catId}}" {{ old('sub_category') == $item->id ? 'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="" class="form-label">Subject</label>
                                        <select name="subject[]" id="" class="form-select mb-3 select2" required multiple>
                                            <option value="">--Select--</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{$subject->id}}">{{$subject->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="" class="form-label">Writer</label>
                                        <select name="writer[]" id="" class="form-select mb-3 select2" required multiple>
                                            <option value="">--Select--</option>
                                            @foreach($writers as $writer)
                                                <option value="{{$writer->id}}">{{$writer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="" class="form-label">Publisher</label>
                                        <select name="publisher[]" id="" class="form-select mb-3 select2" required multiple>
                                            <option value="">--Select--</option>
                                            @foreach($publishers as $publisher)
                                                <option value="{{$publisher->id}}">{{$publisher->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Price</label>
                                        <input type="number"  class="form-control" name="price" step="0.01" required>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Offer Price</label>
                                        <input type="number"  class="form-control" name="offer_price" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Offer From</label>
                                        <input type="date"  class="form-control" name="offer_from" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label  class="form-label" class="form-label">Offer To</label>
                                        <input type="date"  class="form-control" name="offer_to" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="image" class="form-label">Image(366x366)</label>
                                        <input type="file" class="form-control" id="image" name="images" onchange="previewImages(event)">
                                        <div class="mt-2" id="image-preview-container"></div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="image" class="form-label">Short Readable PDF</label>
                                        <input type="file" class="form-control" id="pdf" name="pdf" >
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select mb-3" name="status" required>
                                            <option selected="" value="1">Actve</option>
                                            <option value="0">InActve</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="editor" name="description" placeholder="Enter product description" rows="3">{{ old('description')}}</textarea>
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
