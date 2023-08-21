@extends('layouts.front')


@section('styles')
<!--<link rel="stylesheet" type="text/css" href="{!!asset('assets/plugins/select2/select2.min.css')!!}">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">

<style type="text/css" media="screen">
	#phone .form-label {
		
	}
	.left50 .form-line .form-label {
		left: 50px !important;
	}

	.text-green {
		color: green;
	}
	.error {
		color: #F44336;
	}	                                                 	
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add New User</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view user'))
          <li><a href="{{ route('view-all-users') }}" class="btn btn-success">View all</a></li>
          @endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-users.post') }}">
					{{ csrf_field() }}

					
						@include('admin.user._create')
					

					<button class="btn btn-primary waves-effect"  type="submit">SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<!--<script type="text/javascript" src="{!!asset('assets/plugins/select2/select2.min.js')!!}"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>

<script>

// $("#state").select2({
// 	minimumInputLength: 2
// });

// $("#district").select2();
	
$('.datepicker').Zebra_DatePicker({
      // direction: 1,
      format: 'd-m-Y',
      direction: false
  });
	

// function confirmEmail() {
//     var email = document.getElementById("email").value
//     var confemail = document.getElementById("confemail").value
//     if(email != confemail) {
//         alert('Email Not Matching!');
//     }
// }

function confirmPassword() {
    var password              = document.getElementById("password").value
    var password_confirmation = document.getElementById("password_confirmation").value
    if(password != password_confirmation) {
        alert('Password Not Matching!');
        return false;
    }
    return true;
}

$("#state").change(function(){
  
  var state = $('option:selected', this).attr('data-themeid');

    // alert(state);

  $.ajax({
    type: "GET",
    url: "{{ route('getdistlist.ajax.post') }}",
    data: {
      'state': state
    },

    success: function(response) {
      if(response) {

        

        var toAppend = '';

        toAppend +='<option value="">All districts</option>';
        $.each(response, function(i,o){

        	console.log(o);
          toAppend += '<option  value="'+o.name+'" data-themeid="'+o.id+'">'+o.name+'</option>';
        });

        $('#district').html(toAppend);

      }else{
        alert("No district found");
      }
    }
  });
  });
</script>
@stop





