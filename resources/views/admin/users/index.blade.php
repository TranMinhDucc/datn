@extends('layouts.admin')
@section('title', 'Danh sách thành viên')
@section('content')
           <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">
                                            
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 " 
     
         >

            <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">
            
    

<!--begin::Page title-->
<div  class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
    <!--begin::Title-->
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
        View Project
            </h1>
    <!--end::Title-->

            
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <!--begin::Item-->
                                    <li class="breadcrumb-item text-muted">
                                                    <a href="../../index.html" class="text-muted text-hover-primary">
                                Home                            </a>
                                            </li>
                                <!--end::Item-->
                                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                                        
                            <!--begin::Item-->
                                    <li class="breadcrumb-item text-muted">
                                                    Projects                                            </li>
                                <!--end::Item-->
                                        
                    </ul>
        <!--end::Breadcrumb-->
    </div>
<!--end::Page title-->
<!--begin::Actions-->
<div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
        <div class="m-0">
            <!--begin::Menu toggle-->
            <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-6 text-muted me-1"><span class="path1"></span><span class="path2"></span></i>               
                Filter
            </a>
            <!--end::Menu toggle-->
            
            

<!--begin::Menu 1-->
<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_683db6e644ede">
    <!--begin::Header-->
    <div class="px-7 py-5">
        <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
    </div>
    <!--end::Header-->

    <!--begin::Menu separator-->
    <div class="separator border-gray-200"></div>
    <!--end::Menu separator-->
    

    <!--begin::Form-->
    <div class="px-7 py-5">
        <!--begin::Input group-->
        <div class="mb-10">
            <!--begin::Label-->
            <label class="form-label fw-semibold">Status:</label>
            <!--end::Label-->

            <!--begin::Input-->
            <div>
                <select class="form-select form-select-solid" multiple data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_683db6e644ede" data-allow-clear="true">
                    <option></option>
                    <option value="1">Approved</option>
                    <option value="2">Pending</option>
                    <option value="2">In Process</option>
                    <option value="2">Rejected</option>
                </select>
            </div>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10">
            <!--begin::Label-->
            <label class="form-label fw-semibold">Member Type:</label>
            <!--end::Label-->

            <!--begin::Options-->
            <div class="d-flex">
                <!--begin::Options-->    
                <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                    <input class="form-check-input" type="checkbox" value="1"/>
                    <span class="form-check-label">
                        Author
                    </span>
                </label>
                <!--end::Options-->    

                <!--begin::Options-->    
                <label class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="2" checked="checked"/>
                    <span class="form-check-label">
                        Customer
                    </span>
                </label>
                <!--end::Options-->    
            </div>        
            <!--end::Options-->    
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10">
            <!--begin::Label-->
            <label class="form-label fw-semibold">Notifications:</label>
            <!--end::Label-->

            <!--begin::Switch-->
            <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="" name="notifications" checked />
                <label class="form-check-label">
                    Enabled
                </label>
            </div>
            <!--end::Switch-->
        </div>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>

            <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Form-->
</div>
<!--end::Menu 1-->        </div>
        <!--end::Filter menu-->
    
    
    <!--begin::Secondary button-->
        <!--end::Secondary button-->
    
    <!--begin::Primary button-->
            <a href="#" class="btn btn-sm fw-bold btn-primary"  data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">
            Create        </a>
        <!--end::Primary button-->
</div>
<!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
<!--end::Toolbar-->                                        
                    
<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    
           
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            
<!--begin::Navbar-->
<div class="card mb-6 mb-xl-9">
    <div class="card-body pt-9 pb-0">
        <!--begin::Details-->
        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
            <!--begin::Image-->
            <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                <img class="mw-50px mw-lg-75px" src="../../assets/media/svg/brand-logos/volicity-9.svg" alt="image"/>
            </div>
            <!--end::Image-->

            <!--begin::Wrapper-->
            <div class="flex-grow-1">
                <!--begin::Head-->
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <!--begin::Details-->
                    <div class="d-flex flex-column">
                        <!--begin::Status-->
                        <div class="d-flex align-items-center mb-1">
                            <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">CRM Dashboard</a>
                            <span class="badge badge-light-success me-auto">In Progress</span>
                        </div>
                        <!--end::Status-->

                        <!--begin::Description-->
                        <div class="d-flex flex-wrap fw-semibold mb-4 fs-5 text-gray-500">
                            #1 Tool to get started with Web Apps any Kind & size
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Details-->

                    <!--begin::Actions-->
                    <div class="d-flex mb-4">
                        <a href="#" class="btn btn-sm btn-bg-light btn-active-color-primary me-3"  data-bs-toggle="modal" data-bs-target="#kt_modal_users_search" >Add User</a>

                        <a href="#" class="btn btn-sm btn-primary me-3"  data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" >Add Target</a>

                        <!--begin::Menu-->
                        <div class="me-0">
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="ki-solid ki-dots-horizontal fs-2x"></i>                            </button>
                            
