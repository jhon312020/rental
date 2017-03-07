@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.setting_update')}}
@endsection

@section('contentheader_title')
	{{trans('message.setting_update')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.setting_update')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						{{ Form::open($setting) }}
							<?php
								$i = 0;
								$count = count($setting);
							?>
							<div class="form-group">
							@foreach($setting as $key => $value)
								@if($i%2 == 0)
								<div class="row form-top">
								@endif
									
										<div class="col-sm-6 {{ $errors->has($key)? 'has-error': '' }}">
											{{ Form::label($key, trans('message.'.$key)) }}
											{{ Form::text($key, old($key) ? old($key) : $value, array('class' => 'form-control')) }}
											@if ($errors->has($key))
											<small class="help-block">{{ $errors->first($key) }}</small>
											@endif
										</div>

								@if($i%2 != 0 || $count == $i + 1)
								</div>
								@endif
								<?php
									$i++;
								?>
							@endforeach
							<div class="row form-top">
								<div class="col-sm-12">
									<center>
									{{ Form::submit(trans('message.updatesetting'), array('class' => 'btn btn-primary')) }}
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
