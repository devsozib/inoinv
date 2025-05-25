@extends('admin.layout.app')

@section('content')
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

                    <div class="card-header align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Books</h4>
                      <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{ route('books.create') }}" class="btn btn-info">Create Book</a>
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
                                    <th>Images</th>
                                    <th>Category</th>                                 
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Writer</th>
                                    <th>Publisher</th>
                                    <th>Price</th>                                 
                                    <th>status</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach ($products as $item)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>
                                      <img width="60px" height="60px" src="{{ asset('frontend/product_images/' . $item->image) }}" alt="Book Image">
                                    </td>
                                    <td>{{ get_names($categories, $item->category_id) }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ get_names($subjects, $item->subject_id) }}</td>
                                    <td>{{ get_names($writers, $item->writer_id) }}</td>
                                    <td>{{ get_names($publishers, $item->publisher_id) }}</td>
                                    <td>{{ $item->price }}</td>                                 
                                    <td class="{{ $item->status=='1'?'text-danger':'' }}">{{ $item->status=='1'?'Active':'Inactive' }}</td>
                                      <td>
                                        <a href="{{ route('books.edit',$item->id) }}"><button type="button" class="btn btn-sm btn-primary waves-effect waves-light"><i class="ri-ball-pen-line" style="color: #fff"></i> </button></a>
                                       | <button type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $item->id }}" class="btn btn-sm btn-danger waves-effect waves-light"><i class="ri-delete-bin-line"></i></button>
                                      <div class="btn-group material-shadow d-none">
                                          <button class="btn btn-primary btn-sm  material-shadow-none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                          <i class="las la-angle-double-down"></i>
                                          </button>
                                          <div class="dropdown-menu " data-popper-placement="top-start" data-popper-reference-hidden="" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -30px);">
                                              <a class="dropdown-item" href="{{route('product_size',$item->id)}}">Size</a>
                                              <a class="dropdown-item" href="{{ route('product_topting',$item->id) }}">Topings</a>
                                          </div>
                                      </div>
                                    </td>
                                    <!-- Default Modals -->
                                    <div id="myModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                </div>
                                                <div class="modal-body">
                                                  Are you sure you want to delete this book:
                                                  <strong
                                                      style="color: darkorange">{{ $item->name }}</strong>
                                                  ?
                                                </div>
                                                <div class="modal-footer">

                                                    <form
                                                        action="{{ route('books.destroy',$item->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-default">Delete</button>

                                                    </form>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                </div>

                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
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

@endsection
@endsection
