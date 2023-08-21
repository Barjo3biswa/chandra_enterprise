@extends('layouts.front')


@section('styles')
<style>
	#locationMap {
		height: 70vh;
	}
</style>
@stop

@section('content')

<div class="row">

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<input type="hidden" id="track_date" value="{{ $track_date }}" name="track_date">
		<input type="hidden" id="user_id" value="{{ $engineer_name->id }}" name="user_id">

		<div class="card">
			<div class="header bg-cyan">
				<h2>
					Engineer Location Tracking <small>Details</small>
				</h2>

				<ul class="header-dropdown m-r--5">
					<li>
						<button type="button" data-toggle="modal" data-target="#myModal" data-backdrop="static"
							data-keyboard="false" class="btn bg-lime waves-effect btn-sm"><i class="fa fa-list"></i>
							Location details </button>
					</li>

				</ul>

			</div>

			<div class="body">
				<table class="table table-condensed">

					<thead>
						<tr>
							<th>Engineer Name</th>
							<th>Track Date From</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								{{ ucwords($engineer_name->first_name.' '.$engineer_name->middle_name.' '.$engineer_name->last_name) }}
							</td>
							<td>
								@if($track_date != "0000-00-00")
								{{ date('d M, Y', strtotime($track_date)) }}
								@endif
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>

		<div class="col-md-12">
			{{-- <div class="track_location"> --}}
			<div id="locationMap" style="width: 100%;"></div>
			{{-- </div> --}}
		</div>

	</div>


	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Location details</h4>

				</div>
				<div class="modal-body">
					<table class="table table-condensed">

						<thead>
							<tr>
								<th class="col-teal">#</th>
								<th class="col-teal">Location name</th>
								<th class="col-teal">Track time</th>
							</tr>
						</thead>
						<tbody>
							@if($track_details->count()>0)
							@php $i=1 @endphp
							@foreach($track_details as $track_detail)
							<tr>
								<td>{{ $i }}.</td>
								<td>{{ ucwords($track_detail->location) }}</td>
								<td>{{ date('d M, Y h:i:s A', strtotime($track_detail->track_date)) }}</td>
							</tr>
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
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBS7ngGD4Xesm8Ec9EArp0qG7dD-x11mT8"
		type="text/javascript"></script>
	<script src="{{ asset('assets/js/gmaps.min.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		$(window).on("load", function() {
  trackEngineerLocation();
 });

function trackEngineerLocation() {
  var data = '';
  var url  = '';

  var track_date = $("#track_date").val();
  var user_id = $("#user_id").val();

  url = "{{ route('all-engineer-track-locations') }}";

  $.ajax({
     data: {
     'track_date': track_date,
     'user_id': user_id
    },
    url  : url,
    type : "GET",

    error : function(resp) {
      console.log(resp);
    },

    success : function(response) {
		if(!response.length){
			var map = new google.maps.Map(document.getElementById('locationMap'), {
				zoom: 13,
				center: new google.maps.LatLng(26.1445, 91.7362),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
		}else{
			var map = new google.maps.Map(document.getElementById('locationMap'), {
				zoom: 13,
				center: new google.maps.LatLng(response[0].latitude, response[0].longitude),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
		}


		var infowindow = new google.maps.InfoWindow();

		var marker, i;

		// console.log(response);
		var bounds = new google.maps.LatLngBounds();

		$.each(response, function(i, val) {
			// console.log('lat : '+val.latitude);
			// console.log('lng : '+val.longitude);
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(val.latitude, val.longitude),
				map: map,
				icon: "{!! asset('assets/img/map-pin/location-32x32.png') !!}"
			});
			bounds.extend(marker.position);
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {


					var my_date = val.track_date;
					my_date = my_date.replace(/-/g, "/");
					var d = new Date(my_date);

					first_name = '';
					if (val.user.first_name != null)  {
						first_name = val.user.first_name;
					}

					middle_name = '';
					if (val.user.middle_name != null) {
						middle_name = val.user.middle_name;
					}

					last_name = '';
					if (val.user.last_name != null)  {
						last_name = val.user.last_name;
					}

					var contentString = 
					'<div id="content" style="width:400px; padding:10px; background-color:#eeeeee;"> <strong style="text-decoration: underline;">Location</strong>  : ' +
					val.location+" <br> by <strong>"+first_name+' '+middle_name+' '+last_name+"</strong> <br> on "+d + 
					'</div>';

					infowindow.setContent(contentString);
					infowindow.open(map, marker);
				}
			})(marker, i));
		});
		if(response.length){
			map.fitBounds(bounds);
			//(optional) restore the zoom level after the map is done scaling
			var listener = google.maps.event.addListener(map, "idle", function () {
				map.setZoom(13);
				google.maps.event.removeListener(listener);
			});
		}
    }

  });
}






	// var locations = [
 //      ['Bondi Beach', -33.890542, 151.274856, 4],
 //      ['Coogee Beach', -33.923036, 151.259052, 5],
 //      ['Cronulla Beach', -34.028249, 151.157507, 3],
 //      ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
 //      ['Maroubra Beach', -33.950198, 151.259302, 1]
 //    ];

 //    var map = new google.maps.Map(document.getElementById('map'), {
 //      zoom: 10,
 //      center: new google.maps.LatLng(-33.92, 151.25),
 //      mapTypeId: google.maps.MapTypeId.ROADMAP
 //    });

 //    var infowindow = new google.maps.InfoWindow();

 //    var marker, i;

 //    for (i = 0; i < locations.length; i++) {  
 //      marker = new google.maps.Marker({
 //        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
 //        map: map
 //      });

 //      google.maps.event.addListener(marker, 'click', (function(marker, i) {
 //        return function() {
 //          infowindow.setContent(locations[i][0]);
 //          infowindow.open(map, marker);
 //        }
 //      })(marker, i));
 //    }
	</script>
	@stop