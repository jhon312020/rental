@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.notes_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.notes_create')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.notes_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-6 col-sm-offset-3">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::open(array('url' => 'notes')) }}
								<div class="form-group {{ $errors->has('date_of_notes')? 'has-error': '' }}">
										{{ Form::label('date_of_notes', trans('message.date_of_notes')) }}
										{{ Form::text('date_of_notes', old('date_of_notes'), array('class' => 'form-control datepicker')) }}
										@if ($errors->has('date_of_notes'))
										<small class="help-block">{{ $errors->first('date_of_notes') }}</small>
										@endif
								</div>
								<div class="form-group {{ $errors->has('notes')? 'has-error': '' }}">
										{{ Form::label('notes', trans('message.notes')) }}
										{{ Form::text('notes', old('notes'), array('class' => 'form-control')) }}
										@if ($errors->has('notes'))
										<small class="help-block">{{ $errors->first('notes') }}</small>
										@endif
								</div>
								<div class="form-group form-top">
									<div class="col-sm-12">
										<center>
										{{ Form::submit(trans('message.create_notes'), array('class' => 'btn btn-primary')) }}
										</center>
									</div>
								</div>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
