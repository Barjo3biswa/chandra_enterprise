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
        <h2>Edit Assign Zone To Engineer</h2>
        <ul class="header-dropdown m-r--5">
          @if(Auth::user()->can('view assign-engineer'))
          <li><a href="{{ route('view-all-assign-engineer') }}" class="btn btn-success">View all</a></li>
          @endif
        </ul>
      </div>
      <div class="body">
        
          <div class="row">

            <div class="col-md-6">
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

          </div>

          <div class="row" id="auto_products_view1">
            <div class="col-md-12">
              <div>


                <div class="body table-responsive">
 
                  <table class="table" id="c_details1">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Client name</th>
                        <th>Branch name</th>
                        <th>Zone</th>
                        <th>Email</th>
                        <th>Ph no</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $i=1 @endphp
                      @foreach($assign_eng as $asgn_eng)
                        <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $asgn_eng->client->name }}</td>
                          <td>{{ $asgn_eng->client->branch_name }}</td>
                          <td>{{ $asgn_eng->client->zone->name }}</td>
                          <td>{{ $asgn_eng->client->email }}</td>
                          <td>{{ $asgn_eng->client->ph_no }}</td>
                          <td>
                            @if(Auth::user()->can('edit client assign-engineer'))
                            <form action="{{ route('assign-client-engineer.edit', Crypt::encrypt($assign_eng_name->engineer_id)) }}" method="get">
                              {{ csrf_field() }}
                              <input type="hidden" name="engineer_id" value="{{ $assign_eng_name->engineer_id }}">

                              <input type="hidden" name="client_id1" value="{{ $asgn_eng->client_id }}">

                              <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                            </form>
                            @endif
                          </td>

                          <td>
                            @if(Auth::user()->can('delete client assign-engineer'))
                            <form action="{{ route('assign-client-to-engineer.delete', Crypt::encrypt($assign_eng_name->engineer_id)) }}" method="get">
                              {{ csrf_field() }}

                              <input type="hidden" name="engineer_id1" value="{{ $assign_eng_name->engineer_id }}">
                              <input type="hidden" name="client_id" value="{{ $asgn_eng->client_id }}">

                              <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></button>
                            </form>
                            @endif
                          </td>
                        </tr>
                        @php $i++ @endphp
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

       </div>
    </div>
  </div>
</div>




@endsection


@section('scripts')
<script>


</script>
@stop

