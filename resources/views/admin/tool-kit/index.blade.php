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
                                Tool kit
                            </h2>
                            <ul class="header-dropdown m-r--5">

                                <li><a href="{{ route('tool-kit-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

                                @if(Auth::user()->can('add tool-kit'))
                                <li><a href="{{ route('add-new-tool-kit') }}" class="btn btn-success">Add new</a></li>
                                @endif
                                
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                        	<th>#</th>
                                            <th>Tool kit name</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        	<th>#</th>
                                            <th>Tool kit name</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    	@php $i=1 @endphp
                                    	@foreach($tool_kits as $tool_kit)
                                        <tr>
                                        	<td>{{ $i }}</td>
                                            <td>{{ ucwords($tool_kit->name) }}</td>
                                            <td>{{ $tool_kit->quantity_to_be_issued }}</td>
                                            <td>
                                            	<div class="btn-group">

                                                    @if(Auth::user()->can('edit tool-kit'))
                                            		<a href="{{ route('edit-tool-kit',Crypt::encrypt($tool_kit->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                    @endif

                                            	    
                                                    <a href="{{ route('show-tool-kit',Crypt::encrypt($tool_kit->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>
                                                    

                                            		@if(Auth::user()->can('delete tool-kit'))
                                                    <a href="{{ route('destroy-tool-kit',Crypt::encrypt($tool_kit->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
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
