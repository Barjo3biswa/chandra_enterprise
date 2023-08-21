@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>
					Zone 
				</h2>
				<ul class="header-dropdown m-r--5">

					<li><a href="{{ route('zone-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>



				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Remarks</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Remarks</th>
								<th>Action</th>
							</tr>
						</tfoot>

						@if(count($assigned_zones)>0)
						<tbody>
							@php $i=1 @endphp
							@foreach($assigned_zones as $zone)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ ucwords($zone->zone->name) }}</td>
								<td>{{ $zone->zone->remarks }}</td>
								<td>
									<div class="btn-group">

										<a href="{{ route('show-assigned-zone',Crypt::encrypt($zone->zone_id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>

									</div>
								</td>
							</tr>
							@php $i++ @endphp
							@endforeach 
						</tbody>
						@endif
					</table>
					
					
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="{!!asset('assets/plugins/jquery-datatable/jquery.dataTables.js')!!}"></script>
<script src="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')!!}"></script>
<script src="{!!asset('assets/js/jquery-datatable.js')!!}"></script>
<script>
	$('.js-basic-example').DataTable({
		pageLength: 50,
		responsive: true

	});
</script>
@stop


