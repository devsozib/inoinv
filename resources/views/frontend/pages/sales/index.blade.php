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
  <div class="row">
    <div class="col-12">
      <div class="page-header">
        <div class="content-page-header">
          <h5>Sales Report</h5>
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('sales.index', ['from' => date('Y-m-d'), 'to' => date('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">Today's Sales</div>
              <div class="dash-counts">
                <p>{{$todaysSalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('sales.index', ['from' => now()->startOfWeek()->format('Y-m-d'), 'to' => now()->endOfWeek()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">This week Sales</div>
              <div class="dash-counts">
                <p>{{$thisWeeksSalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('sales.index', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->endOfMonth()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">This Month Sales</div>
              <div class="dash-counts">
                <p>{{$thisMonthsSalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('sales.index', ['from' => now()->startOfYear()->format('Y-m-d'), 'to' => now()->endOfYear()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">This Year Sales</div>
              <div class="dash-counts">
                <p>{{$thisYearsSalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('sales.index', ['sales_type' => 'due'])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1 bg-danger">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title text-danger">Dues of Sales</div>
              <div class="dash-counts">
                <p class="text-danger">{{$totalSalesDues}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>

    
    <div class="col-12">
      <div class="page-header">
        <div class="content-page-header">
          <h5>Daily Sales Report</h5>
        </div>
      </div>
    </div>

    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('dailySales.index', ['from' => date('Y-m-d'), 'to' => date('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">Today's Sales</div>
              <div class="dash-counts">
                <p>{{$todaysDailySalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('dailySales.index', ['from' => now()->startOfWeek()->format('Y-m-d'), 'to' => now()->endOfWeek()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">This week Sales</div>
              <div class="dash-counts">
                <p>{{$thisWeeksDailySalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('dailySales.index', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->endOfMonth()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">This Month Sales</div>
              <div class="dash-counts">
                <p>{{$thisMonthsDailySalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="custom-col-xl-2 col-sm-6 col-12">
      <div class="card">
        <div class="card-body p-2">
          <div class="d-flex justify-content-end" style="">
            <a href="{{route('dailySales.index', ['from' => now()->startOfYear()->format('Y-m-d'), 'to' => now()->endOfYear()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
          </div>
          <div class="dash-widget-header">
            <span class="dash-widget-icon bg-1">
              <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="dash-count">
              <div class="dash-title">This Year Sales</div>
              <div class="dash-counts">
                <p>{{$thisYearsDailySalesRevenue}}</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
 
  <!-- Page Header -->
  <div class="page-header">
    <div class="content-page-header">
      <h5>Sales</h5>
     
      <div class="list-btn">
        <ul class="filter-list">
          <li class="d-none">
            <a class="btn btn-filters w-auto popup-toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Filter">
              <span class="me-2">
                <img src="assets/img/icons/filter-icon.svg" alt="filter">
              </span>Filter </a>
          </li>
          <li class="d-none">
            <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download">
              <a href="#" class="btn-filters" data-bs-toggle="dropdown" aria-expanded="false">
                <span>
                  <i class="fe fe-download"></i>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <ul class="d-block">
                  <li>
                    <a class="d-flex align-items-center download-item" href="javascript:void(0);" download="">
                      <i class="far fa-file-pdf me-2"></i>PDF </a>
                  </li>
                  <li>
                    <a class="d-flex align-items-center download-item" href="javascript:void(0);" download="">
                      <i class="far fa-file-text me-2"></i>CVS </a>
                  </li>
                </ul>
              </div>
            </div>
          </li>
          <li class="d-none">
            <a class="btn-filters" href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Print" data-bs-original-title="Print">
              <span>
                <i class="fe fe-printer"></i>
              </span>
            </a>
          </li>
          <li class="d-none">
            <a class="btn btn-import" href="javascript:void(0);">
              <span>
                <i class="fe fe-check-square me-2"></i>Import Customer </span>
            </a>
          </li>
          <li>
            <a class="btn btn-primary" href="{{route('sales.create')}}">
              <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Sales </a>
          </li>
        </ul>
      </div>
    </div>
    <form action="{{route('sales.index')}}" method="get">
      <div class="row">
        <div class="col-12 col-md-2">
            <label for="">From</label>
            <input type="date" name="from" class="form-control" value="{{isset($request) ? $request->from : ''}}">
        </div>
        <div class="col-12 col-md-2">
          <label for="">To</label><br>
          <input type="date" name="to" class="form-control" value="{{isset($request) ? $request->to : ''}}">
        </div>
        <div class="col-12 col-md-2">
          <label for="">Sales Type</label><br>
          <select name="sales_type" id="" class="form-select">
            <option value="">--Select--</option>
            <option value="paid" {{ (isset($request) && $request->sales_type == 'paid') ? 'selected' : ''}} >Paid</option>
            <option value="due" {{ (isset($request) && $request->sales_type == 'due') ? 'selected' : ''}}>Due</option>
          </select>
        </div>
        <div class="col-12 col-md-2">
          <label for="">Search By</label><br>
          <select name="serach_by" id="" class="form-select">
            <option value="">--Select--</option>
            <option value="name" {{ (isset($request) && $request->serach_by == 'name') ? 'selected' : ''}} >Name</option>
            <option value="phone" {{ (isset($request) && $request->serach_by == 'phone') ? 'selected' : ''}}>Phone</option>
            <option value="email" {{ (isset($request) && $request->serach_by == 'email') ? 'selected' : ''}}>Email</option>
            <option value="product_name" {{ (isset($request) && $request->serach_by == 'product_name') ? 'selected' : ''}}>Product Name</option>
          </select>
        </div>
        <div class="col-12 col-md-2">
          <label for="">Search Key</label><br>
          <input type="text" name="key" class="form-control" value="{{isset($request) ? $request->key : ''}}">
        </div>
        <div class="col-12 col-md-2">
          <label for=""></label>
          <button type="submit" name="search_for" value="filter" class="btn btn-primary" style="margin-top:25px;">Search</button>
          <label for=""></label>
          <button type="submit" name="search_for" value="pdf" class="btn btn-primary" style="margin-top:25px;"><i class="fe fe-download"></i></button>
        </div>
      </div>
    </form>
  </div>
  <!-- /Page Header -->
  <!-- Search Filter -->
  <div id="filter_inputs" class="card filter-card">
    <div class="card-body pb-0">
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="input-block mb-3">
            <label>Name</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="input-block mb-3">
            <label>Email</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="input-block mb-3">
            <label>Phone</label>
            <input type="text" class="form-control">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Search Filter -->
  <div class="row">
    <div class="col-sm-12">
      <div class="card-table">
        <div class="card-body">
          <div class="table-responsive">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <table class="table table-center table-hover datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead class="thead-light">
                  <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending">#</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Date</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Email</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Phone</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Product Name</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Price</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Qty</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Total</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Discount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Bill</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Paid Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Due Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Sales By</th>

                    <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Actions</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($services as $service)
                  <tr role="row" class="odd">
                    <td class="sorting_1">{{$loop->index+1}}</td>
                    <td>
                      <h2 class="table-avatar"> <span>{{$service->created_at->format('Y-m-d')}}</span></h2>
                    </td>
                    <td>
                      <h2 class="table-avatar">
                        <a href="profile.html" class="avatar avatar-md me-2 d-none">
                          <img class="avatar-img rounded-circle" src="assets/img/profiles/avatar-14.jpg" alt="User Image">
                        </a>
                        <a href="javascript:void(0)">{{$service->name}}
                        </a>
                      </h2>
                    </td>
                    <td>
                      <h2 class="table-avatar"> <span>{{$service->email}}</span></h2>
                    </td>
                    <td>
                      <h2 class="table-avatar"> <span>{{$service->phone}}</span></h2>
                    </td>
                    <td> {{$service->product_name}} </td>
                    <td> ${{$service->price}} </td>
                    <td> {{$service->qty}} </td>
                    <td> ${{$service->total}} </td>
                    <td> ${{$service->discount}} </td>
                    <td> ${{$service->bill}} </td>
                    <td> ${{$service->paid_amount}} </td>
                    <td> ${{$service->due_amount}} </td>
                    <td> {{$service->sales_by}} </td>
                    <td class="d-flex align-items-center">
                      <div class="dropdown dropdown-action">
                        <a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                          <ul>
                            <li>
                              <a class="dropdown-item" href="{{route('payments', ['id' => $service->id, 'payment_for' => '2'])}}">
                                <i class="far fa-edit me-2"></i>Get Payments </a>
                            </li>
                            <li>
                              <a class="dropdown-item" target="_blank" href="{{route('sales.invoice', $service->id)}}">
                                <i class="far fa-edit me-2"></i>Invoice </a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="{{route('sales.edit', $service->id)}}">
                                <i class="far fa-edit me-2"></i>Edit </a>
                            </li>
                            <li>
                              <a onclick="if (confirm('Are you sure to delete the Sales?')) { document.getElementById('serviceDelete{{$service->id}}').submit(); }" class="dropdown-item" href="javascript:void(0)">
                                <i class="far fa-edit me-2"></i>Delete </a>
                                <form id="serviceDelete{{$service->id}}" action="{{route('sales.destroy', $service->id)}}" method="post">
                                  @csrf
                                  @method('DELETE')
                                </form>
                            </li>
                            <li class="d-none">
                              <a class="dropdown-item" href="">
                                <i class="far fa-eye me-2"></i>View </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
              
              <div class="dataTables_length" id="DataTables_Table_0_length">
                <label>
                  <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
                </label>
              </div>
              <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                <ul class="pagination">
                  <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous">
                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">
                      <i class="fa fa-angle-double-left me-2"></i> Previous </a>
                  </li>
                  <li class="paginate_button page-item active">
                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                  </li>
                  <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next">
                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0" class="page-link">Next <i class=" fa fa-angle-double-right ms-2"></i>
                    </a>
                  </li>
                </ul>
              </div>
              <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 6 of 6 entries</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection