@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">
<style type="text/css" media="screen">
   .font_blue {
    color: #00BCD4;
    font-weight: bold;
   } 

   .font_green {
    color: #2B982B;
    font-weight: bold;
   } 

   .font_lime {
    color: #CDDC39;
    font-weight: bold;
   }

   .card .header .header-dropdown .bg_blue {
    color: #00BCD4!important;
   }
   .card .header .header-dropdown .bg_green {
    color: #2B982B!important;
   }
   .card .header .header-dropdown .bg_lime {
    color: #CDDC39!important;
   }
</style>
@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Search results for <span class="label bg-blue-grey">{{ Request::get('from_date') }}</span>  <span class="label bg-blue-grey">{{ Request::get('to_date') }}</span>  <span class="label bg-blue-grey">@if(Request::get('zone_id')){{ ucwords($zone_id) }}@endif</span> <span class="label bg-blue-grey">{{ Request::get('client_id') }}</span>  <span class="label bg-blue-grey">{{ Request::get('branch') }}</span>  <span class="label bg-blue-grey">{{ Request::get('priority') }}</span>  <span class="label bg-blue-grey">@if(Request::get('group_id')){{ ucwords($group_id) }}@endif</span>  <span class="label bg-blue-grey">@if(Request::get('complaint_master_id')){{ ucwords($comp_type) }}@endif</span> <span class="label bg-blue-grey">@if(Request::get('product_id')){{ ucwords($product_id) }}@endif</span>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><a href="" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
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
                            <tr>
                                <td>{{ $i }}</td>
                                @if($complaint->complaint_status == 1)
                                <td>
                                    {{ str_limit(ucwords($complaint->complaint_no), $limit = 10, $end = '...') }}
                                </td>
                                @endif

                                @if($complaint->complaint_status == 2)
                                <td class="">
                                    {{ str_limit(ucwords($complaint->complaint_no), $limit = 10, $end = '...') }}
                                </td>
                                @endif

                                @if($complaint->complaint_status == 3)
                                <td class="">
                                    {{ str_limit(ucwords($complaint->complaint_no), $limit = 10, $end = '...') }}
                                </td>
                                @endif

                                @if($complaint->complaint_status == 4)
                                <td class="">
                                    {{ str_limit(ucwords($complaint->complaint_no), $limit = 10, $end = '...') }}
                                </td>
                                @endif

                                <td>
                                    @if($complaint->complaint_call_date != "0000-00-00")
                                    {{ date('d M, Y', strtotime($complaint->complaint_call_date)) }}
                                    @endif
                                </td>
                                <td>{{ ucwords($complaint->client->name) }}</td>
                                <td>{{ ucwords($complaint->client->branch_name) }}</td>
                                <td>{{ ucwords($complaint->comp_master->complaint_details) }}</td>
                                <td>
                                    <div class="btn-group">

                                        <a href="{{ route('show-complaint-reports.store',Crypt::encrypt($complaint->id)) }}" target="_blank" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                     
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