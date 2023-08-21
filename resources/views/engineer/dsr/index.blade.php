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
					Daily Service Reports 
				</h2>
				<ul class="header-dropdown m-r--5">

					<li><a href="{{ route('engg-dsr-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

					@if(Auth::user()->can('add all-daily-service-report'))
					<li><a href="{{ route('add-new-daily-service-report') }}" class="btn btn-success">Add new</a></li>
					@endif

				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Date of DSR</th>
								<th>Client name</th>
								<th>Branch name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>#</th>
								<th>Date of DSR</th>
								<th>Client name</th>
								<th>Branch name</th>
								<th>Action</th>
							</tr>
						</tfoot>

						@if(count($dsr_reports)>0)
						<tbody>
							@php $i=1 @endphp
							@foreach($dsr_reports as $dsr)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ date('d M, Y h:i A', strtotime($dsr->entry_datetime)) }}</td>
								<td>{{ ucwords($dsr->client->name) }}</td>
								<td>{{ ucwords($dsr->client->branch_name) }}</td>
								<td>
									<div class="btn-group">
										@if(Auth::user()->can('edit all-daily-service-report'))
										 @if((date('Y-m-d', strtotime($dsr->entry_datetime))) == (date('Y-m-d')))
											<a href="{{ route('edit-dsr-details',Crypt::encrypt($dsr->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
										 @endif 
										 @endif

										<a href="{{ route('get-dsr-details',Crypt::encrypt($dsr->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>

										<a href="{{ route('print-view-dsr', Crypt::encrypt($dsr->id)) }}" target="_blank" class="btn bg-lime waves-effect btn-sm"><i class="fa fa-print"></i></a>

										 @if(Auth::user()->can('delete all-daily-service-report'))
										 @if((date('Y-m-d', strtotime($dsr->entry_datetime))) == (date('Y-m-d')))
										 <a href="{{ route('destroy-dsr',Crypt::encrypt($dsr->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
										 @endif 
										 @endif

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


