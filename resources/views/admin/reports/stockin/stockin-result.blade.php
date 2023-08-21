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
                    {{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }} <small>Spare Part Stockin Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
					@if(count($all_sp_prts)>0)
		            <h4>Assigned Spare Part Details</h4>
		            <table class="table table-condensed">

		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>Spare Part Name</th>
		                        <th>Spare Part No</th>
		                        <th>Stock In Hand</th>
		                    </tr>
		                </thead>
		                <tbody>
		                    @php $i=1 @endphp
		                    @foreach($all_sp_prts as $key => $sp)
		                    <tr>
		                        <td>{{ $i }}</td>
		                        <td>{{ $sp->spare_part->name }}</td>
		                        <td>{{ $sp->spare_part->part_no }}</td>
		                        <td>{{ $stock_in_hand[$sp->spare_part->id] }}</td>
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