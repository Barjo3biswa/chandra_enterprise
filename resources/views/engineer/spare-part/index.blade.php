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
					All assigned spare parts
				</h2>
				<ul class="header-dropdown m-r--5">

					<li><a href="{{ route('issued-spare-parts-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Spare part name</th>
								<th>Spare part no</th>
								<th>stock in hand</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>#</th>
								<th>Spare part name</th>
								<th>Spare part no</th>
								<th>stock in hand</th>
							</tr>
						</tfoot>

						@if(isset($all_sp_prts))
						<tbody>
							@php $i=1 @endphp
							@foreach($all_sp_prts as $key => $all_spare_part)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ ucwords($all_spare_part->spare_part->name) }}</td>
								<td>{{ ucwords($all_spare_part->spare_part->part_no) }}</td>
								<td>
									{{ $stock_in_hand[$all_spare_part->spare_part->id] }}
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


