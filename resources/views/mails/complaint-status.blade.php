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
				<strong>Complaint no : </strong> {{ $complaint_no }}
				<p>{{ $complaint_status }} by {{ ucwords($transaction_by->first_name.' '.$transaction_by->middle_name.' '.$transaction_by->last_name) }} on {{ date('d M, Y', strtotime($transaction_date)) }} </p>

				<strong>Remarks: </strong> {{ $remarks }}
			</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>





