@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.income_report')}}
@endsection

@section('contentheader_title')
	{{trans('message.income_report')}}
@endsection

@section('main-content')
	

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.income_report')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							
					    <label class="btn btn-primary monthCalendar">
					    	<input type="hidden" class="chartMonthPicker">
					      <span id="monthText"><?php echo date('F-Y'); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
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
					<div class="panel-heading">{{trans('message.income_report')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							
					    <label class="btn btn-primary yearCalendar">
					    	<input type="hidden" class="chartYearPicker">
					      <span id="yearText"><?php echo date('Y'); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
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
					<div class="panel-heading">{{trans('message.income_report')}}</div>
					<div class="panel-body">
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
		                  <input type="text" class="form-control pull-right dateRange" id="reservation">
		                </div>
		                <!-- /.input group -->
		              </div>
		             </div>
					    </div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-striped table-bordered incomeReportTable">
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

	var columns_defs = {
			"columnDefs": [
		    {
		    	"targets" : [0],
		    	"data" : "room_no"
		    },
		    {
		    	"targets" : [1],
		    	"data" : "no_of_person_stayed"
		    },
		    {
		    	"targets" : [2],
		    	"data" : "max_persons_allowed"
		    },
		    {
		    	"targets" : [3],
		    	"data" : "total_rent_amount"
		    },
		    {
		    	"targets" : [4],
		    	"data" : "rent_amount_get"
		    }
	  	]
		};
	</script>
	{{ HTML::script('plugins/highchart/js/income.js') }}
@endsection