@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.user_profile_update') }}
@endsection

@section('contentheader_title')
	{{ trans('message.user_profile_update') }}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.user_profile_update') }}</div>
					<div class="panel-body">
						<!-- if there are creation errors, they will show here -->
						<!--{{ HTML::ul($errors->all()) }}-->
						@include('layouts.common.messages')
						{{ Form::model($user, array('method' => 'POST', 'enctype' => 'multipart/form-data')) }}
						<div class="col-sm-6">
							
								<div class="form-group form-top {{ $errors->has('username')? 'has-error': '' }}">
										{{ Form::label('username', trans('message.username')) }}
										{{ Form::text('username', null, array('class' => 'form-control')) }}
										@if ($errors->has('username'))
										<small class="help-block">{{ $errors->first('username') }}</small>
										@endif
								<div class="form-group form-top {{ $errors->has('email')? 'has-error': '' }}">
										{{ Form::label('email', trans('message.email')) }}
										{{ Form::email('email', null, array('class' => 'form-control')) }}
										@if ($errors->has('email'))
										<small class="help-block">{{ $errors->first('email') }}</small>
										@endif
								</div>

									<div class="form-group form-top {{ $errors->has('password')? 'has-error': '' }}">
											{{ Form::label('password', trans('message.password')) }}
											{{ Form::password('password', array('class' => 'form-control')) }}
											@if ($errors->has('password'))
											<small class="help-block">{{ $errors->first('password') }}</small>
											@endif
									</div>

									<div class="row form-top">
										<div class="col-sm-12">
											<center>
											{{ Form::submit(trans('message.update_user_profile'), array('class' => 'btn btn-primary')) }}
											</center>
										</div>
									</div>
								</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
					      <input id="avatar" name="avatar" type="file" class="file-loading">
					    </div>
						</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
@endsection
<script type="text/javascript">
var avatar = "{{$user->avatar}}";
if(avatar) {
	var file_path = "{{ asset('/images/'.Auth::User()->id.'/'.$user->avatar) }}";
	
}
</script>
