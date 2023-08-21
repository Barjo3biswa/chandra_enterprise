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
                    {{ ucwords($client_name->name) }} <small>Details</small>
                </h2>
            </div>
            <div class="body table-responsive">


                <table class="table table-condensed">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 15%;">Client Name : </th>
                            <td>{{ ucwords($client_name->name) }}</td>
                       
                            <th scope="row" style="width: 15%;">Branch Name : </th>
                            <td>{{ ucwords($client_name->branch_name) }}</td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 15%;">Zone Name : </th>
                            <td>{{ ucwords($client_name->zone->name) }}</td>
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
                                <td>{{ ucwords($client_name->contact_person_1_name) }}</td>
                                <td>{{ $client_name->contact_person_1_email }}</td>
                                <td>{{ $client_name->contact_person_1_ph_no }}</td>
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
                                <td>{{ ucwords($client_name->contact_person_2_name) }}</td>
                                <td>{{ $client_name->contact_person_2_email }}</td>
                                <td>{{ $client_name->contact_person_2_ph_no }}</td>
                            </tr>
                        </tbody>

                    </table>

                <h4>Assigned Product Details </h4>
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Serial no</th>
                            <th>Date of install </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($assign_product as $assgn_p)
                        <tr>
                            <td>{{ $i }}</td>
                            <td><span data-toggle="popover" title="{{ ucwords($assgn_p->product->name) }} Detail" data-content= "Code : {{ $assgn_p->product->product_code }} <br> Model no : {{ $assgn_p->product->model_no }} <br> Brand : {{  $assgn_p->product->brand }} <br>  Group : {{ ucwords($assgn_p->product->group->name) }} <br> Company : {{ $assgn_p->company->name }}">

                                {{ $assgn_p->product->name }}
                            </span></td>
                            <td>{{ $assgn_p->product->serial_no }}</td>
                           
                            <td>
                                @if($assgn_p->date_of_install != null)
                                {{ date('d M, Y', strtotime($assgn_p->date_of_install)) }}
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    {{-- <a href="" class="btn bg-lime waves-effect">Inactive</a> --}}
                                    <a href="{{ route('transfer-assign-product-to-another-client',Crypt::encrypt($assgn_p->id)) }}" title="Transfer product" class="btn bg-light-green waves-effect">Transfer</a>
                                </div>
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