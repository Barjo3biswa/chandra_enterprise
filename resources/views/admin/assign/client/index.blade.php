@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">
<style>
    .form-group .form-line .form-label{
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
                    Assign Product To Client <small>({{$assign_clients->count()}} Records found)</small>
                </h2>
                <ul class="header-dropdown m-r--5">

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>

                    <li><a href="{{ route('assign-new-product-to-client-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                 
                    
                    @if(Auth::user()->can('add assign-product-to-client'))
                    <li><a href="{{ route('assign-new-product-to-client') }}" class="btn btn-success">Add new</a></li>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Branch name</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($assign_clients as $ac)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>@if($ac->client){{ ucwords($ac->client->name) }}@endif</td>
                                <td>@if($ac->client){{ ucwords($ac->client->branch_name) }}@endif</td>
                                <td>
                                    <div class="btn-group">
                                        
                                        @if(Auth::user()->can('edit assign-product-to-client'))
                                        <a href="{{ route('edit-assign-new-product-to-client', Crypt::encrypt($ac->client_id)) }}" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                       

                                        <a href="{{ route('show-assign-new-product-to-client', Crypt::encrypt($ac->client_id)) }}" class="btn btn-xs btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                     
                                        
                                        @if(Auth::user()->can('delete assign-product-to-client'))
                                        <a href="{{ route('destroy-assign-new-product-to-client', Crypt::encrypt($ac->client_id)) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')" ><i class="fa fa-trash"></i></a>
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
        <h4 class="modal-title">Filter Assigned Products By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
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

             <div class="col-md-3">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="branch" id="branch">
                            <option value=""> Please select a branch </option>
                            <?php foreach ($client_branches as $branch_name): ?>
                            <option value="{{ $branch_name }}" {{ old('branch') == "$branch_name" ? 'selected' : '' }}>{{ ucwords($branch_name) }}</option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label">Select Branch</label>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
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

            <div class="col-md-3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="group_id" id="group_id">
                           <option value="">-- Please select group --</option>
                           <?php foreach ($groups as $group): ?>
                            <option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                        <label class="form-label">Select Group</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick select2ajax" name="product_id" id="product_id">
                        </select>
                        <label class="form-label">Select Product <small class="text-danger">(Product name or Serial No)</small></label>
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
        pageLength: 100,
        responsive: true
        
    });
    $(document).ready(function(){
        var URL = '{{route("ajax.products")}}';
        // $(".select2ajax").select2({});
        $(".select2ajax").select2({
            minimumInputLength: 3,
            ajax: {
                url: URL,
                dataType: 'json',
                type: "GET",
                quietMillis: 100,
                data: function (term) {
                    return {
                        "product_name": term.term
                    };
                },
                /* results: function (data) {
                    return {
                        results: $.each(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                } */
                processResults: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.name+" - "+ item.serial_no
                        });
                    });
                    return {
                        results: myResults
                    };
                }
            }
        });
    });
</script>
@stop