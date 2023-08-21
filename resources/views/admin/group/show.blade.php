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
                    {{ ucwords($grp->name) }} <small>Details</small>
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
                                <td>{{ ucwords($grp->name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Remarks : </th>
                                <td>{{ $grp->remarks }}</td>
                            </tr>
                           
                        </tbody>
                    </table>

                @if(count($products) >0)
                <h4>Products under {{ ucwords($grp->name) }} details </h4>
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Serial no</th>
                            <th>Code</th>
                            <th>Model</th>
                            <th>Brand</th>
                            <th>Group</th>
                            <th>Company</th>
                            <th>Date of purchase </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->serial_no }}</td>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->model_no }}</td>
                            <td>{{ $product->brand }}</td>
                            <td>{{ ucwords($product->group->name) }}</td>
                            @if($product->company_id != null)
                            <td>{{ $product->company->name }}</td>
                            @else
                            <td></td>
                            @endif
                            <td>
                                @if($product->date_of_purchase != "0000-00-00")
                                {{ date('d M, Y', strtotime($product->date_of_purchase)) }}
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

@stop