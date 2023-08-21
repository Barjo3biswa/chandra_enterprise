@extends('layouts.front')


@section('styles')
<style>
    .deco_none  {
        text-decoration: none!important;
        cursor: pointer!important;
    }
</style>
@stop

@section('content')


        <div class="block-header">
            <h2>Dashboard</h2>
        </div>

        <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    @if(Auth::user()->can('view company'))
                    <a href="{{ route('view-all-company') }}" class="deco_none">
                        <div class="info-box-3 bg-pink hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">business</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL COMPANIES</div>
                                <div class="number">{{ $tot_company }}</div>
                            </div>
                        </div>
                    </a>
                    @else
                        <div class="info-box-3 bg-pink hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">business</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL COMPANIES</div>
                                <div class="number">{{ $tot_company }}</div>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    @if(Auth::user()->can('view product'))
                    <a href="{{ route('view-all-product') }}" class="deco_none">
                        <div class="info-box-3 bg-blue hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">devices_other</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL PRODUCTS</div>
                                <div class="number">{{ $tot_products }}</div>
                            </div>
                        </div>
                    </a>
                    @else
                        <div class="info-box-3 bg-blue hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">devices_other</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL PRODUCTS</div>
                                <div class="number">{{ $tot_products }}</div>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    @if(Auth::user()->can('view user'))
                    <a href="{{ route('view-all-users') }}" class="deco_none">
                        <div class="info-box-3 bg-light-green hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">supervisor_account</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL USERS</div>
                                <div class="number">{{ $tot_users }}</div>
                            </div>
                        </div>
                    </a>
                    @else
                        <div class="info-box-3 bg-light-green hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">supervisor_account</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL USERS</div>
                                <div class="number">{{ $tot_users }}</div>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                @if(Auth::user()->can('view client'))
                    <a href="{{ route('view-all-client') }}" class="deco_none">
                        <div class="info-box-3 bg-amber hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">business_center</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL CLIENTS</div>
                                <div class="number">{{ $tot_clients }}</div>
                            </div>
                        </div>
                    </a>
                    @else
                        <div class="info-box-3 bg-amber hover-zoom-effect">
                            <div class="icon">
                                <i class="material-icons">business_center</i>
                            </div>
                            <div class="content">
                                <div class="text">TOTAL CLIENTS</div>
                                <div class="number">{{ $tot_clients }}</div>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
            </div>

    <div class="row clearfix">

     @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('view-all-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-teal hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL COMPLAINTS</div>
                        <div class="number">{{ $tot_complaints }}</div>
                    </div>
                </div>
            </a>

        </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('view-all-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-deep-orange hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL PENDING COMPLAINTS</div>
                        <div class="number">{{ $tot_pendind_complaints }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('view-all-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-brown hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL CLOSED COMPLAINTS</div>
                        <div class="number">{{ $tot_closed_complaints }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('view-all-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-grey hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL ASSIGNED COMPLAINTS</div>
                        <div class="number">{{ $tot_assigned_complaints }}</div>
                    </div>
                </div>
            </a>
        </div>

    @endif

    @if(Auth::user()->user_type == 3)
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('engg-view-all-assigned-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-teal hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL ASSIGNED COMPLAINTS</div>
                        <div class="number">{{ $engg_tot_assigned_complaints }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('view-closed-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-amber hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL CLOSED COMPLAINTS</div>
                        <div class="number">{{ $engg_tot_closed_complaints }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('engg-view-all-complaints') }}" class="deco_none">
                <div class="info-box-3 bg-light-green hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL COMPLAINTS</div>
                        <div class="number">{{ $engg_tot_complaints }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('view-all-assigned-zone') }}" class="deco_none">
                <div class="info-box-3 bg-brown hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL ASSIGNED CLIENTS</div>
                        <div class="number">{{ $tot_assigned_clients }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('view-all-daily-service-report') }}" class="deco_none">
                <div class="info-box-3 bg-grey hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL DSR</div>
                        <div class="number">{{ $tot_dsr }}</div>
                    </div>
                </div>
            </a>
        </div>

         <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('view-all-assigned-clients-amc') }}" class="deco_none">
                    <div class="info-box-3 bg-blue hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">build</i>
                        </div>
                        <div class="content">
                            <div class="text">AMC</div>                            
                            <div class="number">{{ $amc_count }}</div>
                        </div>
                    </div>
                </a>
            </div>

        @if(isset($monthly_amcs) && $monthly_amcs != "")
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
            <div class="header">
                <h2>Amc Details for <span class="font-underline col-teal">{{ date('M, Y') }}</span></h2>
            </div>
            <div class="body">

            <table class="table table-condensed">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Branch name</th>
                        <th>AMC request date</th>
                       
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                    
                    @foreach($monthly_amcs as $key1 => $value1)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ ucwords($value1->client_master->client->name) }}</td>
                        <td>{{ ucwords($value1->client_master->client->branch_name) }}</td>
                        <td>{{ date('d M,Y',strtotime($value1->amc_rqst_date)) }}</td>
                        <td>{{ $value1->remarks }}</td>
                    </tr>
                    @php $i++ @endphp
                    @endforeach

                </tbody>
            </table>
            </div>
        </div>
        @endif



        </div>


     @endif

        
</div>

@endsection


@section('scripts')

@stop