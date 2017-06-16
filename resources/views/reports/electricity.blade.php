@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.electricity_bill_report')}}
@endsection

@section('contentheader_title')
	{{trans('message.electricity_bill_report')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.yearly_electricity_bill_report')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							
					    <label class="btn btn-primary yearCalendar">
					    	<input type="hidden" class="chartYearPicker">
					      <span id="yearText"><?php echo date('Y'); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
						</div>
						<div class="row text-center">
					    <p class="total-p"><span class="total-span">Total Electricity amount: &#8377;&nbsp;<span id="year_total">{{ $total_monthly_bill }}</span></span></p>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div id="incomeYear" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.electricity_bill_report_between_months')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						<div class="row text-center">
					    <div class="col-sm-12">
					    	<div class="col-sm-4 col-sm-offset-4">
						    	<div class="form-group">
		                <label>Electricity bill between:</label>

		                
		                <!-- /.input group -->
		              </div>
		              <div class="row text-center">
		              	
		              	<div class="col-sm-12">
			              	<div id="sla-data-range" class="mrp-container">
							          <span class="mrp-icon"><i class="fa fa-calendar"></i></span>
							          <div class="mrp-monthdisplay">
							            <span class="mrp-lowerMonth"><?php echo date('F Y', strtotime($start_date)); ?></span>
							            <span class="mrp-to"> to </span>
							            <span class="mrp-upperMonth"><?php echo date('F Y', strtotime($start_date)); ?></span>
							          </div>
								        <input type="hidden" class="dateRange" value="<?php echo date('Y-m-01', strtotime($start_date)).' - '.date('Y-m-01', strtotime($start_date));; ?>" />
								        <span class="search-btn">
								        	<button class="btn btn-secondary searchReport" type="button"><i class="fa fa-search"></i> Search</button>
								      </span>
							      	</div>
							      	</div>
		                
		              </div>
		              <div class="row text-center">
								    <p class="total-p"><span class="total-span">Total electricity bills: &#8377;&nbsp;<span id="total_amount_date">{{ $total_bill_between_months }}</span></span></p>
									</div>
		             </div>
					    </div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-striped table-bordered reportTable">
									<thead>
										<tr>
											<td>{{trans('message.billing_month_year')}}</td>
											<td>{{trans('message.room_no')}}</td>
											<td>{{trans('message.amount')}}</td>
										</tr>
									</thead>
									<tbody>
										@foreach($bill_between_months as $key => $value)
											<tr>
												<td>{{ $value->billing_month_year }}</td>
												<td>{{ $value->room_no }}</td>
												<td>{{ $value->amount }}</td>
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
	
	<script type="text/javascript">
	var reportTable = null;
	var start_date = '<?php echo $start_date; ?>';
	var end_date = '<?php echo $end_date; ?>';

	var month_year = '<?php echo date("F-Y"); ?>';

	var year = '<?php echo date("Y"); ?>';

	//var month_report_ajax_url = ajax_url.get_electricity_bill_report_month;
	var year_report_ajax_url = ajax_url.get_electricity_bill_report_month;
	var repot_between_date = ajax_url.get_electricity_bill_report_between_month;

	var x_axis = <?php echo json_encode($x_axis); ?>;
	var y_axis = <?php echo $y_axis; ?>;

	var monthChart = null;
	var yearChart = null;
	
	var columns_defs = {
			"columnDefs": [
		    {
		    	"targets" : [0],
		    	"data" : "billing_month_year"
		    },
		    {
		    	"targets" : [1],
		    	"data" : "room_no"
		    },
		    {
		    	"targets" : [2],
		    	"data" : "amount"
		    },
	  	]
		};
	</script>
	{{ HTML::script('public/plugins/highchart/js/electricity.js') }}
@endsection