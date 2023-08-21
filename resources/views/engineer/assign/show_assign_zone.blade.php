@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="card">
            <div class="header bg-cyan">
               <h2>All assigned clients</h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
               
                @if(count($zones) >0)

                <h4>Clients details </h4>
                {{-- <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect pull-right"><i class="fa fa-filter"></i> Filter </button></h4> --}}
                 

                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Branch name</th>
                            <th>Region name</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($zones as $key => $zone)
                        <tr>
                            <td>{{ $key+ 1 + ($zones->perPage() * ($zones->currentPage() - 1)) }}</td>
                            <td>{{ $zone->client->name }}</td>
                            <td>{{ $zone->client->branch_name }}</td>
                            <td>{{ $zone->client->region->name }}</td>
                            <td>{{ $zone->client->address }}</td>
                         </tr>
                        @php $i++ @endphp
                        @endforeach
                    </tbody>
                </table>
                {{$zones->render()}}
                @endif
                </div>
            </div>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <form action="" method="get">
            
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter Clients By</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options" class="form-control" name="client_id" id="client_id" value="{{ old('client_id') }}" autocomplete="off">
                        <label class="form-label">Client name</label>
                        @if(count($c_group_by) > 0)
                        <datalist id="options">
                          @foreach($c_group_by->unique("name") as $c_group)
                          <option value="{{$c_group->name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" autocomplete="off">
                        <label class="form-label">Branch name</label>
                        @if(count($c_group_by) > 0)
                        <datalist id="options_branch">
                          @foreach($c_group_by->unique("branch_name") as $c_group)
                          <option value="{{$c_group->branch_name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
            </div>

          <div class="col-md-4">
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control show-tick" name="region_id" id="region_id">
                           <option value="">-- Please select region --</option>
                           <?php foreach ($regions as $region): ?>
                            <option value="{{ $region->id }}" data-themeid="{{ $region->id }}" {{ old('region_id') == "$region->id" ? 'selected' : '' }}>{{ ucwords($region->name) }}</option>
                           <?php endforeach; ?> 
                        </select>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary waves-effect"  type="submit">Filter</button>
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
      </form>
    </div>

  </div>
</div>

@endsection


@section('scripts')

@stop