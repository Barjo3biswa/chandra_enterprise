@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
<style>

<style>
.form-group .form-line .form-label{
    top: -10px !important;
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
                    Bill Followups By Engineer
                </h2>
                <ul class="header-dropdown m-r--5">

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>

                    <li><a href="" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                 
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Engineer Name</th>
                                <th>Client Name</th>
                                <th>Branch</th>
                                <th>Bill No</th>
                                <th>Pay By Date</th>
                                <th>Next Pay By Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Engineer Name</th>
                                <th>Client Name</th>
                                <th>Branch</th>
                                <th>Bill No</th>
                                <th>Pay By Date</th>
                                <th>Next Pay By Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($engg_bill_follow as $c_bill_follow)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ ucwords($c_bill_follow->engineer->first_name.' '.$c_bill_follow->engineer->middle_name.' '.$c_bill_follow->engineer->last_name) }}</td>
                                <td>{{ ucwords($c_bill_follow->client_bill->client->name ?? 'N/A') }}</td>
                                <td>{{ ucwords($c_bill_follow->client_bill->client->branch_name ?? 'N/A') }}</td>
                                <td>{{ $c_bill_follow->client_bill->bill_no ?? 'N/A' }}</td>
                                <td>
                                    @if($c_bill_follow->client_bill && $c_bill_follow->client_bill->pay_by_date != "0000-00-00")
                                    {{ date('d M, Y', strtotime($c_bill_follow->client_bill->pay_by_date)) ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    @if($c_bill_follow->next_pay_by_date != "0000-00-00")
                                    {{ date('d M, Y', strtotime($c_bill_follow->next_pay_by_date)) }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                       
                                        <a href="{{ route('show-engineer-view-outstanding-bill', Crypt::encrypt($c_bill_follow->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                  
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
        <h4 class="modal-title">Filter Bill Follow-up By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control datepicker" id="next_pay_by_date" name="next_pay_by_date" data-zdp_readonly_element="false" placeholder="Bill pay by date eg,(dd-mm-yyyy)" value="{{ old('next_pay_by_date') }}">
                        <label class="form-label" style="top: -10px;">Next bill pay by date</label>
                    </div>
                </div>
            </div>

             <div class="col-md-6">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="bill_no" id="bill_no">
                           <option value="">-- Please select bill no --</option>
                           @if(isset($all_client_bills))
                           <?php foreach ($all_client_bills as $all_client_bill): ?>
                            <option value="{{ $all_client_bill->id }}" {{ old('bill_no') == "$all_client_bill->id" ? 'selected' : '' }}>{{ ucwords($all_client_bill->bill_no) }}</option>
                           <?php endforeach; ?> 
                           @endif
                        </select>
                        <label class="form-label" style="top: -10px;">Select Bill No</label>
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
                        <label class="form-label" style="top: -10px;">Select User</label>
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
                        <label class="form-label" style="top: -10px;">Select Client</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" placeholder="Branch name" autocomplete="off">
                        <label class="form-label" style="top: -10px;">Branch name</label>
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