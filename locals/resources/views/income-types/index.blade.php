@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.income_type_lists')}}
@endsection

@section('contentheader_title')
	{{trans('message.income_type_lists')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.income_type_lists')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						<div class="row min-height">
							<div class="col-sm-12" height="60px">
								<a href="{{ URL::to('income-types/create') }}" class="btn btn-primary pull-right">New income types</a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered datatable">
										<thead>
											<tr>
												<td>{{trans('message.type_of_income')}}</td>
												<td>{{trans('message.actions')}}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($income_types as $key => $value)
												<tr>
													<td>{{ $value->type_of_income }}</td>

													<!-- we will also add show, edit, and delete buttons -->
													<td>

													<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
													<!-- we will add this later since its a little more complicated than the other two buttons -->

													<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
													<!--<a class="btn btn-small btn-success" href="{{ URL::to('guests/' . $value->id) }}">Show this Nerd</a>-->
													<a href="{{ URL::to('income-types/' . $value->id . '/edit') }}" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-edit"></span>
													</a>
													<a href="javascript:;" data-href="{{ URL::to('income-types/' . $value->id . '/destroy') }}" class="btn btn-danger btn-sm jsDelete">
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
@endsection
