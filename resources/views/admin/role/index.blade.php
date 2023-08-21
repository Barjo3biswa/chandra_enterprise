@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">
<style>
    .font_success {
        color: #2b982b !important;
    }

    .font_danger {
        color: #fb483a !important;
    }
</style>
@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Roles
                </h2>
                <ul class="header-dropdown m-r--5">
                    
                    <li><a href="{{ route('role-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                  
                    @if(Auth::user()->can('add role'))
                        <li><a href="{{ route('add-new-role') }}" class="btn btn-success">Add new</a></li>
                    @endif
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                               <th>#</th>
                               <th>User</th>
                               <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ ucwords($role->first_name.' '.$role->middle_name.' '.$role->last_name) }}</td>

                                <td>
                                    <div class="btn-group">
                                        @if(Auth::user()->can('edit role'))
                                        <a href="{{ route('edit-role', ['user_id' => Crypt::encrypt($role->model_id)] ) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit permission"><i class="fa fa-edit"></i></a>
                                        @endif

                                        
                                        <a href="{{ route('show-role', ['user_id' => Crypt::encrypt($role->model_id)] ) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details view of permission"><i class="fa fa-eye"></i></a>
                                       

                                         @if(Auth::user()->can('delete role'))
                                        <a href="{{ route('delete-role', ['user_id' => Crypt::encrypt($role->model_id)] ) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Remove permission" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
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