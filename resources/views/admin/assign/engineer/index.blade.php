@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">

<style>
    .form-group .form-line .form-label {
        top: -10px!important;
    }
</style>
@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Assign Client To Engineer
                </h2>
                <ul class="header-dropdown m-r--5">

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>

                    <li><a href="{{ route('assign-new-client-to-engineer-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                 
                    
                    @if(Auth::user()->can('add assign-engineer'))
                    <li><a href="{{ route('assign-new-client-to-engineer') }}" class="btn btn-success">Add new</a></li>
                    @endif
                  

                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($assign_engineers as $aeng)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ ucwords($aeng->user->first_name.' '.$aeng->user->middle_name.' '.$aeng->user->last_name) }}</td>
                                <td>{{ ucwords($aeng->user->role) }}</td>
                                <td>
                                    <div class="btn-group">
                                        
                                        @if(Auth::user()->can('edit assign-engineer'))
                                        <a href="{{ route('edit-assign-new-client-to-engineer', Crypt::encrypt($aeng->engineer_id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                       

                                        <a href="{{ route('show-assign-new-client-to-engineer', Crypt::encrypt($aeng->engineer_id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                     
                                        
                                        @if(Auth::user()->can('delete assign-engineer'))
                                        <a href="{{ route('destroy-assign-new-client-to-engineer', Crypt::encrypt($aeng->engineer_id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                            @php $i++ @endphp
                            @endforeach 
                        </tbody>
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
        <h4 class="modal-title">Filter Assigned Zones By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                         <select class="form-control show-tick" name="client_id" id="client_id">
                            <option value=""> Please select a client </option>
                            <?php foreach ($clients as $client): ?>
                            <option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id') == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label">Select Client</label>
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="branch" id="branch">
                            <option value=""> Please select a branch </option>
                            <?php foreach ($branches as $client): ?>
                            <option value="{{ $client->branch_name }}" {{ old('branch') == "$client->branch_name" ? 'selected' : '' }}>{{ ucwords($client->branch_name) }}</option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label">Select Branch</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="company_id" id="company_id">
                           <option value="">-- Please select company --</option>
                           <?php foreach ($companies as $company): ?>
                            <option value="{{ $company->id }}" data-themeid="{{ $company->id }}" {{ old('company_id') == "$company->id" ? 'selected' : '' }}>{{ ucwords($company->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Select Company</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="engineer_id" id="engineer_id">
                           <option value="">-- Please select engineer --</option>
                           <?php foreach ($engineers as $engineer): ?>
                            <option value="{{ $engineer->id }}" data-themeid="{{ $engineer->id }}" {{ old('engineer_id') == "$engineer->id" ? 'selected' : '' }}>{{ ucwords($engineer->first_name.' '.$engineer->middle_name.' '.$engineer->last_name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Select Engineer</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="zone_id" id="zone_id">
                           <option value="">-- Please select zone --</option>
                           <?php foreach ($zones as $zone): ?>
                            <option value="{{ $zone->id }}" data-themeid="{{ $zone->id }}" {{ old('zone_id') == "$zone->id" ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Select Zone</label>
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
@stop