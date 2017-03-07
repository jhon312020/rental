@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.room_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.room_create')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.room_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::open(array('url' => 'rooms')) }}
								<div class="form-group">
									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('room_name')? 'has-error': '' }}">
											{{ Form::label('room_name', trans('message.room_name')) }}
											{{ Form::text('room_name', old('room_name'), array('class' => 'form-control')) }}
											@if ($errors->has('room_name'))
											<small class="help-block">{{ $errors->first('room_name') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('room_no')? 'has-error': '' }}">
											{{ Form::label('room_no', trans('message.room_no')) }}
											{{ Form::text('room_no', old('room_no'), array('class' => 'form-control')) }}
											@if ($errors->has('room_no'))
											<small class="help-block">{{ $errors->first('room_no') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('max_persons_allowed')? 'has-error': '' }}">
											{{ Form::label('max_persons_allowed', trans('message.max_persons_allowed')) }}
											{{ Form::text('max_persons_allowed', old('max_persons_allowed'), array('class' => 'form-control')) }}
											@if ($errors->has('max_persons_allowed'))
											<small class="help-block">{{ $errors->first('max_persons_allowed') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('rent_amount_person')? 'has-error': '' }}">
											{{ Form::label('rent_amount_person', trans('message.rent_amount_person')) }}
											{{ Form::text('rent_amount_person', old('rent_amount_person'), array('class' => 'form-control')) }}
											@if ($errors->has('rent_amount_person'))
											<small class="help-block">{{ $errors->first('rent_amount_person') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('total_rent_amount')? 'has-error': '' }}">
											{{ Form::label('total_rent_amount', trans('message.total_rent_amount')) }}
											{{ Form::text('total_rent_amount', old('total_rent_amount'), array('class' => 'form-control')) }}
											@if ($errors->has('total_rent_amount'))
											<small class="help-block">{{ $errors->first('total_rent_amount') }}</small>
											@endif
										</div>
									</div>
									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.create_room'), array('class' => 'btn btn-primary')) }}
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
