@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_update')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_update')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_update')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here-->
							<!--{{ HTML::ul($errors->all()) }}-->
							{{ Form::model($rent, array('route' => array('rents.update', $rent->id), 'method' => 'PUT')) }}
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
								                  {{ Form::select('rent[room_id]', $rooms, old('room_id') ? old('room_id') : $rent->room_id, array('class' => 'form-control select2')) }}
								                  @if ($errors->has('rent.room_id'))
								                  <small class="help-block">{{ $errors->first('rent.room_id') }}</small>
								                  @endif
								               </div>
								               <div class="col-sm-6 {{ $errors->has('rent.advance')? 'has-error': '' }}">
								                  {{ Form::label('rent[advance]', trans('message.advance')) }}
								                  {{ Form::text('rent[advance]', old('advance') ? old('advance') : $rent->advance, array('class' => 'form-control')) }}
								                  @if ($errors->has('rent.advance'))
								                  <small class="help-block">{{ $errors->first('rent.advance') }}</small>
								                  @endif
								               </div>
								            </div>
								            <div class="row form-top">
								            	<div class="col-sm-6 {{ $errors->has('rent.checkin_date')? 'has-error': '' }}">
								                  {{ Form::label('rent[checkin_date]', trans('message.checkin_date')) }}
								                  {{ Form::text('rent[checkin_date]', old('checkin_date') ? old('checkin_date') : $rent->checkin_date, array('class' => 'form-control datepicker')) }}
								                  @if ($errors->has('rent.checkin_date'))
								                  <small class="help-block">{{ $errors->first('rent.checkin_date') }}</small>
								                  @endif
								               </div>
								               <div class="col-sm-6 {{ $errors->has('rent.checkout_date')? 'has-error': '' }}">
								                  {{ Form::label('rent[checkout_date]', trans('message.checkout_date')) }}
								                  {{ Form::text('rent[checkout_date]', old('checkout_date') ? old('checkout_date') : $rent->checkout_date, array('class' => 'form-control datepicker')) }}
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
								         <div class="row form-top">
								            <div class="col-sm-12">
								               
								            		<div class="form-group">
					                        <div class="row form-top">
					                           <div class="col-sm-6 {{ $errors->has('guest.name')? 'has-error': '' }}">
					                              {{ Form::label('guest[name]', trans('message.name')) }}
					                              {{ Form::text('guest[name]', old('name') ? old('name') : $rent->name, array('class' => 'form-control')) }}
					                              {{ Form::hidden('guest[guest_id]', $rent->guest_id, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.name'))
					                              <small class="help-block">{{ $errors->first('guest.name') }}</small>
					                              @endif
					                           </div>
					                           <div class="col-sm-6 {{ $errors->has('guest.city')? 'has-error': '' }}">
					                              {{ Form::label('guest[city]', trans('message.city')) }}
					                              {{ Form::text('guest[city]', old('city') ? old('city') : $rent->city, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.city'))
					                              <small class="help-block">{{ $errors->first('guest.city') }}</small>
					                              @endif
					                           </div>
					                        </div>
					                        <div class="row form-top">
					                           <div class="col-sm-6 {{ $errors->has('guest.state')? 'has-error': '' }}">
					                              {{ Form::label('guest[state]', trans('message.state')) }}
					                              {{ Form::text('guest[state]', old('state') ? old('state') : $rent->state, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.state'))
					                              <small class="help-block">{{ $errors->first('guest.state') }}</small>
					                              @endif
					                           </div>
					                           <div class="col-sm-6 {{ $errors->has('guest.country')? 'has-error': '' }}">
					                              {{ Form::label('guest[country]', trans('message.country')) }}
					                              {{ Form::text('guest[country]', old('country') ? old('country') : $rent->country, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.country'))
					                              <small class="help-block">{{ $errors->first('guest.country') }}</small>
					                              @endif
					                           </div>
					                        </div>
					                        <div class="row form-top">
					                           <div class="col-sm-6 {{ $errors->has('guest.zip')? 'has-error': '' }}">
					                              {{ Form::label('guest[zip]', trans('message.zip')) }}
					                              {{ Form::text('guest[zip]', old('zip') ? old('zip') : $rent->zip, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.zip'))
					                              <small class="help-block">{{ $errors->first('guest.zip') }}</small>
					                              @endif
					                           </div>
					                           <div class="col-sm-6 {{ $errors->has('guest.email')? 'has-error': '' }}">
					                              {{ Form::label('guest[email]', trans('message.email')) }}
					                              {{ Form::email('guest[email]', old('email') ? old('email') : $rent->email, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.email'))
					                              <small class="help-block">{{ $errors->first('guest.email') }}</small>
					                              @endif
					                           </div>
					                        </div>
					                        <div class="row form-top">
					                           <div class="col-sm-6 {{ $errors->has('guest.address')? 'has-error': '' }}">
					                              {{ Form::label('guest[address]', trans('message.address')) }}
					                              {{ Form::textarea('guest[address]', old('address') ? old('address') : $rent->address, array('class' => 'form-control area-class', 'cols' => 50, 'rows' => 5)) }}
					                              @if ($errors->has('guest.address'))
					                              <small class="help-block">{{ $errors->first('guest.address') }}</small>
					                              @endif
					                           </div>
					                           <div class="col-sm-6 {{ $errors->has('guest.mobile_no')? 'has-error': '' }}">
					                              {{ Form::label('guest[mobile_no]', trans('message.mobile_no')) }}
					                              {{ Form::text('guest[mobile_no]', old('mobile_no') ? old('mobile_no') : $rent->mobile_no, array('class' => 'form-control')) }}
					                              @if ($errors->has('guest.mobile_no'))
					                              <small class="help-block">{{ $errors->first('guest.mobile_no') }}</small>
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
							<div class="form-group">
								<div class="row form-top">
									<div class="col-sm-12">
										<center>
										{{ Form::submit(trans('message.update_rent'), array('class' => 'btn btn-primary')) }}
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
@endsection
