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
        <h2>Assign Zone To Engineer</h2>
        <ul class="header-dropdown m-r--5">
          @if(Auth::user()->can('view assign-engineer'))
          <li><a href="{{ route('view-all-assign-engineer') }}" class="btn btn-success">View all</a></li>
          @endif
        </ul>
      </div>
      <div class="body">
        <form id="form_validation" method="POST" action="{{ route('assign-new-client-to-engineer.post') }}">
          {{ csrf_field() }}

          <div class="row">

            <div class="col-md-4">
              <div class="form-group form-float">
                <div class="form-line">
                  <select class="form-control show-tick" name="user_role" id="user_role" required>
                    <option value=""> Please select user role </option>
                    <option value="1" data-themeid="1">Admin</option>
                    <option value="2" data-themeid="2">Manager</option>
                    <option value="3" data-themeid="3">Engineer</option>
                  </select>
                  <label class="form-label">Select User Role</label>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              

              <div class="form-group form-float">
                <div class="form-line">
                   <select class="form-control show-tick" name="engineer_id" id="engineer_id" required>
                        <option value=""> Please select a user </option>
                       
                    </select>
                  <label class="form-label">Select User</label>
                </div>
              </div>


            </div>

            <div class="col-md-4">
              <div class="form-group form-float">
                <div class="form-line">
                  <select class="form-control show-tick" name="zone_id" id="zone_id" required>
                    <option value=""> Please select a zone </option>
                    <?php foreach ($zones as $zone): ?>
                    <option value="{{ $zone->id }}" data-themeid="{{ $zone->id }}" {{ old('zone_id') == "$zone->id" ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
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
          <button class="btn btn-primary waves-effect" type="submit" onclick="return confirm('Are you sure')" id="cnt_submt">SUBMIT</button>
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

 $("#user_role").change(function(){
  
  var user_role = $('option:selected', this).attr('data-themeid');

  // alert(user_role);

   
  $.ajax({
    type: "GET",
    url: "{{ route('get-rolewise-user-details.ajax') }}",
    data: {
      'user_role': user_role
    },

    success: function(response) {
      if(response) {

        var toAppend = '';

        toAppend +='<option value="">All User</option>';
        $.each(response, function(i,o){

          console.log(o.id);

          first_name = '';
          if (o.first_name != null) 
          {
            first_name = o.first_name;
          }

          middle_name = '';
          if (o.middle_name != null) 
          {
            middle_name = o.middle_name;
          }

          last_name = '';
          if (o.last_name != null) 
          {
            last_name = o.last_name;
          }

          toAppend += '<option  value="'+o.id+'" data-themeid="'+o.id+'">'+first_name+' '+middle_name+' '+last_name+'</option>';
        });

        $('#engineer_id').html(toAppend);

      }else{
        alert("No branch found");
      }
    }
  });
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
              // alert("is blank: " + response);
              $('#auto_products_view').hide();
              $('#cnt_submt').hide();
              $("#cnt_submt").prop("disabled", true);
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

              
              $('#c_details').html(toAppend);
              $('.auto_hide').show();
              $(".client_detail").prop("checked", true);
              $('#cnt_submt').show();
              $("#cnt_submt").prop("disabled", false);
              $('#error_msg').fadeOut(1000);
            }

        }else{
          alert("No client found");
          toAppend +='<p>No client found for this zone</p>';
          $('#c_details').html(toAppend);
 
          $(".client_detail").prop("checked", false);
          $('.auto_hide').hide();
          $('#cnt_submt').hide();
          $("#cnt_submt").prop("disabled", true);
        }
      }
    });
  });

</script>
@stop

