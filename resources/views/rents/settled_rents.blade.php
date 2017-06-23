@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.settled_rent_lists')}}
@endsection

@section('contentheader_title')
	{{trans('message.settled_rent_lists')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.settled_rent_lists')}}</div>
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
									<table class="table table-striped table-bordered datatable settle_table">
										<thead>
											<tr>
												<td>{{trans('message.room_no')}}</td>
												<td>{{trans('message.advance')}}</td>
												<td>{{trans('message.rent_amount')}}</td>
												<td>{{trans('message.max_persons_allowed')}}</td>
												<td>{{trans('message.no_of_person_stayed')}}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($rents as $key => $value)
												<tr data-toggle="tooltip" data-title="Click row to view guest details" data-room-id="{{ $value->room_id }}" class="details-row">
													<td>{{ $value->room_no }}</td>
													<td>{{ $value->advance }}</td>
													<td>{{ $value->rent_amount }}</td>
													<td>{{ $value->max_persons_allowed }}</td>
													<td>{{ $value->no_of_person_stayed }}</td>
													<!-- we will also add show, edit, and delete buttons -->
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
		<script type="text/javascript">
			var column_array = [0, 1, 2, 3, 4];
		</script>
@endsection
