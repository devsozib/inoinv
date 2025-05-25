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
                            <script>
                                Swal.fire({
                                    icon: '{{ session('sweet_alert.type') }}',
                                    title: '{{ session('sweet_alert.title') }}',
                                    text: '{{ session('sweet_alert.text') }}',
                                });
                            </script>
                        @endif
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Ads</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100" id="dataTbl">
                                <thead>
                                    <tr>
                                        <th scope="col">First Ad</th>                                   
                                        <th scope="col">Second Ad</th>                                   
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>                                 
                                    <tr>
                                        @if($ad)                                     
                                            <td>
                                                <img style="width:100px; border-radius:5px" 
                                                    src="{{ $ad->first_ad ? asset('frontend/assets/images/ads/'.$ad->first_ad) : '' }}" 
                                                    alt="">
                                            </td>                                                                            
                                            <td>
                                                <img style="width:100px; border-radius:5px" 
                                                    src="{{ $ad->second_ad ? asset('frontend/assets/images/ads/'.$ad->second_ad) : '' }}" 
                                                    alt="">
                                            </td>
                                        @endif                                                                           
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-modal">
                                                <i class="bx bx-edit"></i>
                                            </button>

                                            <div id="edit-modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> 
                                            @if($ad)
                                                <form action="{{ route('home-ad.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="myModalLabel">Update Ads</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="col-12 col-md-12 col-lg-12">
                                                                        <label for="firstEditSliderImage" class="form-label">First Ad(Dimensions should be 375x374 pixels.)</label>
                                                                        <input type="file" class="form-control" id="firstEditSliderImage" name="first_ad">
                                                                        @if ($ad->first_ad)
                                                                            <div id="first_image_sec">
                                                                                <div class="form-group">
                                                                                    <img style="width:200px; border-radius:10px; margin-to:6px"
                                                                                        id="first_image-preview"
                                                                                        src="{{ asset('frontend/assets/images/ads/'.$ad->first_ad) }}"
                                                                                        alt="Preview">
                                                                                </div>
                                                                            </div>
                                                                        @endif                                                                    
                                                                        @error('first_ad')
                                                                            <div class="invalid">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>    
                                                                    <div class="col-12 col-md-12 col-lg-12">
                                                                        <label for="secondEditSliderImage" class="form-label">Second Ad(Dimensions should be 375x374 pixels.)</label>
                                                                        <input type="file" class="form-control" id="secondEditSliderImage" name="second_ad">
                                                                        @if ($ad->second_ad)
                                                                            <div id="second_image_sec">
                                                                                <div class="form-group">
                                                                                    <img style="width:200px; border-radius:10px; margin-to:6px"
                                                                                        id="second_image-preview"
                                                                                        src="{{ asset('frontend/assets/images/ads/'.$ad->second_ad) }}"
                                                                                        alt="Preview">
                                                                                </div>
                                                                            </div>
                                                                        @endif                                                                    
                                                                        @error('second_ad')
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
                                                    @if (!empty(Session::get('error_code')) && Session::get('error_code') == 'update')
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                var modalId = 'edit-modal';
                                                                var modalElement = document.getElementById(modalId);
                                                        
                                                                if (modalElement) {
                                                                    var modal = new bootstrap.Modal(modalElement);
                                                                    modal.show();
                                                                }
                                                            });
                                                        </script>
                                                    @endif 

                                                </div>
                                            @endif

                                        </td>
                                    </tr>                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script type="text/javascript">
    
        document.addEventListener('DOMContentLoaded', function() {
            var fileInput = document.getElementById('firstEditSliderImage');
            var imageSection = document.getElementById('first_image_sec');
            var imagePreview = document.getElementById('first_image-preview');

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

        document.addEventListener('DOMContentLoaded', function() {
            var fileInput = document.getElementById('secondEditSliderImage');
            var imageSection = document.getElementById('second_image_sec');
            var imagePreview = document.getElementById('second_image-preview');

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