<?php 
	if(count($errors)) {
		$old_input = session()->getOldInput();
		$form_data = isset($old_input['guest']) ? $old_input['guest'] : [];
		//print_r($form_data);die;
		if(isset($old_input['guest'])) {
			$new_data = array_filter($form_data, function($v) { return !isset($v['rent_id']); });
		} else {
			$new_data = [];
		}
		
		//print_r($old_input);die;
		//print_r($new_data);die;
	} else {
		$form_data = [];
		$new_data = [];
	}
	//echo old('guest.1.name');die;
	//print_r($errors);die;
?>
@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_create')}}
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
	</style>	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here-->
							<!--{{ HTML::ul($errors->all()) }}-->
							{{ Form::open(array('url' => 'rents')) }}
							<div class="panel-group" id="accordion">
								<div class="panel panel-default">
								   <div class="panel-heading p-head" data-parent="#accordion" data-toggle="collapse" data-target="#collapse1">
								      <h4 class="panel-title">
								         {{trans('message.rent_details')}}
								      </h4>
								   </div>
								   <div id="collapse1" class="panel-collapse collapse in">
								      <div class="panel-body">
								         <div class="form-group">
								            <div class="row form-top">
								               <div class="col-sm-6 {{ $errors->has('rent.room_id')? 'has-error': '' }}">
								                  {{ Form::label('rent[room_id]', trans('message.room_no')) }}
								                  {{ Form::select('rent[room_id]', $rooms, old('rent.room_id'), array('class' => 'form-control select2')) }}
								                  @if ($errors->has('rent.room_id'))
								                  <small class="help-block">{{ $errors->first('rent.room_id') }}</small>
								                  @endif
								               </div>
								               <!--<div class="col-sm-6 {{ $errors->has('rent.advance')? 'has-error': '' }}">
								                  {{ Form::label('rent[advance]', trans('message.advance')) }}
								                  {{ Form::text('rent[advance]', old('rent.advance'), array('class' => 'form-control')) }}
								                  @if ($errors->has('rent.advance'))
								                  <small class="help-block">{{ $errors->first('rent.advance') }}</small>
								                  @endif
								               </div>-->
								               <div class="col-sm-6 {{ $errors->has('rent.checkin_date')? 'has-error': '' }}">
								                  {{ Form::label('rent[checkin_date]', trans('message.checkin_date')) }}
								                  {{ Form::text('rent[checkin_date]', old('rent.checkin_date'), array('class' => 'form-control datepicker')) }}
								                  @if ($errors->has('rent.checkin_date'))
								                  <small class="help-block">{{ $errors->first('rent.checkin_date') }}</small>
								                  @endif
								               </div>
								            </div>
								            <!--<div class="row form-top">
								               
								               <div class="col-sm-6 {{ $errors->has('rent.checkout_date')? 'has-error': '' }}">
								                  {{ Form::label('rent[checkout_date]', trans('message.checkout_date')) }}
								                  {{ Form::text('rent[checkout_date]', old('rent.checkout_date'), array('class' => 'form-control datepicker')) }}
								                  @if ($errors->has('rent.checkout_date'))
								                  <small class="help-block">{{ $errors->first('rent.checkout_date') }}</small>
								                  @endif
								               </div>
								            </div>-->
								         </div>
								      </div>
								   </div>
								</div>
								<div class="panel panel-default">
								   <div class="panel-heading p-head" data-parent="#accordion" data-toggle="collapse" data-target="#collapse2">
								      <h4 class="panel-title">
								         {{trans('message.guest_details')}}
								      </h4>
								   </div>
								   <div id="collapse2" class="panel-collapse collapse in">
								      <div class="panel-body">
								         <div class="row">
								         		<div class="col-sm-12">
								         			<ul>
								         				<?php
								         					/* $mobile = false;
								         					$email = false;
								         					$name = false;
								         				?>
								         			@foreach($errors->getMessages() as $key => $error)
								         				<?php
								         					if(preg_match('/guest.\d+.mobile_no/', $key) && !$mobile) {
								         						$mobile = true;
								         						echo "<li>" . $errors->first($key) . "</li>";
								         					}
								         					if(preg_match('/guest.\d+.email/', $key) && !$email) {
								         						$email = true;
								         						echo "<li>" . $errors->first($key) . "</li>";
								         					}
								         					if(preg_match('/guest.\d+.name/', $key) && !$name) {
								         						$name = true;
								         						echo "<li>" . $errors->first($key) . "</li>";
								         					}
								         				?>
								         			@endforeach
								         			@if ($errors->has('email'))
							         					<li>{{ $errors->first('email') }}</li>
							         				@endif
							         				@if ($errors->has('mobile_no'))
							         					<li>{{ $errors->first('mobile_no') }}</li>
							         				@endif */ ?>
								         			</ul>
								         		</div>
								         </div>
								         <div class="row form-top">
								            <div class="col-sm-12">
								               
								            		<div id="guestTable" style="overflow-x:scroll;"></div>

								            </div>
								         </div>
								      </div>
								   </div>
								</div>
							</div>
							<div class="form-group">
								<div class="row form-top">
									<div class="col-sm-12">
										<center>
										{{ Form::submit(trans('message.create_rent'), array('class' => 'btn btn-primary')) }}
										</center>
									</div>
								</div>
							</div>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	

	<!-- Modal -->
	<div id="guestSearch" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">{{trans('message.guest_search')}}</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
	      		<div class="col-sm-12">
	      			<?php
	      				$search_key = Config::get('constants.SEARCH_KEY');
	      				$search_key = array('' => 'select') + array_combine($search_key, $search_key);
	      			?>
	      			{!! Form::open(['method'=>'POST','url'=>'offices','class'=>'','role'=>'search'])  !!}
	      			<div class="form-group">
	      				<div class="col-sm-6">
	      					{{ Form::label('search_by', trans('message.search_by')) }}
								  {{ Form::select('search_by', $search_key, null, array('class' => 'form-control select2')) }}
	      				</div>
	      				<div class="col-sm-6">
	      					{{ Form::label('search_value', trans('message.search_value')) }}
									{{ Form::text('search_value', null, array('class' => 'form-control selectAutoAjax', 'data-url' => 'rent_serach')) }}
	      				</div>
	      			</div>
	      			{!! Form::close() !!}
	      		</div>
	      	</div>
	        <div class="col-xs-12 col-sm-12 toppad" style="display:none;">
	          <div class="panel panel-info">
	            <div class="panel-heading">
	              <h3 class="panel-title">Sheena Shrestha</h3>
	            </div>
	            <div class="panel-body">
	              <div class="row">
	                <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="http://babyinfoforyou.com/wp-content/uploads/2014/10/avatar-300x300.png" class="img-circle img-responsive"> </div>
	                
	                <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
	                  <dl>
	                    <dt>DEPARTMENT:</dt>
	                    <dd>Administrator</dd>
	                    <dt>HIRE DATE</dt>
	                    <dd>11/12/2013</dd>
	                    <dt>DATE OF BIRTH</dt>
	                       <dd>11/12/2013</dd>
	                    <dt>GENDER</dt>
	                    <dd>Male</dd>
	                  </dl>
	                </div>-->
	                <div class=" col-md-9 col-lg-9 "> 
	                  <table class="table table-user-information">
	                    <tbody>
	                      
	                     
	                    </tbody>
	                  </table>
	                </div>
	              </div>
	            </div>
	            <div class="panel-footer">
	              <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
	              <span class="pull-right">
		              <a href="" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
		              <a href="" data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
	              </span>
	            </div>
	          </div>
        	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>
	<script type="text/javascript">
		var formData = <?php echo json_encode($form_data); ?>;
		var newData = <?php echo json_encode($new_data); ?>;
		var rent_errors = <?php echo json_encode($errors->getMessages()); ?>;
		console.log(rent_errors)
	</script>
	<script type="text/babel" src="{{ asset('plugins/react/rent/guest.jsx') }}"></script>
@endsection
