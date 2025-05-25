@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add Area</button>
                        <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('settings.area.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Area</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="basiInput" class="form-label">District</label>
                                                <select name="district_id" id="" class="form-control">
                                                    @foreach ($lib_districts as $key => $name)
                                                        <option value="{{$key}}">{{$name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="basiInput" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="name" required>
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
                            <h4 class="card-title mb-0 flex-grow-1">Area List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($areas as $item)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{getArrayData($lib_districts, $item->district_id)}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->status == 1 ? "Active" : "Deactive"}}</td>
                                        
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$item->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>|<button type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $item->id }}" class="btn btn-sm btn-danger waves-effect waves-light"><i class="ri-delete-bin-line"></i></button>
                                            <div id="myModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        Are you sure you want to delete this product:
                                                        <strong
                                                            style="color: darkorange">{{ $item->name }}</strong>
                                                        ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('settings.area.delete',$item->id) }}" method="post">
                                                                @csrf
                                                                <button type="submit" class="btn btn-default">Delete</button>
                                                            </form>
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                            <div id="ctegory{{$item->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('settings.area.update', $item->id) }}" method="POST"  enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Update Area</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="basiInput" class="form-label">District</label>
                                                                    <select name="district_id" id="" class="form-control">
                                                                        @foreach ($lib_districts as $key => $name)
                                                                            <option value="{{$key}}" {{$key == $item->district_id ? 'selected' : ''}}>{{$name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="name" class="form-label">Area</label>
                                                                    <input type="text" class="form-control" id="name" name="name"value="{{$item->name}}" placeholder="Name" required>
                                                                </div>
                                                                
                                                                <div class="d-block">
                                                                    <label for="" class="form-label">Status</label>
                                                                    <select name="status" id="" class="form-control">
                                                                        @foreach (getStatus() as $key => $status)
                                                                            <option value="{{$key}}" {{$item->status == 1 ? 'selected' : ''}}>{{$status}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary ">Update</button>
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
