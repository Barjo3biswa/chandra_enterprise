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
                    {{ $engg_bill_follow->client_bill->bill_no ?? 'N/A' }} <small>Details</small>
                </h2>
            </div>
            <div class="body table-responsive">

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="width: 15%;">Client Name : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->client->name ?? 'N/A') }}</td>
                               
                                    <th style="width: 15%;">Branch Name : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->client->branch_name ?? 'N/A') }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 15%;">Zone Name : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->client->zone->name ?? 'N/A') }}</td>

                                    <th style="width: 15%;">Region Name : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->client->region->name ?? 'N/A') }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 15%;">Group Name : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->group->name ?? 'N/A') }}</td>

                                    <th style="width: 15%;">Company Name : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->client->zone->name ?? 'N/A') }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 15%;">Bill No : </th>
                                    <td>{{ ucwords($engg_bill_follow->client_bill->bill_no ?? 'N/A') }}</td>

                                    <th style="width: 15%;">Bill Amount : </th>
                                    <td>{{ $engg_bill_follow->client_bill->bill_amount ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 15%;">Bill Date : </th>
                                    <td>
                                    	@if($engg_bill_follow->client_bill && $engg_bill_follow->client_bill->bill_date != "0000-00-00")
                                        {{ date('d M, Y', strtotime($engg_bill_follow->client_bill->bill_date )) ?? 'N/A' }}
                                        @endif
                                    </td>

                                    <th style="width: 15%;">Bill Pay By Date : </th>
                                    <td>
                                    	@if($engg_bill_follow->client_bill && $engg_bill_follow->client_bill->pay_by_date != "0000-00-00")
                                        {{ date('d M, Y', strtotime($engg_bill_follow->client_bill->pay_by_date)) ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>

        				    </tbody>
                        </table>
                    </div>

                        <div class="col-md-12">
                            <h4>Outstanding Product Details </h4>
                                <table class="table table-condensed">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Product quantity</th>
                                            <th>Product price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @if($engg_bill_follow->client_bill)
                                        @foreach($engg_bill_follow->client_bill->bill_transaction as $bill_trans)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ ucwords($bill_trans->product_name) }}</td>
                                            <td>{{ $bill_trans->product_quantity }}</td>
                                            <td>{{ $bill_trans->product_price }}</td>
                              
                                        </tr>
                                        @php $i++ @endphp
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>


                <div class="row">
                    
                    <div class="col-md-4">
                        <h5 class="font-bold col-blue-grey">Bill Report By</h5>
                        <span class="col-blue-grey">{{ ucwords($engg_bill_follow->engineer->first_name.' '.$engg_bill_follow->engineer->middle_name.' '.$engg_bill_follow->engineer->last_name)  }}</span>
                    </div>

                    <div class="col-md-4">
                        @if(isset($engg_bill_follow->bill_status))
                        <h5 class="font-bold col-blue-grey">Bill Status</h5>
                            <span class="col-blue-grey">
                                @if($engg_bill_follow->bill_status == 1)
                                Yet to clear payment
                                @endif
                            </span>

                            <span>
                                @if($engg_bill_follow->bill_status == 2)
                                Payment cleared
                                @endif
                            </span>
                        @endif
                    </div>

                    <div class="col-md-4">
                        @if(isset($engg_bill_follow->next_pay_by_date))
                        <h5 class="font-bold col-blue-grey">Next Pay By Date</h5>
                        <span class="col-blue-grey">
                            @if($engg_bill_follow->next_pay_by_date != "0000-00-00")
                            {{ date('d M, Y', strtotime($engg_bill_follow->next_pay_by_date)) }}
                            @endif
                        </span>
                        @endif

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="font-bold col-blue-grey">Bill Remarks</h5>
                        <span class="col-blue-grey">{{ $engg_bill_follow->bill_remarks }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

@stop