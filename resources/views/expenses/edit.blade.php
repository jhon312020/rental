@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.expense_update') }}
@endsection

@section('contentheader_title')
	{{ trans('message.expense_update') }}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.expense_update') }}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::model($expense, array('route' => array('expenses.update', $expense->id), 'method' => 'PUT')) }}
								<div class="form-group">
									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('expense_type')? 'has-error': '' }}">
											{{ Form::label('expense_type', trans('message.expense_type')) }}
											{{ Form::select('expense_type', $expense_types, null, array('class' => 'form-control')) }}
											@if ($errors->has('expense_type'))
											<small class="help-block">{{ $errors->first('expense_type') }}</small>
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

										<div class="col-sm-6 {{ $errors->has('date_of_expense')? 'has-error': '' }}">
											{{ Form::label('date_of_expense', trans('message.date_of_expense')) }}
											{{ Form::text('date_of_expense', null, array('class' => 'form-control datepicker')) }}
											@if ($errors->has('date_of_expense'))
											<small class="help-block">{{ $errors->first('date_of_expense') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.update_expense'), array('class' => 'btn btn-primary')) }}
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
