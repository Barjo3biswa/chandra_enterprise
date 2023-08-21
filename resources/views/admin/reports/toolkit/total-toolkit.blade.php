@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a> 

		<div class="card">
			<div class="header bg-cyan">
				<h2>
				Total Assigned Toolkit Details</small>
			</h2>

			<ul class="header-dropdown m-r--5">

				<li>
					<a href="{{ route('total-assigned-toolkit.excel') }}" class="btn bg-brown waves-effect"><i class="fa fa-download" aria-hidden="true"></i> Export to Excel</a>
				</li>

			</ul>

		</div>
		<div class="body">
			<div class="table-responsive">
				@if(count((array)$toolkits)>0)
				<h4>Total Assigned Toolkit Details</h4>
				<table class="table table-condensed">

					<thead>
						<tr>
							<th>#</th>
							<th> Toolkit Name</th>
							<th> Toolkit Code</th>
							<th> Total Assigned </th>
							<th> Remarks </th>
						</tr>
					</thead>
					<tbody>
						@php $i=1 @endphp
						@foreach($toolkits as $key => $assignedtoolkit)
						<tr>
							@php
							$j=1;
							$engineers_names ="";

							$engineers_names.= "<table class='table table-condensed'>"."<tbody>";

							$engineers_names.= "<thead>";
							$engineers_names.= "<tr>";
							$engineers_names.= "<th>#</th>";
							$engineers_names.= "<th> Engineer</th>";
							$engineers_names.= "<th> Quantity </th>";
							$engineers_names.= "</tr>";
							$engineers_names.= "</thead>";
							$engineers_names.= "<tbody>";

							if($all_asgn_tlkts[$key]){
								foreach ($all_asgn_tlkts[$key] as $key1 => $all_asgn_tlkt) {

									$engineers_names.= "<tr>";
									$engineers_names.= "<td>".$j."</td>";
									$engineers_names.= "<td>".ucwords($all_asgn_tlkt->user->first_name." ".$all_asgn_tlkt->user->middle_name." ".$all_asgn_tlkt->user->last_name)."</td>";
									$engineers_names.= "<td>".$all_asgn_tlkt->quantity_to_be_issued."</td>";
									$engineers_names.= "</tr>";

									$j++;
								}
							}
							// dump(count($all_asgn_tlkts[$key]));
							$engineers_names.= "</tbody>";
							$engineers_names.= "</table>";

							@endphp

							<td>{{ $i }}</td>
							<td><span data-toggle="popover" title="{{ ucwords($assignedtoolkit->name) }}" data-content="{{$engineers_names}}"> {{ ucwords($assignedtoolkit->name) }}</span></td>
							<td>{{ $assignedtoolkit->tool_kit_code }}</td>
							<td>{{ $all_assigned_toolkits[$key] }}</td>
							<td>{{ $assignedtoolkit->remarks }}</td>
						</tr>
						@php $i++ @endphp
						@endforeach
					</tbody>
				</table>
				@endif
			</div>
		</div>
	</div>
</div>
</div>

@endsection


@section('scripts')
<script>
	$(document).ready(function() {
		$('[data-toggle="popover"]').popover({
			placement: 'right',
			trigger: 'hover',
			html:true
		});
	});
</script>
@stop