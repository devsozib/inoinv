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
                      <h4 class="card-title mb-0 flex-grow-1">Edit Product</h4>
                      <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{ route('products.index') }}" class="btn btn-info">Product List</a>
                        </div>
                      </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                      <div class="live-preview">
                        <div class="row gy-4">
                            <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" value="{{ $product->name }}" id="name" name="name" placeholder="Enter product name" required>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="name" class="form-label">Category</label>
                                        <select class="form-select mb-3 select2" name="category[]" multiple>
                                            <option value="">--Select Category--</option>
                                            @foreach ($categories as $item)
                                                <option {{ in_array($item->id , explode(',', $product->category_id)) ? 'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3 d-none">
                                        <label for="name" class="form-label">Sub Category</label>
                                        <select id="sub_category" class="form-select mb-3" name="sub_category" >
                                            <option value="" selected>--Select Category--</option>
                                            @foreach ($subCategories as $catId => $items)
                                                @foreach($items as $item)
                                                    <option class="{{ $product->category_id == $catId ? '' : 'd-none'}} subcategories categoryWise{{$catId}}" {{ $product->sub_category_id == $item->id ? 'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="" class="form-label">Brand</label>
                                        <select name="brand" id="" class="form-select mb-3" required>
                                            <option value="">--Select--</option>
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->id}}" {{$product->brand_id == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3">
                                        <label for="" class="form-label">Select</label><br>
                                        <input onclick="sizeableProduct(this)" type="checkbox" name="is_size" id="is_size" {{$product->is_size == '1' ? 'checked' : ''}}> 
                                        <label for="is_size" class="m-0">Sizeable Product.</label>
                                        <br>
                                        <input onclick="sizeWisePrice(this)" type="checkbox" name="is_size_wise_price" id="is_size_wise_price" {{$product->is_size == '1' ? '' : 'disabled'}} {{$product->is_size_wise_price == '1' ? 'checked' : ''}}> 
                                        <label class="m-0" for="is_size_wise_price">Size wise Price.</label>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3" id="price_section" style="{{$product->is_size_wise_price == '1' ? 'display:none;' : ''}}">
                                        <label  class="form-label" class="form-label">Price</label>
                                        <input type="number"  class="form-control" name="price" id="price" step="0.01" value="{{$product->price}}" >
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3" id="offer_price_section"  style="{{$product->is_size_wise_price == '1' ? 'display:none;' : ''}}">
                                        <label  class="form-label" class="form-label">Offer Price</label>
                                        <input type="number"  class="form-control" name="offer_price" id="offer_price" value="{{$product->offer_price}}" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3" id="offer_from_section"  style="{{$product->is_size_wise_price == '1' ? 'display:none;' : ''}}">
                                        <label  class="form-label" class="form-label">Offer From</label>
                                        <input type="date"  class="form-control" name="offer_from" id="offer_from" value="{{$product->offer_from}}" step="0.01">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 p-2 mb-3" id="offer_to_section"  style="{{$product->is_size_wise_price == '1' ? 'display:none;' : ''}}">
                                        <label  class="form-label" class="form-label">Offer To</label>
                                        <input type="date"  class="form-control" name="offer_to" id="offer_to"  value="{{$product->offer_to}}" step="0.01">
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


@endsection

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

    function sizeableProduct(ele){
        if(ele.checked){
            document.getElementById("is_size_wise_price").disabled = false;
        }else{
            document.getElementById("is_size_wise_price").disabled = true;
            document.getElementById("is_size_wise_price").checked = false;
            sizeWisePrice(document.getElementById("is_size_wise_price"));
        }
    }

    function sizeWisePrice(ele){
        var price = document.getElementById('price');
        var offerPrice = document.getElementById('offer_price');
        var offerFrom = document.getElementById('offer_from');
        var offerTo = document.getElementById('offer_to');

        var priceSection = document.getElementById('price_section');
        var offerPriceSection = document.getElementById('offer_price_section');
        var offerFromSection = document.getElementById('offer_from_section');
        var offerToSection = document.getElementById('offer_to_section');
        if(ele.checked){
            price.value = 0;
            offerPrice.value = "";
            offerFrom.value = "";
            offerTo.value = "";
            priceSection.style.display = 'none';
            offerPriceSection.style.display = 'none';
            offerFromSection.style.display = 'none';
            offerToSection.style.display = 'none';
        }else{
            priceSection.style.display = '';
            offerPriceSection.style.display = '';
            offerFromSection.style.display = '';
            offerToSection.style.display = '';
        }
    }
</script>
<script>
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
