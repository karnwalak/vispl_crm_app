<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <div style="background-color: white; height: 70px;">
        <a href="{{ SITEURL }}dashboard/"><img src="{{ SITEURL }}/public/images/logo.png" style="margin: 10px 10px 10px 50px;"></a>
    </div>
    <li class="nav-item active">
        <a class="nav-link" href="{{ SITEURL }}dashboard/">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @can('isAdmin')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsemaster" aria-expanded="true" aria-controls="collapsemaster">
            <i class="fa fa-file-invoice"></i>
            <span>Master Management</span>
        </a>
        <div id="collapsemaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ SITEURL }}admin/department">Manage Department</a>
                <a class="collapse-item" href="{{ SITEURL }}admin/empolyees">Manage Empolyees</a>
                <a class="collapse-item" href="{{ SITEURL }}admin/region">Manage Region</a>
                <a class="collapse-item" href="{{ SITEURL }}admin/product">Manage Product</a>
                <a class="collapse-item" href="{{ SITEURL }}admin/source">Manage Source</a>
                <a class="collapse-item" href="{{ SITEURL }}admin/accountind">Manage Accountind</a>

            </div>
        </div>
    </li>
    @endcan
    @if(Gate::check('isCountryHead') || Gate::check('isZonalLevelAdmin') || Gate::check('isStateLevelAdmin') || Gate::check('isMember'))
    @php
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $CheckManagementRoute = (
    ($actual_link == SITEURL . 'admin/leadmanagement') ||
    ($actual_link == SITEURL . 'admin/activitymanagement') ||
    ($actual_link == SITEURL . 'admin/opportunities') ||
    ($actual_link == SITEURL . 'admin/opportunities') ||
    (request()->route()->getName() == "editlead") ||
    (request()->route()->getName() == "editOpportunity")
    );
    $CheckReportsRoute = (
    (stripos($actual_link, SITEURL . 'wcr-reports') !== false) ||
    (stripos($actual_link, SITEURL . 'wap-reports') !== false) ||
    (stripos($actual_link, SITEURL . 'funnel-reports') !== false)
    );
    $CheckDashboardReportsRoute = (
    (stripos($actual_link, SITEURL . 'dashboard') !== false)
    );
    @endphp
    <li class="nav-item">
        <a class="nav-link @if($CheckManagementRoute) active @else collapsed @endif" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <i class="fa fa-file-invoice @if($CheckManagementRoute) text-white @endif"></i>
            <span class="@if($CheckManagementRoute) text-white @endif">Lead Management</span>
        </a>
        <div id="collapseOne" class="collapse @if($CheckManagementRoute) show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item @if($actual_link == SITEURL . 'admin/leadmanagement' || (request()->route()->getName() == "editlead")) text-white @endif" href="{{ SITEURL }}admin/leadmanagement">Leads</a>
                <a class="collapse-item @if($actual_link == SITEURL . 'admin/activitymanagement') text-white @endif  " href="{{ SITEURL }}admin/activitymanagement">Activities</a>
                <a class="collapse-item @if($actual_link == SITEURL . 'admin/opportunities') text-white @endif   " href="{{ SITEURL }}admin/opportunities">Opportunities</a>

            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($CheckReportsRoute) active @else collapsed @endif" href="#" data-toggle="collapse" data-target="#reportstab" aria-expanded="true" aria-controls="reportstab">
            <i class="fa fa-file-invoice @if($CheckReportsRoute) text-white @endif"></i>
            <span class="@if($CheckReportsRoute) text-white @endif">Reports</span>
        </a>
        <div id="reportstab" class="collapse @if($CheckReportsRoute) show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'funnel-reports') !== false) text-white @endif"  href="{{route('funnel-report')}}">Funnel Report</a>
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'wcr-reports') !== false) text-white @endif"  href="{{route('wcr-report')}}">WCR Report</a>
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'wap-reports') !== false) text-white @endif"  href="{{route('wap-report')}}">WAP Report</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($CheckDashboardReportsRoute) active @else collapsed @endif" href="#" data-toggle="collapse" data-target="#dashboardtab" aria-expanded="true" aria-controls="dashboardtab">
            <i class="fa fa-file-invoice @if($CheckDashboardReportsRoute) text-white @endif"></i>
            <span class="@if($CheckDashboardReportsRoute) text-white @endif">Dashboard Reports</span>
        </a>
        <div id="dashboardtab" class="collapse @if($CheckDashboardReportsRoute) show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'dashboard#product') !== false) text-white @endif"  href="{{route('dashboard') . "#product"}}">Product Wise</a>
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'dashboard#opportunity') !== false) text-white @endif"  href="{{route('dashboard') . "#opportunity"}}">Opportunity Wise</a>
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'dashboard#account') !== false) text-white @endif"  href="{{route('dashboard') . "#account"}}">Account Type</a>
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'dashboard#customer') !== false) text-white @endif"  href="{{route('dashboard') . "#customer"}}">Customer Type</a>
                <a class="collapse-item @if(stripos($actual_link, SITEURL . 'dashboard#kam') !== false) text-white @endif"  href="{{route('dashboard') . "#kam"}}">KAM</a>
            </div>
        </div>
    </li>
    @endif
</ul>
<!-- End of Sidebar -->
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link rounded-circle">
                <i class="fa fa-bars"></i>
            </button>
            <!-- Topbar Search -->
            <form class="d-none form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fa fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="mr-auto font-weight-normal text-gray-800 text-font-size">
                <head>Dashboard</head>
            </div>
            <div class="clock-space">
                <small id="clock"></small>
                <!-- <p class="datetime-dasbaord">Thur 7 May 5:30 PM</p> -->
            </div>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav">
                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fa fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user fa-color-black"></i><span class="ml-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span><i class="fa fa-caret-down ml-2"></i>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('changepassword')}}">
                            <i class="fa fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                            Password
                        </a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">

                            <i class="fa fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('Logout') }} </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
