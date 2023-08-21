@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    Assign {{ ucwords($complaint_details->complaint_no) }} <small>To Engineer</small>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 18%;">Complaint no : </th>
                                <td>{{ ucwords($complaint_details->complaint_no) }}</td>


                                <th scope="row" style="width: 18%;">Complaint date : </th>
                                <td>
                                    @if($complaint_details->complaint_call_date != '0000-00-00')
                                    {{ date('d M, Y', strtotime($complaint_details->complaint_call_date)) }}
                                    @endif
                                </td>
                            </tr>
                           
                            <tr>
                                <th scope="row" style="width: 18%;">Complaint for : </th>
                                <td>{{ ucwords($complaint_details->complaint_details) }}</td>

                                <th scope="row" style="width: 18%;">Complaint entry date : </th>
                                <td>
                                    @if($complaint_details->complaint_entry_date != '0000-00-00')
                                    {{ date('d M, Y', strtotime($complaint_details->complaint_entry_date)) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Complaint entered by : </th>
                                <td>{{ ucwords($complaint_details->first_name.' '.$complaint_details->middle_name.' '.$complaint_details->last_name) }}</td>

                                <th scope="row" style="width: 18%;">Complaint status : </th>
                                <td>{{ ucwords($complaint_trans->remarks) }}</td>
                                       
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Complaint details : </th>
                                <td>{{ ucwords($complaint_details->complaint_details) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Complaint remarks : </th>
                                <td>{{ ucwords($complaint_details->last_updated_remarks) }}</td>

                                <th scope="row" style="width: 18%;">Complaint priority : </th>
                                <td>
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



                    <h4>Product/Machine Details</h4>
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


                    {{-- @if($complaint_details->complaint_status == 2)
                        <h4>Assigned to engineer details</h4>
                        <table class="table table-condensed">

                            <thead>
                                <tr>
                                    <th>Engineer Name</th>
                                    <th>Assigned zone</th>
                                    <th>Email</th>
                                    <th>Ph no</th>
                                    <th>Emp code</th>
                                    <th>Designation</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ ucwords($complaint_trans->first_name.' '.$complaint_trans->middle_name.' '.$complaint_trans->last_name) }}</td>
                                    <td>
                                        {{ ucwords($complaint_trans->zone_id) }}
                                    </td>
                                    <td>{{ $complaint_trans->email }}</td>
                                    <td>{{ $complaint_trans->ph_no }}</td>
                                    <td>{{ $complaint_trans->emp_code }}</td>

                                    <td>{{ $complaint_trans->emp_designation }}</td>
                                    <td>{{ $complaint_trans->role }}</td>
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

            </div>
           

        <h4>Assign Complaint To Engineer</h4>
        <form id="form_validation" method="POST" action="{{ route('update-assigned-complaint-to-engineer',Crypt::encrypt($complaint_details->id)) }}">
          {{ csrf_field() }}
          {!! method_field('PATCH') !!}
          <div class="row">

            <div class="col-md-4">
              <div class="form-group form-float">
                <div class="form-line">
                  <select class="form-control show-tick" name="zone_id" id="zone_id" required>
                    <option value=""> Please select a zone </option>
                    <?php foreach ($zones as $zone): ?>
                    <option value="{{ $zone->id }}" data-themeid="{{ $zone->id }}" {{ old('zone_id') == "$zone->id" ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
                    <?php endforeach; ?>
                  </select>
                  {{-- <label class="form-label">Select Group</label> --}}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group form-float">
                <div class="form-line">
                  <select class="form-control show-tick" name="assigned_to" id="assigned_to" required>
                    <option value=""> Please select an engineer </option>
                    
                  </select>
                  {{-- <label class="form-label">Select Group</label> --}}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="priority" id="priority" required>
                            <option value="0" {{ old('priority',$complaint_details->priority) == "0" ? 'selected' : '' }}> No priority </option>
                            <option value="1" {{ old('priority',$complaint_details->priority) == "1" ? 'selected' : '' }}> Low priority </option>
                            <option value="2" {{ old('priority',$complaint_details->priority) == "2" ? 'selected' : '' }}> High priority </option>
                        </select>
                        <label class="form-label">Complaint priority</label>
                    </div>
                </div>
            </div>

          </div>


        <div class="row">
           <div class="col-md-12">
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

      </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
$(window).load(function() {
    var zone_id = $('option:selected', this).attr('data-themeid');
    $.ajax({
        type: "GET",
        url: "{{ route('get-all-engineers-details.ajax') }}",
        data: {
            'zone_id': zone_id
        },

        success: function(response) {
            if (response) {
                var toAppend = '';
                toAppend += '<option value="">All Engineers</option>';
                $.each(response, function(i, o) {
                    // console.log(o.user.first_name);
                    first_name = '';
                    if (o.user.first_name != null) {
                        first_name = o.user.first_name;
                    }

                    middle_name = '';
                    if (o.user.middle_name != null) {
                        middle_name = o.user.middle_name;
                    }

                    last_name = '';
                    if (o.user.last_name != null) {
                        last_name = o.user.last_name;
                    }
                    toAppend += '<option  value="' + o.user.id + '" data-themeid="' + o
                        .user.id + '">' + first_name + ' ' + middle_name + ' ' +
                        last_name + '</option>';
                });
                $('#assigned_to').html(toAppend);
            } else {
                alert("No engineer found");
            }
        }
    });
});

$("#zone_id").change(function(){
  
  var zone_id = $('option:selected', this).attr('data-themeid');

  
  $.ajax({
    type: "GET",
    url: "{{ route('get-all-engineers-details.ajax') }}",
    data: {
      'zone_id': zone_id
    },

    success: function(response) {
      if(response) {

        var toAppend = '';

        toAppend +='<option value="">All Engineers</option>';
        $.each(response, function(i,o){

        	// console.log(o.user.first_name);

          first_name = '';
          if (o.user.first_name != null) 
          {
            first_name = o.user.first_name;
          }

          middle_name = '';
          if (o.user.middle_name != null) 
          {
            middle_name = o.user.middle_name;
          }

          last_name = '';
          if (o.user.last_name != null) 
          {
            last_name = o.user.last_name;
          }


          toAppend += '<option  value="'+o.user.id+'" data-themeid="'+o.user.id+'">'+first_name+' '+middle_name+' '+last_name+'</option>';
        });

        $('#assigned_to').html(toAppend);

      }else{
        alert("No engineer found");
      }
    }
  });
  });

</script>
@stop

