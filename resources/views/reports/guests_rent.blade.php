@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_report')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_report')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_report')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						<div class="row text-center">
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-primary active">
				            <input type="radio" name="report_type" checked value="all"> All
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" name="report_type" value="paid"> Paid guests
				        </label>
				        <label class="btn btn-primary">
				            <input type="radio" name="report_type" value="unpaid"> Unpaid guests
				        </label>
				        
				    	</div>
					    <label class="btn btn-primary monthCalendar">
					    	<input type="hidden" class="monthPicker">
					      <span id="monthText"><?php echo date('F-Y', strtotime($start_date)); ?></span>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>
					    </label>
						</div>
						<!--<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>-->
						<table class="table table-striped table-bordered roomReportTable" id="guest_report_table">
							<thead>
								<tr>
									<td>{{trans('message.name')}}</td>
									<td>{{trans('message.email')}}</td>
									<td>{{trans('message.mobile_no')}}</td>
									<td>{{trans('message.amount')}}</td>
									<td>{{trans('message.rent_amount_received')}}</td>
								</tr>
							</thead>
							<tbody>
								@foreach($rent_monthly as $key => $value)

									<tr>
										<td>{{ $value->name }}</td>
										<td>{{ $value->email }}</td>
										<td>{{ $value->mobile_no }}</td>
										<td>{{ $value->amount }}</td>
										<td class="text-center">{{ $value->rent_amount_received }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var reportTable = null;
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
		    	"data" : "rent_amount_received",
		    	render : function ( data, type, full, meta ) {
		    		if(data == 0) {
		    			return '<span class="red amount-yes">No</span>';
		    		} else {
		    			return '<span class="green amount-yes">Yes</span>';
		    		}
		    	}
		    }
	  	]
		};
	</script>
	{{ HTML::script('plugins/highchart/js/income.js') }}
@endsection