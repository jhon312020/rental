@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.rent_create')}}
@endsection

@section('contentheader_title')
	{{trans('message.rent_create')}}
@endsection

@section('main-content')
	<div class="content spark-screen">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.rent_create')}}</div>
					<div class="panel-body">
						<div class="col-sm-12">
							<!-- if there are creation errors, they will show here-->
							<!--{{ HTML::ul($errors->all()) }}-->
								<div class="row">
									<div class="col-sm-12">
										<div id="guestTable" style="overflow-x:scroll;"></div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<script type="text/javascript">
		var formData = <?php echo json_encode($rent); ?>;
		var room = <?php echo json_encode($room); ?>;
	</script>
	<script type="text/babel" src="{{ asset('plugins/react/rent/guest_edit.jsx') }}"></script>
@endsection
