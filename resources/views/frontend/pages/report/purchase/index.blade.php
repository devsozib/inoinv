@extends('frontend.layouts.app') 
@section('content')
<style>
  /* Default: Columns stack vertically */
  .custom-col-xl-2 {
    flex: 0 0 100%;
    max-width: 100%;
  }

  /* Media Query for XL screens (≥1200px) */
  @media (min-width: 1200px) {
    .custom-col-xl-2 {
      flex: 0 0 20%; /* Equivalent to col-xl-2 (2/12 = 16.67%) */
      max-width: 20%;
    }
  }
  .page-wrapper .content {
      padding: 14px!important;
  }
  </style>
<div class="content container-fluid">

 
  <!-- Page Header -->
  <div class="page-header">
    <div class="content-page-header">
      <h5>Purchase Report</h5>
    </div>    
  </div>
  <div id="filter_inputs" class="card mb-3">
  <div class="card-body pb-0">
    <form action="{{ route('purchase.report.get') }}" method="GET">
      <div class="row">
        <div class="col-sm-3 col-md-3">
                <div class="input-block mb-3">
                    <label>Product Name</label>
                    <select name="item_name" class="form-control">
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ request('item_name') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                        </option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3 col-md-3">
                <div class="input-block mb-3">
                    <label>Vendor</label>
                    <select name="vendor" class="form-control">
                    <option value="">-- Select Vendor --</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                        </option>
                    @endforeach
                    </select>
                </div>
            </div>

        <div class="col-sm-3 col-md-3">
          <div class="input-block mb-3">
            <label>From Date</label>
            <input type="date" class="form-control" name="from" value="{{ request('from') }}">
          </div>
        </div>
        <div class="col-sm-3 col-md-3">
          <div class="input-block mb-3">
            <label>To Date</label>
            <input type="date" class="form-control" name="to" value="{{ request('to') }}">
          </div>
        </div>
        <div class="col-sm-12 col-md-12 text-end mb-3">
          <button type="submit" class="btn btn-primary">Filter</button>
        </div>
      </div>
    </form>
  </div>
</div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card-table">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered mt-4">
                <thead class="thead-dark">
                    <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $index => $purchase)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $purchase->product->name ?? 'N/A' }}</td>
                        <td>{{ $purchase->total_qty }}</td>
                        <td>{{ number_format($purchase->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection