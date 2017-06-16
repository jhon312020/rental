@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_lists')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_lists')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_lists')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						<div class="row min-height">
							<div class="col-sm-12" height="60px">
								<a href="{{ URL::to('rents/create') }}" class="btn btn-primary pull-right">{{trans('message.new_rent')}}</a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered datatable">
										<thead>
											<tr>
												<td>{{trans('message.name')}}</td>
												<td>{{trans('message.room_no')}}</td>
												<td>{{trans('message.advance')}}</td>
												<td>{{trans('message.checkin_date')}}</td>
												<td>{{trans('message.mobile_no')}}</td>
												<td>{{trans('message.actions')}}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($rents as $key => $value)
												<tr>
													<td>{{ $value->name }}</td>
													<td>{{ $value->room_no }}</td>
													<td>{{ $value->advance }}</td>
													<td>{{ $value->checkin_date	 }}</td>
													<td>{{ $value->mobile_no }}</td>
													<!-- we will also add show, edit, and delete buttons -->
													<td>

													<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
													<!-- we will add this later since its a little more complicated than the other two buttons -->

													<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
													<!--<a class="btn btn-small btn-success" href="{{ URL::to('guests/' . $value->id) }}">Show this Nerd</a>-->
													<a href="javascript:;" class="btn btn-info btn-sm jsSettlement" data-id="{{$value->id}}">
														<span class="glyphicon glyphicon-briefcase" data-toggle="tooltip" title="Settlement"></span>
													</a>
													<a href="{{ URL::to('rents/' . $value->id . '/edit') }}" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-edit"></span>
													</a>
													<a href="javascript:;" data-href="{{ URL::to('rents/' . $value->id . '/destroy') }}" class="btn btn-danger btn-sm jsDelete">
														<span class="glyphicon glyphicon-trash"></span>
													</a>
													<!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
													</td>
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
		</div>

		<!-- Modal -->
		<div class="modal fade" id="settlementModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog zoomInUp animated modal-md" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">{{trans('message.settlement')}}</h4>
		      </div>
		      <div class="modal-body">
		      	<div class="row">
      			<div class="col-sm-6 pad-15" style="background:#f3b44c;">
		    			<span>Advance:&nbsp;₹&nbsp;<span id="advance">0</span></span>
		    		</div>
		    		<div class="col-sm-6 pad-15" style="background:#f26d5f;">
		    			<span>Total rent amount:&nbsp;₹&nbsp;<span id="rent">0</span></span>
		    		</div>
		    		<div class="col-sm-6 pad-15" style="background:#4cbfe5;">
		    			<span>Total amount paid:&nbsp;₹&nbsp;<span id="incomes">0</span></span>
		    		</div>
		    		<div class="col-sm-6 pad-15" style="background:#f3b44c;">
		    			<span>Balance:&nbsp;₹&nbsp;<span id="balance">16232</span></span>
		    		</div>
		    		
			        <form id="settle_form">
			        	<div class="col-sm-6">
			        	<div class="form-group">
			            <div class="row form-top">
			              <div class="col-sm-12">
			              	<label>Checkout date</label>
			              	<input type="text" name="checkout_date" id="checkout_date" class="form-control datepicker" />
			              </div>
			            </div>
			            <div class="row form-top">
			              <div class="col-sm-12">
			              	<label>Amount</label>
			              	<input type="text" name="amount" id="amount" class="form-control" />
			              </div>
			            </div>
			            <div class="row form-top">
			              <div class="col-sm-12">
			              	<label>Advance</label>
			              	<input type="text" name="advance" id="advance_amount" class="form-control" />
			              </div>
			            </div>
			            
			          </div>
			        </div>
			        <div class="col-sm-6" style="margin-top:20px;">
			        	<fieldset>
			        		<legend>List of group guests:</legend>
			        	<div class="form-group" style="margin-bottom:0px;">
			        	<div class="row" style="margin-left:15px;">
									  <div class="col-sm-12 span12 jsIncharge">
									 </div>
									</div>
								</div>
							</fieldset>
			        </div>
			        </form>
		      </div>
		    </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary jsSettlementSubmit">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>
		<script type="text/javascript">
		var rent_id, advance, table_row;
		</script>
@endsection
