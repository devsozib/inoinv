@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
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
                    <div class="card p-3">
                        <form action="{{route('currency.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-3">
                                    <label for="code" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="code" name="name" placeholder="Name" required>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <label for="discount" class="form-label">Symbol</label>
                                    <input type="text" class="form-control" id="symbol" name="symbol" placeholder="Symbol" pattern=".{1}" title="Please enter exactly one character for the symbol" required>
                                </div>
                                <div class="col-12 col-md-6 col-lg-3">
                                    <label for="discount" class="form-label">Type</label>
                                    <select name="type" class="form-control" id="type">
                                        @foreach (currecySymbleType() as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-lg-3">
                                    <label for="basiInput" class="form-label">Status</label>
                                    <select name="status" id="" class="form-control">
                                        @foreach (getStatus() as $key => $status) 
                                            <option value="{{$key}}" {{$key == 1 ? 'selected' : ''}}>{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <button type="submit" class="btn btn-primary mt-1">Add Currency</button>
                                </div>
                            </div>
                        </form>

                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Category List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Name</th>                                    
                                        <th scope="col">Currency Symbol</th>                                    
                                        <th scope="col">Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$coupon->name}}</td>                                       
                                        <td>{{$coupon->symbol}}</td>                                       
                                        <td>{{currecySymbleType()[$coupon->type]}}</td>                                       
                                        <td>{{getStatus()[$coupon->status]}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$coupon->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>

                                            <div id="ctegory{{$coupon->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('currency.update', $coupon->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Add Symbol</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="col-12 col-md-12 col-lg-12">
                                                                    <label for="discount" class="form-label">Name</label>
                                                                    <input type="text" class="form-control" id="discount" value="{{ $coupon->name }}" name="name" placeholder="Name" required>
                                                                </div>       
                                                                <div class="col-12 col-md-12 col-lg-12">
                                                                    <label for="discount" class="form-label">Symbol</label>
                                                                    <input type="text" class="form-control" id="symbol" value="{{ $coupon->symbol }}" name="symbol" placeholder="Symbol" pattern=".{1}" title="Please enter exactly one character for the symbol" required>
                                                                </div>    
                                                                <div class="col-12 col-md-12 col-lg-12">
                                                                    <label for="discount" class="form-label">Type</label>
                                                                    <select name="type" class="form-control" id="type">
                                                                        @foreach (currecySymbleType() as $key => $type)
                                                                            <option {{ $key== $coupon->type?'selected':''}} value="{{ $key }}">{{ $type }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>                                                     
                                                                <div class="">
                                                                    <label for="basiInput" class="form-label">Status</label>
                                                                    <select name="status" id="" class="form-control">
                                                                        @foreach (getStatus() as $key => $status) 
                                                                            <option value="{{$key}}" {{$coupon->status == 1 ? 'selected' : ''}}>{{$status}}</option>
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