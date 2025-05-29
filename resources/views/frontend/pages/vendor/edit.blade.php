@extends('frontend.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<div class="row justify-content-center">
    <div class="col">
        <div class="card p-4 shadow">
            <h2 class=" mb-3">Add Vendor</h2>
            <form method="POST" action="{{ route('vendors.update', $customer->id) }}">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$customer->name}}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="phone" value="{{$customer->phone}}" pattern="[0-9]{11}" maxlength="11" placeholder="Enter phone number" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{$customer->email}}" id="email" >
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" required>{{$customer->address}}</textarea>
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