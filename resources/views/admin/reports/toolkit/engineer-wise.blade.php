@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i>
            Back</a>

        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    Engineer Wise Assigned Toolkits</small>
                </h2>

                <ul class="header-dropdown m-r--5">

                    <li>
                        <a href="{{ route('view-user-assigned-toolkit-reports.engineer-wise', ["export" => true]) }}" class="btn bg-info waves-effect"><i
                                class="fa fa-download" aria-hidden="true"></i> Export to Excel</a>
                    </li>

                </ul>

            </div>
            <div class="body">
                <div class="table-responsive">
                    @if($user_wise_toolkits->count())
                    <h4>Total Assigned Toolkit Details</h4>
                    @include('admin.reports.toolkit.engineer-wise-table')
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