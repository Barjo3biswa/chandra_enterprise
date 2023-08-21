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

   .font_lime {
    color: #CDDC39;
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
                    Closed Complaints
                </h2>
                <ul class="header-dropdown m-r--5">

                    @if(count($closed_comp) > 0)
                        <li><i class="fa fa-square bg_green"></i> Closed Complaint</li>
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
                                {{-- <th>Details</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Complaint no</th>
                                <th>Date of complaint</th>
                                <th>Client</th>
                                <th>Branch</th>
                                <th>Complaint for</th>
                                <th>Action</th>
                            </tr>
                        </tfoot> --}}
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($closed_comp as $complaint)

                            @if($complaint->complaint_status == 1)
                            <tr>
                            @endif
                         
                            @if($complaint->complaint_status == 2)
                            <tr class="font_blue">
                            @endif

                            @if($complaint->complaint_status == 3)
                            <tr class="font_green">
                            @endif

                            @if($complaint->complaint_status == 4)
                            <tr  class="font_lime">
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
  
                                        <a href="{{ route('details-complaint-register',Crypt::encrypt($complaint->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                 
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