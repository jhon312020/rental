@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.messages') }}
@endsection

@section('contentheader_title')
{{ trans('message.messages').' : ' }}
	<span id="monthText">{{ $month_name.'-'.$year }}</span>
	<label class="monthCalendar">
		<span><i class="fa fa-calendar" style="cursor:pointer;"></i></span>
		<input type="text" class="form-control hide chartMonthPicker">
	</label>
	<form class="hide jsDateForm" method="post">
		{{ csrf_field() }}
		<input type="text" name="start_date" id="start_date" value="{{$start_date}}" />
		<input type="text" name="send_message" id="send_message" value="0" />
	</form>
	@if($message_count == 0)
	<input type="button" value="Send message to all" id="jsSendMessage" class="btn btn-primary" style="margin-left:15px;">
	@endif
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.delivered_messages') }}

					</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered datatable">
										<thead>
											<tr>
												<td>{{ trans('message.name') }}</td>
												<td>{{ trans('message.mobile_no') }}</td>
												<td>{{ trans('message.message') }}</td>
												<td>{{ trans('message.room_no') }}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($deliveredUsers as $key => $value)
												<tr>
													<td>{{ $value->name }}</td>
													<td>{{ $value->mobile_no }}</td>
													<td>{{ $value->message }}</td>
													<td>{{ $value->room_no }}</td>
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
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.nondelivered_messages') }}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered datatable">
										<thead>
											<tr>
												<td>{{ trans('message.name') }}</td>
												<td>{{ trans('message.mobile_no') }}</td>
												<td>{{ trans('message.message') }}</td>
												<td>{{ trans('message.room_no') }}</td>
												<td>{{ trans('message.error') }}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($nonDeliveredUsers as $key => $value)
												<tr>
													<td>{{ $value->name }}</td>
													<td>{{ $value->mobile_no }}</td>
													<td>{{ $value->message }}</td>
													<td>{{ $value->room_no }}</td>
													<td>{{ $value->error_message }}</td>
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
<script type="text/javascript">
var end_date = '<?php echo $end_date; ?>'
var start_date = '<?php echo $start_date; ?>';
</script>