<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOC Management | Karnataka State Allied & Healthcare Council</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        #wrapper {
            overflow-x: hidden;
        }
        
        #sidebar-wrapper {
            min-height: 100vh;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: margin 0.25s ease-out;
        }
        
        #sidebar-wrapper .sidebar-heading {
            padding: 1.2rem 1.25rem;
        }
        
        #sidebar-wrapper .list-group-item {
            border: none;
            transition: all 0.3s;
        }
        
        #sidebar-wrapper .list-group-item.active {
            background-color: #4e73df;
            border-left: 4px solid #fff;
        }
        
        #sidebar-wrapper .list-group-item:hover:not(.active) {
            background-color: #3a3b45;
            border-left: 4px solid #4e73df;
        }
        
        #page-content-wrapper {
            min-width: 80vw;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
            color: #4e73df;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-success {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        
        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
        }
        
        .table {
            color: #5a5c69;
        }
        
        .table th {
            background-color: #f8f9fc;
            border-top: none;
        }
        
        .table-bordered {
            border: 1px solid #e3e6f0;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e3e6f0;
        }
        
        .badge {
            font-size: 85%;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 10rem;
        }
        
        .badge-success {
            background-color: #1cc88a;
        }
        
        .badge-warning {
            background-color: #f6c23e;
            color: #fff;
        }
        
        .badge-danger {
            background-color: #e74a3b;
        }
        
        .badge-info {
            background-color: #36b9cc;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .pagination .page-link {
            color: #4e73df;
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .navbar {
            padding: 0.5rem 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        /* Custom styling for Select2 */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            min-height: 38px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4e73df;
            border: none;
            color: white;
            border-radius: 0.25rem;
            padding: 3px 8px;
            margin-top: 5px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #d1d3e2;
        }
        
        /* State statistics styling */
        #state-statistics {
            max-height: 150px;
            overflow-y: auto;
        }
        
        #state-statistics .alert {
            margin-bottom: 0.5rem !important;
        }
        
        /* Badge styles */
        .badge-approved {
            background-color: #1cc88a;
        }
        
        .badge-pending {
            background-color: #f6c23e;
        }
        
        .badge-issued {
            background-color: #4e73df;
        }
        
        .badge-rejected {
            background-color: #e74a3b;
        }
        
        /* Custom scrollbar for state statistics */
        #state-statistics::-webkit-scrollbar {
            width: 6px;
        }
        
        #state-statistics::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        #state-statistics::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        #state-statistics::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Enhanced Professional Design Styles */
        .main-heading {
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        
        .main-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background-color: #4e73df;
            border-radius: 2px;
        }
        
        /* Filter tags styling */
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .filter-tag {
            background-color: #4e73df;
            color: white;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            animation: fadeInRight 0.3s ease-out;
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.2);
            transition: all 0.2s;
        }
        
        .filter-tag:hover {
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
            transform: translateY(-2px);
        }
        
        .filter-tag .close {
            margin-left: 8px;
            color: white;
            opacity: 0.8;
            text-shadow: none;
            font-size: 14px;
            font-weight: 400;
        }
        
        .filter-tag .close:hover {
            opacity: 1;
        }
        
        /* Status update toast */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            min-width: 300px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        /* Table row animation */
        #noc-table-body tr {
            transition: all 0.3s;
        }
        
        #noc-table-body tr:hover {
            transform: translateX(5px);
            background-color: rgba(78, 115, 223, 0.03);
        }
        
        /* Enhanced button styles */
        .btn {
            border-radius: 5px;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Filter summary section */
        .filter-summary {
            background-color: #f8faff;
            border-left: 4px solid #4e73df;
            border-radius: 5px;
            padding: 10px 15px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            display: none;
        }
        
        .filter-summary.active {
            display: block;
            animation: fadeIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Status badges with more attractive design */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-badge-success {
            background-color: rgba(28, 200, 138, 0.12);
            color: #1cc88a;
            border: 1px solid rgba(28, 200, 138, 0.2);
        }
        
        .status-badge-warning {
            background-color: rgba(246, 194, 62, 0.12);
            color: #f6c23e;
            border: 1px solid rgba(246, 194, 62, 0.2);
        }
        
        .status-badge-danger {
            background-color: rgba(231, 74, 59, 0.12);
            color: #e74a3b;
            border: 1px solid rgba(231, 74, 59, 0.2);
        }
        
        .status-badge-info {
            background-color: rgba(54, 185, 204, 0.12);
            color: #36b9cc;
            border: 1px solid rgba(54, 185, 204, 0.2);
        }
        
        /* Page background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(#4e73df 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.03;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-center text-white py-4">
                <img src="../../ksahc_logo.png" alt="Logo" class="logo-img mb-2" style="max-width: 80px;">
                <div>KSAHC Central Admin</div>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> Approve Practitioner
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-md mr-2"></i> Central Registry
                </a>
                <a href="noc.php" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="fas fa-file-contract mr-2"></i> NOC
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-money-bill-wave mr-2"></i> Accounts
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-headset mr-2"></i> HelpLine
                </a>
                <a href="settings.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-chart-line mr-2"></i> Reports
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
                <button class="btn btn-dark" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-1"></i> Admin User
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profile.php"><i class="fas fa-user mr-1"></i> Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold main-heading">
                    <i class="fas fa-file-contract mr-2 text-primary"></i>NOC Management
                </h1>
                
                <!-- Dashboard Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total NOCs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">215</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved NOCs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">132</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending NOCs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">83</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">States Involved</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">9</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Advanced Filter Section -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-filter mr-2"></i>Filter Options
                        </h6>
                        <button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <div class="card-body">
                            <form method="GET" id="filter-form">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="states"><strong>States</strong></label>
                                        <select class="form-control select2-multiple" id="states" name="states[]" multiple="multiple">
                                            <option value="Karnataka" selected>Karnataka</option>
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</option>
                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana</option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Kerala">Kerala</option>
                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                            <option value="Maharashtra">Maharashtra</option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Odisha">Odisha</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                            <option value="Telangana">Telangana</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttarakhand">Uttarakhand</option>
                                            <option value="West Bengal">West Bengal</option>
                                        </select>
                                        <div id="state-statistics" class="mt-2">
                                            <!-- State statistics will be shown here -->
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="noc-status"><strong>NOC Status</strong></label>
                                        <select class="form-control" id="noc-status" name="status">
                                            <option value="">All Statuses</option>
                                            <option value="Issued">Issued</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="noc-year"><strong>Year</strong></label>
                                        <select class="form-control" id="noc-year" name="year">
                                            <option value="">All Years</option>
                                            <option value="2023" selected>2023</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="approved-status"><strong>Approved Status</strong></label>
                                        <select class="form-control" id="approved-status" name="approved">
                                            <option value="">All</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <div class="form-group mb-0">
                                            <label for="search-input"><strong>Search</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="search-input" name="search" placeholder="Search by name, registration number...">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-search mr-1"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="reset" class="btn btn-secondary btn-block" id="reset-btn">
                                            <i class="fas fa-sync-alt mr-1"></i> Reset Filters
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Active Filters Summary -->
                                <div class="filter-summary mt-4" id="filter-summary">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-filter mr-2"></i>Active Filters
                                        </h6>
                                        <small class="text-muted">
                                            <span id="filtered-count">0</span> records found
                                        </small>
                                    </div>
                                    <div class="filter-tags" id="active-filters">
                                        <!-- Active filter tags will be added here dynamically -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- NOC Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>NOC Applications
                        </h6>
                        <div>
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#createNOCModal">
                                <i class="fas fa-plus mr-1"></i> New NOC
                            </button>
                            <button class="btn btn-sm btn-info ml-2" id="export-data">
                                <i class="fas fa-download mr-1"></i> Export
                            </button>
                            <button class="btn btn-sm btn-outline-primary ml-2" id="refresh-data">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0" id="noc-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">SL. No</th>
                                        <th width="20%">Name</th>
                                        <th width="20%">NOC From</th>
                                        <th width="20%">NOC To</th>
                                        <th width="15%">Approved</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="noc-table-body">
                                    <!-- Sample Data Row 1 -->
                                    <tr data-state="Karnataka" data-status="Issued" data-approved="Yes">
                                        <td>1</td>
                                        <td>Rajesh Kumar</td>
                                        <td>Karnataka</td>
                                        <td>Maharashtra</td>
                                        <td><span class="status-badge status-badge-success">Yes</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Download NOC">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sample Data Row 2 -->
                                    <tr data-state="Karnataka" data-status="Pending" data-approved="No">
                                        <td>2</td>
                                        <td>Priya Sharma</td>
                                        <td>Karnataka</td>
                                        <td>Tamil Nadu</td>
                                        <td><span class="status-badge status-badge-danger">No</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" data-toggle="tooltip" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sample Data Row 3 -->
                                    <tr data-state="Karnataka" data-status="Approved" data-approved="Yes">
                                        <td>3</td>
                                        <td>Anil Joshi</td>
                                        <td>Karnataka</td>
                                        <td>Delhi</td>
                                        <td><span class="status-badge status-badge-success">Yes</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Download NOC">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sample Data Row 4 -->
                                    <tr data-state="Karnataka" data-status="Pending" data-approved="No">
                                        <td>4</td>
                                        <td>Sunita Patel</td>
                                        <td>Karnataka</td>
                                        <td>Gujarat</td>
                                        <td><span class="status-badge status-badge-danger">No</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" data-toggle="tooltip" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sample Data Row 5 -->
                                    <tr data-state="Karnataka" data-status="Issued" data-approved="Yes">
                                        <td>5</td>
                                        <td>Mohan Das</td>
                                        <td>Karnataka</td>
                                        <td>Kerala</td>
                                        <td><span class="status-badge status-badge-success">Yes</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Download NOC">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sample Data Row 6 -->
                                    <tr data-state="Andhra Pradesh" data-status="Approved" data-approved="Yes">
                                        <td>6</td>
                                        <td>Kavita Singh</td>
                                        <td>Andhra Pradesh</td>
                                        <td>Karnataka</td>
                                        <td><span class="status-badge status-badge-success">Yes</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Download NOC">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Additional sample rows with different states -->
                                    <tr data-state="Maharashtra" data-status="Pending" data-approved="No">
                                        <td>7</td>
                                        <td>Ravi Deshmukh</td>
                                        <td>Maharashtra</td>
                                        <td>Tamil Nadu</td>
                                        <td><span class="status-badge status-badge-danger">No</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" data-toggle="tooltip" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-state="Tamil Nadu" data-status="Issued" data-approved="Yes">
                                        <td>8</td>
                                        <td>Lakshmi Subramaniam</td>
                                        <td>Tamil Nadu</td>
                                        <td>Kerala</td>
                                        <td><span class="status-badge status-badge-success">Yes</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Download NOC">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <small class="text-muted">Last updated: <span id="last-updated">Today at 10:45 AM</span></small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2 for multiple select
            $('.select2-multiple').select2({
                placeholder: "Select states",
                allowClear: true,
                theme: "classic"
            });
            
            // Toggle sidebar
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Create toast container if it doesn't exist
            if ($('#toast-container').length === 0) {
                $('body').append('<div id="toast-container"></div>');
            }
            
            // State statistics data (dummy values)
            const stateStats = {
                "Karnataka": "68 NOC applications (32 pending, 36 approved)",
                "Andhra Pradesh": "45 NOC applications (22 pending, 23 approved)",
                "Maharashtra": "53 NOC applications (18 pending, 35 approved)",
                "Tamil Nadu": "39 NOC applications (12 pending, 27 approved)",
                "Delhi": "27 NOC applications (15 pending, 12 approved)",
                "Uttar Pradesh": "41 NOC applications (19 pending, 22 approved)",
                "Kerala": "35 NOC applications (14 pending, 21 approved)",
                "Gujarat": "49 NOC applications (23 pending, 26 approved)",
                "Telangana": "31 NOC applications (13 pending, 18 approved)"
            };
            
            // Function to show state statistics
            function showStateStatistics(selectedStates) {
                $('#state-statistics').empty();
                
                selectedStates.forEach(function(state) {
                    if (stateStats[state]) {
                        $('#state-statistics').append(
                            `<div class="alert alert-info p-2 mb-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small><strong>${state}:</strong> ${stateStats[state]}</small>
                                    <button type="button" class="close state-remove" data-state="${state}" style="font-size: 1rem;" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>`
                        );
                    }
                });
            }
            
            // Function to show toast notification
            function showToast(message, type = 'success', title = 'Notification') {
                const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
                
                // Set icon based on type
                let icon = 'info-circle';
                if (type === 'success') icon = 'check-circle';
                if (type === 'warning') icon = 'exclamation-triangle';
                if (type === 'danger') icon = 'exclamation-circle';
                
                // Create toast element
                const toast = `
                    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
                        <div class="toast-header bg-${type} text-white">
                            <i class="fas fa-${icon} mr-2"></i>
                            <strong class="mr-auto">${title}</strong>
                            <small>Just now</small>
                            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;
                
                // Add toast to container
                $('#toast-container').append(toast);
                
                // Show toast
                $(`#${toastId}`).toast('show');
                
                // Remove toast after it's hidden
                $(`#${toastId}`).on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }
            
            // Initial state
            const initialStates = $('#states').val();
            if (initialStates && initialStates.length > 0) {
                showStateStatistics(initialStates);
                filterTable(); // Apply initial filtering
                updateFilterSummary(); // Show initial filter summary
            }
            
            // Function to filter table based on selected criteria
            function filterTable() {
                const selectedStates = $('#states').val() || [];
                const selectedStatus = $('#noc-status').val();
                const selectedYear = $('#noc-year').val();
                const selectedApproval = $('#approved-status').val();
                const searchTerm = $('#search-input').val().toLowerCase();
                
                // Get all rows in the table
                const rows = $('#noc-table-body tr');
                
                // Filter rows based on criteria
                let visibleCount = 0;
                rows.each(function() {
                    const row = $(this);
                    const rowState = row.data('state');
                    const rowStatus = row.data('status');
                    const rowApproval = row.data('approved');
                    const rowText = row.text().toLowerCase();
                    
                    // Check if row matches all selected filters
                    const matchesState = selectedStates.length === 0 || selectedStates.includes(rowState);
                    const matchesStatus = !selectedStatus || rowStatus === selectedStatus;
                    const matchesApproval = !selectedApproval || rowApproval === selectedApproval;
                    const matchesSearch = !searchTerm || rowText.includes(searchTerm);
                    
                    // Show/hide row based on filter matches
                    if (matchesState && matchesStatus && matchesApproval && matchesSearch) {
                        row.show();
                        visibleCount++;
                    } else {
                        row.hide();
                    }
                });
                
                // Update filtered count
                $('#filtered-count').text(visibleCount);
                
                // Update table counter for visible rows
                updateRowNumbers();
                
                // Update filter summary
                updateFilterSummary();
                
                // Show toast notification about filter results
                if (hasActiveFilters()) {
                    showToast(`Found ${visibleCount} NOC applications matching your filters`, 'primary', 'Filter Results');
                }
            }
            
            // Function to check if any filters are active
            function hasActiveFilters() {
                const selectedStates = $('#states').val() || [];
                const selectedStatus = $('#noc-status').val();
                const selectedYear = $('#noc-year').val();
                const selectedApproval = $('#approved-status').val();
                const searchTerm = $('#search-input').val();
                
                return selectedStates.length > 0 || selectedStatus || selectedYear || selectedApproval || searchTerm;
            }
            
            // Function to update the filter summary
            function updateFilterSummary() {
                const selectedStates = $('#states').val() || [];
                const selectedStatus = $('#noc-status').val();
                const selectedYear = $('#noc-year').val();
                const selectedApproval = $('#approved-status').val();
                const searchTerm = $('#search-input').val();
                
                // Clear existing filter tags
                $('#active-filters').empty();
                
                // Add filter tags for each active filter
                if (selectedStates.length > 0) {
                    selectedStates.forEach(function(state) {
                        addFilterTag('State: ' + state, 'state', state);
                    });
                }
                
                if (selectedStatus) {
                    addFilterTag('Status: ' + selectedStatus, 'status', selectedStatus);
                }
                
                if (selectedYear) {
                    addFilterTag('Year: ' + selectedYear, 'year', selectedYear);
                }
                
                if (selectedApproval) {
                    addFilterTag('Approved: ' + selectedApproval, 'approved', selectedApproval);
                }
                
                if (searchTerm) {
                    addFilterTag('Search: ' + searchTerm, 'search', searchTerm);
                }
                
                // Show or hide the filter summary
                if (hasActiveFilters()) {
                    $('#filter-summary').addClass('active');
                } else {
                    $('#filter-summary').removeClass('active');
                }
            }
            
            // Function to add a filter tag
            function addFilterTag(text, type, value) {
                const tag = $(`
                    <div class="filter-tag">
                        <span>${text}</span>
                        <button type="button" class="close" aria-label="Remove">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
                
                // Add click handler to remove this filter
                tag.find('.close').on('click', function() {
                    removeFilter(type, value);
                });
                
                // Add to active filters
                $('#active-filters').append(tag);
            }
            
            // Function to remove a filter
            function removeFilter(type, value) {
                switch(type) {
                    case 'state':
                        const currentStates = $('#states').val() || [];
                        const newStates = currentStates.filter(state => state !== value);
                        $('#states').val(newStates).trigger('change');
                        break;
                    case 'status':
                        $('#noc-status').val('').trigger('change');
                        break;
                    case 'year':
                        $('#noc-year').val('').trigger('change');
                        break;
                    case 'approved':
                        $('#approved-status').val('').trigger('change');
                        break;
                    case 'search':
                        $('#search-input').val('').trigger('keyup');
                        break;
                }
                
                // Show toast notification
                showToast(`Filter '${value}' has been removed`, 'info', 'Filter Updated');
            }
            
            // Function to update row numbers after filtering
            function updateRowNumbers() {
                let counter = 1;
                $('#noc-table-body tr:visible').each(function() {
                    $(this).find('td:first').text(counter++);
                });
                
                // Show message if no results
                if ($('#noc-table-body tr:visible').length === 0) {
                    if ($('#no-results-row').length === 0) {
                        $('#noc-table-body').append(
                            '<tr id="no-results-row"><td colspan="6" class="text-center py-3">No records found matching your filters</td></tr>'
                        );
                    }
                } else {
                    $('#no-results-row').remove();
                }
            }
            
            // On state selection change
            $('#states').on('change', function() {
                const selectedStates = $(this).val() || [];
                showStateStatistics(selectedStates);
                filterTable();
            });
            
            // On status change
            $('#noc-status').on('change', function() {
                filterTable();
            });
            
            // On year change
            $('#noc-year').on('change', function() {
                filterTable();
            });
            
            // On approval status change
            $('#approved-status').on('change', function() {
                filterTable();
            });
            
            // On search input
            $('#search-input').on('keyup', function() {
                filterTable();
            });
            
            // Form submission
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                filterTable();
                showToast('Filters have been applied', 'success', 'Filters Applied');
            });
            
            // Remove state when clicking the close button
            $(document).on('click', '.state-remove', function() {
                const stateToRemove = $(this).data('state');
                const currentStates = $('#states').val() || [];
                const newStates = currentStates.filter(state => state !== stateToRemove);
                
                $('#states').val(newStates).trigger('change');
            });
            
            // Reset button should clear the statistics as well
            $('#reset-btn').on('click', function() {
                $('#state-statistics').empty();
                $('#states').val(['Karnataka']).trigger('change');
                $('#noc-status').val('').trigger('change');
                $('#noc-year').val('2023').trigger('change');
                $('#approved-status').val('').trigger('change');
                $('#search-input').val('').trigger('keyup');
                
                // Show toast notification
                showToast('All filters have been reset', 'warning', 'Filters Reset');
            });
            
            // Filter section toggle
            $('#filterCollapse').on('shown.bs.collapse', function () {
                $('#filterCollapse').find('button i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            });
            
            $('#filterCollapse').on('hidden.bs.collapse', function () {
                $('#filterCollapse').find('button i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });
            
            // Handle approve buttons
            $(document).on('click', '.btn-success', function() {
                const row = $(this).closest('tr');
                const name = row.find('td:nth-child(2)').text();
                
                // Update badge
                row.find('.badge-danger').removeClass('badge-danger').addClass('badge-approved').text('Yes');
                row.data('approved', 'Yes');
                
                // Update buttons
                const btnGroup = row.find('.btn-group');
                btnGroup.html(`
                    <button class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Download NOC">
                        <i class="fas fa-download"></i>
                    </button>
                `);
                
                // Reinitialize tooltips
                $('[data-toggle="tooltip"]').tooltip();
                
                // Show toast notification
                showToast(`NOC for ${name} has been approved successfully`, 'success', 'NOC Approved');
                
                // Reapply filters
                filterTable();
            });
            
            // Handle view details buttons
            $(document).on('click', '.btn-info', function() {
                const row = $(this).closest('tr');
                const name = row.find('td:nth-child(2)').text();
                
                // Show toast notification
                showToast(`Viewing details for ${name}`, 'info', 'View Details');
            });
            
            // Handle download buttons
            $(document).on('click', '.btn-primary', function() {
                const row = $(this).closest('tr');
                const name = row.find('td:nth-child(2)').text();
                
                // Show toast notification
                showToast(`Downloading NOC for ${name}`, 'primary', 'Download NOC');
            });
            
            // Handle refresh button
            $('#refresh-data').on('click', function() {
                // Set button to loading state
                const $button = $(this);
                $button.html('<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...');
                $button.prop('disabled', true);
                
                // Simulate data reload delay
                setTimeout(function() {
                    // Apply filters again
                    filterTable();
                    
                    // Update last updated time
                    const now = new Date();
                    const hours = now.getHours();
                    const minutes = now.getMinutes();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const formattedHours = hours % 12 || 12;
                    const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
                    const timeString = `Today at ${formattedHours}:${formattedMinutes} ${ampm}`;
                    $('#last-updated').text(timeString);
                    
                    // Reset button state
                    $button.html('<i class="fas fa-sync-alt mr-1"></i> Refresh');
                    $button.prop('disabled', false);
                    
                    // Show toast notification
                    showToast('NOC data has been refreshed successfully', 'success', 'Data Refreshed');
                }, 1200);
            });
            
            // Handle export button
            $('#export-data').on('click', function() {
                const selectedStates = $('#states').val() || [];
                let stateText = selectedStates.length > 0 ? selectedStates.join(', ') : 'All States';
                
                showToast(`Exporting NOC data for ${stateText}...`, 'info', 'Export Started');
                
                // Simulate export delay
                setTimeout(function() {
                    showToast('Export completed successfully. File downloaded.', 'success', 'Export Complete');
                }, 1500);
            });
            
            // Add animation to cards on hover
            $('.card').hover(
                function() { $(this).addClass('shadow-lg'); },
                function() { $(this).removeClass('shadow-lg'); }
            );
            
            // Animate table rows when they appear
            function animateRows() {
                $('#noc-table-body tr:visible').each(function(index) {
                    const $row = $(this);
                    $row.css({
                        'opacity': 0,
                        'transform': 'translateX(-20px)'
                    });
                    
                    setTimeout(function() {
                        $row.css({
                            'transition': 'all 0.3s ease',
                            'opacity': 1,
                            'transform': 'translateX(0)'
                        });
                    }, 50 * index);
                });
            }
            
            // Call animate rows on initial load and after filtering
            animateRows();
            
            // Enhanced filter function to include animation
            const originalFilterTable = filterTable;
            filterTable = function() {
                originalFilterTable();
                animateRows();
            };
        });
    </script>
</body>
</html>