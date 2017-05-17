@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.expense_create') }}
@endsection

@section('contentheader_title')
	{{ trans('message.expense_create') }}
@endsection

@section('main-content')
	<div id="slide-out" class="side-nav">
		<div class="userView">
			<div class="form-group">
				<label><?php echo  trans('message.type_of_expense'); ?></label>
				<input type="text" name="category" class="form-control" />
			</div>
			<div class="form-group form-top text-center">
				<div class="col-sm-12">
					<buttton type="button"  class="btn btn-primary jsCreateType"><?php echo trans('message.create_expense_type') ?></button>
				</div>
			</div>
		</div>	
	</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.expense_create') }}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here
							{{ HTML::ul($errors->all()) }} -->

							{{ Form::open(array('url' => 'expenses')) }}
								<div class="form-group">
									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('expense_type')? 'has-error': '' }}">
											{{ Form::label('expense_type', trans('message.expense_type')) }}
											<i class="fa fa-plus-circle add-types jaAddTypes"></i>
											{{ Form::select('expense_type', $expense_types, old('expense_type'), array('class' => 'form-control select2')) }}
											@if ($errors->has('expense_type'))
											<small class="help-block">{{ $errors->first('expense_type') }}</small>
											@endif
										</div>
										<div class="col-sm-6 {{ $errors->has('amount')? 'has-error': '' }}">
											{{ Form::label('amount', trans('message.amount')) }}
											{{ Form::text('amount', old('amount'), array('class' => 'form-control')) }}
											@if ($errors->has('amount'))
											<small class="help-block">{{ $errors->first('amount') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-6 {{ $errors->has('notes')? 'has-error': '' }}">
											{{ Form::label('notes', trans('message.notes')) }}
											{{ Form::textarea('notes', old('notes'), array('class' => 'form-control area-class', 'cols' => 50, 'rows' => 5)) }}
											@if ($errors->has('notes'))
											<small class="help-block">{{ $errors->first('notes') }}</small>
											@endif
										</div>

										<div class="col-sm-6 {{ $errors->has('date_of_expense')? 'has-error': '' }}">
											{{ Form::label('date_of_expense', trans('message.date_of_expense')) }}
											{{ Form::text('date_of_expense', old('date_of_expense'), array('class' => 'form-control datepicker')) }}
											@if ($errors->has('date_of_expense'))
											<small class="help-block">{{ $errors->first('date_of_expense') }}</small>
											@endif
										</div>
									</div>

									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.create_expense'), array('class' => 'btn btn-primary')) }}
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
		<script type="text/javascript">
		var type_url = ajax_url.create_expense_type;
		</script>
@endsection
