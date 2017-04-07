<?php
//echo $date_month;die;
?>
@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_monthly')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_monthly')}}
@endsection

@section('main-content')
	<div class="content spark-screen">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_monthly_report')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here-->
							<!--{{ HTML::ul($errors->all()) }}-->
							<div class="panel-group" id="accordion">
								<div class="panel panel-default">
								   <div class="panel-heading p-head" data-parent="#accordion" data-toggle="collapse" data-target="#collapse1">
								      <h4 class="panel-title">
								         {{trans('message.electric_bill_details_month')}}
								      </h4>
								   </div>
								   <div id="collapse1" class="panel-collapse collapse in">
								      <div class="panel-body">
								        <div class="form-group">
										       <div class="row form-top">
										        <div class="col-sm-12">
										               
										          <div id="billTable"></div>

										        </div>
										      </div>
										     </div>
										  </div>
										</div>
								</div>

								<div class="panel panel-default">
								  <div class="panel-heading p-head" data-parent="#accordion" data-toggle="collapse" data-target="#collapse2">
								      <h4 class="panel-title">
								         {{trans('message.room_rent_details_month')}}
								      </h4>
								  </div>
								  <div id="collapse2" class="panel-collapse collapse in">
								    <div class="panel-body">
								      <div class="form-group">
										    <div class="row form-top">
										      <div class="col-sm-12">

										      	<div id="guestTable" style="overflow-x:scroll;"></div>

										      </div>
								      	</div>
									    </div>
									  </div>
									</div>
								</div>
								

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<script type="text/javascript">
		var rentData = <?php echo json_encode($rent_monthly); ?>;
		var billData = <?php echo json_encode($bill_monthly); ?>;
		var trashRentData = <?php echo json_encode($inactive_rent_monthly); ?>;
		var trashBillData = <?php echo json_encode($inactive_bill_monthly); ?>;
		var dateMonth = '<?php echo $date_month; ?>';
		var nextMonth = '<?php echo $next_month; ?>';
		console.log(nextMonth)
	</script>
	<script type="text/babel" src="{{ asset('plugins/react/rent/rent_month.jsx') }}"></script>
	<script type="text/babel" src="{{ asset('plugins/react/rent/electricity_bill_month.jsx') }}"></script>
@endsection
