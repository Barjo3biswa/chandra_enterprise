@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($client->name) }} @if(isset($client->client_code))({{ $client->client_code }})@endif<small>Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    <table class="table table-condensed">
                        <thead>
                            <!--<tr>
                                <th></th>
                                <th></th>
                            </tr>-->
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 16%;">Name : </th>
                                <td>{{ ucwords($client->name) }}</td>

                                <th scope="row" style="width: 16%;">Branch name : </th>
                                <td>{{ ucwords($client->branch_name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 16%;">Zone name : </th>
                                <td>
                                    @if($client->zone_id != null)
                                    {{ ucwords($client->zone->name) }}
                                    @endif
                                </td>

                                <th scope="row" style="width: 16%;">Region name : </th>
                                <td>
                                    @if($client->region_id != null)
                                    {{ ucwords($client->region->name) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 16%;">Region address : </th>
                                <td>
                                    @if($client->region_id != null)
                                    {{ ucwords($client->region->address) }}
                                    @endif
                                </td>

                                <th scope="row" style="width: 16%;">Pin code : </th>
                                <td>{{ $client->pin_code }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 16%;">Email : </th>
                                <td>{{ $client->email }}</td>

                                <th scope="row" style="width: 16%;">Phone no : </th>
                                <td>{{ $client->ph_no }}</td>
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 16%;">State : </th>
                                <td>{{ ucwords($client->state) }}</td>

                                <th scope="row" style="width: 16%;">District : </th>
                                <td>{{ ucwords($client->district) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 25%;">Address : </th>
                                <td>{{ $client->address }}</td>
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 25%;">Remarks : </th>
                                <td>{{ $client->remarks }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4>Contact Person Details</h4>
                    <table class="table table-condensed">

                        <thead>
                            <tr>
                                <th>Contact person 1 name</th>
                                <th>Contact person 1 email</th>
                                <th>Contact person 1 phone no</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ ucwords($client->contact_person_1_name) }}</td>
                                <td>{{ $client->contact_person_1_email }}</td>
                                <td>{{ $client->contact_person_1_ph_no }}</td>
                            </tr>
                        </tbody>

                        <thead>
                            <tr>
                                <th>Contact person 2 name</th>
                                <th>Contact person 2 email</th>
                                <th>Contact person 2 phone no</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ ucwords($client->contact_person_2_name) }}</td>
                                <td>{{ $client->contact_person_2_email }}</td>
                                <td>{{ $client->contact_person_2_ph_no }}</td>
                            </tr>
                        </tbody>

                    </table>

                @if(count($assign_product) > 0)
                <h4>Assigned Product Details </h4>
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Serial no</th>
                            <th>Date of install </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($assign_product as $assgn_p)
                        <tr>
                            <td>{{ $i }}</td>
                            <td> 
                                <span data-toggle="popover" title="{{ ucwords($assgn_p->product->name) }} Detail" data-content= "Code : {{ $assgn_p->product->product_code }} <br> Model no : {{ $assgn_p->product->model_no }} <br> Brand : {{  $assgn_p->product->brand }} <br>  Group : {{ ucwords($assgn_p->product->group->name) }} <br> Company : {{ $assgn_p->company->name }}">{{ $assgn_p->product->name }}</span>
                            </td>


                           <td>{{ $assgn_p->product->serial_no }}</td>
                           <td>
                                @if($assgn_p->date_of_install != "0000-00-00")
                                {{ date('d M, Y', strtotime($assgn_p->date_of_install)) }}
                                @endif
                            </td>
                        </tr>
                        @php $i++ @endphp
                        @endforeach
                    </tbody>
                </table>
                @endif


                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({
            placement: 'top',
            trigger: 'hover',
            html:true
        });
    });
</script>
@stop