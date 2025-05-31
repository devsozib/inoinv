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
    <div class="content-page-header">
      <h5>Inventories</h5>
      <div class="list-btn">
        <ul class="filter-list">
          <li>
            <a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add-product-modal">
              <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Opening Stock
            </a>
          </li>
        </ul>

        <!-- Add Product Modal -->
        <div id="add-product-modal" class="modal fade" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                  <div class="auth-logo">
                    <a href="{{ route('index') }}" class="logo logo-dark">
                      <span class="logo-lg">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="42">
                      </span>
                    </a>
                  </div>
                </div>
                <form method="post" action="{{ route('inventory.store') }}" class="px-3">
                  @csrf

                  <div class="mb-3">
                    <label for="product_id" class="form-label">Select Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-select" required>
                      <option value="" disabled selected>-- Select Product --</option>
                      @foreach ($products as $product)
                        <option value="{{ $product->id }}">
                          {{ $product->name }} ({{ $product->model ?? 'N/A' }})
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="opening_stock" class="form-label">Opening Stock <span class="text-danger">*</span></label>
                    <input type="number" name="opening_stock" id="opening_stock" class="form-control" min="0" value="0" required>
                  </div>
                  

                  <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
        <!-- End Add Modal -->

      </div>
    </div>
  </div>

  <!-- Product Table -->
  <div class="row">
    <div class="col-sm-12">
      <div class="card-table">
        <div class="card-body">
          <div class="table-responsive">
            <table id="productTable" class="table table-hover datatable">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Product and Model</th>
                  <th>Opening Stock</th>
                  <th>Current Stock</th>
                  {{-- <th class="no-sort">Actions</th> --}}
                </tr>
              </thead>
              <tbody>
                @foreach($inventories as $inventory)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $inventory->product->name }}({{ $inventory->product->model??'N/A' }})</td>                  
                  <td>{{ $inventory->opening_stock ?? 0 }}</td>
                  <td>{{ $inventory->current_stock ?? 0 }}</td>               
                  {{-- <td>
                    <div class="dropdown dropdown-action">
                      <a href="#" class="btn-action-icon" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit-inventory-modal{{ $inventory->id }}">
                          <i class="far fa-edit me-2"></i>Edit
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)"
                           onclick="if (confirm('Are you sure to delete this inventory?')) document.getElementById('deleteForm{{ $inventory->id }}').submit();">
                          <i class="far fa-trash-alt me-2"></i>Delete
                        </a>
                        <form id="deleteForm{{ $inventory->id }}" action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" style="display:none;">
                          @csrf @method('DELETE')
                        </form>
                      </div>
                    </div>
                  </td> --}}
                </tr>

                <!-- Edit inventory Modal -->
                <!-- Inventory Edit Modal -->
                {{-- <div id="edit-inventory-modal{{ $inventory->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-body">
                        <div class="text-center mt-2 mb-4">
                          <div class="auth-logo">
                            <a href="{{ route('index') }}" class="logo logo-dark">
                              <span class="logo-lg">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="42">
                              </span>
                            </a>
                          </div>
                        </div>

                        <form method="POST" action="{{ route('inventory.update', $inventory->id) }}" class="px-3">
                          @csrf
                          @method('PUT')

                          <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" value="{{ $inventory->product->name }}" readonly>
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Model Name</label>
                            <input type="text" class="form-control" value="{{ $inventory->product->model_name ?? 'N/A' }}" readonly>
                          </div>

                          <div class="mb-3">
                            <label for="opening_stock{{ $inventory->id }}" class="form-label">Opening Stock <span class="text-danger">*</span></label>
                            <input type="number" name="opening_stock" id="opening_stock{{ $inventory->id }}" class="form-control" min="0" value="{{ $inventory->qty }}" required>
                          </div>

                          <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                          </div>
                        </form>

                      </div>
                    </div>
                  </div>
                </div> --}}

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
