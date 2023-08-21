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
                                Stock in
                            </h2>
                            <ul class="header-dropdown m-r--5">

                                <li><a href="{{ route('stockin-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

                                @if(Auth::user()->can('add spare-part-stock-in'))
                                <li><a href="{{ route('add-new-stock-in') }}" class="btn btn-success">Add new</a></li>
                                @endif
                                
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                        	<th>#</th>
                                            <th>Date of transaction</th>
                                            <th>Purchased from</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        	<th>#</th>
                                            <th>Date of transaction</th>
                                            <th>Purchased from</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    	@php $i=1 @endphp
                                    	@foreach($stock_ins as $stock_in)
                                        <tr>
                                        	<td>{{ $i }}</td>
                                            <td>{{ date('d M, Y', strtotime($stock_in->date_of_transaction)) }}</td>
                                            <td>{{ ucwords($stock_in->purchased_from) }}</td>
                                            <td>{{ $stock_in->remarks }}</td>
                                            <td>
                                            	<div class="btn-group">

                                                    @if(Auth::user()->can('edit spare-part-stock-in'))
                                                        <a href="{{ route('edit-stock-in',Crypt::encrypt($stock_in->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                    @endif

                                                    <a href="{{ route('show-stock-in',Crypt::encrypt($stock_in->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" target="_blank" title="Details"><i class="fa fa-eye"></i></a>

                                                    @if(Auth::user()->can('delete spare-part-stock-in'))
                                                        <a href="{{ route('deactivate-stock-in',Crypt::encrypt($stock_in->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
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
