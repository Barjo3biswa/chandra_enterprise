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
                    {{ ucwords($company->name) }} <small>Details</small>
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
                                <td>{{ ucwords($company->name) }}</td>

                                <th scope="row" style="width: 18%;">Email : </th>
                                <td>{{ $company->email }}</td>
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 18%;">Phone no : </th>
                                <td>{{ $company->ph_no }}</td>

                                <th scope="row" style="width: 18%;">Pin code : </th>
                                <td>{{ $company->pin_code }}</td>
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 18%;">State : </th>
                                <td>{{ ucwords($company->state) }}</td>

                                <th scope="row" style="width: 18%;">District : </th>
                                <td>{{ ucwords($company->district) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">GST no : </th>
                                <td>{{ $company->gst_no }}</td>

                                <th scope="row" style="width: 18%;">Pan card no : </th>
                                <td>{{ $company->pan_card_no }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Address : </th>
                                <td>{{ $company->address }}</td>
                            </tr>
                            <tr>
                            	<th scope="row" style="width: 18%;">Remarks : </th>
                                <td>{{ $company->remarks }}</td>
                            </tr>
                        </tbody>
                    </table>

                @if(count($products) >0)
                <h4>Products under {{ ucwords($company->name) }} details </h4>
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Serial no</th>
                            <th>Date of purchase </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($products as $key => $product)
                        <tr>
                            <td>{{ $key+ 1 + ($products->perPage() * ($products->currentPage() - 1)) }}</td>
                            <td>
                                <span data-toggle="popover" title="{{ ucwords($product->name) }} Detail" data-content= "Code : {{ $product->product_code }} <br> Model no : {{ $product->model_no }} <br> Brand : {{  $product->brand }} <br>  Group : {{ ucwords($product->group->name) }} <br> Company : @if($product->company_id != null){{ $product->company->name }}@endif">{{ $product->name }}</span>
                            </td>

                            <td>{{ $product->serial_no }}</td>
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
                {{$products->render()}}
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