<!--begin::Menu 3-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
    <!--begin::Heading-->
    <div class="menu-item px-3">
        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
            Payments
        </div>
    </div>
    <!--end::Heading-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3">
            Create Invoice
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="#" class="menu-link flex-stack px-3">
            Create Payment

            <span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
                <i class="ki-duotone ki-information fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>            </span>
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3">
            Generate Bill
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
        <a href="#" class="menu-link px-3">
            <span class="menu-title">Subscription</span>
            <span class="menu-arrow"></span>
        </a>

        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-dropdown w-175px py-4">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3">
                    Plans
                </a>
            </div>
            <!--end::Menu item-->

            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3">
                    Billing
                </a>
            </div>
            <!--end::Menu item-->

            <!--begin::Menu item-->            
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3">
                    Statements
                </a>
            </div>
            <!--end::Menu item-->
            
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->

            <!--begin::Menu item-->            
            <div class="menu-item px-3">
                <div class="menu-content px-3">
                    <!--begin::Switch-->      
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <!--begin::Input-->   
                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications"/>
                        <!--end::Input-->   

                        <!--end::Label-->   
                        <span class="form-check-label text-muted fs-6">
                            Recuring
                        </span>
                        <!--end::Label-->   
                    </label>
                    <!--end::Switch-->   
                </div>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3 my-1">
        <a href="#" class="menu-link px-3">
            Settings
        </a>
    </div>
    <!--end::Menu item-->
</div>
<!--end::Menu 3-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Head-->

                <!--begin::Info-->
                <div class="d-flex flex-wrap justify-content-start">
                    <!--begin::Stats-->
                    <div class="d-flex flex-wrap">
                        <!--begin::Stat-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <div class="fs-4 fw-bold">29 Jan, 2025</div>
                            </div>
                            <!--end::Number-->

                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 text-gray-500">Due Date</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Stat-->

                        <!--begin::Stat-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-arrow-down fs-3 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>                                <div class="fs-4 fw-bold" data-kt-countup="true" data-kt-countup-value="75">0</div>
                            </div>
                            <!--end::Number-->

                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 text-gray-500">Open Tasks</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Stat-->

                        <!--begin::Stat-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span class="path1"></span><span class="path2"></span></i>                                <div class="fs-4 fw-bold" data-kt-countup="true" data-kt-countup-value="15000" data-kt-countup-prefix="$">0</div>
                            </div>
                            <!--end::Number-->                                

                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 text-gray-500">Budget Spent</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Stat-->
                    </div>
                    <!--end::Stats-->

                    <!--begin::Users-->
                    <div class="symbol-group symbol-hover mb-3">
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                                                                    <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
                                                                    <img alt="Pic" src="../../assets/media/avatars/300-11.jpg" />
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michelle Swanston">
                                                                    <img alt="Pic" src="../../assets/media/avatars/300-7.jpg" />
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Francis Mitcham">
                                                                    <img alt="Pic" src="../../assets/media/avatars/300-20.jpg" />
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
                                                                    <span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
                                                                    <img alt="Pic" src="../../assets/media/avatars/300-2.jpg" />
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
                                                                    <span class="symbol-label bg-info text-inverse-info fw-bold">P</span>
                                                            </div>
                            <!--end::User-->
                                                    <!--begin::User-->
                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
                                                                    <img alt="Pic" src="../../assets/media/avatars/300-12.jpg" />
                                                            </div>
                            <!--end::User-->
                        
                        <!--begin::All users-->
                        <a href="#" class="symbol symbol-35px symbol-circle"  data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                            <span class="symbol-label bg-dark text-inverse-dark fs-8 fw-bold" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View more users">+42</span>
                        </a>
                        <!--end::All users-->
                    </div>
                    <!--end::Users-->
                </div>
                <!--end::Info-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Details-->

        <div class="separator"></div>

        <!--begin::Nav-->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 active" href="project.html">
                        Overview                    </a>
                </li>
                <!--end::Nav item-->
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 " href="targets.html">
                        Targets                    </a>
                </li>
                <!--end::Nav item-->
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 " href="budget.html">
                        Budget                    </a>
                </li>
                <!--end::Nav item-->
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 " href="users.html">
                        Users                    </a>
                </li>
                <!--end::Nav item-->
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 " href="files.html">
                        Files                    </a>
                </li>
                <!--end::Nav item-->
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 " href="activity.html">
                        Activity                    </a>
                </li>
                <!--end::Nav item-->
                            <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary py-5 me-6 " href="settings.html">
                        Settings                    </a>
                </li>
                <!--end::Nav item-->
                    </ul>
        <!--end::Nav-->
    </div>
