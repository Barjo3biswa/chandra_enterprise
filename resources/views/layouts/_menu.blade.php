<!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="{{request()->is('dashboard')? 'active' : '' }}{{request()->is('dashboard/*')? 'active' : '' }} || {{request()->is('home')? 'active' : '' }}{{request()->is('home/*')? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>


                    <li class="{{request()->is('change-password')? 'active' : '' }}{{request()->is('change-password/*')? 'active' : '' }}">
                        <a href="{{ route('change-password') }}">
                            <i class="material-icons">lock_open</i>
                            <span>Change password</span>
                        </a>
                    </li>
                   
 
                 
                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    <li class="{{request()->is('group')? 'active' : '' }}{{request()->is('group/*')? 'active' : '' }} || {{request()->is('sub-group')? 'active' : '' }}{{request()->is('sub-group/*')? 'active' : '' }} || {{request()->is('zones')? 'active' : '' }}{{request()->is('zones/*')? 'active' : '' }} || {{request()->is('user')? 'active' : '' }}{{request()->is('user/*')? 'active' : '' }} || {{request()->is('products')? 'active' : '' }}{{request()->is('products/*')? 'active' : '' }} || {{request()->is('companies')? 'active' : '' }}{{request()->is('companies/*')? 'active' : '' }} || {{request()->is('clients')? 'active' : '' }}{{request()->is('clients/*')? 'active' : '' }} || {{request()->is('menu')? 'active' : '' }}{{request()->is('menu/*')? 'active' : '' }} || {{request()->is('sub-menu')? 'active' : '' }}{{request()->is('sub-menu/*')? 'active' : '' }} || {{request()->is('role')? 'active' : '' }}{{request()->is('role/*')? 'active' : '' }} || {{request()->is('tool-kits')? 'active' : '' }}{{request()->is('tool-kits/*')? 'active' : '' }} || {{request()->is('regions')? 'active' : '' }}{{request()->is('regions/*')? 'active' : '' }} || {{request()->is('spare-parts')? 'active' : '' }}{{request()->is('spare-parts/*')? 'active' : '' }}">

                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>Master</span>
                        </a>
                        <ul class="ml-menu">

                            @if(Auth::user()->can('view group'))
                            <li class="{{request()->is('group')? 'active' : '' }}{{request()->is('group/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-groups') }}">Product Group</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view sub_group'))
                            <li class="{{request()->is('sub-group')? 'active' : '' }}{{request()->is('sub-group/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-sub-groups') }}">Product Sub Group</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view company'))
                            <li class="{{request()->is('companies')? 'active' : '' }}{{request()->is('companies/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-company') }}">Company</a>
                            </li>
                            @endif
  
                            @if(Auth::user()->can('view district'))
                            <li class="{{request()->is('districts')? 'active' : '' }}{{request()->is('districts/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-district') }}">District</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view product'))
                            <li class="{{request()->is('products')? 'active' : '' }}{{request()->is('products/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-product') }}">Product</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view zone'))
                            <li class="{{request()->is('zones')? 'active' : '' }}{{request()->is('zones/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-zones') }}">Zone</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view region'))
                            <li class="{{request()->is('regions')? 'active' : '' }}{{request()->is('regions/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-regions') }}">Region</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view client'))
                            <li class="{{request()->is('clients')? 'active' : '' }}{{request()->is('clients/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-client') }}">Client</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view user'))
                            <li class="{{request()->is('user')? 'active' : '' }}{{request()->is('user/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-users') }}">User</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view role'))
                            <li class="{{request()->is('role')? 'active' : '' }}{{request()->is('role/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-roles') }}">Permission</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view tool-kit'))
                            <li class="{{request()->is('tool-kits')? 'active' : '' }}{{request()->is('tool-kits/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-tool-kit') }}">Tool kit</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view spare-parts'))
                            <li class="{{request()->is('spare-parts')? 'active' : '' }}{{request()->is('spare-parts/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-spare-parts') }}">Spare part</a>
                            </li>
                            @endif
          
                       </ul>
                    </li>
                @endif


                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    <li class="{{request()->is('assign-product-to-client')? 'active' : '' }}{{request()->is('assign-product-to-client/*')? 'active' : '' }} || {{request()->is('assign-engineer')? 'active' : '' }}{{request()->is('assign-engineer/*')? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment_ind</i>
                            <span>Assign</span>
                        </a>
                        <ul class="ml-menu">
                            @if(Auth::user()->can('view assign-product-to-clien'))
                            <li class="{{request()->is('assign-product-to-client')? 'active' : '' }}{{request()->is('assign-product-to-client/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-assign-client') }}">Product to client</a>
                            </li>
                            @endif
                            @if(Auth::user()->can('view assign-engineer'))
                            <li class="{{request()->is('assign-engineer')? 'active' : '' }}{{request()->is('assign-engineer/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-assign-engineer') }}">Zones to engineer</a>
                            </li>
                            @endif
                            
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->user_type == 3 || Auth::user()->user_type == 2 || Auth::user()->user_type == 1)
                    @if(Auth::user()->can('view engineer-all-assigned-zone'))
                    <li class="{{request()->is('all-assigned-zone')? 'active' : '' }}{{request()->is('all-assigned-zone/*')? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment_ind</i>
                            <span>Assigned</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{request()->is('all-assigned-zone')? 'active' : '' }}{{request()->is('all-assigned-zone/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-assigned-zone') }}">All assigned zone</a>
                            </li>
         
                        </ul>
                    </li>
                    @endif
                @endif


                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                @if(Auth::user()->can('view complaint'))
                    <li class="{{request()->is('complaint-register')? 'active' : '' }}{{request()->is('complaint-register/*')? 'active' : '' }}">
                        <a href="{{ route('view-all-complaints') }}">
                            <i class="material-icons">layers</i>
                            <span>Complaint Register</span>
                        </a>
                    </li>
                @endif
                @endif

                @if( Auth::user()->user_type == 3 || Auth::user()->user_type == 2 )
                @if(Auth::user()->can('view complaint'))
                    <li class="{{request()->is('complaint-details')? 'active' : '' }}{{request()->is('complaint-details/*')? 'active' : '' }}">
                        <a href="{{ route('engg-view-all-complaints') }}">
                            <i class="material-icons">layers</i>
                            <span>Complaint Register</span>
                        </a>
                    </li>
                @endif
                @endif

                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    <li class="{{request()->is('reports')? 'active' : '' }}{{request()->is('reports/*')? 'active' : '' }} || {{request()->is('service-reports')? 'active' : '' }}{{request()->is('service-reports/*')? 'active' : '' }} || {{request()->is('engineer-outstanding-bill')? 'active' : '' }}{{request()->is('engineer-outstanding-bill/*')? 'active' : '' }} || {{request()->is('engineer-track-location')? 'active' : '' }}{{request()->is('engineer-track-location/*')? 'active' : '' }} || {{request()->is('user-stockin-reports')? 'active' : '' }}{{request()->is('user-stockin-reports/*')? 'active' : '' }} || {{request()->is('user-assigned-toolkit-reports')? 'active' : '' }}{{request()->is('user-assigned-toolkit-reports/*')? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Reports</span>
                        </a>
                        <ul class="ml-menu">
                            @if(Auth::user()->can('view all-complaint-reports'))
                            <li class="{{request()->route()->getName() == "view-all-complaint-reports" ? 'active' : '' }}">
                                <a href="{{ route('view-all-complaint-reports') }}">Complaint</a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->can('view all-service-reports'))
                            <li class="{{request()->is('service-reports') && request()->get('type') == "breakdown" ?  'active' : '' }}{{request()->is('service-reports/*')? 'active' : '' }}">
                                <a href="{{ route('get-all-service-report-detail', ["type" => "breakdown"]) }}">Breakdown</a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->can('view all-service-reports'))
                            <li class="{{request()->is('service-reports')  && request()->get('type') == "preventive" ? 'active' : '' }}{{request()->is('service-reports/*')? 'active' : '' }}">
                                <a href="{{ route('get-all-service-report-detail', ["type" => "preventive"]) }}">Preventive Maintenance</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view outstanding-bill-reports'))
                            <li class="{{request()->is('engineer-outstanding-bill')? 'active' : '' }}{{request()->is('engineer-outstanding-bill/*')? 'active' : '' }}">
                                <a href="{{ route('engineer-view-outstanding-bill') }}">Bill Followups</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view engineer-track-location'))
                            <li class="{{request()->is('engineer-track-location')? 'active' : '' }}{{request()->is('engineer-track-location/*')? 'active' : '' }}">
                                <a href="{{ route('view-engineer-track-location') }}">Track Engineer</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view all-service-reports'))
                            <li class="{{request()->is('user-stockin-reports')? 'active' : '' }}{{request()->is('user-stockin-reports/*')? 'active' : '' }}">
                                <a href="{{ route('view-user-stockin-reports') }}">Spare Part Stockin</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view spare-part-stockin-reports'))
                            <li class="{{request()->is('user-assigned-toolkit-reports')? 'active' : '' }}{{request()->is('user-assigned-toolkit-reports/*')? 'active' : '' }}">
                                <a href="{{ route('view-user-assigned-toolkit-reports') }}">Assigned Toolkit</a>
                            </li>
                            @endif
                            @if(Auth::user()->can('view spare-part-stockin-reports'))
                            <li class="{{request()->route()->getName() == 'toolkit-requested' ? 'active' : '' }}">
                                <a href="{{ route('toolkit-requested') }}">Requested Toolkits</a>
                            </li>
                            @endif
                            @if(Auth::user()->can('view engineer-machines'))
                            <li class="{{request()->route()->getName() == 'admin.reports.engineers-machine' ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.engineers-machine') }}">Engineer Machines</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif


                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    <li class="{{request()->is('stock-in')? 'active' : '' }}{{request()->is('stock-in/*')? 'active' : '' }} || {{request()->is('engineer-issue-stockin')? 'active' : '' }}{{request()->is('engineer-issue-stockin/*')? 'active' : '' }} ||  {{request()->is('client-outstanding-bill')? 'active' : '' }}{{request()->is('client-outstanding-bill/*')? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>Transactions</span>
                        </a>
                        <ul class="ml-menu">
                            @if(Auth::user()->can('view spare-part-stock-in'))
                            <li class="{{request()->is('stock-in')? 'active' : '' }}{{request()->is('stock-in/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-stock-in') }}">
                                    <span>Stock In</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view issue-stockin'))
                            <li class="{{request()->is('engineer-issue-stockin')? 'active' : '' }}{{request()->is('engineer-issue-stockin/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-engineer-issue-stockin') }}">
                                    <span>Stock Out</span>
                                </a>
                            </li>
                            @endif
      
                            @if(Auth::user()->can('view client outstanding bill'))
                            <li class="{{request()->is('client-outstanding-bill')? 'active' : '' }}{{request()->is('client-outstanding-bill/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-client-outstanding-bill') }}">
                                    <span>Client Bill Outstanding</span>
                                </a>
                            </li>
                            @endif

                       </ul>
                    </li>
                @endif

              
                @if(Auth::user()->user_type == 3 || Auth::user()->user_type == 2 || Auth::user()->user_type == 1)
                  
                    <li class="{{request()->is('all-assigned-clients-amc')? 'active' : '' }}{{request()->is('all-assigned-clients-amc/*')? 'active' : '' }} || {{request()->is('daily-service-report')? 'active' : '' }}{{request()->is('daily-service-report/*')? 'active' : '' }} || {{request()->is('outstanding-details')? 'active' : '' }}{{request()->is('outstanding-details/*')? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">list</i>
                            <span>My Transactions</span>
                        </a>
                        <ul class="ml-menu">
                            @if(Auth::user()->can('view all-assigned-clients-amc'))
                            <li class="{{request()->is('all-assigned-clients-amc')? 'active' : '' }}{{request()->is('all-assigned-clients-amc/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-assigned-clients-amc') }}">Preventive Maintenance</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view all-daily-service-report'))
                            <li class="{{request()->is('daily-service-report')? 'active' : '' }}{{request()->is('daily-service-report/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-daily-service-report') }}">DSR</a>
                            </li>
                            @endif

                            @if(Auth::user()->can('view all-bill-outstanding-details'))
                            <li class="{{request()->is('outstanding-details')? 'active' : '' }}{{request()->is('outstanding-details/*')? 'active' : '' }}">
                                <a href="{{ route('all-bill-outstanding-details') }}">Bill Outstanding</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(Auth::user()->user_type == 3 || Auth::user()->user_type == 2 || Auth::user()->user_type == 1)
                    @if(Auth::user()->can('view engineer-issued-spare-parts-details'))
                    <li class="{{request()->is('issued-spare-part-details')? 'active' : '' }}{{request()->is('issued-spare-part-details/*')? 'active' : '' }}">
                        <a href="{{ route('issued-spare-parts-details') }}">
                            <i class="material-icons">layers</i>
                            <span>Issued spare parts</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->can('view engineer-assigned-toolkits-details'))
                    <li class="{{request()->is('assigned-toolkit-details')? 'active' : '' }}{{request()->is('assigned-toolkit-details/*')? 'active' : '' }}">
                        <a href="{{ route('assigned-toolkits-details') }}">
                            <i class="material-icons">layers</i>
                            <span>Assigned tool kit</span>
                        </a>
                    </li>
                    @endif
              

                @endif

                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                @if(Auth::user()->can('view client amc'))
                    <li class="{{request()->is('client-amc')? 'active' : '' }}{{request()->is('client-amc/*')? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">build</i>
                            <span>Client AMC</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{request()->is('client-amc')? 'active' : '' }}{{request()->is('client-amc/*')? 'active' : '' }}">
                                <a href="{{ route('view-all-client-amc') }}">View all amc list</a>
                            </li>

                             
                        </ul>
                    </li>
                @endif
                @endif




     
                </ul>
            </div>
            <!-- #Menu -->











