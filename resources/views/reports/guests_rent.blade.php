@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_report')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_report')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_report')}} for <span id="jsRoomReportSpan">all</span> guests in the month of <span id="jsReportMonthSpan"><?php echo $month_year; ?></div>
					<div class="panel-body" id="jsReportPanel">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-primary active">
				            <input type="radio" name="report_type" checked value="all" data-text="all"> All
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" name="report_type" value="paid" data-text="paid"> Paid guests
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" name="report_type" value="unpaid" data-text="unpaid"> Unpaid guests
				        </label>
				        
				    	</div>
					    <label class="btn btn-primary monthCalendar">
					    	<input type="hidden" class="monthPicker">
					      <span id="monthText"><?php echo date('F-Y', strtotime($start_date)); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
						</div>
						<!--<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>-->
						<table class="table table-striped table-bordered reportTable" id="guest_report_table">
							<thead>
								<tr>
									<td>{{trans('message.name')}}</td>
									<td>{{trans('message.email')}}</td>
									<td>{{trans('message.mobile_no')}}</td>
									<td>{{trans('message.amount')}}</td>
									<td>{{trans('message.electricity_amount')}}</td>
									<td>{{trans('message.total_amount')}}</td>
									<td>{{trans('message.pending_amount')}}</td>
									<td>{{trans('message.total_pending_amount')}}</td>
								</tr>
							</thead>
							<tbody>
								@foreach($rent_monthly as $key => $value)

									<tr>
										<td>{{ $value->name }}</td>
										<td>{{ $value->email }}</td>
										<td>{{ $value->mobile_no }}</td>
										<td>{{ $value->amount }}</td>
										<td>{{ $value->electricity_amount }}</td>
										<td>0</td>
										<td>{{ $value->pending_amount }}</td>
										<td>{{ $value->total_pending_amount }}</td>
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
	var rowHighlightColumn = 'pending_amount';
	var start_date = '<?php echo $start_date; ?>';
	var end_date = '<?php echo $end_date; ?>';
	var month_change_ajax_url = ajax_url.get_income_report;
	var columns_defs = {
			"columnDefs": [
		    {
		    	"targets" : [0],
		    	"data" : "name"
		    },
		    {
		    	"targets" : [1],
		    	"data" : "email"
		    },
		    {
		    	"targets" : [2],
		    	"data" : "mobile_no"
		    },
		    {
		    	"targets" : [3],
		    	"data" : "amount"
		    },
		    {
		    	"targets" : [4],
		    	"data" : "electricity_amount"
		    },
		    {
		    	"targets" : [5],
		    	"data": "0",
		    	render : function ( data, type, full, meta ) {
		    		return parseInt(full.amount) + parseInt(full.electricity_amount);
		    	}
		    },
		    {
		    	"targets" : [6],
		    	"data" : "pending_amount"
		    },
		    {
		    	"targets" : [7],
		    	"data" : "total_pending_amount"
		    },
	  	],
	  	"rowCallback": function( row, data, index ) {
		    if ( data.pending_amount == 0 ) {
		      $(row).addClass( 'tr-success' );
		    } else if ( data.pending_amount > 0 ) {
		    	$(row).addClass( 'tr-danger' );
		    }
		  }
		};
	</script>
@endsection