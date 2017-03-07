@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.guests_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.guests_create')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.guests_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::open(array('url' => 'guests')) }}
								<div class="form-group">
									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('name')? 'has-error': '' }}">
											{{ Form::label('name', trans('message.name')) }}
											{{ Form::text('name', old('name'), array('class' => 'form-control')) }}
											@if ($errors->has('name'))
											<small class="help-block">{{ $errors->first('name') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('city')? 'has-error': '' }}">
											{{ Form::label('city', trans('message.city')) }}
											{{ Form::text('city', old('city'), array('class' => 'form-control')) }}
											@if ($errors->has('city'))
											<small class="help-block">{{ $errors->first('city') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('state')? 'has-error': '' }}">
											{{ Form::label('state', trans('message.state')) }}
											{{ Form::text('state', old('state'), array('class' => 'form-control')) }}
											@if ($errors->has('state'))
											<small class="help-block">{{ $errors->first('state') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('country')? 'has-error': '' }}">
											{{ Form::label('country', trans('message.country')) }}
											{{ Form::text('country', old('country'), array('class' => 'form-control')) }}
											@if ($errors->has('country'))
											<small class="help-block">{{ $errors->first('country') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('zip')? 'has-error': '' }}">
											{{ Form::label('zip', trans('message.zip')) }}
											{{ Form::text('zip', old('zip'), array('class' => 'form-control')) }}
											@if ($errors->has('zip'))
											<small class="help-block">{{ $errors->first('zip') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('email')? 'has-error': '' }}">
											{{ Form::label('email', trans('message.email')) }}
											{{ Form::email('email', old('email'), array('class' => 'form-control')) }}
											@if ($errors->has('email'))
											<small class="help-block">{{ $errors->first('email') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('address')? 'has-error': '' }}">
											{{ Form::label('address', trans('message.address')) }}
											{{ Form::textarea('address', old('address'), array('class' => 'form-control area-class', 'cols' => 50, 'rows' => 5)) }}
											@if ($errors->has('address'))
											<small class="help-block">{{ $errors->first('address') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('mobile_no')? 'has-error': '' }}">
											{{ Form::label('mobile_no', trans('message.mobile_no')) }}
											{{ Form::text('mobile_no', old('mobile_no'), array('class' => 'form-control')) }}
											@if ($errors->has('mobile_no'))
											<small class="help-block">{{ $errors->first('mobile_no') }}</small>
											@endif
										</div>
									</div>
									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.create_guest'), array('class' => 'btn btn-primary')) }}
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
