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
                    {{ $client_bill->bill_no }} <small>Details</small>
                </h2>
            </div>
            <div class="body table-responsive">


                <table class="table table-condensed">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="width: 15%;">Client Name : </th>
                            <td>{{ ucwords($client_bill->client->name) }}</td>
                       
                            <th style="width: 15%;">Branch Name : </th>
                            <td>{{ ucwords($client_bill->client->branch_name) }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">Zone Name : </th>
                            <td>{{ ucwords($client_bill->client->zone->name) }}</td>

                            <th style="width: 15%;">Region Name : </th>
                            <td>{{ ucwords($client_bill->client->region->name) }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">Group Name : </th>
                            <td>{{ ucwords($client_bill->group->name) }}</td>

                            <th style="width: 15%;">Company Name : </th>
                            <td>{{ ucwords($client_bill->company->name) }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">Bill No : </th>
                            <td>{{ ucwords($client_bill->bill_no) }}</td>

                            <th style="width: 15%;">Bill Amount : </th>
                            <td>{{ $client_bill->bill_amount }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">Bill Date : </th>
                            <td>
                            	@if($client_bill->bill_date != "0000-00-00")
                                {{ date('d M, Y', strtotime($client_bill->bill_date)) }}
                                @endif
                            </td>

                            <th style="width: 15%;">Bill Pay By Date : </th>
                            <td>
                            	@if($client_bill->pay_by_date != "0000-00-00")
                                {{ date('d M, Y', strtotime($client_bill->pay_by_date)) }}
                                @endif
                            </td>
                        </tr>

				    </tbody>
                </table>

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
                        @foreach($client_bill->bill_transaction->where('follow_up','==','0') as $bill_trans)
                        <tr>
                            <td>{{ $i }}.</td>
                            <td>{{ ucwords($bill_trans->product_name) }}</td>
                            <td>{{ $bill_trans->product_quantity }}</td>
                            <td>{{ $bill_trans->product_price }}</td>
                        </tr>
                        @php $i++ @endphp
                       @endforeach
                    </tbody>
                </table>


            @if($client_bill->bill_transaction->where('follow_up','==','1')->count())
{{-- {{dump($client_bill->bill_transaction->where('follow_up','==','1'))}}
@php die(); @endphp --}}

                <h4>Bill Follow up Details </h4>
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Next Pay By Date</th>
                            <th>Bill Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($client_bill->bill_transaction->where('follow_up','==','1') as $bill_tran)
                        <tr>
                            <td>{{ $i }}.</td>
                            <td>
                                @if($bill_tran->next_pay_by_date != "0000-00-00")
                                {{ date('d M, Y', strtotime($bill_tran->next_pay_by_date)) }}
                                @endif
                            </td>
                            <td>{{ $bill_tran->bill_remarks }}</td>
                        </tr>
                        @php $i++ @endphp
                       @endforeach
                    </tbody>
                </table>
             @endif


                {{-- @if(isset($next_pay_by_date))
                <h5>Next Pay By Date</h5>
                    <span>
                        @if($next_pay_by_date->next_pay_by_date != "0000-00-00")
                        {{ date('d M, Y', strtotime($next_pay_by_date->next_pay_by_date)) }}
                        @endif
                    </span>
                @endif --}}
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

@stop