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
                    District
                </h2>
                <ul class="header-dropdown m-r--5">
                   
                    @if(Auth::user()->can('add district'))
                    <a href="{{ route('add-new-district') }}" class="btn btn-success">Add new</a></li>
                    @endif

                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>                              
                                <th>State</th>
                                <th>District</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>                              
                                <th>State</th>
                                <th>District</th>                             
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                          @php $i=1 @endphp 
                            @foreach($districts as $district) 
                            <tr>
                                <td> {{ $i }} </td>
                               
                                <td>{{ $district->state->name ?? "" }}</td>
                                <td> {{ $district->name }} </td>
                               
                                <td>
                                    <div class="btn-group">
                                            @if(Auth::user()->can('edit district'))
                                            <a href="{{ route('edit-district',Crypt::encrypt($district->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                            @endif
                                        <a href="" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
                                       
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
