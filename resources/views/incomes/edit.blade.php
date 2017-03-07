@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.income_update') }}
@endsection

@section('contentheader_title')
	{{ trans('message.income_update') }}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.income_update') }}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::model($income, array('route' => array('incomes.update', $income->id), 'method' => 'PUT')) }}
								<div class="form-group">
									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('income_type')? 'has-error': '' }}">
											{{ Form::label('income_type', trans('message.income_type')) }}
											{{ Form::select('income_type', $income_types, null, array('class' => 'form-control')) }}
											@if ($errors->has('income_type'))
											<small class="help-block">{{ $errors->first('income_type') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('amount')? 'has-error': '' }}">
											{{ Form::label('amount', trans('message.amount')) }}
											{{ Form::text('amount', null, array('class' => 'form-control')) }}
											@if ($errors->has('amount'))
											<small class="help-block">{{ $errors->first('amount') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('notes')? 'has-error': '' }}">
											{{ Form::label('notes', trans('message.notes')) }}
											{{ Form::textarea('notes', null, array('class' => 'form-control area-class', 'cols' => 50, 'rows' => 5)) }}
											@if ($errors->has('notes'))
											<small class="help-block">{{ $errors->first('notes') }}</small>
											@endif
										</div>

										<div class="col-sm-6 {{ $errors->has('date_of_income')? 'has-error': '' }}">
											{{ Form::label('date_of_income', trans('message.date_of_income')) }}
											{{ Form::text('date_of_income', null, array('class' => 'form-control datepicker')) }}
											@if ($errors->has('date_of_income'))
											<small class="help-block">{{ $errors->first('date_of_income') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.update_income'), array('class' => 'btn btn-primary')) }}
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
