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
  <div class="page-header">
    <div class="content-page-header mt-5">
    <h5>Purchases</h5>
        <div class="list-btn">
            <ul class="filter-list">
            <li>
                <a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add-purchase-modal">
                <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Purchase</a>
            </li>
            </ul>
        </div>
    </div>

    <div id="add-purchase-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-body">
            <form class="px-3" method="post" action="{{ route('purchase.store') }}">
            @csrf
            <div class="mb-3">
                <label for="product_id" class="form-label">Product</label>
                <select class="form-control select2" name="product_id" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="unit_price" class="form-label">Unit Price</label>
                <input type="number" step="0.01" name="unit_price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sub_price" class="form-label">Sub Price</label>
                <input type="number" step="0.01" name="sub_price" class="form-control">
            </div>

            <div class="mb-3">
                <label for="total_price" class="form-label">Total Price</label>
                <input type="number" step="0.01" name="total_price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="payment" class="form-label">Payment</label>
                <input type="number" step="0.01" name="payment" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="due" class="form-label">Due</label>
                <input type="number" step="0.01" name="due" class="form-control" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

            </form>
        </div>
        </div>
    </div>
    </div>
  </div>
  <!-- /Page Header -->
  <!-- Search Filter -->
  <div class="row">
    <div class="col-sm-12">
      <div class="card-table">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-center table-hover datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Payment</th>
                        <th>Due</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $purchase->product->name ?? 'N/A' }}</td>
                        <td>{{ $purchase->quantity }}</td>
                        <td>{{ $purchase->unit_price }}</td>
                        <td>{{ $purchase->total_price }}</td>
                        <td>{{ $purchase->payment }}</td>
                        <td>{{ $purchase->due }}</td>
                        <td>
                        <div class="dropdown">
                            <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit-purchase-{{ $purchase->id }}">Edit</a>
                            <form method="POST" action="{{ route('purchase.destroy', $purchase->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            </div>
                        </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div id="edit-purchase-{{ $purchase->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                            <form method="POST" action="{{ route('purchase.update', $purchase->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                <label>Product</label>
                                <select name="product_id" class="form-control select2" required>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $purchase->product_id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                    @endforeach
                                </select>
                                </div>

                                <div class="mb-3"><input name="quantity" value="{{ $purchase->quantity }}" class="form-control" placeholder="Quantity" /></div>
                                <div class="mb-3"><input name="unit_price" value="{{ $purchase->unit_price }}" class="form-control" placeholder="Unit Price" /></div>
                                <div class="mb-3"><input name="sub_price" value="{{ $purchase->sub_price }}" class="form-control" placeholder="Sub Price" /></div>
                                <div class="mb-3"><input name="total_price" value="{{ $purchase->total_price }}" class="form-control" placeholder="Total Price" /></div>
                                <div class="mb-3"><input name="payment" value="{{ $purchase->payment }}" class="form-control" placeholder="Payment" /></div>
                                <div class="mb-3"><input name="due" value="{{ $purchase->due }}" class="form-control" placeholder="Due" /></div>

                                <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                </div>

                            </form>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection