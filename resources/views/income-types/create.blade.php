@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.income_type_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.income_type_create')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.income_type_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-6 col-sm-offset-3">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::open(array('url' => 'income-types')) }}
								<div class="form-group {{ $errors->has('type_of_income')? 'has-error': '' }}">
										{{ Form::label('type_of_income', trans('message.type_of_income')) }}
										{{ Form::text('type_of_income', old('type_of_income'), array('class' => 'form-control')) }}
										@if ($errors->has('type_of_income'))
										<small class="help-block">{{ $errors->first('type_of_income') }}</small>
										@endif
								</div>
								<div class="form-group form-top">
									<div class="col-sm-12">
										<center>
										{{ Form::submit(trans('message.create_income_type'), array('class' => 'btn btn-primary')) }}
										</center>
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
