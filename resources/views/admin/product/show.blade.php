@extends('layouts.front')


@section('styles')
<style>
    .no_style a{
        text-decoration: none!important;
        color: #fff!important;
        font-weight: bold!important;
        font-size: 15px!important;
    }
</style>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($product->name) }}<small> <span class="no_style">@if($product->company_id != null)<a href="{{ route('get-company-product-detail',Crypt::encrypt($product->company_id)) }}">{{ ucwords($product->company->name) }}</a>@endif</span> Details</small>
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
                                <th scope="row" style="width: 18%;">Name : </th>
                                <td>{{ ucwords($product->name) }}</td>

                                <th scope="row" style="width: 10%;">Warranty : </th>
                                @if($product->warranty != null)
                                <td>{{ $product->warranty }} years</td>
                                @endif

                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Product code : </th>
                                <td>{{ ucwords($product->product_code) }}</td>

                                <th scope="row" style="width: 18%;">Serial no : </th>
                                <td>{{ $product->serial_no }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Date of Installation : </th>
                                <td>
                                    @if($product->date_of_purchase != "0000-00-00" && $product->date_of_purchase)
                                    {{ date('d M, Y', strtotime($product->date_of_purchase)) }}
                                    @endif
                                </td>

                                <th scope="row" style="width: 18%;">Menufacture date : </th>
                                <td>
                                    @if($product->manufacture_date != "0000-00-00")
                                    {{ date('d M, Y', strtotime($product->manufacture_date)) }}
                                    @endif
                                </td>
                            </tr>
                           
                           <tr>
                                <th scope="row" style="width: 18%;">Brand name : </th>
                                <td>{{ ucwords($product->brand_id) }}</td>

                                <th scope="row" style="width: 18%;">Company name : </th>
                                @if($product->company_id != null)
                                <td>{{ ucwords($product->company->name) }}</td>
                                @endif
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 18%;">Model no : </th>
                                <td>{{ $product->model_no }}</td>

                                <th scope="row" style="width: 18%;">Equipment no : </th>
                                <td>{{ ucwords($product->equipment_no) }}</td>
                            </tr>
                            <tr>
                            	
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 18%;">Group name : </th>
                                @if($product->group_id != null)
                                <td>{{ ucwords($product->group->name) }}</td>
                                @endif

                                <th scope="row" style="width: 18%;">Sub group name : </th>
                                @if($product->subgroup_id != null)
                                <td>{{ ucwords($product->subgroup->name) }}</td>
                                @endif
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

@stop