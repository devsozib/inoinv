@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card p-3">
                        <form action="{{route('coupons.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="code" class="form-label">Coupon Code</label>
                                    <input type="text" class="form-control" id="code" name="code" placeholder="Coupon Code">
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount">
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="is_percentage" class="form-label">Discount Type</label>
                                    <select class="form-control" id="discount_type" name="discount_type">
                                        @foreach (getAmountType() as $key => $type)
                                            <option value="{{$key}}" {{$key == 1 ? 'selected' : ''}}>{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity">
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="expires_at" class="form-label">Expires At</label>
                                    <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="basiInput" class="form-label">Status</label>
                                    <select name="status" id="" class="form-control">
                                        @foreach (getStatus() as $key => $status) 
                                            <option value="{{$key}}" {{$key == 1 ? 'selected' : ''}}>{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <button type="submit" class="btn btn-primary mt-1">Add Coupon</button>
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
                                        <th scope="col">Coupon Code</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Discount Type</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Expires At</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$coupon->code}}</td>
                                        <td>{{$coupon->discount}}</td>
                                        <td>{{getAmountType()[$coupon->discount_type]}}</td>
                                        <td>{{$coupon->quantity}}</td>
                                        <td>{{$coupon->expires_at}}</td>
                                        <td>{{getStatus()[$coupon->status]}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$coupon->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>

                                            <div id="ctegory{{$coupon->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Add Category</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="">
                                                                    <label for="code" class="form-label">Coupon Code</label>
                                                                    <input type="text" class="form-control" id="code" name="code" value="{{$coupon->code}}" placeholder="Coupon Code">
                                                                </div>

                                                                <div class="">
                                                                    <label for="discount" class="form-label">Discount</label>
                                                                    <input type="text" class="form-control" id="discount" name="discount" value="{{$coupon->discount}}" placeholder="Discount">
                                                                </div>

                                                                <div class="">
                                                                    <label for="is_percentage" class="form-label">Discount Type</label>
                                                                    <select class="form-control" id="discount_type" name="discount_type">
                                                                        @foreach (getAmountType() as $key => $type)
                                                                            <option value="{{$key}}" {{$coupon->discount_type == 1 ? 'selected' : ''}}>{{$type}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="">
                                                                    <label for="quantity" class="form-label">Quantity</label>
                                                                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{$coupon->quantity}}" placeholder="Quantity">
                                                                </div>

                                                                <div class="">
                                                                    <label for="expires_at" class="form-label">Expires At</label>
                                                                    <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" value="{{$coupon->expires_at}}">
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