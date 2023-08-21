@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }} <small>Assigned Toolkit Details</small>
                </h2>

                <ul class="header-dropdown m-r--5">

					<li><a href="{{ route('assigned-toolkits-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

				</ul>

            </div>
            <div class="body">
                <div class="table-responsive">
					@if(count($assignedtoolkits)>0)
		            <h4>Assigned Toolkit Details</h4>
		            <table class="table table-condensed">

		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th> Toolkit Name</th>
		                        <th> Toolkit Code </th>
		                        <th> Quantity </th>
		                        <th> Remarks </th>
		                    </tr>
		                </thead>
		                <tbody>
		                    @php $i=1 @endphp
		                    @foreach($assignedtoolkits as $key => $assignedtoolkit)
		                    <tr>
		                        <td>{{ $i }}</td>
		                        <td>{{ $assignedtoolkit->toolkit->name }}</td>
		                        <td>{{ $assignedtoolkit->toolkit->tool_kit_code }}</td>
		                        <td>{{ $assignedtoolkit->quantity_to_be_issued }}</td>
		                        <td>{{ $assignedtoolkit->toolkit->remarks }}</td>
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

@stop