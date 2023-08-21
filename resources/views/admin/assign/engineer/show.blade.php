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
                    {{ ucwords($assign_eng_name->user->first_name.' '.$assign_eng_name->user->middle_name.' '.$assign_eng_name->user->last_name) }}
                    <small>Details</small>

                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    <table class="table table-condensed">
                        <thead>

                        </thead>
                        <tbody>

                            <tr>
                                <th scope="row" style="width: 18%;">Engineer Name : </th>
                                <td>{{ ucwords($assign_eng_name->user->first_name.' '.$assign_eng_name->user->middle_name.' '.$assign_eng_name->user->last_name) }}</td>

                                <th>Zone name</th>
                                <td>{{ ucwords($assign_eng_name->zone->name) }}</td>
                            </tr>
                            
                                @php $j=1; @endphp
                                @foreach($assign_eng as $assign_engineer)
                               
                                <tr>

                                    <th style="width: 18%;">{{ $j }}. Client Name</th>
                                    <td>
                                        <span data-toggle="popover" title="{{ $assign_engineer->client->name }} Detail" data-content= "Zone : {{ ucwords($assign_engineer->client->zone->name) }} <br> Email : {{ $assign_engineer->client->email }} <br> Ph no : {{  $assign_engineer->client->ph_no }} <br>  Address : {{ $assign_engineer->client->address }} <br> Remarks : {{ $assign_engineer->client->remarks }}">{{ $assign_engineer->client->name }}</span>
                                    </td>
                                
                                    <th style="width: 18%;">Branch Name</th>
                                    <td>{{ $assign_engineer->client->branch_name }}</td>
                                </tr>

                                <tr>
                                    <th><h5>Assigned Products</h5></th>
                                    <td>
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
                                                @php $i=1;  @endphp
                                                @foreach($assign_engineer->client->assigned_products as $key => $assgn_p)
                                                    <tr>
                                                    <td>{{ $i }}.</td>
                                                    <td><span data-toggle="popover" title="{{ $assgn_p->product->name }} Detail" data-content= "Code : {{ $assgn_p->product->product_code }} <br> Model no : {{ $assgn_p->product->model_no }} <br> Brand : {{ $assgn_p->product->brand }} <br>  Company : {{ $assgn_p->product->company_name }}"> {{ $assgn_p->product->name }} </span></td>

                                                    <td>{{ $assgn_p->serial_no }}</td>

                                                    <td>
                                                        @if($assgn_p->date_of_install != null)
                                                        {{ date('d M, Y', strtotime($assgn_p->date_of_install)) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php $i++; @endphp
                                             @endforeach
                                            </tbody>
                                        </table></td>
                                </tr>
                                @php $j++; @endphp
                                @endforeach
                            
                        </tbody>

                    </table>
  
                    {{-- <table class="table table-condensed">
                       @php $i=1 @endphp
                            @foreach($assign_eng as $assign_engineer)

                            <thead>                               
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Branch name</th>
                            </tr>
                        </thead>
                         <tbody>
                            <tr>    <td>  <h4>  Client Details:</h4>  </td></tr>
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    <span data-toggle="popover" title="{{ $assign_engineer->client->name }} Detail" data-content= "Zone : {{ ucwords($assign_engineer->client->zone->name) }} <br> Email : {{ $assign_engineer->client->email }} <br> Ph no : {{  $assign_engineer->client->ph_no }} <br>  Address : {{ $assign_engineer->client->address }} <br> Remarks : {{ $assign_engineer->client->remarks }}">{{ $assign_engineer->client->name }}</span>
                                </td>

                                <td>{{ $assign_engineer->client->branch_name }}</td>
                               
                            </tr>
                                
                            <tr><td>
                                        <h5>Assigned Products</h5>
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
                                                @foreach($assign_engineer->client->assigned_products as $key => $assgn_p)
                                                    <tr>
                                                    <td>1</td>
                                                    <td><span data-toggle="popover" title="{{ $assgn_p->product->name }} Detail" data-content= "Code : {{ $assgn_p->product->product_code }} <br> Model no : {{ $assgn_p->product->model_no }} <br> Brand : {{ $assgn_p->product->brand }} <br>  Company : {{ $assgn_p->product->company_name }}"> {{ $assgn_p->product->name }} </span></td>

                                                    <td>{{ $assgn_p->serial_no }}</td>

                                                    <td>
                                                        @if($assgn_p->product->date_of_install != "0000-00-00")
                                                        {{ date('d M, Y', strtotime($assgn_p->product->date_of_install)) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                             @endforeach
                                            </tbody>
                                        </table>

                            </td></tr>
                                    
                                    
                            @php $i++ @endphp
                            @endforeach
                        </tbody>
                    </table> --}}

{{-- ###################################################################################### --}}

                    {{-- <h4>Product/Machine Details</h4>
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
                            @foreach($assign_client_details as $assgn_p)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><span data-toggle="popover" title="{{ $assgn_p->name }} Detail" data-content= "Code : {{ $assgn_p->product_code }} <br> Model no : {{ $assgn_p->model_no }} <br> Brand : {{ $assgn_p->brand }} <br>  Company : {{ $assgn_p->company_name }}"> {{ $assgn_p->name }} </span></td>

                                <td>{{ $assgn_p->serial_no }}</td>

                                <td>
                                    @if($assgn_p->date_of_install != "0000-00-00")
                                    {{ date('d M, Y', strtotime($assgn_p->date_of_install)) }}
                                    @endif
                                </td>
                            </tr>
                            @php $i++ @endphp
                            @endforeach
                        </tbody>
                    </table> --}}

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