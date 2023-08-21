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

				<h1>Do you want submit data for the same client ?</h1>

				<a class="btn btn-success" href="{{route('add-new-daily-service-report', ["client_id" => $client_name, "branch" => $branch, "maintenance_type" => $maintenance_type])}}">Yes</a>

				<a class="btn btn-danger" href="{{route('add-new-daily-service-report')}}">No</a>

				<a class="btn btn-primary" href="{{ route('view-all-daily-service-report') }}">View all records</a>
			</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>