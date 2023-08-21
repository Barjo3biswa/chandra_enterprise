@extends('layouts.front')


@section('styles')
<style>
    .bg_teal {
        color: #009688!important;
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
                    {{ ucwords($complaint_details->complaint_no) }} <small>Details</small>
                </h2>
            <ul class="header-dropdown m-r--5">
                <li>
                    <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-lime waves-effect btn-sm"><i class="fa fa-list"></i>
                        Transaction details </button>
                </li>
                
            </ul>

            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="width: 18%;">Complaint no : </th>
                                <td style="width: 30%;">{{ ucwords($complaint_details->complaint_no) }}</td>


                                <th style="width: 18%;">Complaint date : </th>
                                <td style="width: 30%;">
                                    @if($complaint_details->complaint_call_date != '0000-00-00')
                                    {{ date('d M, Y', strtotime($complaint_details->complaint_call_date)) }}
                                    @endif
                                </td>
                            </tr>
                           
                            <tr>
                                <th style="width: 18%;">Complaint for : </th>
                                <td style="width: 30%;">{{ ucwords($complaint_details->m_complaint_details) }} </td>


                                <th style="width: 18%;">Not in the list details : </th>
                                <td style="width: 30%;">@if($complaint_details->not_in_the_list_detail != null){{ $complaint_details->not_in_the_list_detail }}@endif</td>
      
                            </tr>

                            <tr>
                                <th style="width: 18%;">Complaint entry date : </th>
                                <td style="width: 30%;">
                                    @if($complaint_details->complaint_entry_date != '0000-00-00')
                                    {{ date('d M, Y', strtotime($complaint_details->complaint_entry_date)) }}
                                    @endif
                                </td>

                                 <th style="width: 18%;">Complaint priority : </th>
                                <td style="width: 30%;">
                                    @if($complaint_details->priority == 0)
                                        No priority
                                    @endif
                                    @if($complaint_details->priority == 1)
                                        Low priority
                                    @endif
                                    @if($complaint_details->priority == 2)
                                        High priority
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th style="width: 18%;">Complaint entered by : </th>
                                <td style="width: 30%;">{{ ucwords($complaint_details->first_name.' '.$complaint_details->middle_name.' '.$complaint_details->last_name) }}</td>

                                <th style="width: 18%;"></th>
                                <td style="width: 30%;"></td>
                                       
                            </tr>
                            
                            <tr>
                                {{-- <th style="width: 18%;">Complaint remarks : </th>
                                <td>{{ ucwords($complaint_details->last_updated_remarks) }}</td> --}}

                                <th style="width: 18%;">Complaint status : </th>
                                <td style="width: 30%; font-weight: bold; color: #607D8B;">{{ ucwords($complaint_trans->remarks) }}</td>

                                <th style="width: 18%;"></th>
                                <td style="width: 30%;"></td>
     
                            </tr>

                            <tr>
                                <th style="width: 18%;">Complaint details : </th>
                                <td>{{ ucwords($complaint_details->complaint_details) }}</td>
                            </tr>

                        </tbody>
                    </table>

                    <h4>Contact Person Details</h4>
                    <table class="table table-condensed">

                        <thead>
                            <tr>
                                <th>Contact person name</th>
                                <th>Contact person email</th>
                                <th>Contact person phone no</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ ucwords($complaint_details->contact_person_name) }}</td>
                                <td>{{ $complaint_details->contact_person_email }}</td>
                                <td>{{ $complaint_details->contact_person_ph_no }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4>Client Details</h4>
                    <table class="table table-condensed">

                        <thead>
                            <tr>
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
                            <tr>
                                <td>{{ ucwords($complaint_details->c_name) }}</td>
                                <td>{{ ucwords($complaint_details->c_branch_name) }}</td>

                                <td>{{ ucwords($complaint_details->z_name) }}</td>

                                <td>{{ $complaint_details->c_email }}</td>
                                <td>{{ $complaint_details->c_ph_no }}</td>
                                <td>
                                    {{ $complaint_details->c_address }}
                                </td>
                                <td>{{ $complaint_details->c_remarks }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4>Assigned Product/Machine Details</h4>
                    <table class="table table-condensed">

                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Group</th>
                                <th>Serial no</th>
                                <th>Code</th>
                                <th>Model</th>
                                <th>Brand</th>
                                <th>Company</th>
                                <th>Date of install </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ ucwords($complaint_details->p_name) }}</td>
                                <td>{{ ucwords($complaint_details->g_name) }}</td>
                                <td>{{ $complaint_details->p_serial_no }}</td>
                                <td>{{ $complaint_details->p_product_code }}</td>
                                <td>{{ $complaint_details->p_model_no }}</td>

                                <td>{{ $complaint_details->p_brand }}</td>
                                <td>
                                    @if($complaint_details->company_name != null)
                                        {{ $complaint_details->company_name }}
                                    @endif
                                </td>
                                <td>
                                    @if($complaint_details->date_of_install != null)
                                        @if($complaint_details->date_of_install != "0000-00-00")
                                        {{ date('d M, Y', strtotime($complaint_details->date_of_install)) }}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    {{-- @if($complaint_details->assigned_to != null)
                    <h4>Assigned to engineer details</h4>
                    <table class="table table-condensed">

                        <thead>
                            <tr>
                                <th>Engineer Name</th>
                                <th>Email</th>
                                <th>Ph no</th>
                                <th>Emp code</th>
                                <th>Designation</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ ucwords($assigned_engg_details->f_name.' '.$assigned_engg_details->m_name.' '.$assigned_engg_details->l_name) }}</td>
                                <td>{{ $assigned_engg_details->email }}</td>
                                <td>{{ $assigned_engg_details->ph_no }}</td>
                                <td>{{ $assigned_engg_details->emp_code }}</td>

                                <td>{{ $assigned_engg_details->designation }}</td>
                                <td>{{ $assigned_engg_details->role }}</td>
                                </tr>
                        </tbody>
                    </table>
                    @endif --}}
                    <h4>Assigned to engineer details</h4>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Engineer Name</th>
                                <th>Emp code</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assigned_engineers as $assigned)
                                <tr>
                                    <td>{{ ucwords($assigned->engineer->full_name()) }}</td>
                                    <td>{{ $assigned->engineer->emp_code }}</td>
                                    <td>{{ $assigned->remark }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-danger text-center">No engineers assigned yet.</td>
                                </tr>
                            @endforelse
                            
                        </tbody>
                    </table> 

                     {{-- @if($complaint_details->complaint_status == 3) --}}
                        <h4>Complaint Status</h4>
                        <table class="table table-condensed">

                        <thead>
                            <tr>
                               <th>Date</th>
                               <th>By user</th>
                               <th>Status</th>
                               <th>Transaction remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                               <td>
                                @if($closed_complaint->transaction_date != '0000-00-00')
                                    {{ date('d M, Y', strtotime($closed_complaint->transaction_date)) }}
                                @endif
                               </td>
                               <td>{{ $closed_complaint->user->first_name.' '.$closed_complaint->user->middle_name.' '.$closed_complaint->user->last_name }}</td>
                               <td>{{ $closed_complaint->remarks }}</td>
                               <td>{{ $closed_complaint->transaction_remarks }}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{-- @endif --}}

                    @if(Auth::user()->can('add complaint status'))
                    @if($complaint_details->complaint_status != 3)
                    <h4>Add complaint status</h4>
                    <form id="form_validation" method="POST" action="{{ route('update-complaint-status',Crypt::encrypt($complaint_details->id)) }}">

                      {{ csrf_field() }}
                      {!! method_field('PATCH') !!}
                      <div class="row">

                        <div class="col-md-4">
                          <div class="form-group form-float">
                            <div class="form-line">
                              <select class="form-control show-tick" name="complaint_status" id="complaint_status" required>
                                <option value=""> Complaint status </option>
                                <option value="2">Complaint under-process</option>
                                <option value="3"> Complaint closed </option>
                              </select>
                              {{-- <label class="form-label">Select Group</label> --}}
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>

                      </div>

                      <div class="row">
                          <div class="col-md-8">
                              <div class="form-group form-float">
                                <div class="form-line">
                                    <textarea class="form-control" name="transaction_remarks" required>{{ old('transaction_remarks') }}</textarea>
                                    <label class="form-label">Remarks</label>
                                </div>
                            </div>
                          </div>
                      </div>


                      <div class="row">
                        <div class="col-md-12">
                           <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>

                            @if(Auth::user()->can('view complaint'))
                            <a href="{{ route('view-all-complaints') }}" target="_blank" class="btn btn-success pull-right">View all complaints</a>
                            @endif
                        </div>
                      </div>
                 
                    </form>
                    @endif
                    @endif
                    

                

               
            @if($complaint_details->complaint_status == 1 || $complaint_details->complaint_status == 4)
                <div class="row">
                    <div class="col-md-12">
                        @if(Auth::user()->can('edit complaint'))
                        <a href="{{ route('edit-complaint-register-details',Crypt::encrypt($complaint_details->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i> Edit complaint</a>
                        @endif

                        @if(Auth::user()->can('assign complaint to engineer'))
                        <a href="{{ route('assigned-complaint-to-engineer',Crypt::encrypt($complaint_details->id)) }}" class="btn btn-warning btn-sm pull-right"><i class="fa fa-user"></i> Assign to engineer </a>
                        @endif
                    </div>
                </div>
            @endif

            </div>
                
            </div>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Transaction details</h4>
        <strong>Complaint no :</strong> <span>{{ ucwords($complaint_details->complaint_no) }}</span><br>
        <strong>Complaint call date :</strong> <span>{{ date('d M, Y', strtotime($complaint_details->complaint_call_date)) }}</span><br>
        <strong>Complaint entry date :</strong> <span>{{ date('d M, Y', strtotime($complaint_details->complaint_entry_date)) }}</span><br>
        <strong>Complaint details :</strong><span>{!! ucwords($complaint_details->complaint_details) !!}</span><br>
        
        <strong>Last transaction by :</strong><span>{!! ucwords($last_transaction_by->first_name.' '.$last_transaction_by->middle_name.' '.$last_transaction_by->last_name) !!}</span><br>


        <strong>Complaint by :</strong><span>{!! ucwords($complaint_details->first_name.' '.$complaint_details->middle_name.' '.$complaint_details->last_name) !!}</span><br>
        <small><i class="fa fa-square bg_teal"></i> Active transaction &nbsp;</small>
      </div>
      <div class="modal-body">
        <table class="table table-condensed">

            <thead>
                <tr>
                   <th>#</th>
                   <th>Transaction date</th>
                   <th>Transaction by</th>
                   <th>Remarks</th>
                   <th>Transaction remarks</th>
                </tr>
            </thead>
            <tbody>
                @if(count($complaint_transaction_details)>0)
                @php $i=1 @endphp
                @foreach($complaint_transaction_details as $complaint_transaction_detail)
                    @if($complaint_transaction_detail->status == 1)
                    <tr>
                       <td class="col-teal">{{ $i }}.</td>
                       <td class="col-teal">
                            @if($complaint_transaction_detail->transaction_date != '0000-00-00')
                                {{ date('d M, Y', strtotime($complaint_transaction_detail->transaction_date)) }}
                            @endif
                       </td>
                       <td class="col-teal">{{ ucwords($complaint_transaction_detail->user->first_name.' '.$complaint_transaction_detail->user->middle_name.' '.$complaint_transaction_detail->user->last_name) }}</td>
                       <td class="col-teal">{{ $complaint_transaction_detail->remarks }}</td>
                       <td class="col-teal">{{ $complaint_transaction_detail->transaction_remarks }}</td>
                    </tr>
                    @endif

                    @if($complaint_transaction_detail->status == 0)
                    <tr>
                       <td>{{ $i }}.</td>
                       <td>
                            @if($complaint_transaction_detail->transaction_date != '0000-00-00')
                                {{ date('d M, Y', strtotime($complaint_transaction_detail->transaction_date)) }}
                            @endif
                       </td>
                       <td>{{ ucwords($complaint_transaction_detail->user->first_name.' '.$complaint_transaction_detail->user->middle_name.' '.$complaint_transaction_detail->user->last_name) }}</td>
                       <td>{{ $complaint_transaction_detail->remarks }}</td>
                       <td>{{ $complaint_transaction_detail->transaction_remarks }}</td>
                    </tr>
                @endif
                @php $i++ @endphp
                @endforeach
                @endif
            </tbody>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

@endsection


@section('scripts')

@stop