@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_lists')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_lists')}}
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-11">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_lists')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						<div class="row min-height">
							<div class="col-sm-12" height="60px">
								<a href="{{ URL::to('rents/create') }}" class="btn btn-primary pull-right">{{trans('message.new_rent')}}</a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered datatable">
										<thead>
											<tr>
												<td>{{trans('message.room_no')}}</td>
												<td>{{trans('message.checkin_date')}}</td>
												<td>{{trans('message.checkout_date')}}</td>
												<td>{{trans('message.advance')}}</td>
												<td>{{trans('message.actions')}}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($rents as $key => $value)
												<tr>
													<td>{{ $value->room_no }}</td>
													<td>{{ $value->checkin_date }}</td>
													<td>{{ $value->checkout_date }}</td>
													<td>{{ $value->advance }}</td>

													<!-- we will also add show, edit, and delete buttons -->
													<td>

													<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
													<!-- we will add this later since its a little more complicated than the other two buttons -->

													<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
													<!--<a class="btn btn-small btn-success" href="{{ URL::to('guests/' . $value->id) }}">Show this Nerd</a>-->
													<a href="{{ URL::to('rents/' . $value->id . '/edit') }}" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-edit"></span>
													</a>
													<a href="javascript:;" data-href="{{ URL::to('rents/' . $value->id . '/destroy') }}" class="btn btn-danger btn-sm jsDelete">
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
