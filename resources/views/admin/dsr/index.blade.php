@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
<style>
	
.form-group .form-line .form-label{
	top: -10px!important;
}

.Zebra_DatePicker_Icon_Wrapper {
	width: unset !important;
}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>
					Daily Service Reports By Engineer
				</h2>
				<ul class="header-dropdown m-r--5">

					<li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>

					<li><a href="{{ route('service-report-details.excel', request()->all()) }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Date of DSR</th>
								<th>SCR No.</th>
								<th>Type</th>
								<th>Engineer name</th>
								<th>Client name</th>
								<th>Branch name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>#</th>
								<th>Date of DSR</th>
								<th>SCR No.</th>
								<th>Type</th>
								<th>Engineer name</th>
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
								<td>{{ strtoupper($dsr->scr_no ?? "-") }}</td>
								<td>{{ $dsr->maintenance_type == 1 ? "Breakdown" : "Preventive" }}</td>
								<td>{{ ucwords($dsr->engineer->first_name.' '.$dsr->engineer->middle_name.' '.$dsr->engineer->last_name) }}</td>
								<td>{{ ucwords($dsr->client->name) }}</td>
								<td>{{ ucwords($dsr->client->branch_name) }}</td>
								<td>
									<div class="btn-group">

										<a href="{{ route('get-service-report-detail',Crypt::encrypt($dsr->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>

										<a href="{{ route('service-report-print-view-dsr', Crypt::encrypt($dsr->id)) }}" target="_blank" class="btn bg-lime waves-effect btn-sm"><i class="fa fa-print"></i></a>

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


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <form action="" method="get">
            
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter Daily Service Report By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                 <div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="form-control datepicker" id="entry_datetime" name="entry_datetime" data-zdp_readonly_element="false" placeholder="Date of entry eg,(dd-mm-yyyy)" value="{{ old('entry_datetime') }}">
						<label class="form-label">Date of entry</label>
					</div>
				</div>
            </div>

             <div class="col-md-4">
                 <div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="form-control datepicker" id="date_from" name="date_from" data-zdp_readonly_element="false" placeholder="Date from eg,(dd-mm-yyyy)" value="{{ old('date_from') }}">
						<label class="form-label">Date from</label>
					</div>
				</div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="form-control datepicker" id="date_to" name="date_to" data-zdp_readonly_element="false" placeholder="Date to eg,(dd-mm-yyyy)" value="{{ old('date_to') }}">
						<label class="form-label">Date to</label>
					</div>
				</div>
            </div>
     
        </div>

        <div class="row">
        	<div class="col-md-4">
        		<div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="engineer_id" id="engineer_id">
                           <option value="">-- Please select user --</option>
                           <?php foreach ($users as $user): ?>
                            <option value="{{ $user->id }}" data-themeid="{{ $user->id }}" {{ old('engineer_id') == "$user->id" ? 'selected' : '' }}>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name.' '.'('.$user->role.')') }}</option>
                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Select User</label>
                    </div>
                </div>
        	</div>

        	<div class="col-md-4">
        		<div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="client_id" id="client_id">
                           <option value="">-- Please select client --</option>
                           <?php foreach ($clients as $client): ?>
                           <option value="{{ $client->name }}" {{ old('client_id') == $client->name ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>

                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Select Client</label>
                    </div>
                </div>
        	</div>

        	<div class="col-md-4">
        		<div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" placeholder="Branch name" autocomplete="off">
                        <label class="form-label">Branch name</label>
                        @if(count($all_clients) > 0)
                        <datalist id="options_branch">
                          @foreach($all_clients->unique("branch_name") as $c_group)
                          <option value="{{$c_group->branch_name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
        	</div>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary waves-effect"  type="submit">Filter</button>
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
      </form>
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

<script src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
<script>
$(document).ready(function() {
	$('.datepicker').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false,
	      
	 });
});
</script>
@stop


