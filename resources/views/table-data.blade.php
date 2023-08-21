<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
      #mytable td, #mytable th {
        border: 1px solid #ddd;
        padding: 8px;
      }

      #mytable tr:nth-child(even){background-color: #f2f2f2;}

      #mytable tr:hover {background-color: #ddd;}

      #mytable th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
      }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped" id="mytable">
                    <caption>table title and/or explanatory text</caption>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>State name</th>
                            <th>District name</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach($dists as $key => $dist)
                        <tr>
                            <td>{{ $key+ 1 + ($dists->perPage() * ($dists->currentPage() - 1)) }}</td>
                            <td>{{ $dist->state->name }}</td>
                            <td>{{ $dist->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $dists->render() }}
                <div class="pull-right">
                    <a href="" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Filter</a>
                </div>
            </div>
        </div>
    </div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                <select class="form-control show-tick" name="state" id="state">
                   <option value="">-- Please select state --</option>
                   <?php foreach ($states as $state): ?>
                    <option value="{{ $state->id }}" data-themeid="{{ $state->id }}" {{ old('state') == "$state->id" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
                   <?php endforeach; ?> 
                </select>
            </div>

           
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    
    $("#state").change(function(){
  
  var state = $('option:selected', this).attr('data-themeid');

    // alert(state);

  $.ajax({
    type: "GET",
    url: "{{ route('add-data.ajax.post') }}",
    data: {
      'state': state
    },

    success: function(response) {
      if(response) {

        

        var toAppend = '<tr><th>#</th><th>State name</th><th>District name</th></tr>';

         $.each(response, function(i,o){

          i=i+1;

          // console.log(o.state.name);

            toAppend +='<tr><td> '+i+' </td> <td> '+o.state.name+' </td> <td> '+o.name+' </td></tr>';
        });

       
        $('#mytable').addClass('table-bordered');
        $('#mytable').html(toAppend);

      }else{
        alert("No district found");
      }
    }
  });
  });
    </script>
</body>
</html>