@extends('admin.layout.app')

@section('content')
<style>
    .invalid{
        color: red;
    }
</style>
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card p-3">
                        @if(session('sweet_alert'))
                            <div class="alert alert-{{ session('sweet_alert.type') }} alert-dismissible fade show" role="alert">
                                <strong>{{ session('sweet_alert.title') }}</strong> {{ session('sweet_alert.text') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="slider" class="form-label">Image(Dimensions should be 746x374 pixels.)</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="selectImage" name="image" required>                                   
                                    <img id="preview" src="#" alt="your image"
                                    class="mt-3" style="display:none; width:200px" />
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="sequence" class="form-label">URL</label>
                                    <input type="text" placeholder="URL" class="form-control @error('URL') is-invalid @enderror" id="URL" name="URL">
                                    @error('URL')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="sequence" class="form-label">Sequence</label>
                                    <input type="number" placeholder="Sequence" class="form-control @error('sequence') is-invalid @enderror" id="sequence" name="sequence">
                                    @error('sequence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        @foreach (getStatus() as $key => $status)
                                            <option value="{{ $key }}" {{ $key == old('status', 1) ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <button type="submit" class="btn btn-primary mt-1">Add Slider</button>
                                </div>
                            </div>
                        </form>
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Slider List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100" id="dataTbl">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Slider</th>  
                                        <th scope="col">Sequence</th>                                 
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sliders as $slider)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>                                       
                                        <td><img style="width:100px; border-radius:5px" src="{{ asset('frontend/assets/images/slider/'.$slider->image) }}" alt=""></td>                                       
                                        <td>{{$slider->sequence}}</td>                                        
                                        <td>{{getStatus()[$slider->status]}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-modal{{$slider->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>| <button type="button" data-bs-toggle="modal" data-bs-target="#myModal{{ $slider->id }}" class="btn btn-sm btn-danger waves-effect waves-light"><i class="ri-delete-bin-line"></i></button>
                                            <div id="myModal{{ $slider->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                        </div>
                                                        <div class="modal-body">
                                                          Are you sure you want to delete this Slider:                                                         
                                                          ?
                                                        </div>
                                                        <div class="modal-footer">
        
                                                            <form
                                                                action="{{ route('slider.destroy',$slider->id) }}"
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
                                            <div id="edit-modal{{$slider->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <form action="{{ route('slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="myModalLabel">Update Slider</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="col-12 col-md-12 col-lg-12">
                                                                        <label for="editSliderImage" class="form-label">Image(Dimensions should be 746x374 pixels.)</label>
                                                                        <input type="file" class="form-control" id="editSliderImage" name="uimage">
                                                                        @if ($slider->image)
                                                                            <div id="image_sec">
                                                                                <div class="form-group">
                                                                                    <img style="width:200px; border-radius:10px; margin-to:6px"
                                                                                        id="image-preview"
                                                                                        src="{{ asset('frontend/assets/images/slider/'.$slider->image) }}"
                                                                                        alt="Preview">
                                                                                </div>
                                                                            </div>
                                                                        @endif                                                                    
                                                                        @error('uimage')
                                                                            <div class="invalid">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>  
                                                                    <div class="col-12 col-md-12 col-lg-12">
                                                                        <label for="sequence" class="form-label">URL</label>
                                                                        <input type="text" placeholder="URL" class="form-control @error('URL') is-invalid @enderror" id="uURL" name="uURL" value="{{ $slider->url }}">
                                                                        @error('uURL')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                                        <label for="editSliderSequence" class="form-label">Sequence</label>
                                                                        <input type="number" placeholder="Sequence" class="form-control" id="editSliderSequence" name="usequence" value="{{ $slider->sequence }}">
                                                                        @error('usequence')
                                                                            <div  class="invalid">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                                        <label for="editSliderStatus" class="form-label">Status</label>
                                                                        <select name="ustatus" class="form-control">
                                                                            @foreach (getStatus() as $key => $status)
                                                                                <option {{ $slider->status==$key?'selected':'' }} value="{{ $key }}">{{ $status }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('ustatus')
                                                                            <div class="invalid">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>                                                                  
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary ">Save</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </form>
                                                    @if (!empty(Session::get('error_code')) && Session::get('error_code') == $slider->id)
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                var modalId = 'edit-modal{{ $slider->id }}';
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
@endsection