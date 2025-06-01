@extends('frontend.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<div class="row justify-content-center">
    <div class="col">
        <div class="card p-4 shadow">
            <h2 class=" mb-3">Add Brand</h2>
            <form method="POST" action="{{ route('brands.store') }}">
                @csrf
                
                
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="col-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select mb-3" name="status" required>
                            <option selected="" value="1">Actve</option>
                            <option value="0">InActve</option>
                        </select>
                    </div>
                </div>

                <div class="add-customer-btns text-left">
                    <button type="submit" class="btn customer-btn-save">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        
        $('.js-example-basic-single').select2({
            
        });

        $('.js-example-basic-single-no-new-value').select2({
        });

        
    });

   
</script>

@endsection