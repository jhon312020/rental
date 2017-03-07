@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_create')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here-->
							{{ HTML::ul($errors->all()) }}
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
												<div class="col-sm-6 {{ $errors->has('rent.advance')? 'has-error': '' }}">
													{{ Form::label('rent[advance]', trans('message.advance')) }}
													{{ Form::text('rent[advance]', old('rent.advance'), array('class' => 'form-control')) }}
													@if ($errors->has('rent.advance'))
													<small class="help-block">{{ $errors->first('rent.advance') }}</small>
													@endif
												</div>
											</div>	
											<div class="row form-top">
												<div class="col-sm-6 {{ $errors->has('rent.checkin_date')? 'has-error': '' }}">
													{{ Form::label('rent[checkin_date]', trans('message.checkin_date')) }}
													{{ Form::text('rent[checkin_date]', old('rent.checkin_date'), array('class' => 'form-control datepicker')) }}
													@if ($errors->has('rent.checkin_date'))
													<small class="help-block">{{ $errors->first('rent.checkin_date') }}</small>
													@endif
												</div>
												<div class="col-sm-6 {{ $errors->has('rent.checkout_date')? 'has-error': '' }}">
													{{ Form::label('rent[checkout_date]', trans('message.checkout_date')) }}
													{{ Form::text('rent[checkout_date]', old('rent.checkout_date'), array('class' => 'form-control datepicker')) }}
													@if ($errors->has('rent.checkout_date'))
													<small class="help-block">{{ $errors->first('rent.checkout_date') }}</small>
													@endif
												</div>
											</div>
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
						      			<button type="button" class="btn btn-primary pull-right">{{trans('message.add_another_guest')}}</button>
						      		</div>
						      	</div>
						      	<div class="row form-top">
						      		<div class="col-sm-12">
											<div class="box box-primary">
						            <div class="box-header with-border">
						              <h3 class="box-title">Guest1</h3>
						            </div>
						            <!-- /.box-header -->
						              <div class="box-body">
						              <!-- /.box-body -->
						              	<div class="form-group">
															<div class="row form-top">
																<div class="col-sm-6 {{ $errors->has('guest.0.name')? 'has-error': '' }}">
																	{{Form::hidden('guest[0][id]', old('guest.0.id'))}}
																	{{ Form::label('guest[0][name]', trans('message.name')) }}
																	{{ Form::text('guest[0][name]', old('guest.0.name'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.name'))
																	<small class="help-block">{{ $errors->first('guest.0.name') }}</small>
																	@endif
																</div>
																<div class="col-sm-6 {{ $errors->has('guest.0.city')? 'has-error': '' }}">
																	{{ Form::label('guest[0][city]', trans('message.city')) }}
																	{{ Form::text('guest[0][city]', old('guest.0.city'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.city'))
																	<small class="help-block">{{ $errors->first('guest.0.city') }}</small>
																	@endif
																</div>
															</div>

															<div class="row form-top">
																<div class="col-sm-6 {{ $errors->has('guest.0.state')? 'has-error': '' }}">
																	{{ Form::label('guest[0][state]', trans('message.state')) }}
																	{{ Form::text('guest[0][state]', old('guest.0.state'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.state'))
																	<small class="help-block">{{ $errors->first('guest.0.state') }}</small>
																	@endif
																</div>
																<div class="col-sm-6 {{ $errors->has('guest.0.country')? 'has-error': '' }}">
																	{{ Form::label('guest[0][country]', trans('message.country')) }}
																	{{ Form::text('guest[0][country]', old('guest.0.country'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.country'))
																	<small class="help-block">{{ $errors->first('guest.0.country') }}</small>
																	@endif
																</div>
															</div>

															<div class="row form-top">
																<div class="col-sm-6 {{ $errors->has('guest.0.zip')? 'has-error': '' }}">
																	{{ Form::label('guest[0][zip]', trans('message.zip')) }}
																	{{ Form::text('guest[0][zip]', old('guest.0.zip'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.zip'))
																	<small class="help-block">{{ $errors->first('guest.0.zip') }}</small>
																	@endif
																</div>
																<div class="col-sm-6 {{ $errors->has('guest.0.email')? 'has-error': '' }}">
																	{{ Form::label('guest[0][email]', trans('message.email')) }}
																	{{ Form::email('guest[0][email]', old('guest.0.email'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.email'))
																	<small class="help-block">{{ $errors->first('guest.0.email') }}</small>
																	@endif
																</div>
															</div>

															<div class="row form-top">
																<div class="col-sm-6 {{ $errors->has('guest.0.address')? 'has-error': '' }}">
																	{{ Form::label('guest[0][address]', trans('message.address')) }}
																	{{ Form::textarea('guest[0][address]', old('guest.0.address'), array('class' => 'form-control area-class', 'cols' => 50, 'rows' => 5)) }}
																	@if ($errors->has('guest.0.address'))
																	<small class="help-block">{{ $errors->first('guest.0.address') }}</small>
																	@endif
																</div>
																<div class="col-sm-6 {{ $errors->has('guest.0.mobile_no')? 'has-error': '' }}">
																	{{ Form::label('guest[0][mobile_no]', trans('message.mobile_no')) }}
																	{{ Form::text('guest[0][mobile_no]', old('guest.0.mobile_no'), array('class' => 'form-control')) }}
																	@if ($errors->has('guest.0.mobile_no'))
																	<small class="help-block">{{ $errors->first('guest.0.mobile_no') }}</small>
																	@endif
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
	</div>
@endsection
