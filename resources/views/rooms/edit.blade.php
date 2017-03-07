@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.room_update')}}
@endsection

@section('contentheader_title')
	{{trans('message.room_update')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.room_update')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::model($room, array('route' => array('rooms.update', $room->id), 'method' => 'PUT')) }}
								<div class="form-group">
									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('name')? 'has-error': '' }}">
											{{ Form::label('room_name', trans('message.room_name')) }}
											{{ Form::text('room_name', null, array('class' => 'form-control')) }}
											@if ($errors->has('room_name'))
											<small class="help-block">{{ $errors->first('room_name') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('room_no')? 'has-error': '' }}">
											{{ Form::label('room_no', trans('message.room_no')) }}
											{{ Form::text('room_no', null, array('class' => 'form-control')) }}
											@if ($errors->has('room_no'))
											<small class="help-block">{{ $errors->first('room_no') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('max_persons_allowed')? 'has-error': '' }}">
											{{ Form::label('max_persons_allowed', trans('message.max_persons_allowed')) }}
											{{ Form::text('max_persons_allowed', null, array('class' => 'form-control')) }}
											@if ($errors->has('max_persons_allowed'))
											<small class="help-block">{{ $errors->first('max_persons_allowed') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('rent_amount_person')? 'has-error': '' }}">
											{{ Form::label('rent_amount_person', trans('message.rent_amount_person')) }}
											{{ Form::text('rent_amount_person', null, array('class' => 'form-control')) }}
											@if ($errors->has('rent_amount_person'))
											<small class="help-block">{{ $errors->first('rent_amount_person') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('total_rent_amount')? 'has-error': '' }}">
											{{ Form::label('total_rent_amount', trans('message.total_rent_amount')) }}
											{{ Form::text('total_rent_amount', null, array('class' => 'form-control')) }}
											@if ($errors->has('total_rent_amount'))
											<small class="help-block">{{ $errors->first('total_rent_amount') }}</small>
											@endif
										</div>
									</div>
									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.update_room'), array('class' => 'btn btn-primary')) }}
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
