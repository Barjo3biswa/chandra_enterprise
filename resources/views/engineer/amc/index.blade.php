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
					Assigned clients amc details

				</h2>
				<ul class="header-dropdown m-r--5">

					<li><a href="{{ route('client-amc-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th>#</th>
								<th>AMC start date</th>
								<th>AMC end date</th>
								<th>Plan</th>
								<th>Duration</th>
								<th>Client name</th>
								<th>Branch name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>#</th>
								<th>AMC start date</th>
								<th>AMC end date</th>
								<th>Plan</th>
								<th>Duration</th>
								<th>Client name</th>
								<th>Branch name</th>
								<th>Action</th>
							</tr>
						</tfoot>
						@if(isset($client_amc))
						<tbody>
							@php $i=1 @endphp
								@foreach($client_amc as $amc)
								{{-- @if((date('Y-m-d', strtotime($amc->amc_end_date))) >= (date('Y-m-d'))) --}}
									<tr>
										<td>{{ $i }}</td>
										<td>{{ date('d M, Y', strtotime($amc->amc_start_date)) }}</td>
										<td>{{ date('d M, Y', strtotime($amc->amc_end_date)) }}</td>
										<td>{{ $amc->roster->roster_name }}</td>
										<td>{{ getDuration($amc->amc_duration) }}</td>
										<td>{{ ucwords($amc->client->name) }}</td>
										<td>{{ ucwords($amc->client->branch_name) }}</td>
										<td>
											<div class="btn-group">

												<a href="{{ route('view-clients-amc-details',Crypt::encrypt($amc->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>

											</div>
										</td>
									</tr>
								@php $i++ @endphp
								{{-- @endif --}}
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


