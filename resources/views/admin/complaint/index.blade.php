@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">
<style type="text/css" media="screen">
   .font_blue {
    color: #00BCD4;
    } 

   .font_green {
    color: #2B982B;
   } 

   /*.font_lime {
    color: #CDDC39;
   }*/

   .card .header .header-dropdown .bg_blue {
    color: #00BCD4!important;
   }
   .card .header .header-dropdown .bg_green {
    color: #2B982B!important;
   }
   .card .header .header-dropdown .bg_lime {
    color: #CDDC39!important;
   }
   .form-group .form-line .form-label {
        top: -10px!important;
        font-size: 12px !important;
    }
</style>
@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Complaints
                </h2>
                <ul class="header-dropdown m-r--5">

                    @if(count($results) > 0)
                        <li>
                            {{-- <i class="fa fa-square bg_lime"></i> Updated Complaint &nbsp;  --}}
                            <i class="fa fa-square bg_blue"></i> Assigned to engineer &nbsp;
                            @if(Auth::user()->user_type == 3) 
                            <a href="{{ route('view-closed-complaints') }}" style="text-decoration: none; color: #58617A;"><i class="fa fa-square bg_green"></i> Closed Complaint</li></a> &nbsp;
                            @else
                            <i class="fa fa-square bg_green"></i> Closed Complaint</li>&nbsp;
                            @endif
                    @endif

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>

                    <li><a href="{{ route('complaint-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                 
                   @if(Auth::user()->can('add complaint'))
                    <li><a href="{{ route('add-new-complaint') }}" class="btn btn-success">Add new</a></li>
                   @endif

                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Complaint no</th>
                                <th>Date of complaint</th>
                                <th>Client</th>
                                <th>Branch</th>
                                <th>Complaint for</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Complaint no</th>
                                <th>Date of complaint</th>
                                <th>Client</th>
                                <th>Branch</th>
                                <th>Complaint for</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($results as $complaint)

                            @if($complaint->complaint_status == 1 || $complaint->complaint_status == 4)
                            <tr>
                            @endif
                         
                            @if($complaint->assigned_to != null || $complaint->assigned_engineers->count())
                            <tr class="font_blue">
                            @endif

                            @if($complaint->complaint_status == 3)
                            <tr class="font_green">
                            @endif

                           
                                <td>{{ $i }}</td>

                                <td>
                                    {{ str_limit(ucwords($complaint->complaint_no), $limit = 10, $end = '...') }}
                                </td>
                             
                                <td>
                                    @if($complaint->complaint_call_date != "0000-00-00")
                                    {{ date('d M, Y', strtotime($complaint->complaint_call_date)) }}
                                    @endif
                                </td>
                                <td>{{ ucwords($complaint->client->name) }}</td>
                                <td>{{ ucwords($complaint->client->branch_name) }}</td>
                                <td>{{ ucwords($complaint->comp_master->complaint_details) }}</td>
                                {{-- <td>{{ str_limit($complaint->complaint_details, $limit = 10, $end = '...')  }}</td> --}}
                                <td>
                                    <div class="btn-group">

                                        @if($complaint->complaint_status != 3)
                                        @if(Auth::user()->can('edit complaint'))
                                        <a href="{{ route('edit-complaint-register-details',Crypt::encrypt($complaint->id)) }}" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        
                                        {{-- @if($complaint->assigned_to == null) --}}
                                        @if(Auth::user()->can('assign complaint to engineer'))
                                        <a href="{{ route('assigned-complaint-to-engineer',Crypt::encrypt($complaint->id)) }}" class="btn btn-xs btn-success" data-toggle="tooltip" title="Assign to engineer"><i class="fa fa-user"></i></a>
                                        @endif
                                        {{-- @endif --}}
                                        @endif

                                       

                                        <a href="{{ route('show-complaint-register-details',Crypt::encrypt($complaint->id)) }}" class="btn btn-xs btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                     
                                       @if(Auth::user()->can('delete complaint'))
                                       <a href="{{ route('delete-complaint-register-details',Crypt::encrypt($complaint->id)) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
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
        <h4 class="modal-title">Filter Complaints By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="client_id" id="client_id">
                           <option value="">-- Please select client --</option>
                           <?php foreach ($clients as $client): ?>
                            <option value="{{ $client->name }}" {{ old('client_id') == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Complaint client</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" autocomplete="off">
                        <label class="form-label">Branch name</label>
                        @if(count($clients) > 0)
                        <datalist id="options_branch">
                          @foreach($clients->unique("branch_name") as $c_group)
                          <option value="{{$c_group->branch_name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" id="complaint_no" name="complaint_no" value="{{ old('complaint_no') }}">
                        <label class="form-label">Complaint no</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="zone_id" id="zone_id" >
                            <option value=""> Zone </option>

                            @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ (old('zone_id') == $zone->id) ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
                           @endforeach
                            
                        </select>
                        <label class="form-label">Select zone</label>
                    </div>
                </div>
            </div>
       </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" id="from_date" name="from_date" data-zdp_readonly_element="false" value="{{ old('from_date') }}">
                        <label class="form-label">Date from</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" id="to_date" name="to_date" data-zdp_readonly_element="false" value="{{ old('to_date') }}">
                        <label class="form-label">Date to</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="priority" id="priority" >
                            <option value="">Select priority</option>
                            <option value="0"> No priority </option>
                            <option value="1"> Low priority </option>
                            <option value="2"> High priority </option>
                        </select>
                        <label class="form-label">Complaint priority</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="complaint_status" id="complaint_status" >
                            <option value="">Select status</option>
                            <option value="1"> Logged </option>
                            <option value="2"> Under process </option>
                            @if(Auth::user()->user_type != 3)
                            <option value="3"> Closed </option>
                            @endif
                            <option value="4"> Updated</option>
                            
                        </select>
                        <label class="form-label">Complaint status</label>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="group_id" id="group_id" >
                            <option value=""> Please select a group </option>
                            <?php foreach ($groups as $group): ?>
                            <option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label">Select Group</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="complaint_master_id" id="complaint_master_id" >
                            <option value=""> Please select a complaint </option>

                            @foreach ($c_masters as $c_master)
                                    <option value="{{ $c_master->id }}" {{ (old('complaint_master_id') == $c_master->id) ? 'selected' : '' }}>{{ ucwords($c_master->complaint_details) }}</option>
                           @endforeach
                            
                        </select>
                        <label class="form-label">Complaint master</label>
                    </div>
                </div>
            
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="product_id" id="product_id" >
                            <option value=""> Please select a product </option>

                            @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ (old('product_id') == $product->id) ? 'selected' : '' }}>{{ ucwords($product->name) }}</option>
                           @endforeach
                            
                        </select>
                        <label class="form-label">Complaint product</label>
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