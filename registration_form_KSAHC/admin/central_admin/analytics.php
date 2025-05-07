<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOC Analytics | Karnataka State Allied & Healthcare Council</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Poppins', sans-serif;
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
            border-radius: 15px;
            box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.8rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
            color: #4e73df;
            padding: 1.25rem 1.5rem;
        }
        
        .analytics-card {
            height: 480px;
            position: relative;
        }
        
        .main-heading {
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 24px;
            font-weight: 700;
        }
        
        .main-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #4e73df, #36b9cc);
            border-radius: 2px;
        }
        
        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
            padding: 0.5rem;
        }
        
        .filter-btn {
            padding: 0.45rem 1rem;
            font-size: 0.85rem;
            border-radius: 30px;
            margin: 0 5px;
            font-weight: 600;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-color: transparent;
        }
        
        .btn-outline-primary {
            color: #4e73df;
            border-color: #dee2e6;
        }
        
        .btn-outline-success {
            color: #1cc88a;
            border-color: #dee2e6;
        }
        
        .btn-outline-info {
            color: #36b9cc;
            border-color: #dee2e6;
        }
        
        .btn-outline-warning {
            color: #f6c23e;
            border-color: #dee2e6;
        }
        
        .filter-btn.active.btn-outline-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
        
        .filter-btn.active.btn-outline-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        }
        
        .filter-btn.active.btn-outline-info {
            background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        }
        
        .filter-btn.active.btn-outline-warning {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        }
        
        .card-stats {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .card-stats::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .card-stats h4 {
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1.8rem;
        }
        
        .card-stats p {
            opacity: 0.8;
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .stat-icon {
            font-size: 2.8rem;
            opacity: 0.8;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        @keyframes floatAnimation {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        
        .stat-icon i {
            animation: floatAnimation 3s ease-in-out infinite;
        }
        
        .animated {
            animation-duration: 0.8s;
            animation-fill-mode: both;
        }
        
        .fadeInUp {
            animation-name: fadeInUp;
        }
        
        .delay-1 {
            animation-delay: 0.1s;
        }
        
        .delay-2 {
            animation-delay: 0.2s;
        }
        
        .delay-3 {
            animation-delay: 0.3s;
        }
        
        .delay-4 {
            animation-delay: 0.4s;
        }
        
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1rem;
            padding: 0.5rem;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 4px;
            margin-right: 8px;
        }
        
        .tooltip-card {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            font-family: 'Poppins', sans-serif;
        }
        
        .state-selector {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .state-btn {
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid #e3e6f0;
            background: white;
            color: #555;
        }
        
        .state-btn.active {
            background: #4e73df;
            color: white;
            border-color: #4e73df;
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.25);
        }
        
        .state-btn:hover:not(.active) {
            background: #f8f9fc;
            transform: translateY(-2px);
        }
        
        .date-range-selector {
            max-width: 500px;
            margin: 0 auto 2rem auto;
            background: white;
            border-radius: 50px;
            padding: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            border: 1px solid #e3e6f0;
        }
        
        .date-range-selector select, .date-range-selector input {
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            margin: 0 0.25rem;
            font-size: 0.9rem;
            background: #f8f9fc;
            color: #555;
        }
        
        .date-range-selector select:focus, .date-range-selector input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
        }
        
        .date-range-selector .date-separator {
            margin: 0 0.5rem;
            color: #888;
        }
        
        .date-range-selector .date-icon {
            margin: 0 0.5rem;
            color: #4e73df;
        }
        
        .date-range-btn {
            background: #4e73df;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            margin-left: auto;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.25);
            transition: all 0.3s;
        }
        
        .date-range-btn:hover {
            background: #2e59d9;
            box-shadow: 0 6px 15px rgba(78, 115, 223, 0.35);
            transform: translateY(-1px);
        }
        
        /* Loading animation for charts */
        .chart-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .chart-loading.active {
            opacity: 1;
            visibility: visible;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(78, 115, 223, 0.2);
            border-left-color: #4e73df;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Comparison arrow indicators */
        .comparison-indicator {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            margin-left: 8px;
            display: inline-flex;
            align-items: center;
        }
        
        .comparison-indicator i {
            margin-right: 4px;
        }
        
        .comparison-up {
            background: rgba(28, 200, 138, 0.15);
            color: #1cc88a;
        }
        
        .comparison-down {
            background: rgba(231, 74, 59, 0.15);
            color: #e74a3b;
        }
        
        /* State flag icons for better visualization */
        .state-flag {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 8px;
            background-size: cover;
            display: inline-block;
            vertical-align: middle;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Chart overlay controls */
        .chart-overlay-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            z-index: 5;
        }
        
        .chart-control-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e3e6f0;
            margin-left: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .chart-control-btn:hover {
            background: #f8f9fc;
            color: #4e73df;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Enhanced card design with gradient borders */
        .card-gradient-border {
            position: relative;
            padding: 4px;
            background: linear-gradient(135deg, #4e73df, #36b9cc);
            border-radius: 18px;
        }
        
        .card-inner {
            background: white;
            border-radius: 15px;
            height: 100%;
        }
        
        /* Wave animation for stat cards */
        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,128L48,144C96,160,192,192,288,192C384,192,480,160,576,133.3C672,107,768,85,864,96C960,107,1056,149,1152,154.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: center;
        }
        
        /* Glowing effect for active filters */
        .filter-btn.active {
            box-shadow: 0 0 15px rgba(78, 115, 223, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
            100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
        }
        
        /* Year filter pill */
        .year-filter-pill {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            color: #4e73df;
            font-weight: 700;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            z-index: 10;
            font-size: 1.1rem;
            border: 3px solid #f8f9fc;
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
                <a href="analytics.php" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="fas fa-chart-bar mr-2"></i> Analytics
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
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold main-heading animated fadeInUp">
                    <i class="fas fa-chart-bar mr-2 text-primary"></i>NOC Analytics Dashboard
                </h1>
                
                <!-- Date Range Selector -->
                <div class="date-range-selector animated fadeInUp">
                    <i class="fas fa-calendar-alt date-icon"></i>
                    <select id="period-selector" class="custom-select">
                        <option value="this-month">This Month</option>
                        <option value="last-month">Last Month</option>
                        <option value="this-quarter" selected>This Quarter</option>
                        <option value="last-quarter">Last Quarter</option>
                        <option value="this-year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                    <span id="custom-date-range" style="display:none">
                        <span class="date-separator">From</span>
                        <input type="date" id="date-from" class="form-control">
                        <span class="date-separator">To</span>
                        <input type="date" id="date-to" class="form-control">
                    </span>
                    <button id="update-date-range" class="date-range-btn">
                        <i class="fas fa-sync-alt mr-1"></i> Update
                    </button>
                </div>
                
                <!-- Summary Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4 animated fadeInUp delay-1">
                        <div class="card card-stats h-100">
                            <div class="wave-bg"></div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">215</h4>
                                        <p>Total NOCs</p>
                                        <span class="comparison-indicator comparison-up">
                                            <i class="fas fa-arrow-up"></i> 12%
                                        </span>
                                    </div>
                                    <div class="col-4 text-right">
                                        <div class="stat-icon">
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4 animated fadeInUp delay-2">
                        <div class="card card-stats h-100" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                            <div class="wave-bg"></div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">132</h4>
                                        <p>Approved NOCs</p>
                                        <span class="comparison-indicator comparison-up">
                                            <i class="fas fa-arrow-up"></i> 8%
                                        </span>
                                    </div>
                                    <div class="col-4 text-right">
                                        <div class="stat-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4 animated fadeInUp delay-3">
                        <div class="card card-stats h-100" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                            <div class="wave-bg"></div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">83</h4>
                                        <p>Pending NOCs</p>
                                        <span class="comparison-indicator comparison-down">
                                            <i class="fas fa-arrow-down"></i> 3%
                                        </span>
                                    </div>
                                    <div class="col-4 text-right">
                                        <div class="stat-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4 animated fadeInUp delay-4">
                        <div class="card card-stats h-100" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
                            <div class="wave-bg"></div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">9</h4>
                                        <p>States Involved</p>
                                        <span class="comparison-indicator comparison-up">
                                            <i class="fas fa-arrow-up"></i> 2
                                        </span>
                                    </div>
                                    <div class="col-4 text-right">
                                        <div class="stat-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- State Selector -->
                <div class="state-selector animated fadeInUp">
                    <button class="state-btn active" data-state="all">All States</button>
                    <button class="state-btn" data-state="Karnataka">Karnataka</button>
                    <button class="state-btn" data-state="Gujarat">Gujarat</button>
                    <button class="state-btn" data-state="Maharashtra">Maharashtra</button>
                    <button class="state-btn" data-state="Tamil Nadu">Tamil Nadu</button>
                    <button class="state-btn" data-state="Rajasthan">Rajasthan</button>
                    <button class="state-btn" data-state="Punjab">Punjab</button>
                </div>
                
                <!-- State-wise NOC Pending Analytics -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card-gradient-border animated fadeInUp delay-1">
                            <div class="card-inner">
                                <div class="year-filter-pill">2025</div>
                                <div class="card shadow analytics-card">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-chart-bar mr-2"></i>State-wise NOC Status
                                        </h6>
                                        <div class="filter-buttons">
                                            <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="pending" data-chart="stateNoc">Pending</button>
                                            <button class="btn btn-sm btn-outline-success filter-btn" data-filter="approved" data-chart="stateNoc">Approved</button>
                                            <button class="btn btn-sm btn-outline-info filter-btn" data-filter="total" data-chart="stateNoc">Total</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-loading">
                                            <div class="spinner"></div>
                                        </div>
                                        <!-- <div class="chart-overlay-controls">
                                            <div class="chart-control-btn" data-action="download" data-chart="stateNocChart">
                                                <i class="fas fa-download"></i>
                                            </div>
                                            <div class="chart-control-btn" data-action="fullscreen" data-chart="stateNocChart">
                                                <i class="fas fa-expand"></i>
                                            </div>
                                        </div> -->
                                        <div class="chart-container">
                                            <canvas id="stateNocChart"></canvas>
                                        </div>
                                        <div class="chart-legend" id="stateNocLegend"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- NOC Issuance Analytics -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card-gradient-border animated fadeInUp delay-2">
                            <div class="card-inner">
                                <div class="year-filter-pill">2025</div>
                                <div class="card shadow analytics-card">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-exchange-alt mr-2"></i>State-wise NOC Issuance
                                        </h6>
                                        <div class="filter-buttons">
                                            <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="sent" data-chart="nocIssuance">Sent</button>
                                            <button class="btn btn-sm btn-outline-success filter-btn" data-filter="received" data-chart="nocIssuance">Received</button>
                                            <!-- <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="both" data-chart="nocIssuance">Both</button> -->
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-loading">
                                            <div class="spinner"></div>
                                        </div>
                                        <!-- <div class="chart-overlay-controls">
                                            <div class="chart-control-btn" data-action="download" data-chart="nocIssuanceChart">
                                                <i class="fas fa-download"></i>
                                            </div>
                                            <div class="chart-control-btn" data-action="fullscreen" data-chart="nocIssuanceChart">
                                                <i class="fas fa-expand"></i>
                                            </div>
                                        </div> -->
                                        <div class="chart-container">
                                            <canvas id="nocIssuanceChart"></canvas>
                                        </div>
                                        <div class="chart-legend" id="nocIssuanceLegend"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Analytics Info -->
                <!-- <div class="row mb-4">
                    <div class="col-lg-6 animated fadeInUp delay-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-calendar-alt mr-2"></i>Monthly Trend
                                </h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 250px">
                                    <canvas id="monthlyTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 animated fadeInUp delay-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-tasks mr-2"></i>Top NOC Requirements
                                </h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 250px">
                                    <canvas id="requirementsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Chart.js custom colors
            const colorPalette = {
                primary: '#4e73df',
                success: '#1cc88a',
                warning: '#f6c23e',
                danger: '#e74a3b',
                info: '#36b9cc',
                primaryLight: 'rgba(78, 115, 223, 0.6)',
                successLight: 'rgba(28, 200, 138, 0.6)',
                warningLight: 'rgba(246, 194, 62, 0.6)',
                dangerLight: 'rgba(231, 74, 59, 0.6)',
                infoLight: 'rgba(54, 185, 204, 0.6)',
                gradients: {
                    primary: createGradient('#4e73df', 'rgba(78, 115, 223, 0.05)'),
                    success: createGradient('#1cc88a', 'rgba(28, 200, 138, 0.05)'),
                    warning: createGradient('#f6c23e', 'rgba(246, 194, 62, 0.05)'),
                    info: createGradient('#36b9cc', 'rgba(54, 185, 204, 0.05)')
                }
            };
            
            // Function to create gradient for chart backgrounds
            function createGradient(colorStart, colorEnd) {
                const ctx = document.createElement('canvas').getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            }
            
            // State-wise data - expanded with state-specific data
            const states = ['Karnataka', 'Gujarat', 'Maharashtra', 'Tamil Nadu', 'Rajasthan', 'Punjab'];
            
            // NOC data - refined to match state-wise specifics
            const nocData = {
                all: {
                    pending: [32, 28, 45, 19, 36, 23],
                    approved: [68, 59, 73, 42, 54, 37],
                    total: [100, 87, 118, 61, 90, 60]
                },
                Karnataka: {
                    pending: [32, 0, 0, 0, 0, 0],
                    approved: [68, 0, 0, 0, 0, 0],
                    total: [100, 0, 0, 0, 0, 0]
                },
                Gujarat: {
                    pending: [0, 28, 0, 0, 0, 0],
                    approved: [0, 59, 0, 0, 0, 0],
                    total: [0, 87, 0, 0, 0, 0]
                },
                Maharashtra: {
                    pending: [0, 0, 45, 0, 0, 0],
                    approved: [0, 0, 73, 0, 0, 0],
                    total: [0, 0, 118, 0, 0, 0]
                },
                'Tamil Nadu': {
                    pending: [0, 0, 0, 19, 0, 0],
                    approved: [0, 0, 0, 42, 0, 0],
                    total: [0, 0, 0, 61, 0, 0]
                },
                Rajasthan: {
                    pending: [0, 0, 0, 0, 36, 0],
                    approved: [0, 0, 0, 0, 54, 0],
                    total: [0, 0, 0, 0, 90, 0]
                },
                Punjab: {
                    pending: [0, 0, 0, 0, 0, 23],
                    approved: [0, 0, 0, 0, 0, 37],
                    total: [0, 0, 0, 0, 0, 60]
                }
            };
            
            // Issuance data - with state-to-state NOC flows
            const issuanceData = {
                all: {
                    sent: [42, 35, 51, 29, 38, 25],
                    received: [58, 52, 67, 32, 52, 35],
                    both: [100, 87, 118, 61, 90, 60]
                },
                Karnataka: {
                    sent: [0, 12, 10, 8, 7, 5],
                    received: [0, 15, 18, 10, 8, 7],
                    both: [0, 27, 28, 18, 15, 12]
                },
                Gujarat: {
                    sent: [15, 0, 8, 5, 4, 3],
                    received: [12, 0, 14, 9, 10, 7],
                    both: [27, 0, 22, 14, 14, 10]
                },
                Maharashtra: {
                    sent: [18, 14, 0, 7, 6, 6],
                    received: [10, 8, 0, 5, 4, 5],
                    both: [28, 22, 0, 12, 10, 11]
                },
                'Tamil Nadu': {
                    sent: [10, 9, 5, 0, 3, 2],
                    received: [8, 5, 7, 0, 7, 5],
                    both: [18, 14, 12, 0, 10, 7]
                },
                Rajasthan: {
                    sent: [8, 10, 4, 7, 0, 9],
                    received: [7, 4, 6, 3, 0, 10],
                    both: [15, 14, 10, 10, 0, 19]
                },
                Punjab: {
                    sent: [7, 7, 5, 5, 10, 0],
                    received: [5, 3, 6, 2, 9, 0],
                    both: [12, 10, 11, 7, 19, 0]
                }
            };
            
            // Time period data for filters
            const timePeriodsData = {
                'this-month': {
                    nocData: {
                        pending: [27, 23, 38, 15, 31, 19],
                        approved: [58, 49, 62, 35, 46, 31],
                        total: [85, 72, 100, 50, 77, 50]
                    },
                    issuanceData: {
                        sent: [36, 29, 43, 24, 32, 21],
                        received: [49, 43, 57, 26, 45, 29],
                        both: [85, 72, 100, 50, 77, 50]
                    }
                },
                'last-month': {
                    nocData: {
                        pending: [35, 31, 52, 23, 41, 27],
                        approved: [75, 66, 81, 48, 59, 43],
                        total: [110, 97, 133, 71, 100, 70]
                    },
                    issuanceData: {
                        sent: [47, 41, 57, 34, 42, 30],
                        received: [63, 56, 76, 37, 58, 40],
                        both: [110, 97, 133, 71, 100, 70]
                    }
                },
                'this-quarter': {
                    nocData: {
                        pending: [32, 28, 45, 19, 36, 23],
                        approved: [68, 59, 73, 42, 54, 37],
                        total: [100, 87, 118, 61, 90, 60]
                    },
                    issuanceData: {
                        sent: [42, 35, 51, 29, 38, 25],
                        received: [58, 52, 67, 32, 52, 35],
                        both: [100, 87, 118, 61, 90, 60]
                    }
                },
                'last-quarter': {
                    nocData: {
                        pending: [29, 25, 40, 17, 32, 20],
                        approved: [61, 54, 65, 38, 48, 33],
                        total: [90, 79, 105, 55, 80, 53]
                    },
                    issuanceData: {
                        sent: [38, 32, 46, 26, 34, 22],
                        received: [52, 47, 59, 29, 46, 31],
                        both: [90, 79, 105, 55, 80, 53]
                    }
                },
                'this-year': {
                    nocData: {
                        pending: [38, 33, 54, 23, 43, 28],
                        approved: [82, 71, 88, 51, 65, 45],
                        total: [120, 104, 142, 74, 108, 73]
                    },
                    issuanceData: {
                        sent: [51, 42, 62, 35, 46, 31],
                        received: [69, 62, 80, 39, 62, 42],
                        both: [120, 104, 142, 74, 108, 73]
                    }
                },
                'custom': {
                    nocData: {
                        pending: [32, 28, 45, 19, 36, 23],
                        approved: [68, 59, 73, 42, 54, 37],
                        total: [100, 87, 118, 61, 90, 60]
                    },
                    issuanceData: {
                        sent: [42, 35, 51, 29, 38, 25],
                        received: [58, 52, 67, 32, 52, 35],
                        both: [100, 87, 118, 61, 90, 60]
                    }
                }
            };
            
            // Current filters state
            let currentState = 'all';
            let currentPeriod = 'this-quarter';
            let currentNocFilter = 'pending';
            let currentIssuanceFilter = 'sent';
            
            // Show loading animation
            function showLoading(chartId) {
                $(chartId).closest('.card-body').find('.chart-loading').addClass('active');
            }
            
            // Hide loading animation
            function hideLoading(chartId) {
                setTimeout(function() {
                    $(chartId).closest('.card-body').find('.chart-loading').removeClass('active');
                }, 500);
            }
            
            // Initialize state-wise NOC Chart
            const stateNocCtx = document.getElementById('stateNocChart').getContext('2d');
            let stateNocChart = new Chart(stateNocCtx, {
                type: 'bar',
                data: {
                    labels: states,
                    datasets: [{
                        label: 'Pending NOCs',
                        data: nocData.all.pending,
                        backgroundColor: colorPalette.gradients.primary,
                        borderColor: colorPalette.primary,
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                        hoverBackgroundColor: colorPalette.primary
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold',
                                family: 'Poppins'
                            },
                            bodyFont: {
                                size: 13,
                                family: 'Poppins'
                            },
                            padding: 12,
                            cornerRadius: 8,
                            caretSize: 8,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw} applications`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    family: 'Poppins'
                                },
                                callback: function(value) {
                                    return value;
                                }
                            },
                            title: {
                                display: true,
                                text: '(in thousands)',
                                font: {
                                    family: 'Poppins',
                                    size: 12,
                                    weight: 'bold'
                                },
                                padding: {top: 0, bottom: 10}
                            }
                        }
                    }
                }
            });
            
            // Initialize NOC Issuance Chart
            const nocIssuanceCtx = document.getElementById('nocIssuanceChart').getContext('2d');
            let nocIssuanceChart = new Chart(nocIssuanceCtx, {
                type: 'bar',
                data: {
                    labels: states,
                    datasets: [{
                        label: 'NOCs Sent',
                        data: issuanceData.all.sent,
                        backgroundColor: colorPalette.gradients.info,
                        borderColor: colorPalette.info,
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                        hoverBackgroundColor: colorPalette.info
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold',
                                family: 'Poppins'
                            },
                            bodyFont: {
                                size: 13,
                                family: 'Poppins'
                            },
                            padding: 12,
                            cornerRadius: 8,
                            caretSize: 8,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw} applications`;
                                },
                                afterLabel: function(context) {
                                    const state = context.label;
                                    const filter = currentIssuanceFilter;
                                    
                                    if (filter === 'sent') {
                                        return `From ${currentState === 'all' ? 'all states' : currentState} to ${state}`;
                                    } else if (filter === 'received') {
                                        return `From ${state} to ${currentState === 'all' ? 'all states' : currentState}`;
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    family: 'Poppins'
                                },
                                callback: function(value) {
                                    return value;
                                }
                            },
                            title: {
                                display: true,
                                text: '(in thousands)',
                                font: {
                                    family: 'Poppins',
                                    size: 12,
                                    weight: 'bold'
                                },
                                padding: {top: 0, bottom: 10}
                            }
                        }
                    }
                }
            });
            
            // Create chart legends
            createChartLegend('stateNocLegend', [{
                color: colorPalette.primary,
                label: 'Pending NOCs'
            }]);
            
            createChartLegend('nocIssuanceLegend', [{
                color: colorPalette.info,
                label: 'NOCs Sent'
            }]);
            
            // Function to create custom legend
            function createChartLegend(elementId, items) {
                const container = document.getElementById(elementId);
                container.innerHTML = '';
                
                items.forEach(item => {
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item';
                    
                    const colorBox = document.createElement('span');
                    colorBox.className = 'legend-color';
                    colorBox.style.backgroundColor = item.color;
                    
                    const label = document.createElement('span');
                    label.textContent = item.label;
                    
                    legendItem.appendChild(colorBox);
                    legendItem.appendChild(label);
                    container.appendChild(legendItem);
                });
            }
            
            // Function to update State NOC Chart
            function updateStateNocChart() {
                showLoading('#stateNocChart');
                
                let backgroundColor, borderColor, label;
                const data = timePeriodsData[currentPeriod].nocData[currentNocFilter];
                const stateData = currentState === 'all' ? data : nocData[currentState][currentNocFilter];
                
                switch(currentNocFilter) {
                    case 'pending':
                        backgroundColor = colorPalette.gradients.primary;
                        borderColor = colorPalette.primary;
                        label = 'Pending NOCs';
                        break;
                    case 'approved':
                        backgroundColor = colorPalette.gradients.success;
                        borderColor = colorPalette.success;
                        label = 'Approved NOCs';
                        break;
                    case 'total':
                        backgroundColor = colorPalette.gradients.info;
                        borderColor = colorPalette.info;
                        label = 'Total NOCs';
                        break;
                }
                
                stateNocChart.data.datasets[0].data = stateData;
                stateNocChart.data.datasets[0].label = label;
                stateNocChart.data.datasets[0].backgroundColor = backgroundColor;
                stateNocChart.data.datasets[0].borderColor = borderColor;
                stateNocChart.options.plugins.tooltip.callbacks.afterLabel = function(context) {
                    const state = context.label;
                    return currentState === 'all' ? '' : `Showing data for ${currentState}`;
                };
                
                createChartLegend('stateNocLegend', [{
                    color: borderColor,
                    label: label
                }]);
                
                stateNocChart.update();
                hideLoading('#stateNocChart');
            }
            
            // Function to update NOC Issuance Chart
            function updateNocIssuanceChart() {
                showLoading('#nocIssuanceChart');
                
                let backgroundColor, borderColor, label;
                const data = timePeriodsData[currentPeriod].issuanceData[currentIssuanceFilter];
                const stateData = currentState === 'all' ? data : issuanceData[currentState][currentIssuanceFilter];
                
                switch(currentIssuanceFilter) {
                    case 'sent':
                        backgroundColor = colorPalette.gradients.info;
                        borderColor = colorPalette.info;
                        label = 'NOCs Sent';
                        break;
                    case 'received':
                        backgroundColor = colorPalette.gradients.success;
                        borderColor = colorPalette.success;
                        label = 'NOCs Received';
                        break;
                    case 'both':
                        backgroundColor = colorPalette.gradients.warning;
                        borderColor = colorPalette.warning;
                        label = 'Total NOC Transactions';
                        break;
                }
                
                nocIssuanceChart.data.datasets[0].data = stateData;
                nocIssuanceChart.data.datasets[0].label = label;
                nocIssuanceChart.data.datasets[0].backgroundColor = backgroundColor;
                nocIssuanceChart.data.datasets[0].borderColor = borderColor;
                
                createChartLegend('nocIssuanceLegend', [{
                    color: borderColor,
                    label: label
                }]);
                
                nocIssuanceChart.update();
                hideLoading('#nocIssuanceChart');
            }
            
            // Filter buttons functionality for charts
            $('.filter-btn[data-filter]').click(function() {
                const filterType = $(this).data('filter');
                const chartType = $(this).data('chart');
                
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                
                if (chartType === 'stateNoc') {
                    currentNocFilter = filterType;
                    updateStateNocChart();
                } else if (chartType === 'nocIssuance') {
                    currentIssuanceFilter = filterType;
                    updateNocIssuanceChart();
                }
            });
            
            // State selector functionality
            $('.state-btn').click(function() {
                $('.state-btn').removeClass('active');
                $(this).addClass('active');
                
                currentState = $(this).data('state');
                
                // Update both charts
                updateStateNocChart();
                updateNocIssuanceChart();
            });
            
            // Time period selector
            $('#period-selector').change(function() {
                currentPeriod = $(this).val();
                
                if (currentPeriod === 'custom') {
                    $('#custom-date-range').show();
                } else {
                    $('#custom-date-range').hide();
                    
                    // Update both charts
                    updateStateNocChart();
                    updateNocIssuanceChart();
                }
            });
            
            // Update button for custom date range
            $('#update-date-range').click(function() {
                // In a real application, this would make an AJAX call to get data for the custom range
                // For this demo, we'll just use the 'custom' preset data
                
                if (currentPeriod === 'custom') {
                    const fromDate = $('#date-from').val();
                    const toDate = $('#date-to').val();
                    
                    if (fromDate && toDate) {
                        // Show loading
                        showLoading('#stateNocChart');
                        showLoading('#nocIssuanceChart');
                        
                        // Simulate loading delay
                        setTimeout(function() {
                            updateStateNocChart();
                            updateNocIssuanceChart();
                        }, 800);
                    } else {
                        alert('Please select both From and To dates');
                    }
                } else {
                    updateStateNocChart();
                    updateNocIssuanceChart();
                }
            });
            
            // Chart control buttons
            $('.chart-control-btn').click(function() {
                const action = $(this).data('action');
                const chartId = $(this).data('chart');
                const chart = chartId === 'stateNocChart' ? stateNocChart : nocIssuanceChart;
                
                if (action === 'download') {
                    // Download chart as image
                    const link = document.createElement('a');
                    link.href = document.getElementById(chartId).toDataURL('image/png');
                    link.download = chartId + '.png';
                    link.click();
                } else if (action === 'fullscreen') {
                    // Toggle fullscreen for chart container
                    const container = $(this).closest('.card');
                    
                    if (!document.fullscreenElement) {
                        container[0].requestFullscreen().catch(err => {
                            console.log(`Error attempting to enable fullscreen: ${err.message}`);
                        });
                        $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
                    } else {
                        document.exitFullscreen();
                        $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
                    }
                }
            });
            
            // Handle fullscreen change event
            document.addEventListener('fullscreenchange', function() {
                if (!document.fullscreenElement) {
                    $('.chart-control-btn[data-action="fullscreen"] i').removeClass('fa-compress').addClass('fa-expand');
                }
            });
            
            // Initialize with default filters
            updateStateNocChart();
            updateNocIssuanceChart();
        });
    </script>
</body>
</html>
