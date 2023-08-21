@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">

<style>
    .form-group .form-line .form-label {
        top: -10px!important;
    }
    .Zebra_DatePicker_Icon_Wrapper {
        width: 100%!important;
    }
</style>
@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    User <small>{!!request()->get('deactivated_user') == 1 ? "Deactivated" : ""!!}</small>
                </h2>
                <ul class="header-dropdown m-r--5">

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>
                    @if(!request()->get('deactivated_user'))
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(["deactivated_user" => 1]) }}" class="btn btn-danger waves-effect"> <i class="fa fa-users" aria-hidden="true"></i> Deactivated User's </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ request()->url() }}" class="btn btn-success waves-effect"> <i class="fa fa-users" aria-hidden="true"></i> All User's </a>
                        </li>

                    @endif

                    <li><a href="{{ route('user-details.excel', request()->all()) }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>
                    

                    @if(Auth::user()->can('add user'))
                    <li><a href="{{ route('add-new-users') }}" class="btn btn-success">Add new</a></li>
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
                                <th>Emp code</th>
                                <th>Role</th>
                                <th>Phone no</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            	<th>#</th>
                                <th>Name</th>
                                <th>Emp code</th>
                                <th>Role</th>
                                <th>Phone no</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@php $i=1 @endphp
                        	@foreach($users as $user)
                            <tr>
                            	<td>{{ $i }}</td>
                                <td>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }}</td>
                                <td>{{ $user->emp_code }}</td>
                                <td>{{ ucwords($user->role) }}</td>
                                <td>{{ $user->ph_no }}</td>
                                <td>
                                	<div class="btn-group">
                                        @if(Auth::user()->can('edit user'))
                                		<a href="{{ route('edit-users',Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        
                                        <a href="{{ route('details-users',Crypt::encrypt($user->id)) }}" target="_blank" class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i class="fa fa-eye"></i></a>

                                        @if(request()->get("deactivated_user") == 1)
                                            @if(Auth::user()->can('delete user'))
                                            <a href="{{ route('activate-user', Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-success" data-toggle="tooltip" title="Activate User" onclick="return confirm('Are you sure')"><i class="fa fa-check"></i></a>
                                            @endif
                                            @if(Auth::user()->can('delete user'))
                                            <a href="{{ route('destroy-user',Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete User" onclick="return confirm('Are you sure')"><i class="fa fa-trash-o"></i></a>
                                            @endif
                                        @else
                                        
                                            @if(Auth::user()->can('reset user-password'))
                                            <a href="{{ route('reset-password_user',Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-success" data-toggle="tooltip" title="Reset Password" onclick="return confirm('Reset password to chandra@2019 ?')"><i class="fa fa-unlock"></i></a>
                                            @endif
                                            @if(Auth::user()->can('add assign tools'))
                                            <a href="{{ route('user-assign-tools',Crypt::encrypt($user->id)) }}" target="_blank" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Assign tool kit"><i class="fa fa-user"></i></a>
                                            @endif
                                            @if(Auth::user()->can('delete user'))
                                            <a href="{{ route('deactivate-user',Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Deactivate User" onclick="return confirm('Are you sure')"><i class="fa fa-times"></i></a>
                                            @endif
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
        <h4 class="modal-title">Filter Users By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First name">
                        <label class="form-label">First Name</label>
                        @if(request()->get("deactivated_user"))
                        <input type="hidden" name="deactivated_user" value="1">
                        @endif
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle name">
                        <label class="form-label">Middle Name</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last name">
                        <label class="form-label">Last Name</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="emp_code" id="emp_code" value="{{ old('emp_code') }}" placeholder="Employee code">
                        <label class="form-label">Emp Code</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control datepicker" name="dob" placeholder="DOB eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('dob') }}">
                        <label class="form-label">DOB</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float left50">
                    <div class="form-line">
                        <input type="text" class="form-control" name="ph_no"  maxlength="11" placeholder="Employee phone no" value="{{ old('ph_no') }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')">
                        <label class="form-label">Phone no</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Employee email">
                        <label class="form-label">Email</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="emp_designation" value="{{ old('emp_designation') }}" placeholder="Employee designation">
                        <label class="form-label">Designation</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control" name="role" >
                            <option value="">-- Please select user role --</option>
                            <option value="admin" {{ old('role') == "admin" ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role') == "manager" ? 'selected' : '' }}>Manager</option>
                            <option value="engineer" {{ old('role') == "engineer" ? 'selected' : '' }}>Engineer</option>
                        </select>
                        <label class="form-label">User role</label>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
<script src="{!!asset('assets/plugins/jquery-datatable/jquery.dataTables.js')!!}"></script>
<script src="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')!!}"></script>
<script src="{!!asset('assets/js/jquery-datatable.js')!!}"></script>
<script>
    $('.datepicker').Zebra_DatePicker({
        // direction: 1,
        format: 'd-m-Y',
        direction: false
    });

    $('.js-basic-example').DataTable({
        pageLength: 50,
        responsive: true
        
    });
</script>

@stop