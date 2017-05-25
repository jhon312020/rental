@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.expense_type_update')}}
@endsection

@section('contentheader_title')
	{{trans('message.expense_type_update')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.expense_type_update')}}</div>
					<div class="panel-body">
						<div class="col-sm-6 col-sm-offset-3">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::model($expense_type, array('route' => array('expense-types.update', $expense_type->id), 'method' => 'PUT')) }}
								<div class="form-group {{ $errors->has('type_of_expense')? 'has-error': '' }}">
										{{ Form::label('type_of_expense', trans('message.type_of_expense')) }}
										{{ Form::text('type_of_expense', null, array('class' => 'form-control')) }}
										@if ($errors->has('type_of_expense'))
										<small class="help-block">{{ $errors->first('type_of_expense') }}</small>
										@endif
								</div>
								<div class="form-group form-top">
									<div class="col-sm-12">
										<center>
										{{ Form::submit(trans('message.update_expense_type'), array('class' => 'btn btn-primary')) }}
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
