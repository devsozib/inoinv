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
  
  <h3>Overview</h3>
  <div class="row g-3">

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
                <h6 class="mb-1" style="color: #6c757d;">Today's Sales</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">{{$todaysSalesRevenue}}</p>
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
                <h6 class="mb-1" style="color: #6c757d;">This Week Sales</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">{{$thisWeeksSalesRevenue}}</p>
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
                <h6 class="mb-1" style="color: #6c757d;">This Month Sales</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">{{$thisMonthsSalesRevenue}}</p>
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
                <h6 class="mb-1" style="color: #6c757d;">This Year Sales</h6>
                <p class="fw-bold mb-0" style="color: #333; font-size:20px; font-weight:bold">{{$thisYearsSalesRevenue}}</p>
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
            monthlyRevenue: '', // Passing the PHP array to JavaScript @ json($monthlyRevenue)
            yearlyRevenue: ''   // Similarly, for yearly revenue @ json($yearlyRevenue)
        };
  </script>


@endsection