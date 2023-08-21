<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<strong>Complaint no : </strong> {{ $complaint_no }} <br>
				<strong>Complaint call date : </strong> {{ date('d M, Y', strtotime($complaint_call_date)) }} <br>
				<strong>Complaint entry date : </strong> {{ date('d M, Y', strtotime($complaint_entry_date)) }} <br>
				<p>Your complaint has successfully registered with us.</p>

				<strong>Complaint details : </strong> {{ $complaint_details }}
			</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

