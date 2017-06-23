<?php
//echo $date_month;die;
?>
@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.bill_monthly')}}
@endsection

@section('contentheader_title')
	{{trans('message.bill_monthly')}}
@endsection

@section('main-content')
	<style type="text/css">
		#guestTable .select2-container {
			left : 20px;
		}
		#guestTable .select2-container:after {
		  content: "";
		  position: absolute;
		  z-index: 1;
		  width: 0px;
		  top: 1px;
		  height: 30px;
		  border-radius: 50%;
		  border-right: 1px solid #DDD;
		  border-left: 1px solid #fafafa;
		  margin-left: 165px;
		}
		#billTable .select2-container {
			left : 20px;
		}
		#billTable .select2-container:after {
		  content: "";
		  position: absolute;
		  z-index: 1;
		  width: 0px;
		  top: 1px;
		  height: 30px;
		  border-radius: 50%;
		  border-right: 1px solid #DDD;
		  border-left: 1px solid #fafafa;
		  margin-left: 165px;
		}
	</style>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.bill_monthly_report')}}</div>
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
								      <div class="panel-body" id="jsRentPanel">
								      	<div class="row min-height" id="jsBillUpdateDiv" style="display:none;">
													<div class="col-sm-12" height="60px">
														<button class="btn btn-large btn-primary pull-right" data-toggle="confirmation"
														        data-btn-ok-label="Continue" data-btn-ok-icon="glyphicon glyphicon-share-alt"
														        data-btn-ok-class="btn-success"
														        data-btn-cancel-label="Stoooop!" data-btn-cancel-icon="glyphicon glyphicon-ban-circle"
														        data-btn-cancel-class="btn-danger"
														        data-title="Is it ok?" data-content="This will reflect the particular active room rent details.">
														  Do you want to update the same into rental details?
														</button>
													</div>
												</div>
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
								

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	
	<script type="text/javascript">
		var billData = <?php echo json_encode($bill_monthly); ?>;
		var trashBillData = <?php echo json_encode($inactive_bill_monthly); ?>;
		var dateMonth = '<?php echo $date_month; ?>';
		var nextMonth = '<?php echo $next_month; ?>';
		var rooms = <?php echo json_encode($rooms); ?>;
		//console.log(nextMonth)
	</script>
	<script type="text/babel" src="{{ asset('plugins/react/rent/electricity_bill_month.jsx') }}"></script>
@endsection
