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
                    Outstanding Bills
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><a href="{{ route('all-client-outstanding-bill.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                 
                    @if(Auth::user()->can('add client outstanding bill'))
                    <li><a href="{{ route('add-new-client-outstanding-bill') }}" class="btn btn-success">Add new</a></li>
                    @endif

                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Branch</th>
                                <th>Bill No</th>
                                <th>Bill Amount</th>
                                <th>Pay By Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Branch</th>
                                <th>Bill No</th>
                                <th>Bill Amount</th>
                                <th>Pay By Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($client_bill as $c_bill)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ ucwords($c_bill->client->name ?? "NA") }}</td>
                                <td>{{ ucwords($c_bill->client->branch_name ?? "NA") }}</td>
                                <td>{{ $c_bill->bill_no }}</td>
                                <td>{{ $c_bill->bill_amount }}</td>
                                <td>
                                    @if($c_bill->pay_by_date != "0000-00-00")
                                    {{ date('d M, Y', strtotime($c_bill->pay_by_date)) }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(Auth::user()->can('edit client outstanding bill'))
                                        <a href="{{ route('edit-client-outstanding-bill', Crypt::encrypt($c_bill->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        @endif

                                        <a href="{{ route('details-client-outstanding-bill', Crypt::encrypt($c_bill->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>

                                        @if(Auth::user()->can('delete client outstanding bill'))
                                        <a href="{{ route('delete-client-outstanding-bill', Crypt::encrypt($c_bill->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
                                        @endif

                                        @if(Auth::user()->can('update bill followup payment status'))
                                        <a href="{{ route('followup-client-outstanding-bill-edit', Crypt::encrypt($c_bill->id)) }}" class="btn btn-sm bg-indigo" data-toggle="tooltip" title="Update followup bill detail"><i class="fa fa-cog"></i></a>
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