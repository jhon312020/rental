@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.guests_lists')}}
@endsection

@section('contentheader_title')
	{{trans('message.guests_lists')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.guests_lists')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						<div class="row min-height">
							<div class="col-sm-12" height="60px">
								<a href="{{ URL::to('guests/create') }}" class="btn btn-primary pull-right">{{trans('message.new_guest')}}</a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered datatable">
										<thead>
											<tr>
												<td>{{trans('message.name')}}</td>
												<td>{{trans('message.city')}}</td>
												<td>{{trans('message.state')}}</td>
												<td>{{trans('message.email')}}</td>
												<td>{{trans('message.mobile_no')}}</td>
												<td>{{trans('message.actions')}}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($guests as $key => $value)
												<tr>
													<td>{{ $value->name }}</td>
													<td>{{ $value->city }}</td>
													<td>{{ $value->state }}</td>
													<td>{{ $value->email }}</td>
													<td>{{ $value->mobile_no }}</td>

													<!-- we will also add show, edit, and delete buttons -->
													<td>

													<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
													<!-- we will add this later since its a little more complicated than the other two buttons -->

													<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
													<!--<a class="btn btn-small btn-success" href="{{ URL::to('guests/' . $value->id) }}">Show this Nerd</a>-->
													<a href="{{ URL::to('guests/' . $value->id . '/edit') }}" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-edit"></span>
													</a>
													<a href="javascript:;" data-href="{{ URL::to('guests/' . $value->id . '/destroy') }}" class="btn btn-danger btn-sm jsDelete">
														<span class="glyphicon glyphicon-trash"></span>
													</a>
													<!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
