<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Management | Karnataka State Allied & Healthcare Council</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            border-radius: 12px;
            box-shadow: 0 0.25rem 1.75rem 0 rgba(58, 59, 69, 0.12);
            margin-bottom: 1.8rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2.5rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
            color: #4e73df;
            padding: 1.2rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #3a5cce;
            border-color: #3a5cce;
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
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f8f9fc;
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #4e73df;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #eaecf4;
        }
        
        .table-bordered {
            border: 1px solid #e3e6f0;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e3e6f0;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f8f9fc;
            transition: all 0.2s;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(78, 115, 223, 0.03);
        }
        
        .badge {
            font-size: 85%;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 10rem;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .pagination .page-link {
            color: #4e73df;
        }
        
        .form-control {
            border-radius: 6px;
            padding: 0.01rem 1rem;
            font-size: 0.95rem;
            border: 1px solid #d1d3e2;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .navbar {
            padding: 0.5rem 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        /* Custom styling for Select2 */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d3e2;
            border-radius: 6px;
            height: 38px;
            padding-top: 4px;
            transition: all 0.2s;
        }
        
        .select2-container--default .select2-selection--single:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4e73df;
        }
        
        .select2-dropdown {
            border-radius: 6px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid #e3e6f0;
            overflow: hidden;
        }
        
        /* State selection chip styling */
        .state-chip {
            background-color: #4e73df;
            color: white;
            padding: 12px 18px;
            border-radius: 30px;
            font-weight: 500;
            display: inline-block;
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
            animation: fadeInUp 0.4s ease-out forwards;
            transition: all 0.3s;
        }
        
        .state-chip:hover {
            box-shadow: 0 6px 15px rgba(78, 115, 223, 0.3);
            transform: translateY(-2px);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .state-chip i {
            margin-left: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .state-chip i:hover {
            transform: scale(1.15);
            opacity: 0.9;
        }
        
        /* Summary cards */
        .summary-card {
            border-radius: 14px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.07);
            transition: all 0.4s;
            height: 100%;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: -1;
        }
        
        .summary-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }
        
        .summary-card .card-body {
            padding: 1.8rem;
        }
        
        .summary-card .icon-area {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .summary-card:hover .icon-area {
            transform: scale(1.1);
        }
        
        .summary-card h3 {
            font-size: 1.85rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #333;
        }
        
        .summary-card p {
            font-size: 15px;
            color: #6e707e;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .bg-primary-light {
            background-color: rgba(78, 115, 223, 0.12);
            color: #4e73df;
        }
        
        .bg-success-light {
            background-color: rgba(28, 200, 138, 0.12);
            color: #1cc88a;
        }
        
        .bg-info-light {
            background-color: rgba(54, 185, 204, 0.12);
            color: #36b9cc;
        }
        
        .bg-warning-light {
            background-color: rgba(246, 194, 62, 0.12);
            color: #f6c23e;
        }
        
        .border-left-primary {
            border-left: 6px solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 6px solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 6px solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 6px solid #f6c23e !important;
        }
        
        /* Amount column styling */
        .amount-value {
            font-weight: 700;
            font-family: 'Roboto Mono', monospace;
            color: #2e3d51;
            transition: all 0.2s;
        }
        
        tr:hover .amount-value {
            color: #4e73df;
        }
        
        /* Table footer styling */
        .table tfoot tr {
            background-color: #f8f9fc;
            font-weight: 700;
        }
        
        .table tfoot td {
            border-top: 2px solid #4e73df;
        }
        
        /* Main heading styling */
        .h3.text-gray-800 {
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        
        .h3.text-gray-800::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background-color: #4e73df;
            border-radius: 2px;
        }
        
        /* Export buttons styling */
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        /* Animation for summary cards */
        #summary-cards .col-xl-3 {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInStagger 0.5s ease-out forwards;
        }
        
        #summary-cards .col-xl-3:nth-child(1) { animation-delay: 0.1s; }
        #summary-cards .col-xl-3:nth-child(2) { animation-delay: 0.2s; }
        #summary-cards .col-xl-3:nth-child(3) { animation-delay: 0.3s; }
        #summary-cards .col-xl-3:nth-child(4) { animation-delay: 0.4s; }
        
        @keyframes fadeInStagger {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Table row hover animation */
        #accounts-table tbody tr {
            transition: all 0.2s;
        }
        
        #accounts-table tbody tr:hover {
            transform: translateX(5px);
        }
        
        /* Status badges */
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
        }
        
        .status-badge-warning {
            background-color: rgba(246, 194, 62, 0.12);
            color: #f6c23e;
        }
        
        .status-badge-danger {
            background-color: rgba(231, 74, 59, 0.12);
            color: #e74a3b;
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
                <a href="noc.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-file-contract mr-2"></i> NOC
                </a>
                <a href="accounts.php" class="list-group-item list-group-item-action bg-dark text-white active">
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
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">
                    <i class="fas fa-money-bill-wave mr-2 text-primary"></i>Accounts Management
                </h1>
                
                <!-- State Selection Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-map-marker-alt mr-2"></i>Select State
                        </h6>
                        <span class="ml-auto badge badge-light font-weight-normal px-3 py-2">Step 1 of 1</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="state-select"><strong>Select State</strong></label>
                                <select class="form-control" id="state-select" name="state">
                                    <option value="">-- Select State --</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Kerala">Kerala</option>
                                    <option value="Telangana">Telangana</option>
                                    <option value="Gujarat">Gujarat</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                </select>
                                <small class="text-muted mt-2 d-block">Select a state to view financial accounts data</small>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" id="apply-state-btn">
                                    <i class="fas fa-check mr-1"></i> Apply
                                </button>
                                <button type="button" class="btn btn-secondary ml-2" id="reset-state-btn">
                                    <i class="fas fa-sync-alt mr-1"></i> Reset
                                </button>
                                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#helpModal">
                                    <i class="fas fa-question-circle mr-1"></i> Help
                                </button>
                            </div>
                        </div>
                        
                        <!-- Selected State Display -->
                        <div class="mt-3" id="selected-state-display" style="display: none;">
                            <div class="state-chip">
                                You have selected: <strong id="selected-state-name">Karnataka</strong>
                                <i class="fas fa-times-circle" id="remove-state"></i>
                            </div>
                            <div class="mt-3 text-muted">
                                <i class="fas fa-info-circle mr-1"></i> Showing financial data for the selected state. Last updated: <span class="font-weight-medium">Today at 09:45 AM</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Cards -->
                <div class="row mb-4" id="summary-cards" style="display: none;">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card summary-card border-left-primary">
                            <div class="card-body">
                                <div class="icon-area bg-primary-light">
                                    <i class="fas fa-rupee-sign"></i>
                                </div>
                                <h3 id="total-revenue">₹ 15,38,456</h3>
                                <p>Total Revenue</p>
                                <div class="progress mt-3" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card summary-card border-left-success">
                            <div class="card-body">
                                <div class="icon-area bg-success-light">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 id="total-registrations">2,456</h3>
                                <p>Total Registrations</p>
                                <div class="progress mt-3" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card summary-card border-left-info">
                            <div class="card-body">
                                <div class="icon-area bg-info-light">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h3 id="total-transactions">3,854</h3>
                                <p>Total Transactions</p>
                                <div class="progress mt-3" style="height: 5px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card summary-card border-left-warning">
                            <div class="card-body">
                                <div class="icon-area bg-warning-light">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <h3 id="avg-amount">₹ 628</h3>
                                <p>Average Amount</p>
                                <div class="progress mt-3" style="height: 5px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                <!-- Accounts Table -->
                <div class="card shadow mb-4" id="accounts-table-card" style="display: none;">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-table mr-2"></i>Accounts Data
                        </h6>
                        <div>
                            <button class="btn btn-sm btn-success" id="export-excel">
                                <i class="fas fa-file-excel mr-1"></i> Export to Excel
                            </button>
                            <button class="btn btn-sm btn-danger ml-2" id="export-pdf">
                                <i class="fas fa-file-pdf mr-1"></i> Export to PDF
                            </button>
                            <button class="btn btn-sm btn-dark ml-2" id="print-data">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="accounts-table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>SL. No</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Total Count</th>
                                        <!-- <th>Status</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table data will be populated dynamically -->
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="2" class="text-right">Total:</td>
                                        <td id="total-amount">₹ 15,38,456</td>
                                        <td id="total-count">3,854</td>
                                        <!-- <td></td> -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle mr-1"></i> Showing financial data for <span class="font-weight-medium" id="state-name-footer">Karnataka</span>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" id="refresh-data">
                                    <i class="fas fa-sync-alt mr-1"></i> Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel"><i class="fas fa-question-circle mr-2 text-primary"></i>Help Guide</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold"><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Step 1: Select a State</h6>
                        <p>Choose a state from the dropdown to view financial data specific to that state.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold"><i class="fas fa-chart-pie mr-2 text-success"></i>Step 2: View Summary</h6>
                        <p>The summary cards will show key financial metrics for the selected state.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold"><i class="fas fa-table mr-2 text-info"></i>Step 3: Detailed Data</h6>
                        <p>The accounts table provides a detailed breakdown of financial information.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold"><i class="fas fa-file-export mr-2 text-warning"></i>Step 4: Export Options</h6>
                        <p>You can export the data in Excel or PDF format, or print directly from the browser.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Got it!</button>
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
            // Initialize Select2
            $('#state-select').select2({
                placeholder: "Select a state",
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
            
            // Dummy data for different states
            const stateData = {
                "Karnataka": {
                    totalRevenue: "₹ 15,38,456",
                    totalRegistrations: "2,456",
                    totalTransactions: "3,854",
                    avgAmount: "₹ 628",
                    tableData: [
                        { description: "New Registration Fees", amount: "₹ 5,68,000", count: "568", status: "Completed" },
                        { description: "Renewal Fees", amount: "₹ 3,45,000", count: "690", status: "Completed" },
                        { description: "NOC Processing Fees", amount: "₹ 2,25,200", count: "751", status: "In Progress" },
                        { description: "Late Fee Penalties", amount: "₹ 1,86,756", count: "623", status: "Completed" },
                        { description: "Certificate Verification", amount: "₹ 1,12,500", count: "450", status: "Completed" },
                        { description: "Miscellaneous Fees", amount: "₹ 1,01,000", count: "772", status: "Pending" }
                    ]
                },
                "Andhra Pradesh": {
                    totalRevenue: "₹ 12,23,678",
                    totalRegistrations: "1,890",
                    totalTransactions: "2,967",
                    avgAmount: "₹ 647",
                    tableData: [
                        { description: "New Registration Fees", amount: "₹ 4,75,000", count: "475", status: "Completed" },
                        { description: "Renewal Fees", amount: "₹ 2,86,500", count: "573", status: "Completed" },
                        { description: "NOC Processing Fees", amount: "₹ 1,85,400", count: "618", status: "In Progress" },
                        { description: "Late Fee Penalties", amount: "₹ 1,56,778", count: "523", status: "Completed" },
                        { description: "Certificate Verification", amount: "₹ 98,000", count: "392", status: "Pending" },
                        { description: "Miscellaneous Fees", amount: "₹ 22,000", count: "386", status: "In Progress" }
                    ]
                },
                "Tamil Nadu": {
                    totalRevenue: "₹ 10,56,345",
                    totalRegistrations: "1,645",
                    totalTransactions: "2,456",
                    avgAmount: "₹ 585",
                    tableData: [
                        { description: "New Registration Fees", amount: "₹ 3,89,000", count: "389", status: "Completed" },
                        { description: "Renewal Fees", amount: "₹ 2,75,500", count: "551", status: "Completed" },
                        { description: "NOC Processing Fees", amount: "₹ 1,25,875", count: "503", status: "In Progress" },
                        { description: "Late Fee Penalties", amount: "₹ 98,970", count: "330", status: "Completed" },
                        { description: "Certificate Verification", amount: "₹ 87,000", count: "348", status: "Pending" },
                        { description: "Miscellaneous Fees", amount: "₹ 80,000", count: "335", status: "Completed" }
                    ]
                },
                "Maharashtra": {
                    totalRevenue: "₹ 18,75,225",
                    totalRegistrations: "2,980",
                    totalTransactions: "4,256",
                    avgAmount: "₹ 629",
                    tableData: [
                        { description: "New Registration Fees", amount: "₹ 7,25,000", count: "725", status: "Completed" },
                        { description: "Renewal Fees", amount: "₹ 4,18,500", count: "837", status: "Completed" },
                        { description: "NOC Processing Fees", amount: "₹ 2,98,750", count: "995", status: "In Progress" },
                        { description: "Late Fee Penalties", amount: "₹ 2,15,975", count: "720", status: "Completed" },
                        { description: "Certificate Verification", amount: "₹ 1,32,000", count: "528", status: "Pending" },
                        { description: "Miscellaneous Fees", amount: "₹ 85,000", count: "451", status: "Completed" }
                    ]
                },
                "Kerala": {
                    totalRevenue: "₹ 9,24,580",
                    totalRegistrations: "1,420",
                    totalTransactions: "2,156",
                    avgAmount: "₹ 572",
                    tableData: [
                        { description: "New Registration Fees", amount: "₹ 3,56,000", count: "356", status: "Completed" },
                        { description: "Renewal Fees", amount: "₹ 2,15,000", count: "430", status: "Completed" },
                        { description: "NOC Processing Fees", amount: "₹ 1,12,580", count: "375", status: "In Progress" },
                        { description: "Late Fee Penalties", amount: "₹ 87,000", count: "290", status: "Completed" },
                        { description: "Certificate Verification", amount: "₹ 78,000", count: "312", status: "Pending" },
                        { description: "Miscellaneous Fees", amount: "₹ 76,000", count: "393", status: "Completed" }
                    ]
                }
            };
            
            // Apply state selection button click
            $('#apply-state-btn').on('click', function() {
                const selectedState = $('#state-select').val();
                
                if (selectedState) {
                    // Update selected state display
                    $('#selected-state-name').text(selectedState);
                    $('#state-name-footer').text(selectedState);
                    $('#selected-state-display').slideDown();
                    
                    // Display data for the selected state with animation
                    displayStateData(selectedState);
                    
                    // Show summary cards and table with animation
                    $('#summary-cards').fadeIn(400);
                    setTimeout(() => {
                        $('#trend-card').slideDown(400);
                    }, 300);
                    setTimeout(() => {
                        $('#accounts-table-card').slideDown(400);
                    }, 600);
                } else {
                    // Show error toast
                    showToast('Please select a state first', 'warning');
                }
            });
            
            // Remove selected state
            $('#remove-state').on('click', function() {
                resetStateSelection();
            });
            
            // Reset state button click
            $('#reset-state-btn').on('click', function() {
                resetStateSelection();
                showToast('Filters have been reset', 'info');
            });
            
            // Function to reset state selection
            function resetStateSelection() {
                $('#state-select').val('').trigger('change');
                $('#selected-state-display').slideUp();
                $('#summary-cards, #trend-card, #accounts-table-card').slideUp();
            }
            
            // Function to show toast messages
            function showToast(message, type = 'success') {
                // Create toast container if it doesn't exist
                if ($('#toast-container').length === 0) {
                    $('body').append('<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
                }
                
                // Generate random ID for the toast
                const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
                
                // Set icon based on type
                let icon = 'info-circle';
                if (type === 'success') icon = 'check-circle';
                if (type === 'warning') icon = 'exclamation-triangle';
                if (type === 'danger') icon = 'exclamation-circle';
                
                // Create toast element
                const toast = `
                    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" style="min-width: 250px; opacity: 0; transition: opacity 0.3s ease;">
                        <div class="toast-header bg-${type} text-white">
                            <i class="fas fa-${icon} mr-2"></i>
                            <strong class="mr-auto">Notification</strong>
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
                
                // Show toast with animation
                setTimeout(() => {
                    $(`#${toastId}`).css('opacity', '1');
                }, 100);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    $(`#${toastId}`).css('opacity', '0');
                    setTimeout(() => {
                        $(`#${toastId}`).remove();
                    }, 300);
                }, 3000);
            }
            
            // Function to display data for the selected state
            function displayStateData(state) {
                const data = stateData[state] || stateData["Karnataka"]; // Default to Karnataka if state data not found
                
                // Update summary cards
                $('#total-revenue').text(data.totalRevenue);
                $('#total-registrations').text(data.totalRegistrations);
                $('#total-transactions').text(data.totalTransactions);
                $('#avg-amount').text(data.avgAmount);
                
                // Clear existing table data
                const tableBody = $('#accounts-table tbody');
                tableBody.empty();
                
                // Populate table with state data
                $.each(data.tableData, function(index, item) {
                    // Determine status badge class
                    let statusBadgeClass = 'status-badge-success';
                    if (item.status === 'Pending') statusBadgeClass = 'status-badge-warning';
                    if (item.status === 'In Progress') statusBadgeClass = 'status-badge-info';
                    
                    // Create table row with animation delay
                    const row = $(`
                        <tr style="opacity: 0; transform: translateY(10px); transition: all 0.3s ease;" data-index="${index}">
                            <td>${index + 1}</td>
                            <td>${item.description}</td>
                            <td class="amount-value">${item.amount}</td>
                            <td>${item.count}</td>
                            <!-- <td><span class="status-badge ${statusBadgeClass}">${item.status}</span></td> -->
                        </tr>
                    `);
                    
                    tableBody.append(row);
                    
                    // Animate row entrance with delay
                    setTimeout(() => {
                        row.css({
                            'opacity': '1',
                            'transform': 'translateY(0)'
                        });
                    }, 100 + (index * 50));
                });
                
                // Update table totals
                $('#total-amount').text(data.totalRevenue);
                $('#total-count').text(data.totalTransactions);
                
                // Show success message
                showToast(`Financial data for ${state} loaded successfully`, 'success');
            }
            
            // Export to Excel button click
            $('#export-excel').on('click', function() {
                const selectedState = $('#selected-state-name').text();
                showToast(`Exporting ${selectedState} accounts data to Excel...`, 'success');
                // In a real implementation, this would trigger an AJAX call or form submission
            });
            
            // Export to PDF button click
            $('#export-pdf').on('click', function() {
                const selectedState = $('#selected-state-name').text();
                showToast(`Exporting ${selectedState} accounts data to PDF...`, 'success');
                // In a real implementation, this would trigger an AJAX call or form submission
            });
            
            // Print data button click
            $('#print-data').on('click', function() {
                const selectedState = $('#selected-state-name').text();
                showToast(`Preparing ${selectedState} accounts data for printing...`, 'info');
                // In a real implementation, this would open a print dialog
            });
            
            // Refresh data button click
            $('#refresh-data').on('click', function() {
                const selectedState = $('#selected-state-name').text();
                
                // Show loading state
                $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...');
                $(this).prop('disabled', true);
                
                // Simulate refreshing data
                setTimeout(() => {
                    // Display data again
                    displayStateData(selectedState);
                    
                    // Reset button state
                    $(this).html('<i class="fas fa-sync-alt mr-1"></i> Refresh Data');
                    $(this).prop('disabled', false);
                    
                    // Show success message
                    showToast(`${selectedState} financial data refreshed successfully`, 'success');
                }, 1500);
            });
        });
    </script>
</body>
</html>
