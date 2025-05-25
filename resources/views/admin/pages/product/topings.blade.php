@extends('admin.layout.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                @if(session('sweet_alert'))
                    <script>
                        Swal.fire({
                            icon: '{{ session('sweet_alert.type') }}',
                            title: '{{ session('sweet_alert.title') }}',
                            text: '{{ session('sweet_alert.text') }}',
                        });
                    </script>
                @endif
                <div class="col">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Assign Product Topings</button>
                        <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('product_toping.store')}}" method="post">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Assign Product Topings</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <input type="number" name="product_id" value="{{$id}}" hidden>
                                            </div>
                                            <div>
                                                <label for="basiInput" class="form-label">Toping</label>
                                                <select class="form-select mb-3 select2" name="toping">
                                                    <option selected>--Select Category--</option>
                                                    @foreach ($topings as $item)                                                    
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>   
                                                    @endforeach                                                                                                                                                                           
                                                </select>
                                            </div>                                          
                                            <div>
                                                <label for="basiInput" class="form-label">Status</label>
                                                <select name="status" id="" class="form-control">
                                                    @foreach (getStatus() as $key => $status) 
                                                        <option value="{{$key}}" {{$key == 1 ? 'selected' : ''}}>{{$status}}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary ">Save</button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Product Topings List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productTopings as $item)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td><img width="60px" height="60px" src="{{ asset('frontend/toping_images/' . $item->image) }}" alt="Toping Image">  </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->status == 1 ? "Active" : "Deactive"}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$item->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button> | <button type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $item->id }}" class="btn btn-sm btn-danger waves-effect waves-light"><i class="ri-delete-bin-line"></i></button>
                                            </button>
                                            <!-- Default Modals -->
                                            <div id="myModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        Are you sure you want to delete this Toping:
                                                        <strong
                                                            style="color: darkorange">{{ $item->name }}</strong>
                                                        ?
                                                        </div>
                                                        <div class="modal-footer">
                                                        
                                                            <form
                                                                action="{{ route('topings.destroy',$item->topId) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <input type="hidden" name="deleteProductToping" value="1">
                                                                <input type="hidden" name="product_id" value=" {{$id}} ">
                                                                <button type="submit" class="btn btn-default">Delete</button>
                                                                
                                                            </form>
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>

                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->  
                                            <div id="ctegory{{$item->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('categories.update', $item->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Edit Category</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Toping</label>
                                                                    <select class="form-select mb-3 select2" name="toping">
                                                                        @foreach ($topings as $toping)
                                                                            <option selected>--Select Category--</option>
                                                                            <option {{ $toping->id == $item->toping_id?'selected':'' }} value="{{ $toping->id }}">{{ $toping->name }}</option>   
                                                                        @endforeach                                                                                                                                                                           
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Status</label>
                                                                    <select name="status" id="" class="form-control">
                                                                        @foreach (getStatus() as $key => $status) 
                                                                            <option value="{{$key}}" {{$item->status == 1 ? 'selected' : ''}}>{{$status}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary ">Save</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>


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