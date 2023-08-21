@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-md-offset-3">
  	 <form action="{{ route('user.change-password.post') }}" method="post">
  	 	{{ csrf_field() }}

  	 	<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

	  	<div class="card">
	  		<div class="header bg-blue-grey">
	  			<h2>
	  				Change your password <small></small>
	  			</h2>
	  		</div>

	  		<div class="body">
	  			<div class="row">

            <div class="col-sm-12">
              <label for="old_password">Old Password</label>
              <div class="form-group">
                <div class="form-line">
                  <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" required>
                </div>
              </div>
            </div>


            <div class="col-sm-12">
              <label for="new_password">New Password</label>
  						<div class="form-group">
                <div class="form-line">
                	<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
                </div>
  						</div>
  					</div>

  					<div class="col-sm-12">
              <label for="confirm_password">Confirm Password</label>
  						<div class="form-group">
                <div class="form-line">
  							   <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter Your New Password" onblur="return confirmPassword()" required>
                </div>
  						</div>
  					</div>
	  				
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary waves-effect">SUBMIT</button>
            </div>

	  			</div>
	  		</div>

	  	</div>
  	 </form>
  </div> 
</div>

@endsection


@section('scripts')
<script>
function confirmPassword() {
    var new_password = document.getElementById("new_password").value;
    var confirm_password = document.getElementById("confirm_password").value;
    if(new_password != confirm_password) {
        alert('Password Not Matching!');
        return false;
    }
    return true;
}
</script>
@stop