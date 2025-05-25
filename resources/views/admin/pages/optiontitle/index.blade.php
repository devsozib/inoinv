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
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add Option Title</button>
                        <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('optiontitles.store')}}" method="post">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Option Title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="basiInput" class="form-label">Option Title</label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="Option Title" required>
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
                            <h4 class="card-title mb-0 flex-grow-1">Option Title List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Option Title</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sizes as $size)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$size->name}}</td>
                                        <td>{{$size->status == 1 ? "Active" : "Deactive"}}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$size->id}}">
                                                <i class="bx bx-edit"></i>
                                            </a>|<a type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $size->id }}" class="btn btn-sm btn-danger waves-effect waves-light"><i class="ri-delete-bin-line"></i></a>
                                            <div id="myModal{{ $size->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        Are you sure you want to delete this product:
                                                        <strong
                                                            style="color: darkorange">{{ $size->name }}</strong>
                                                        ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('optiontitles.destroy',$size->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-default">Delete</button>
                                                            </form>
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                            <div id="ctegory{{$size->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('optiontitles.update', $size->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Add Option Title</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Option Title</label>
                                                                    <input type="text" class="form-control" id="Option Title" name="title" value="{{$size->name}}" placeholder="Option Title" required>
                                                                </div>
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Status</label>
                                                                    <select name="status" id="" class="form-control">
                                                                        @foreach (getStatus() as $key => $status)
                                                                            <option value="{{$key}}" {{$size->status == 1 ? 'selected' : ''}}>{{$status}}</option>
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
