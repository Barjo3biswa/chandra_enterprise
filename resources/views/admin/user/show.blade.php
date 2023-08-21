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
                    {{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }} <small>Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-condensed">

                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 18%;">Name : </th>
                            <td>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }}</td>

                            <th scope="row" style="width: 18%;">Username : </th>
                            <td>{{ $user->emp_code }}</td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 18%;">Email : </th>
                            <td>{{ $user->email }}</td>

                            <th scope="row" style="width: 18%;">Phone no : </th>
                            <td>{{ $user->ph_no }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 18%;">Date of birth : </th>
                            <td>
                                @if($user->dob != '0000-00-00')
                                {{ date('d M, Y', strtotime($user->dob)) }}
                                @endif
                            </td>

                            <th scope="row" style="width: 18%;">Pan card no : </th>
                            <td>{{ $user->pan_card_no }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 18%;">Designation : </th>
                            <td>{{ ucwords($user->emp_designation) }}</td>

                            <th scope="row" style="width: 18%;">Role : </th>
                            <td>{{ ucwords($user->role) }}</td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 18%;">State : </th>
                            <td>{{ ucwords($user->state) }}</td>

                            <th scope="row" style="width: 18%;">District : </th>
                            <td>{{ ucwords($user->district) }}</td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 18%;">Pin code : </th>
                            <td>{{ $user->pin_code }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 18%;">Address : </th>
                            <td>{{ $user->address }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 18%;">Remarks : </th>
                            <td>{{ $user->remarks }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($assign_user_tools->count())
            @if(Auth::user()->can('view assign tools'))
            <h4>Assigned tool kits</h4>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">

                        <table class="table table-condensed">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tool kit assigned date</th>
                                    <th>Action</th>
                                 </tr>
                            </thead>
                            <tbody>
                                {{-- @php $i=1 @endphp --}}
                                @foreach($assign_user_tools as $key => $assign_user_tool)
                                <tr>
                                    <td>{{ $key+ 1 + ($assign_user_tools->perPage() * ($assign_user_tools->currentPage() - 1)) }}</td>
                                    <td>
                                        @if($assign_user_tool->assign_date != "0000-00-00")
                                        {{ date('d M, Y', strtotime($assign_user_tool->assign_date)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status = 1)
                                      <div class="btn-group">
                                            <form class="form_inline_custom" action="{{ route('show-user-assign-tools',Crypt::encrypt($user->id)) }}" method="get">
                                                {{ csrf_field() }}

                                                <input type="hidden" name="assign_date" value="{{ $assign_user_tool->assign_date }}">

                                                <button type="submit" class="btn btn-xs btn-success" data-toggle="tooltip" title="Assign tool kit details"><i class="fa fa-eye"></i> &nbsp; Details</button>
                                                
                                            </form>

                                            @if(Auth::user()->can('edit assign tools'))
                                            <form class="form_inline_custom" action="{{ route('edit-user-assign-tools',Crypt::encrypt($user->id)) }}" method="get">
                                                {{ csrf_field() }}

                                                <input type="hidden" name="assign_date_edit" value="{{ $assign_user_tool->assign_date }}">
                                               <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit assign tool kit"><i class="fa fa-edit"></i> &nbsp; Edit</button> 
                                            </form>
                                            @endif

                                            @if(Auth::user()->can('delete assign tools'))
                                            <form class="form_inline_custom" action="{{ route('delete-user-assign-tools',Crypt::encrypt($user->id)) }}" method="get">
                                                {{ csrf_field() }}

                                                <input type="hidden" name="assign_date_delete" value="{{ $assign_user_tool->assign_date }}">

                                                <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete assign tool kit" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i> &nbsp; Delete</button>
                                            </form>
                                            @endif

                                        </div>
                                        @endif
                                    </td>
             
                              </tr>
                                {{-- @php $i++ @endphp --}}
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                           {{ $assign_user_tools->render() }} 
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif


            @if($assign_eng->count())
            <h4>Assigned Client Details</h4>
            <table class="table table-condensed">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Branch name</th>
                        <th>Zone name</th>
                        <th>Email</th>
                        <th>Ph no</th>
                        <th>Address </th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                    @foreach($assign_eng as $assign_engineer)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $assign_engineer->client->name }}</td>
                        <td>{{ $assign_engineer->client->branch_name }}</td>
                        <td>{{ $assign_engineer->client->zone->name }}</td>
                        <td>{{ $assign_engineer->client->email }}</td>
                        <td>{{ $assign_engineer->client->ph_no }}</td>
                        <td>
                            {{ $assign_engineer->client->address }}
                        </td>
                        <td>{{ $assign_engineer->client->remarks }}</td>
                    </tr>
                    @php $i++ @endphp
                    @endforeach
                </tbody>
            </table>

            @if(count($assign_client_details) > 0)
            <h4>Assigned Product/Machine Details</h4>
            <table class="table table-condensed">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Serial no</th>
                        <th>Code</th>
                        <th>Model</th>
                        <th>Brand</th>
                        <th>Company</th>
                        <th>Date of install </th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                    @foreach($assign_client_details as $assgn_p)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $assgn_p->name }}</td>
                        <td>{{ $assgn_p->serial_no }}</td>
                        <td>{{ $assgn_p->product_code }}</td>
                        <td>{{ $assgn_p->model_no }}</td>

                        <td>{{ $assgn_p->brand }}</td>
                        <td>{{ $assgn_p->company_name }}</td>
                        <td>
                            @if($assgn_p->date_of_install != "0000-00-00")
                            {{ date('d M, Y', strtotime($assgn_p->date_of_install)) }}
                            @endif
                        </td>
                    </tr>
                    @php $i++ @endphp
                    @endforeach
                </tbody>
            </table>
            @endif
            @endif



            @if(count($all_sp_prts)>0)
            <h4>Assigned Spare Part Details</h4>
            <table class="table table-condensed">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Spare Part Name</th>
                        <th>Spare Part No</th>
                        <th>Stock In Hand</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                    @foreach($all_sp_prts as $key => $sp)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $sp->spare_part->name }}</td>
                        <td>{{ $sp->spare_part->part_no }}</td>
                        <td>{{ $stock_in_hand[$sp->spare_part->id] }}</td>
                    </tr>
                    @php $i++ @endphp
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
</div>

@endsection


@section('scripts')

@stop