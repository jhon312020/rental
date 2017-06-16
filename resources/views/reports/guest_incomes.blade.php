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
					<div class="panel-heading">{{trans('message.guest_income_report_between_date')}} 
						<span class="pull-right">
							<span class="pad-right-15">Total rent:&nbsp;&#8377;&nbsp;{{$guest_income['rent']}}</span>
							<span class="pad-right-15">Paid rent:&nbsp;&#8377;&nbsp;{{$guest_income['incomes']}}</span>
							<span class="pad-right-15">Balance:&nbsp;&#8377;&nbsp;{{$guest_income['balance']}}</span>
					</span>
					</div>
					<div class="panel-body no-pad-top">
						<!-- will be used to show any messages -->
						<div class="row text-center">
					    <div class="col-sm-12">
					    	<div class="row">
					    		<div class="col-sm-4 pad-15" style="background:#f26d5f;">
					    			<span><i class="fa fa-user"></i>&nbsp;{{ $guest_details->name }}</span>
					    		</div>
					    		<div class="col-sm-4 pad-15" style="background:#4cbfe5;">
					    			<span><i class="fa fa-phone"></i>&nbsp;{{ $guest_details->phone }}</span>
					    		</div>
					    		<div class="col-sm-4 pad-15" style="background:#f3b44c;">
					    			<span><i class="fa fa-envelope"></i>&nbsp;{{ $guest_details->email }}</span>
					    		</div>
					    	</div>
					    	<div class="row">
						    	<div class="col-sm-4 col-sm-offset-4">
							    	<div class="form-group">
			                <label>Guest income between:</label>

			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>

			                  <input type="text" class="form-control pull-right dateRange" id="reservation" value="<?php echo date('01/m/Y').' - '.date('d/m/Y'); ?>">
			                  <span class="input-group-btn">
									        <button class="btn btn-secondary searchReport" type="button"><i class="fa fa-search"></i> Search</button>
									      </span>
			                </div>
			                <!-- /.input group -->
			              </div>
			            </div>
		          	</div>
		          	<div class="row">
					    		<div class="col-sm-4 pad-15" style="background:#f26d5f;">
					    			<span>Total rent amount:&nbsp;&#8377;&nbsp;<span id="rent">{{ $total_guest_income_between_date['rent'] }}</span></span>
					    		</div>
					    		<div class="col-sm-4 pad-15" style="background:#4cbfe5;">
					    			<span>Total amount paid:&nbsp;&#8377;&nbsp;<span id="incomes">{{ $total_guest_income_between_date['incomes'] }}</span></span>
					    		</div>
					    		<div class="col-sm-4 pad-15" style="background:#f3b44c;">
					    			<span>Balance:&nbsp;&#8377;&nbsp;<span id="balance">{{ $total_guest_income_between_date['balance'] }}</span></span>
					    		</div>
					    	</div>
					    </div>
						</div>
						<div class="row" style="margin-top:15px;">
							<div class="col-sm-12">
								<table class="table table-striped table-bordered reportTable">
									<thead>
										<tr>
											<td>{{trans('message.date_of_income')}}</td>
											<td>{{trans('message.rent_amount')}}</td>
											<td>{{trans('message.paid')}}</td>
											<td>{{trans('message.balance')}}</td>
										</tr>
									</thead>
									<tbody>
										@foreach($guest_income_between_date as $key => $value)
											<tr>
												<td>{{ $value->date }}</td>
												<td>{{ $value->rent_amount }}</td>
												<td>{{ $value->amount }}</td>
												<td>{{ $value->balance }}</td>
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
	var repot_between_date = ajax_url.get_guest_income_report_between_date;
	var custom_form_data = { guest_id : "{{$guest_id}}" };
	console.log(custom_form_data)
	var columns_defs = {
			"columnDefs": [
		    {
		    	"targets" : [0],
		    	"data" : "date"
		    },
		    {
		    	"targets" : [1],
		    	"data" : "rent_amount"
		    },
		    {
		    	"targets" : [2],
		    	"data" : "amount"
		    },
		    {
		    	"targets" : [3],
		    	"data" : "balance"
		    }
	  	],
	  	"order": []
		};
	</script>
@endsection