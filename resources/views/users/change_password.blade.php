@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.change_password') }}
@endsection

@section('contentheader_title')
	{{ trans('message.change_password') }}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.change_password') }}</div>
					<div class="panel-body">
						<!-- if there are creation errors, they will show here -->
						<!--{{ HTML::ul($errors->all()) }}-->
						<div class="col-sm-4 col-sm-offset-4">
						@include('layouts.common.messages')
						{{ Form::open(array('url' => 'users/change-password')) }}
							
									<div class="form-group form-top {{ $errors->has('new_password')? 'has-error': '' }}">
											{{ Form::label('new_password', trans('message.password')) }}
											{{ Form::password('new_password', array('class' => 'form-control')) }}
											@if ($errors->has('new_password'))
											<small class="help-block">{{ $errors->first('new_password') }}</small>
											@endif
									</div>

									<div class="form-group form-top {{ $errors->has('confirm_password')? 'has-error': '' }}">
											{{ Form::label('confirm_password', trans('message.confirm_password')) }}
											{{ Form::password('confirm_password', array('class' => 'form-control')) }}
											@if ($errors->has('confirm_password'))
											<small class="help-block">{{ $errors->first('confirm_password') }}</small>
											@endif
									</div>

									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.update_password'), array('class' => 'btn btn-primary')) }}
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

@section('page-js-script')
<script type="text/javascript">
$(document).ready(function() {
	$('#new_password').val("{{ old('new_password') }}");
	$('#confirm_password').val("{{ old('confirm_password') }}");
})
</script>
@endsection