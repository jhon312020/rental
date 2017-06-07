@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.home_page')}}
@endsection

@section('contentheader_title')
  {{trans('message.home_page')}}
@endsection
@section('main-content')
	
<div class="row">
  <div class="col-md-6 col-lg-3">
    <div class="widget-bg-color-icon card-box fadeInDown animated">
      <!--<div class="bg-icon bg-icon-info pull-left">
        <i class="md md-attach-money text-info"></i>
      </div>-->
      <div id="incomeProgress" class="progress-css"></div>
      <div class="text-right">
        <h3 class="text-dark"><b>&#8377;&nbsp;</b><b class="incrementCounter">0</b></h3>
        <p class="text-muted">{{trans('message.total_incomes')}}</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="widget-bg-color-icon card-box">
      <!--<div class="bg-icon bg-icon-pink pull-left">
        <i class="md md-add-shopping-cart text-pink"></i>
      </div>-->
      <div id="expenseProgress" class="progress-css"></div>
      <div class="text-right">
        <h3 class="text-dark"><b>&#8377;&nbsp;</b><b class="incrementCounter">0</b></h3>
        <p class="text-muted">{{trans('message.total_expenses')}}</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="widget-bg-color-icon card-box">
      <!-- <div class="bg-icon bg-icon-purple pull-left">
        <i class="md md-equalizer text-purple"></i>
      </div> -->
      <div id="rentAmountProgress" class="progress-css"></div>
      <div class="text-right">
        <h3 class="text-dark"><b>&#8377;&nbsp;</b><b class="incrementCounter">0</b></h3>
        <p class="text-muted">{{trans('message.pending_rent_amount')}}</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="widget-bg-color-icon card-box">
      <!-- <div class="bg-icon bg-icon-success pull-left">
        <i class="md md-remove-red-eye text-success"></i>
      </div> -->
      <div id="rentCountProgress" class="progress-css"></div>
      <div class="text-right">
        <h3 class="text-dark"><b>&nbsp;</b><b class="incrementCounter">0</b></h3>
        <p class="text-muted">{{trans('message.pending_rent_count')}}</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card-box">
      <h4 class="text-dark header-title m-t-0">{{trans('message.last_30_days_income_expense')}}</h4>
      <div id="incomeChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
  </div>
</div>

<!-- end row -->
<div class="row">
  <div class="col-lg-12">
    <div class="card-box">
      <!-- <a href="#" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a> -->
      <h4 class="text-dark header-title m-t-0">{{trans('message.unpaid_guests_list').' for '.date('F-Y', strtotime($date)) }}</h4>
      <!-- <p class="text-muted m-b-30 font-13">
        Use the button classes on an element.
      </p> -->
      <div class="table-responsive m-t-20">
        <table class="table table-striped table-bordered datatable">
          <thead>
            <tr>
              <td>{{trans('message.name')}}</td>
              <td>{{trans('message.mobile_no')}}</td>
              <td>{{trans('message.amount')}}</td>
              <td>{{trans('message.electricity_amount')}}</td>
              <td>{{trans('message.total_amount')}}</td>
              <td>{{trans('message.pending_amount')}}</td>
            </tr>
          </thead>
          <tbody>
            @foreach($unpaid_guests as $key => $value)

              <tr>
                <td>{{ $value->name }}</td>
                <td>{{ $value->mobile_no }}</td>
                <td>{{ $value->amount }}</td>
                <td>{{ $value->electricity_amount }}</td>
                <td>{{ $value->amount + $value->electricity_amount }}</td>
                <td>{{ $value->pending_amount }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- col -->
  <!--<div class="col-lg-6">
    <div class="card-box">
      <a href="#" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>
      <h4 class="text-dark header-title m-t-0">{{trans('message.paid_guests_list').' for '.date('F-Y', strtotime($date)) }}</h4>
      <p class="text-muted m-b-30 font-13">
        Use the button classes on an element.
      </p>
      <div class="table-responsive m-t-20">
        <table class="table table-striped table-bordered datatable">
          <thead>
            <tr>
              <td>{{trans('message.name')}}</td>
              <td>{{trans('message.mobile_no')}}</td>
              <td>{{trans('message.amount')}}</td>
            </tr>
          </thead>
          <tbody>
            @foreach($paid_guests as $key => $value)

              <tr>
                <td>{{ $value->name }}</td>
                <td>{{ $value->mobile_no }}</td>
                <td>{{ $value->amount }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>-->
  <!-- end col -->
</div>
<!-- end row -->
<script type="text/javascript">
var x_axis = <?php echo json_encode($x_axis); ?>;
var income_y_axis = <?php echo json_encode($income_y_axis); ?>;
var expense_y_axis = <?php echo json_encode($expense_y_axis); ?>;
var total_income = <?php echo $total_income; ?>;
var total_expense = <?php echo $total_expense; ?>;
var total_pending_rent = <?php echo $total_pending_rent; ?>;
var total_pending_guests = <?php echo $total_pending_guests; ?>;
//console.log(x_axis, income_y_axis, expense_y_axis)
//console.log(total_income, total_expense)
</script>
	<script type="text/javascript" src="{{ asset('plugins/progressbar/js/dashboard.js') }}"></script>
  <script type="text/javascript" src="{{ asset('plugins/highchart/js/dashboard.js') }}"></script>
@endsection