</div>
<!--end::Navbar-->

<!--begin::Table-->
<div class="card card-flush mt-6 mt-xl-9">
    <!--begin::Card header-->
    <div class="card-header mt-5">
        <!--begin::Card title-->
        <div class="card-title flex-column">
            <h3 class="fw-bold mb-1">Project Spendings</h3>

            <div class="fs-6 text-gray-500">Total $260,300 sepnt so far</div>
        </div>
        <!--begin::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar my-1">
            <!--begin::Select-->
            <div class="me-6 my-1">
                <select id="kt_filter_year" name="year" data-control="select2" data-hide-search="true" class="w-125px form-select form-select-solid form-select-sm">
                    <option value="All" selected>All time</option>
                    <option value="thisyear">This year</option>
                    <option value="thismonth">This month</option>
                    <option value="lastmonth">Last month</option>
                    <option value="last90days">Last 90 days</option>
                </select>
            </div>
            <!--end::Select-->

            <!--begin::Select-->
            <div class="me-4 my-1">
                <select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="w-125px form-select form-select-solid form-select-sm">
                    <option value="All" selected>All Orders</option>
                    <option value="Approved">Approved</option>
                    <option value="Declined">Declined</option>
                    <option value="In Progress">In Progress</option>
                    <option value="In Transit">In Transit</option>
                </select>
            </div>
            <!--end::Select-->

            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3"><span class="path1"></span><span class="path2"></span></i>                <input type="text" id="kt_filter_search" class="form-control form-control-solid form-select-sm w-150px ps-9" placeholder="Search Order" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card toolbar-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table id="kt_profile_overview_table" class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                <thead class="fs-7 text-gray-500 text-uppercase">
                    <tr>
                        <th class="min-w-250px">Manager</th>
                        <th class="min-w-150px">Date</th>
                        <th class="min-w-90px">Amount</th>
                        <th class="min-w-90px">Status</th>
                        <th class="min-w-50px text-end">Details</th>
                    </tr>
                </thead>
                <tbody class="fs-6">
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-6.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Emma Smith</a>

                                        <div class="fw-semibold text-gray-500">smith@kpmg.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Oct 25, 2025</td>
                            <td>$554.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    M                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Melody Macy</a>

                                        <div class="fw-semibold text-gray-500">melody@altbox.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Oct 25, 2025</td>
                            <td>$929.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-1.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Max Smith</a>

                                        <div class="fw-semibold text-gray-500">max@kt.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Mar 10, 2025</td>
                            <td>$963.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-5.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Sean Bean</a>

                                        <div class="fw-semibold text-gray-500">sean@dellito.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Dec 20, 2025</td>
                            <td>$925.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-25.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Brian Cox</a>

                                        <div class="fw-semibold text-gray-500">brian@exchange.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Jun 24, 2025</td>
                            <td>$712.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-warning text-warning fw-semibold">
                                                    C                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Mikaela Collins</a>

                                        <div class="fw-semibold text-gray-500">mik@pex.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Mar 10, 2025</td>
                            <td>$713.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-9.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Francis Mitcham</a>

                                        <div class="fw-semibold text-gray-500">f.mit@kpmg.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Apr 15, 2025</td>
                            <td>$965.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    O                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Olivia Wild</a>

                                        <div class="fw-semibold text-gray-500">olivia@corpmail.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Mar 10, 2025</td>
                            <td>$610.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-primary text-primary fw-semibold">
                                                    N                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Neil Owen</a>

                                        <div class="fw-semibold text-gray-500">owen.neil@gmail.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Feb 21, 2025</td>
                            <td>$403.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-23.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Dan Wilson</a>

                                        <div class="fw-semibold text-gray-500">dam@consilting.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Oct 25, 2025</td>
                            <td>$898.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    E                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Emma Bold</a>

                                        <div class="fw-semibold text-gray-500">emma@intenso.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Sep 22, 2025</td>
                            <td>$544.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-12.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Ana Crown</a>

                                        <div class="fw-semibold text-gray-500">ana.cf@limtel.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Sep 22, 2025</td>
                            <td>$541.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-info text-info fw-semibold">
                                                    A                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Robert Doe</a>

                                        <div class="fw-semibold text-gray-500">robert@benko.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Dec 20, 2025</td>
                            <td>$896.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-13.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">John Miller</a>

                                        <div class="fw-semibold text-gray-500">miller@mapple.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Apr 15, 2025</td>
                            <td>$551.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-success text-success fw-semibold">
                                                    L                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Lucy Kunic</a>

                                        <div class="fw-semibold text-gray-500">lucy.m@fentech.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Sep 22, 2025</td>
                            <td>$566.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-21.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Ethan Wilder</a>

                                        <div class="fw-semibold text-gray-500">ethan@loop.com.au</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Apr 15, 2025</td>
                            <td>$671.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-12.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Ana Crown</a>

                                        <div class="fw-semibold text-gray-500">ana.cf@limtel.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Aug 19, 2025</td>
                            <td>$744.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-23.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Dan Wilson</a>

                                        <div class="fw-semibold text-gray-500">dam@consilting.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Nov 10, 2025</td>
                            <td>$542.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-25.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Brian Cox</a>

                                        <div class="fw-semibold text-gray-500">brian@exchange.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Feb 21, 2025</td>
                            <td>$675.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-9.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Francis Mitcham</a>

                                        <div class="fw-semibold text-gray-500">f.mit@kpmg.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Jun 20, 2025</td>
                            <td>$636.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-5.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Sean Bean</a>

                                        <div class="fw-semibold text-gray-500">sean@dellito.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Feb 21, 2025</td>
                            <td>$907.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-warning text-warning fw-semibold">
                                                    C                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Mikaela Collins</a>

                                        <div class="fw-semibold text-gray-500">mik@pex.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Feb 21, 2025</td>
                            <td>$722.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-primary text-primary fw-semibold">
                                                    N                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Neil Owen</a>

                                        <div class="fw-semibold text-gray-500">owen.neil@gmail.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>May 05, 2025</td>
                            <td>$701.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    E                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Emma Bold</a>

                                        <div class="fw-semibold text-gray-500">emma@intenso.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Jun 24, 2025</td>
                            <td>$551.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    M                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Melody Macy</a>

                                        <div class="fw-semibold text-gray-500">melody@altbox.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Jun 24, 2025</td>
                            <td>$972.00</td>
                            <td>
                                <span class="badge badge-light-warning fw-bold px-4 py-3">
                                    Pending                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    E                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Emma Bold</a>

                                        <div class="fw-semibold text-gray-500">emma@intenso.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Nov 10, 2025</td>
                            <td>$438.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-primary text-primary fw-semibold">
                                                    N                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Neil Owen</a>

                                        <div class="fw-semibold text-gray-500">owen.neil@gmail.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Dec 20, 2025</td>
                            <td>$866.00</td>
                            <td>
                                <span class="badge badge-light-success fw-bold px-4 py-3">
                                    Approved                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <img alt="Pic" src="../../assets/media/avatars/300-1.jpg" />
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Max Smith</a>

                                        <div class="fw-semibold text-gray-500">max@kt.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Mar 10, 2025</td>
                            <td>$534.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-success text-success fw-semibold">
                                                    L                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Lucy Kunic</a>

                                        <div class="fw-semibold text-gray-500">lucy.m@fentech.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Mar 10, 2025</td>
                            <td>$739.00</td>
                            <td>
                                <span class="badge badge-light-danger fw-bold px-4 py-3">
                                    Rejected                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                             
                                                <tr>
                            <td>
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Wrapper-->
                                    <div class="me-5 position-relative">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle">
                                                                                            <span class="symbol-label bg-light-danger text-danger fw-semibold">
                                                    M                                                </span>
                                                                                    </div>
                                        <!--end::Avatar-->

                                                                                   <!--begin::Online-->
                                           <div class="bg-success position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>
                                           <!--end::Online-->
                                                                            </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-column justify-content-center">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary">Melody Macy</a>

                                        <div class="fw-semibold text-gray-500">melody@altbox.com</div>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </td>
                            <td>Sep 22, 2025</td>
                            <td>$630.00</td>
                            <td>
                                <span class="badge badge-light-info fw-bold px-4 py-3">
                                    In progress                                </span>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-sm">View</a>
                            </td>
                        </tr>
                                    </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table container-->                                      
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->        </div>
        <!--end::Content container-->
    </div>
<!--end::Content-->	

                                    </div>
                <!--end::Content wrapper-->
@endsection