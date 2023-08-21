@extends('layouts.front')


@section('styles')
<style>
  .form-group .form-line .form-label {
    top: -10px !important;
  }
</style>
@stop

@section('content')

<div class="row clearfix">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
      <div class="header">
        <h2>Update {{ ucwords($assign_eng_name->user->first_name.' '.$assign_eng_name->user->middle_name.' '.$assign_eng_name->user->last_name) }} Assigned Client Details</h2>
        <ul class="header-dropdown m-r--5">
          <li><a href="{{ route('view-all-assign-engineer') }}" class="btn btn-success">View all</a></li>
        </ul>
      </div>
      <div class="body">
        <form id="form_validation" method="POST" action="{{ route('assign-client-engineer.update',Crypt::encrypt($assign_eng_name->engineer_id)) }}">

          {{ csrf_field() }}
          {!! method_field('PATCH') !!}

          <input type="hidden" name="client_id" value="{{ $assign_eng_name->client_id }}">
          <input type="hidden" name="engineer_id_to_encrypt" value="{{ $assign_eng_name->engineer_id }}">

          <div class="row">

            <div class="col-md-4">
              <div class="form-group form-float">
                <div class="form-line">
                  <select class="form-control show-tick" id="engineer_id" disabled="true">
                    <option value=""> Please select an engineer </option>
                    <?php foreach ($engineers as $engineer): ?>

                    <option value="{{ $engineer->id }}" data-themeid="{{ $engineer->id }}" {{ old('engineer_id',$assign_eng_name->engineer_id) == "$engineer->id" ? 'selected' : '' }}>{{ ucwords($engineer->first_name.' '.$engineer->middle_name.' '.$engineer->last_name) }}</option>
                    <?php endforeach; ?>
                  </select>
                  <label class="form-label">Select Engineer</label>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group form-float">
                <div class="form-line">
                  <select class="form-control show-tick" name="zone_id" id="zone_id" required>
                    <option value=""> Please select a zone </option>
                    <?php foreach ($zones as $zone): ?>
                    <option value="{{ $zone->id }}" data-themeid="{{ $zone->id }}" {{ old('zone_id',$assign_eng_name->zone_id) == "$zone->id" ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
                    <?php endforeach; ?>
                  </select>
                  <label class="form-label">Select Zone</label>
                </div>
              </div>
            </div>

          </div>

          <div id="error_msg">
            <p class="text-danger">You cannot submit data as there is no client to select</p>
          </div>

          <div class="row" id="auto_products_view">
            <div class="col-md-12">
              <div>


                <div class="body table-responsive">

                  <div class="auto_hide">
                      <input type="checkbox" name="select_all" id="select_all" class="filled-in chk-col-cyan" />
                      <label for="select_all">Select All</label>
                  </div>

                  <table class="table" id="c_details">
                    <thead>

                    </thead>
                    <tbody>


                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


          <div class="row" id="auto_products_view1">
            <div class="col-md-12">
            
                <div class="body table-responsive">

                  <table class="table" id="c_details1">
                    <thead>
                        <tr>
                          <th>Client name</th>
                          <th>Branch name</th>
                          <th>Email</th>
                          <th>Ph no</th>
                          <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                     <tr>
                        <td>{{ $assign_eng_name->client->name }}</td>
                        <td>{{ $assign_eng_name->client->branch_name }}</td>
                        <td>{{ $assign_eng_name->client->email }}</td>
                        <td>{{ $assign_eng_name->client->ph_no }}</td>
                        <td>{{ $assign_eng_name->client->remarks }}</td>
                      </tr>
                   </tbody>
                  </table>
                </div>
              </div>
            </div>
       
          <button class="btn btn-primary waves-effect" type="submit" onclick="return confirm('Are you sure')" id="cnt_submt">UPDATE</button>

        </form>
      </div>
    </div>
  </div>
</div>




@endsection


@section('scripts')
<script>

$(document).ready(function(){
  $('.auto_hide').hide();
  $('#error_msg').hide();
  $('#cnt_submt').hide();
  $('#auto_products_view').hide();
   document.getElementByClass("client_detail").checked = true;
});

 // $("#auto_products_view").hide();


 $('#select_all').click(function() {
  var checkboxes = $('.client_detail');
  // alert($(this).is(':checked'));
  if($(this).is(':checked')) {
    checkboxes.prop("checked" , true);
  } else {
    checkboxes.prop ( "checked" , false );
  }
});


  $("#zone_id").change(function(){

    var zone_id = $('option:selected', this).attr('data-themeid');


    $.ajax({
      type: "GET",
      url: "{{ route('get-client-details.ajax') }}",
      data: {
        'zone_id': zone_id
      },

      success: function(response) {
        if(response) {

          if (!$.trim(response)){   
              alert("No client to select");
              $('#auto_products_view').hide();
              $('#auto_products_view1').show();
              $('#cnt_submt').hide();
              $('#error_msg').fadeIn(1000);

          }
          else{   
              // alert("is not blank: " + response);
        
              $('#auto_products_view').fadeIn(1000);
              var toAppend = '';

              toAppend +='<tr><th>Client name</th><th>Branch name</th><th>Email</th><th>Ph no</th><th>Remarks</th></tr>';
              $.each(response, function(i,o){

              
              email = '';
              if (o.email != null) 
              {
                email = o.email;
              }
              ph_no = '';
              if (o.ph_no != null) 
              {
                ph_no = o.ph_no;
              }
              remarks = '';
              if (o.remarks != null) 
              {
                remarks = o.remarks;
              }

              toAppend += '<tr><th scope="row"><input type="checkbox" id="client_detail'+o.id+'" name="client_detail[]" class="chk-col-cyan client_detail" value="'+o.id+'" aria-required="true" /><label for="client_detail'+o.id+'">'+o.name+'</label></th><td>'+o.branch_name+'</td><td>'+email+'</td><td>'+ph_no+'</td><td>'+remarks+'</td></tr>';
              });

              $('#auto_products_view').show();
              $('#c_details').html(toAppend);
              $('.auto_hide').show();
              $(".client_detail").prop("checked", true);
              $('#auto_products_view1').hide();
              $('#error_msg').fadeOut(1000);
              $('#cnt_submt').show();
            }

        }else{
          alert("No client found");
          toAppend +='<p>No client found for this zone</p>';
          $('#c_details').html(toAppend);
 
          $(".client_detail").prop("checked", false);
          $('.auto_hide').hide();
        }
      }
    });
  });

</script>
@stop

