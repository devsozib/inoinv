@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add Writer</button>
                        <div id="myModal" class="modal modal-lg fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('writer.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Writer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="basiInput" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="name" required>
                                            </div>
                                            <div>
                                                <label for="basiInput" class="form-label">Image</label>
                                                <input type="file" class="form-control" id="image" name="image" placeholder="image">
                                            </div>
                                            <div>
                                                <label for="details_add" class="form-label">Details</label>
                                                <textarea class="form-control editor" name="details" id="details_add" rows="5"></textarea>
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
                            <h4 class="card-title mb-0 flex-grow-1">Writer List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Home View</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($writers as $item)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td><img style="height: 100px; width:100;" src="{{asset('frontend/writer_images/'.$item->image)}}" alt=""></td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->status == 1 ? "Active" : "Deactive"}}</td>
                                        <td>
                                            <div class="form-check form-switch" style="padding: 0px;">
                                                <input onclick="showOnHome({{$item->id}}, this)" class="form-check-input mb-2" style="margin-left: 0.5em !important;" type="checkbox" role="switch" name="whome_view{{ $item->id }}" id="home_view{{ $item->id }}" value="{{ $item->is_home_view }}" {{$item->is_home_view==1 ? 'checked' : ''}}/>
                                            </div>
                                        </td>
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
                                                            <form action="{{ route('writer.destroy',$item->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-default">Delete</button>
                                                            </form>
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                            <div id="ctegory{{$item->id}}" class="modal modal-lg fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('writer.update', $item->id) }}" method="POST"  enctype="multipart/form-data">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Add Writer</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="name" class="form-label">Writer</label>
                                                                    <input type="text" class="form-control" id="name" name="name"value="{{$item->name}}" placeholder="Name" required>
                                                                </div>
                                                                <div>
                                                                    <label for="image" class="form-label">Image</label>
                                                                    <input type="file" class="form-control" id="image" name="image"value="" placeholder="Image">
                                                                    <img style="height:100px; width:100px;" src="{{asset('frontend/writer_images/'.$item->image)}}" alt="">
                                                                </div>
                                                                <div>
                                                                    <label for="details_{{$item->id}}" class="form-label">Details</label>
                                                                    <textarea class="form-control editor" name="details" id="details_{{$item->id}}" rows="5">{{$item->details}}</textarea>
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
@section('script')
    <script>
        document.querySelectorAll('.editor').forEach(editorElement => {
            ClassicEditor
            .create(editorElement)
            .catch(error => {
                console.error(error);
            });
        });

    </script>
    <script>
        function showOnHome(id,ele){
            var homeView = ele.checked;
            $.post('{{route('writer.showOnHome')}}', {id:id,homeView:homeView,_token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }
    </script>
@endsection
