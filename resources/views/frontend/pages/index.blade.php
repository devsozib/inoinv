@extends('frontend.layouts.app') 
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
    {{-- <div class="row" >
      <div class="col-12">
        <h5>Services Report</h5>
      </div>
      <div class="custom-col-xl-2 col-sm-6 col-12">
        <div class="card">
          <div class="card-body p-2">
            <div class="d-flex justify-content-end" style="">
              <a href="{{route('service.complated', ['from' => date('Y-m-d'), 'to' => date('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
            </div>
            <div class="dash-widget-header">
              <span class="dash-widget-icon bg-1">
                <i class="fas fa-dollar-sign"></i>
              </span>
              <div class="dash-count">
                <div class="dash-title">Today's Service </div>
                <div class="dash-counts">
                  <p>{{$todaysRevenue}}</p>
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
              <a href="{{route('service.complated', ['from' => now()->startOfWeek()->format('Y-m-d'), 'to' => now()->endOfWeek()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
            </div>
            <div class="dash-widget-header">
              <span class="dash-widget-icon bg-1">
                <i class="fas fa-dollar-sign"></i>
              </span>
              <div class="dash-count">
                <div class="dash-title">This week Service</div>
                <div class="dash-counts">
                  <p>{{$thisWeeksRevenue}}</p>
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
              <a href="{{route('service.complated', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->endOfMonth()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
            </div>
            <div class="dash-widget-header">
              <span class="dash-widget-icon bg-1">
                <i class="fas fa-dollar-sign"></i>
              </span>
              <div class="dash-count">
                <div class="dash-title">This Month Service</div>
                <div class="dash-counts">
                  <p>{{$thisMonthsRevenue}}</p>
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
              <a href="{{route('service.complated', ['from' => now()->startOfYear()->format('Y-m-d'), 'to' => now()->endOfYear()->format('Y-m-d')])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
            </div>
            <div class="dash-widget-header">
              <span class="dash-widget-icon bg-1">
                <i class="fas fa-dollar-sign"></i>
              </span>
              <div class="dash-count">
                <div class="dash-title">This Year Service</div>
                <div class="dash-counts">
                  <p>{{$thisYearsRevenue}}</p>
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
              <a href="{{route('service.complated', ['service_type' => 'due'])}}" class="bg-1 text-center rounded" style="width:20px; height:20px;"><i class="fe fe-filter"></i></a>
            </div>
            <div class="dash-widget-header">
              <span class="dash-widget-icon bg-1 bg-danger">
                <i class="fas fa-dollar-sign"></i>
              </span>
              <div class="dash-count">
                <div class="dash-title text-danger">Dues of Services</div>
                <div class="dash-counts">
                  <p class="text-danger">{{$totalServiceDues}}</p>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>

      <div class="col-12">
        <h5>Sales Report</h5>
      </div>

      <!-- <div class="custom-col-xl-2 col-sm-6 col-12">
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
      </div> -->

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
        <h5>Daily Sales Report</h5>
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
                  <p>${{$todaysDailySalesRevenue}}</p>
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
    
    <div class="row">
      <div class="col-xl-7 d-flex">
        <div class="card flex-fill">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Monthly Service Report</h5>

              
            </div>
          </div>
          <div class="card-body">
            
            
            <div id="sales_chart"></div>
          </div>
        </div>
      </div>
      <div class="col-xl-5 d-flex">
        <div class="card flex-fill">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Yearly Service Report</h5> 
            </div>
          </div>
          <div class="card-body">
            <div id="sales_chart_yearly"></div>
            
          </div>
        </div>
      </div>
    </div> --}}

  <h3>Overview</h3>
  <div class="row g-3">

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center" style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
          <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
            <i class="fa fa-ellipsis-v"></i>
          </div>
  
          <div class="icon d-flex align-items-center justify-content-center"
              style="background: #FFCE4A; padding: 15px; border-radius: 8px;">
              <img src="{{asset('assets')}}/img/target.png" alt="" >
          </div>
  
          <div class="content">
              <h6 class="mb-1" style="color: #6c757d;">Today's Target</h6>
              <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">
                <span class="text-success">${{ceil((max($monthlySalesTarget-$thisMonthsDailySalesRevenue, 0) / isDivisor(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::now()->endOfMonth()) + 1)))}}
              </p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background: #A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/partnership.png" alt="" >
            </div>
    
            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">This Month Sale</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{round($thisMonthsDailySalesRevenue,0)}}</p>
            </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center" style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
          <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
            <i class="fa fa-ellipsis-v"></i>
          </div>
  
          <div class="icon d-flex align-items-center justify-content-center"
              style="background: #FFCE4A; padding: 15px; border-radius: 8px;">
              <img src="{{asset('assets')}}/img/target.png" alt="" >
          </div>
  
          <div class="content">
              <h6 class="mb-1" style="color: #6c757d;">This Month Target</h6>
              <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">
                <span class="text-success">${{round($monthlySalesTarget,0)}}
              </p>
          </div>
        </div>
      </div>


      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center" style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
          <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
            <i class="fa fa-ellipsis-v"></i>
          </div>
  
          <div class="icon d-flex align-items-center justify-content-center"
              style="background:#FFAEA3; padding: 15px; border-radius: 8px;">
              <img src="{{asset('assets')}}/img/target.png" alt="" >
          </div>
  
          <div class="content">
              <h6 class="mb-1" style="color: #6c757d;">Month Due Target</h6>
              <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">
                <span class="text-success">${{round(max($monthlySalesTarget-$thisMonthsDailySalesRevenue, 0),0)}}
              </p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background:#A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/partnership.png" alt="" >
            </div>
    
            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">Today's Total Sale</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$todaysDailySalesRevenue}}</p>
            </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background:#A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/walet.png" alt=""  style="width: 64px;">
            </div>
    
            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">This Month Card Total</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$thisMonthsDailySalesCardTotal}}</p>
            </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background:#A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/money.png" alt="" style="width: 64px;">
            </div>
    
            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">This Month Cash Total</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$thisMonthsDailySalesCashTotal}}</p>
            </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background:#A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/mobile.png" alt="" style="width: 64px;">
            </div>

            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">This Month Other Total</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$thisMonthsDailySalesOtherTotal}}</p>
            </div>
        </div>
      </div>
    
      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
        style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
              
              <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
                <i class="fa fa-ellipsis-v"></i>
              </div>
      
              <div class="icon d-flex align-items-center justify-content-center"
                  style="background: #FCEEC8; padding: 15px; border-radius: 8px;">
                  <img src="{{asset('assets')}}/img/settings.png" alt="" >
              </div>
      
              <div class="content">
                  <h6 class="mb-1" style="color: #6c757d;">Best Selling Month: {{ $bestDailySellingMonth ? \Carbon\Carbon::createFromFormat('m', $bestDailySellingMonth->month)->format('F, Y')  : '' }}</h6>
                  <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$bestDailySellingMonth ? $bestDailySellingMonth->total : ''}}</p>
              </div>
          </div>
      </div>
    
      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
        style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">            
        <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
                <i class="fa fa-ellipsis-v"></i>
              </div>      
              <div class="icon d-flex align-items-center justify-content-center"
                  style="background: #FCEEC8; padding: 15px; border-radius: 8px;">
                  <img src="{{asset('assets')}}/img/investment.png" alt="" >
              </div>      
              <div class="content">
                  <h6 class="mb-1" style="color: #6c757d;">Best Selling Day: {{ $bestDailySellingDate ? \Carbon\Carbon::parse($bestDailySellingDate->best_date)->format('d M Y') : '' }}</h6>
                  <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$bestDailySellingDate ? $bestDailySellingDate->total : ''}}</p>
              </div>
          </div>
      </div>
      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background:#A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/money.png" alt="" style="width: 64px;">
            </div>
    
            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">Today's Expanse</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$todayDailyExpense}}</p>
            </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card position-relative d-flex flex-row align-items-center"
            style="border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); padding:8px">
      
            <div class="position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
              <i class="fa fa-ellipsis-v"></i>
            </div>
    
            <div class="icon d-flex align-items-center justify-content-center"
                style="background:#A7FFA4; padding: 15px; border-radius: 8px;">
                <img src="{{asset('assets')}}/img/money.png" alt="" style="width: 64px;">
            </div>
    
            <div class="content">
                <h6 class="mb-1" style="color: #6c757d;">This Month Expanse</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">${{$thisMonthDailyExpense}}</p>
            </div>
        </div>
      </div>
  </div>
  <div class="row">
    <div class="col-xl-7 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Monthly Service Report</h5>            
          </div>
        </div>
        <div class="card-body">                    
          <div id="sales_chart"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Yearly Service Report</h5> 
          </div>
        </div>
        <div class="card-body">
          <div id="sales_chart_yearly"></div>
          
        </div>
      </div>
    </div>
  </div>
    
  </div>



  <script>
        // Embed PHP data as a JavaScript object
        window.chartData = {
            monthlyRevenue: @json($monthlyRevenue), // Passing the PHP array to JavaScript
            yearlyRevenue: @json($yearlyRevenue)   // Similarly, for yearly revenue
        };
  </script>


@endsection