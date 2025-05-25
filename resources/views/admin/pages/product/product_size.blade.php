@extends('admin.layout.app')

@section('content')
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
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <a href="{{ route('product_size.create', $id) }}" class="btn btn-sm btn-primary mb-2">Add Size</a>

                    </div>

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Product Size List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Size Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Offer Price</th>
                                        <th scope="col">Offer From</th>
                                        <th scope="col">Offer To</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">image</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productSizes as $productSize)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$productSize->name}}</td>
                                        <td>{{$productSize->price}}</td>
                                        <td>{{$productSize->offer_price}}</td>
                                        <td>{{$productSize->offer_from}}</td>
                                        <td>{{$productSize->offer_to}}</td>
                                        <td>{{$productSize->quantity}}</td>
                                        <td>{{$productSize->status == 1 ? "Active" : "Deactive"}}</td>
                                        <td stype="padding: 5px;">{!!  $productSize->description !!}</td>
                                        <td><img src="{{asset('frontend/product_images/'.$productSize->image)}}" alt="" style="width:40px; height: 40px;"></td>
                                        <td>
                                            <a href="{{ route('product_size.edit', $productSize->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            |<button type="button" data-bs-toggle="modal" data-bs-target="#delete{{ $productSize->id }}" class="btn btn-sm btn-danger waves-effect waves-light">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <div class="btn-group material-shadow d-none">
                                                <button class="btn btn-primary btn-sm  material-shadow-none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="las la-angle-double-down"></i>
                                                </button>
                                                <div class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -30px);">
                                                    <a class="dropdown-item" href="javascript:void(0)">Product Nutritions</a>
                                                </div>
                                            </div>

                                            <!-- Default Modals -->
                                            <div id="delete{{ $productSize->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        Are you sure you want to delete this Size:
                                                        <strong
                                                            style="color: darkorange">{{ $productSize->name }}</strong>
                                                        ?
                                                        </div>
                                                        <div class="modal-footer">

                                                            <form
                                                                action="{{ route('productSize.destroy',$productSize->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <input type="hidden" name="deleteProductToping" value="1">
                                                                <button type="submit" class="btn btn-default">Delete</button>

                                                            </form>
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>

                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
@endsection
