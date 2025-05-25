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

        @if(session('sweet_alert'))
            <script>
                Swal.fire({
                    icon: '{{ session('sweet_alert.type') }}',
                    title: '{{ session('sweet_alert.title') }}',
                    text: '{{ session('sweet_alert.text') }}',
                });
            </script>
        @endif
            <div class="row">
                <div class="col">

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Eit Product Size</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('product_size.update', $productSize->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <input type="number" name="product_id" value="{{$productSize->product_id}}" hidden>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label for="basiInput" class="form-label">Size Name</label>
                                        <select class="form-control" name="size_id" id="size_id">
                                            <option value="">{{ _('--Select Size--') }}</option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $productSize->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6" style="{{$product->is_size_wise_price != '1' ? 'display:none;' : ''}}">
                                        <label for="basiInput" class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" value="{{ $productSize->price }}" step="0.00001" >
                                    </div>
                                    <div class="col-4" style="{{$product->is_size_wise_price != '1' ? 'display:none;' : ''}}">
                                        <label for="basiInput" class="form-label">Offer Price</label>
                                        <input type="number" class="form-control" name="offer_price" step="0.00001" value={{ $productSize->offer_price }}>
                                    </div>
                                    <div class="col-4" style="{{$product->is_size_wise_price != '1' ? 'display:none;' : ''}}">
                                        <label for="basiInput" class="form-label">Offer From</label>
                                        <input type="date" class="form-control" name="offer_from" value={{ $productSize->offer_from }}>
                                    </div>
                                    <div class="col-4" style="{{$product->is_size_wise_price != '1' ? 'display:none;' : ''}}">
                                        <label for="basiInput" class="form-label">Offer To</label>
                                        <input type="date" class="form-control" name="offer_to" value={{ $productSize->offer_to }}>
                                    </div>
                                    <div class="col-6" style="display:none;">
                                        <label for="basiInput" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="quantity" step="0.00001" value="{{ $productSize->quantity }}">
                                    </div>
                                    <div class="col-6">
                                        <label for="basiInput" class="form-label">Image</label>
                                        <input type="file" name="image" class="form-control" onchange="previewImages(event)">
                                        <div class="mt-2" id="image-preview-container"></div>
                                        <ul>                                         
                                            <ul class="list-group list-group-horizontal">
                                                {{-- @foreach ($productImages as $item) --}}
                                                <li class="list-group-item"><img width="60px" height="60px" src="{{ asset('frontend/product_images/' . $product->image) }}" alt="Product Image"></li>
                                                {{-- @endforeach                                           --}}
                                            </ul>                                       
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <label for="basiInput" class="form-label">Status</label>
                                        <select name="status" id="" class="form-control">
                                            @foreach (getStatus() as $key => $status)
                                                <option value="{{$key}}" {{ $productSize->status == $key ? 'selected' : '' }}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="editor" name="description" placeholder="Enter product description" rows="3">{!! $productSize->description !!}</textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                         <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
<script>
    ClassicEditor.create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });

    ClassicEditor.create(document.querySelector('.editorUp'))
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
@endsection
