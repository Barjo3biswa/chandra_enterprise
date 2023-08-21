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
                    Clients
                </h2>
                <ul class="header-dropdown m-r--5">

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>
                   
                    <li><a href="{{ route('client-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                    

                    @if(Auth::user()->can('add client'))
                    <li><a href="{{ route('add-new-client') }}" class="btn btn-success">Add new</a></li>
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
                                <th>Branch name</th>
                                <th>Zone name</th>
                                <th>Region</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            	<th>#</th>
                                <th>Name</th>
                                <th>Branch name</th>
                                <th>Zone name</th>
                                <th>Region</th>
                               <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@php $i=1 @endphp
                        	@foreach($clients as $key => $client)
                            <tr>
                            	<td>{{ $i }}</td>
                                <td>{{ ucwords($client->name) }}</td>
                                <td>{{ ucwords($client->branch_name) }}</td>
                                <td>
                                    @if($client->zone_id != null)
                                    {{ ucwords($client->zone->name) }}
                                    @endif
                                </td>
                                <td>
                                    @if($client->region_id != null)
                                    {{ ucwords($client->region->name) }}
                                    @endif
                                </td>
                                <td>
                                	<div class="btn-group">

                                    @if(Auth::user()->can('edit client'))
                                		<a href="{{ route('edit-client',Crypt::encrypt($client->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                    @endif
                                    
                                		
                                    <a href="{{ route('show-client',Crypt::encrypt($client->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                        

                                		@if(Auth::user()->can('delete client'))
                                    <a href="{{ route('destroy-client',Crypt::encrypt($client->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
                                    @endif
                                        

                                    @if(Auth::user()->can('convert duplicate client'))
                                    <button type="button" data-client-info="{{json_encode($client->only(['id','name','branch_name']))}}" data-toggle="tooltip" data-title="Convert Duplicate Client" onclick="convertClient(this)" class="btn btn-primary btn-sm"><i class="fa fa-exchange"></i></button>
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
        <h4 class="modal-title">Filter Clients By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options" class="form-control" name="client_id" id="client_id" value="{{ old('client_id') }}" autocomplete="off">
                        <label class="form-label">Client name</label>
                        @if(count($c_group_by) > 0)
                        <datalist id="options">
                          @foreach($c_group_by->unique("name") as $c_group)
                          <option value="{{$c_group->name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
            </div>

             <div class="col-md-3">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" autocomplete="off">
                        <label class="form-label">Branch name</label>
                        @if(count($all_branches) > 0)
                        <datalist id="options_branch">
                          @foreach($all_branches as $branch_name)
                          <option value="{{$branch_name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="zone_id" id="zone_id">
                           <option value="">-- Please select zone --</option>
                           <?php foreach ($zones as $zone): ?>
                            <option value="{{ $zone->id }}" data-themeid="{{ $zone->id }}" {{ old('zone_id') == "$zone->id" ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="region_id" id="region_id">
                           <option value="">-- Please select region --</option>
                           <?php foreach ($regions as $region): ?>
                            <option value="{{ $region->id }}" data-themeid="{{ $region->id }}" {{ old('region_id') == "$region->id" ? 'selected' : '' }}>{{ ucwords($region->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
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
@if(Auth::user()->can('convert duplicate client'))
    <div class="modal fade" id="convertClient">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Convert Client</h4>
                </div>
                <form action="{{route("convert-client.post")}}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong>Warning!</strong> After converting branch, all previous transation will also converted to new branch.
                                </div>
                            </div>
                        </div>
                            
                        <input type="hidden" id="client_id" name="from_client_id">
                        <div class="row">
                            {{-- form begin --}}
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{-- <input type="text" list="options" class="form-control" name="client_id" id="client_id" value="{{ old('client_id') }}" autocomplete="off"> --}}
                                        <label class="form-label">From Client name</label>
                                        <input type="text" class="form-control" readonly value="NA" placeholder="From Client Name" id="from_client_name">
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <div class="form-line">
                                            <label class="form-label">From Branch name</label>
                                            <input type="text" class="form-control" readonly value="NA" placeholder="From Branch Name" id="from_branch_name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- from end --}}
                            {{-- To begin --}}
                            <div class="col-md-3">
                                    <div class="form-group form-float">
                                    <div class="form-line">
                                        {{-- <input type="text" list="options" class="form-control" name="client_id" id="client_id" value="{{ old('client_id') }}" autocomplete="off"> --}}
                                        <label class="form-label">To Client name</label>
                                        <select name="to_client" id="to_client" class="form-control" required onChange="filterBranch(this)">
                                            <option value="" selected disabled>--SELECT--</option>
                                            @if($all_clients->count() > 0)
                                                @foreach($all_clients->unique("name")->sortBy("name")->values()->all() as $client)
                                                    <option value="{{$client->name}}">{{$client->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @php
                                $vales = $all_clients->sortBy(function($item, $key){
                                    return $item->branch_name;
                                })->values()->all();
                                $branch_group_wise = collect($vales)->groupBy(function($item){
                                    return $item->branch_name;
                                });
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        {{-- <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" autocomplete="off"> --}}
                                        <label class="form-label">To Branch name</label>
                                        <select name="to_branch" class="form-control" required id="to_branch">
                                            <option value="" selected disabled>--SELECT--</option>
                                            @if(sizeof($branch_group_wise) > 0)
                                                @foreach($branch_group_wise as $branch_name =>  $clients_1)
                                                    @php
                                                        $client_names_arr = [];
                                                        foreach ($clients_1 as $key => $client) {
                                                            $client_names_arr[]= $client->name;
                                                        }
                                                        $client_names = str_replace([" ", ".", "/"], "_", implode(",", $client_names_arr))
                                                    @endphp
                                                    <option value="{{$branch_name}}" data-client-name="{{$client_names}}">{{$branch_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- to end --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

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


  //   $("#client_id").change(function(){
  
  // var client_id = $('option:selected', this).attr('data-themeid');

    
  // $.ajax({
  //   type: "GET",
  //   url: "{{ route('getclientbranch.ajax.post') }}",
  //   data: {
  //     'client_id': client_id
  //   },

  //   success: function(response) {
  //     if(response) {

        

  //       var toAppend = '';

  //       toAppend +='<option value="">All Branches</option>';
  //       $.each(response, function(i,o){

  //           console.log(o);
  //         toAppend += '<option  value="'+o.id+'" {{ old('branch_name') == "'+o.id+'" ? 'selected' : '' }} data-themeid="'+o.id+'">'+o.branch_name+'</option>';
  //       });

  //       $('#branch_name').html(toAppend);

  //     }else{
  //       alert("No sub group found");
  //     }
  //   }
  // });
  // });
  
    @if(Auth::user()->can('convert duplicate client'))
        $(document).ready(function(){
            $("#convertClient").on('hidden.bs.modal', function(){
                $("#convertClient").find("input").val("");
                $("#convertClient").find("#to_client option:first").prop('selected', true);
                $("#convertClient").find("#to_branch option:first").prop('selected', true);
                $("#convertClient").find("#to_branch").find("option").show();
            });
        });
        convertClient  = function(Obj){
            var $this = $(Obj);
            var client_info = $this.data('client-info');
            $("#convertClient").find("#from_client_name").val(client_info.name);
            $("#convertClient").find("#from_branch_name").val(client_info.branch_name);
            $("#convertClient").find("#client_id").val(client_info.id);
            $("#convertClient").modal( {
                backdrop: 'static',
                keyboard: false
            });
        }
        filterBranch = function (Obj){
            var $this = $(Obj);
            var client_name = replaceString($this.val());
            if($("#convertClient").find("#to_branch").data('select2')){
                $("#convertClient").find("#to_branch").select2("destroy");
            }
            var branch_with_selected_client = $("#convertClient").find("#to_branch").find('[data-client-name*='+client_name+']');
            branch_with_selected_client.show();
            $("#convertClient").find("#to_branch").find("option").not(branch_with_selected_client).hide();
            $("#convertClient").find("#to_branch").find("option:first").prop("selected", true).show();
            $("#convertClient").find("#to_branch").prop('selectedIndex',0);
            // $("#convertClient").find("#to_branch").select2();
        }
        replaceString = function(string){
            var local_string = string;
            local_string = local_string.split(' ').join('_');
            local_string = local_string.split('.').join('_');
            local_string = local_string.split('/').join('_');
            return local_string;
        }
    @endif
</script>
@stop
