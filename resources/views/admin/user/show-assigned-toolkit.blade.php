@extends('layouts.front')


@section('styles')
<style>
  .form_inline_custom {
    display: inline-block !important;
}  
</style>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a> 

        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }} <small>Assigned Toolkit Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                 
                 <div class="btn-group">
                     
                    
                    <form class="form_inline_custom" action="{{ route('delete-user-assign-tools',Crypt::encrypt($user->id)) }}" method="get">
                        {{ csrf_field() }}

                        <input type="hidden" name="assign_date_delete" value="{{ $assign_date }}">

                        <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete assign tool kit" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i> &nbsp; Delete</button>
                    </form>
                    
                    <form class="form_inline_custom" action="{{ route('edit-user-assign-tools',Crypt::encrypt($user->id)) }}" method="get">
                        {{ csrf_field() }}

                        <input type="hidden" name="assign_date_edit" value="{{ $assign_date }}">

                       <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit assign tool kit"><i class="fa fa-edit"></i> &nbsp; Edit</button> 
                    </form>
                

                </div>

            @if($assign_user_tools->count())
            <h4>Assigned tool kits</h4>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">

                        <table class="table table-condensed">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tool kit name</th>
                                    <th>Issued quantity</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php $i=1 @endphp --}}
                                @foreach($assign_user_tools as $key => $assign_user_tool)
                                <tr>
                                    <td>{{ $key+ 1 + ($assign_user_tools->perPage() * ($assign_user_tools->currentPage() - 1)) }}</td>
                                    <td>{{ $assign_user_tool->toolkit->name }}</td>
                                    <td>{{ $assign_user_tool->quantity_to_be_issued }}</td>
                                    <td>{{ $assign_user_tool->remarks }}</td>
                                </tr>
                                {{-- @php $i++ @endphp --}}
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                           {{-- {{ $assign_user_tools->render() }}  --}}
                           {{$assign_user_tools->appends(request()->all())->links()}}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
</div>

@endsection


@section('scripts')

@stop