@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Menu List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Home View</th>
                                        <th scope="col">Nav View</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menus as $item)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->status == 1 ? "Active" : "Deactive"}}</td>
                                        <td>
                                            <div class="form-check form-switch" style="padding: 0px;">
                                                <input onclick="showOnHome({{$item->id}}, this)" class="form-check-input mb-2" style="margin-left: 0.5em !important;" type="checkbox" role="switch" name="home_view{{ $item->id }}" id="home_view{{ $item->id }}" value="{{ $item->is_home_view }}" {{$item->is_home_view==1 ? 'checked' : ''}}/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch" style="padding: 0px;">
                                                <input onclick="showOnNav({{$item->id}}, this)" class="form-check-input mb-2" style="margin-left: 0.5em !important;" type="checkbox" role="switch" name="nav_view{{ $item->id }}" id="nav_view{{ $item->id }}" value="{{ $item->is_nav_view }}" {{$item->is_nav_view==1 ? 'checked' : ''}}/>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#menuItem{{$item->id}}">
                                            <i class="bx bx-edit"></i>
                                            </button>

                                            <div id="menuItem{{$item->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <form action="{{ route('menu.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="myModalLabel">Update Menu Item</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div>
                                                                        <label for="basiInput" class="form-label">Name</label>
                                                                        <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" placeholder="Name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary ">Update</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                </form>
                                                @if (!empty(Session::get('error_code')) && Session::get('error_code') == $item->id)
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            var modalId = 'edit-modal{{ $item->id }}';
                                                            var modalElement = document.getElementById(modalId);
                                                    
                                                            if (modalElement) {
                                                                var modal = new bootstrap.Modal(modalElement);
                                                                modal.show();
                                                            }
                                                        });
                                                    </script>
                                                @endif
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

<script>
    function showOnHome(id,ele){
        var homeView = ele.checked;
        $.post('{{route('menus.showOnHome')}}', {id:id,homeView:homeView,_token:'{{csrf_token()}}'}, function(data){
            flashMessage(data.type,data.message);
        });
    }

    function showOnNav(id,ele){
        var navView = ele.checked;
        $.post('{{route('menus.showOnNav')}}', {id:id,navView:navView,_token:'{{csrf_token()}}'}, function(data){
            flashMessage(data.type,data.message);
        });
    }
</script>
