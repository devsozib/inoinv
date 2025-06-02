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
      <h5>Brands</h5>
      <div class="list-btn">
        <ul class="filter-list">
          <li>
            <a class="btn btn-primary" href="{{ route('brands.create') }}">
              <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Brand
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card-table">
        <div class="card-body">
          <div class="table-responsive">
            <table id="brandTable" class="table table-center table-hover datatable">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th class="no-sort">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($brands as $brand)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $brand->name }}</td>
                  <td>
                    @if($brand->status == 1)
                      <span class="badge bg-success">Active</span>
                    @else
                      <span class="badge bg-danger">Inactive</span>
                    @endif
                  </td>
                  <td class="d-flex align-items-center">
                    <div class="dropdown dropdown-action">
                      <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <ul>
                          <li>
                            <a class="dropdown-item" href="{{ route('brands.edit', $brand->id) }}">
                              <i class="far fa-edit me-2"></i>Edit
                            </a>
                          </li>
                          <li>
                            <a onclick="if (confirm('Are you sure to delete this brand?')) { document.getElementById('deleteBrand{{ $brand->id }}').submit(); }" class="dropdown-item" href="javascript:void(0)">
                              <i class="far fa-trash-alt me-2"></i>Delete
                            </a>
                            <form id="deleteBrand{{ $brand->id }}" action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display: none;">
                              @csrf
                              @method('DELETE')
                            </form>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <!-- Optional: Modals for inline edit (if not using separate page) -->
            {{-- You can add modal edit form per brand here if needed --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
