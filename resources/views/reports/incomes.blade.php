@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.income_report')}}
@endsection

@section('contentheader_title')
	{{trans('message.income_report')}}
@endsection

@section('main-content')
	
		@if ($roles->role_name == 'admin')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.monthly_income_report')}} for the month of <span id="jsMonthReportSpan"><?php echo date('F-Y'); ?></span></div>
					<div class="panel-body" id="jsReportChartMonthPanel">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							
					    <label class="btn btn-primary monthCalendar">
					    	<input type="hidden" class="chartMonthPicker">
					      <span id="monthText"><?php echo date('F-Y'); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
						</div>
						<div class="row text-center">
					    <p class="total-p"><span class="total-span">Total income: &#8377;&nbsp;<span id="month_total">{{ number_format($total_monthly_income, 0) }}</span></span></p>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div id="incomeMonth" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.yearly_income_report')}} for the year of <span id="jsYearReportSpan"><?php echo date('Y'); ?></div>
					<div class="panel-body" id="jsReportChartYearPanel">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							
					    <label class="btn btn-primary yearCalendar">
					    	<input type="hidden" class="chartYearPicker">
					      <span id="yearText"><?php echo date('Y'); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
						</div>
						<div class="row text-center">
					    <p class="total-p"><span class="total-span">Total income: &#8377;&nbsp;<span id="year_total">{{ number_format($total_yearly_income, 0) }}</span></span></p>
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
		@endif

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.income_report_between_date')}} from <span id="jsReportDateSpan"><?php echo date('d/m/Y', strtotime($start_date)).' to '.date('d/m/Y', strtotime($end_date)); ?></div>
					<div class="panel-body" id="jsReportPanel">
						<!-- will be used to show any messages -->
						<div class="row text-center">
					    <div class="col-sm-12">
					    	<div class="col-sm-4 col-sm-offset-4">
						    	<div class="form-group">
		                <label>Income between:</label>

		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-calendar"></i>
		                  </div>

		                  <input type="text" class="form-control pull-right dateRange" id="reservation" value="<?php echo date('d/m/Y', strtotime($start_date)).' - '.date('d/m/Y', strtotime($end_date)); ?>">
		                  <span class="input-group-btn">
								        <button class="btn btn-secondary searchReport" type="button"><i class="fa fa-search"></i> Search</button>
								      </span>
		                </div>
		                <!-- /.input group -->
		              </div>
		              <div class="row text-center">
								    <p class="total-p"><span class="total-span">Total income: &#8377;&nbsp;<span id="total_amount_date">{{ number_format($total_incomes_between_date, 0) }}</span></span></p>
									</div>
		             </div>
					    </div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-striped table-bordered reportTable">
									<thead>
										<tr>
											<td>{{trans('message.date_of_income')}}</td>
											<td>{{trans('message.income_type')}}</td>
											<td>{{trans('message.amount')}}</td>
											<td>{{trans('message.notes')}}</td>
										</tr>
									</thead>
									<tbody>
										@foreach($monthly_income_report as $key => $value)
											<tr>
												<td>{{ $value->date_of_income }}</td>
												<td>{{ $value->income_type }}</td>
												<td>{{ $value->amount }}</td>
												<td>{{ $value->notes }}</td>
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

	var month_report_ajax_url = ajax_url.get_income_report_month;
	var year_report_ajax_url = ajax_url.get_income_report_year;

	var x_axis = <?php echo json_encode($x_axis); ?>;
	var y_axis = <?php echo $y_axis; ?>;
	var yearly_x_axis = <?php echo json_encode($yearly_x_axis); ?>;
	var yearly_y_axis = <?php echo $yearly_y_axis; ?>;

	var monthChart = null;
	var yearChart = null;
	var repot_between_date = ajax_url.get_income_report_between_date;
	
	var columns_defs = {
			"columnDefs": [
		    {
		    	"targets" : [0],
		    	"data" : "date_of_income"
		    },
		    {
		    	"targets" : [1],
		    	"data" : "income_type"
		    },
		    {
		    	"targets" : [2],
		    	"data" : "amount"
		    },
		    {
		    	"targets" : [3],
		    	"data" : "notes"
		    }
	  	]
		};
	</script>
	@if ($roles->role_name == 'admin')
		{{ HTML::script('public/plugins/highchart/js/income.js') }}
	@endif
@endsection