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
            @if ($errors->any())
                <div class="alert alert-danger" id="validation-error-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <script>
                    // Set a timeout to hide the alert after 2000 milliseconds (2 seconds)
                    setTimeout(function () {
                        document.getElementById('validation-error-alert').style.display = 'none';
                    }, 3000);
                </script>
            @endif
            <div class="row">
                <div class="col">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add Category</button>
                        <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('book-categories.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="basiInput" class="form-label">Category</label>
                                                <input type="text" class="form-control" id="category" name="category" placeholder="Category" required>
                                            </div>
                                            <div>
                                                <label for="basiInput" class="form-label">Order By</label>
                                                <input type="text" class="form-control" id="order_by" name="order_by" placeholder="Order By" required>
                                            </div>
                                            <div>
                                                <label for="slider" class="form-label">Image(Dimensions should be 370x260 pixels.)</label>
                                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="selectImage" name="image">                                   
                                                <img id="preview" src="#" alt="your image"
                                                class="mt-3" style="display:none; width:200px" />
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="description">description</label>
                                                <textarea name="description" class="form-control"  id="" cols="30" rows="10" placeholder="Write description"></textarea>
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
                            <h4 class="card-title mb-0 flex-grow-1">Category List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Order By</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Home View</th>
                                        <th scope="col">Nav View</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td><img style="width:100px; border-radius:5px" src="{{ asset('frontend/assets/images/category/'.$category->image) }}" alt=""></td>                                       
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->order_by}}</td>
                                        <td>{{$category->status == 1 ? "Active" : "Deactive"}}</td>
                                        <td>
                                            <div class="form-check form-switch" style="padding: 0px;">
                                                <input onclick="showOnHome({{$category->id}}, this)" class="form-check-input mb-2" style="margin-left: 0.5em !important;" type="checkbox" role="switch" name="home_view{{ $category->id }}" id="home_view{{ $category->id }}" value="{{ $category->is_home_view }}" {{$category->is_home_view==1 ? 'checked' : ''}}/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch" style="padding: 0px;">
                                                <input onclick="showOnNav({{$category->id}}, this)" class="form-check-input mb-2" style="margin-left: 0.5em !important;" type="checkbox" role="switch" name="nav_view{{ $category->id }}" id="nav_view{{ $category->id }}" value="{{ $category->is_nav_view }}" {{$category->is_nav_view==1 ? 'checked' : ''}} />
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$category->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>| <button type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $category->id }}" class="btn btn-sm btn-danger waves-effect waves-light"><i class="ri-delete-bin-line"></i></button>
                                            <!-- Default Modals -->
                                            <div id="myModal{{ $category->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        Are you sure you want to delete this product:
                                                        <strong
                                                            style="color: darkorange">{{ $category->name }}</strong>
                                                        ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('book-categories.destroy',$category->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-default">Delete</button>
                                                            </form>
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                                <div id="ctegory{{$category->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('book-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Add Category</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Category</label>
                                                                    <input type="text" class="form-control" id="category" name="category" value="{{$category->name}}" placeholder="Category" required>
                                                                </div>
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Order By</label>
                                                                    <input type="text" class="form-control" id="order_by" value="{{$category->order_by}}" name="order_by" placeholder="Order By" required>
                                                                </div>
                                                                <div>
                                                                    <label for="editSliderImage" class="form-label">Image(Dimensions should be 370x260 pixels.)</label>
                                                                    <input type="file" class="form-control" id="editSliderImage" name="uimage">
                                                                    @if ($category->image)
                                                                        <div id="image_sec">
                                                                            <div class="form-group">
                                                                                <img style="width:200px; border-radius:10px; margin-to:6px"
                                                                                    id="image-preview"
                                                                                    src="{{ asset('frontend/assets/images/category/'.$category->image) }}"
                                                                                    alt="Preview">
                                                                            </div>
                                                                        </div>
                                                                    @endif                                                                    
                                                                    @error('uimage')
                                                                        <div class="invalid">{{ $message }}</div>
                                                                    @enderror
                                                                </div> 
                                                                <div>
                                                                    <label for="description">description</label>
                                                                    <textarea name="description" class="form-control"  id="" cols="30" rows="10" placeholder="Write description">{{ $category->description }}</textarea>
                                                                </div> 
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Status</label>
                                                                    <select name="status" id="" class="form-control">
                                                                        @foreach (getStatus() as $key => $status)
                                                                            <option value="{{$key}}" {{$category->status == 1 ? 'selected' : ''}}>{{$status}}</option>
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
                                                @if (!empty(Session::get('error_code')) && Session::get('error_code') == $category->id)
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var modalId = 'edit-modal{{ $category->id }}';
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
    <script type="text/javascript">
        selectImage.onchange = evt => {
            preview = document.getElementById('preview');
            preview.style.display = 'block';
            const [file] = selectImage.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var fileInput = document.getElementById('editSliderImage');
            var imageSection = document.getElementById('image_sec');
            var imagePreview = document.getElementById('image-preview');

            fileInput.addEventListener('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imageSection.classList.remove('d-none');
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    <script>
        function showOnHome(id,ele){
            var homeView = ele.checked;
            $.post('{{route('book-categories.showOnHome')}}', {id:id,homeView:homeView,_token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }

        function showOnNav(id,ele){
            var navView = ele.checked;
            $.post('{{route('book-categories.showOnNav')}}', {id:id,navView:navView,_token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }
    </script>
@endsection


