@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.room_report')}}
@endsection

@section('contentheader_title')
	{{trans('message.room_report')}}
@endsection

@section('main-content')
	
	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.room_report')}} for <span id="jsRoomReportSpan">all</span> room in the month of <span id="jsReportMonthSpan"><?php echo date('F-Y'); ?></span></div>
					<div class="panel-body" id="jsReportPanel">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-primary active">
				            <input type="radio" name="report_type" checked value="all" data-text="all"> All
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" name="report_type" value="vacant" data-text="vacant"> Vacant rooms
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" name="report_type" value="nonvacant" data-text="non vacant"> Non vacant rooms
				        </label>
				        
				    	</div>
					    <label class="btn btn-primary monthCalendar">
					    	<input type="hidden" class="monthPicker">
					      <span id="monthText"><?php echo date('F-Y'); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
						</div>
						<!--<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>-->
						<table class="table table-striped table-bordered reportTable">
							<thead>
								<tr>
									<td>{{trans('message.room_no')}}</td>
									<td>{{trans('message.no_of_person_stayed')}}</td>
									<td>{{trans('message.no_of_person_stayed_current_month')}}</td>
									<td>{{trans('message.max_persons_allowed')}}</td>
									<td>{{trans('message.monthly_rent_amount')}}</td>
									<td>{{trans('message.monthly_income_amount')}}</td>
									<td>{{trans('message.total_rent_amount')}}</td>
									<td>{{trans('message.total_income_amount')}}</td>
								</tr>
							</thead>
							<tbody>
								@foreach($all_room as $key => $value)
									<tr>
										<td>{{ $value->room_no }}</td>
										<td>{{ $value->no_of_person_stayed }}</td>
										<td>{{ $value->no_of_person_stayed_current }}</td>
										<td>{{ $value->max_persons_allowed }}</td>
										<td>{{ $value->monthly_rent_amount }}</td>
										<td>{{ $value->monthly_income_amount }}</td>
										<td>{{ $value->total_rent_amount }}</td>
										<td>{{ $value->total_income_amount }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	
	<script type="text/javascript">
	var reportTable = null;
	var list_room = <?php echo json_encode($all_room); ?>;
	var start_date = '<?php echo $start_date; ?>';
	var end_date = '<?php echo $end_date; ?>';
	var month_change_ajax_url = ajax_url.get_room_report;
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
		    	"data" : "no_of_person_stayed_current"
		    },
		    {
		    	"targets" : [3],
		    	"data" : "max_persons_allowed"
		    },
		    {
		    	"targets" : [4],
		    	"data" : "monthly_rent_amount"
		    },
		    {
		    	"targets" : [5],
		    	"data" : "monthly_income_amount"
		    },
		    {
		    	"targets" : [6],
		    	"data" : "total_rent_amount"
		    },
		    {
		    	"targets" : [7],
		    	"data" : "total_income_amount"
		    }
	  	]
		};
	</script>
	{{ HTML::script('public/plugins/highchart/js/room.js') }}
@endsection