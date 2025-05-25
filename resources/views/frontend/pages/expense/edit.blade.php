@extends('frontend.layouts.app') 
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #888 transparent;
        border-width: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #888 transparent transparent transparent;
        border-style: solid;
        border-width: 0 !important;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
        top: 50%;
        width: 0;
    }
</style>

<div class="content container-fluid">
    <div class="card mb-0">
        <div class="card-body">
            <!-- Page Header -->
            <div class="page-header">
                <div class="content-page-header">
                    <h5>Edit Daily Expense</h5>
                </div>    
            </div>
            <!-- /Page Header -->                
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('dailyExpenses.update', $expense->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group-item">  
                            <div class="row">  
                                <!-- Date -->
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Date <span class="text-danger">*</span></label>   
                                        <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required autocomplete="off">  
                                    </div>  
                                </div>  

                                <!-- Amount -->
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Amount <span class="text-danger">*</span></label>  
                                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter Amount" value="{{ $expense->amount }}" required autocomplete="off">  
                                    </div>  
                                </div>  

                                <!-- Spend Method -->
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Spend Method <span class="text-danger">*</span></label>  
                                        <select name="spend_method" class="form-select" required>  
                                            <option value="">--Select--</option>  
                                            <option value="cash" {{ $expense->spend_method == 'cash' ? 'selected' : '' }}>Cash Payment</option>  
                                            <option value="card" {{ $expense->spend_method == 'card' ? 'selected' : '' }}>Card Payment</option>  
                                            <option value="bank_transfer" {{ $expense->spend_method == 'bank_transfer' ? 'selected' : '' }}>Other Payment</option>  
                                        </select>  
                                    </div>  
                                </div>  

                                <!-- Assign Person -->
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Assign person of work <span class="text-danger">*</span></label>  
                                        <select name="assign_person" class="form-select" required>  
                                            <option value="">--Select--</option>  
                                            @foreach ($users as $user)  
                                                <option value="{{$user->id}}" {{ $expense->assign_person == $user->id ? 'selected' : '' }}>{{$user->name}}</option>  
                                            @endforeach  
                                        </select>  
                                    </div>  
                                </div> 

                                <!-- Purpose of Expense -->
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Purpose of Expense <span class="text-danger">*</span></label>  
                                        <textarea name="purpose_of_expense" class="form-control" placeholder="Enter Purpose" required>{{ $expense->purpose_of_expense }}</textarea>
                                    </div>  
                                </div> 
                                
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Paying To*</label>  
                                        <input type="text" step="0.01" name="to_payment" class="form-control" placeholder="Enter recipient" value="{{ old('to_payment', $expense->to_payment) }}" required autocomplete="off">  
                                    </div>  
                                </div>  
                            </div>  
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="add-customer-btns text-left">
                            <button type="submit" class="btn customer-btn-save">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            tags: true,
        });
        $('.js-example-basic-single-no-new-value').select2({
            tags: true,
        });
    });
</script>

@endsection