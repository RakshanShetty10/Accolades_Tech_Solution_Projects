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
        
        .analytics-card {
            height: 450px;
        }
        
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
        
        .chart-container {
            position: relative;
            height: 340px;
            width: 100%;
        }
        
        .filter-btn {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 20px;
            margin: 0 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .filter-btn:hover, .filter-btn.active {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .filter-btn.active {
            background-color: #4e73df;
            color: white;
            border-color: #4e73df;
        }
        
        .card-stats {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
        }
        
        .card-stats h4 {
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .card-stats p {
            opacity: 0.8;
            margin-bottom: 0;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        .animated {
            animation-duration: 0.6s;
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
                
                <!-- Summary Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4 animated fadeInUp delay-1">
                        <div class="card card-stats h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">215</h4>
                                        <p>Total NOCs</p>
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
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">132</h4>
                                        <p>Approved NOCs</p>
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
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">83</h4>
                                        <p>Pending NOCs</p>
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
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="mb-1">9</h4>
                                        <p>States Involved</p>
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
                
                <!-- State-wise NOC Pending Analytics -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow analytics-card animated fadeInUp delay-1">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-bar mr-2"></i>State-wise NOC Status
                                </h6>
                                <div class="filter-buttons">
                                    <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="pending">Pending</button>
                                    <button class="btn btn-sm btn-outline-success filter-btn" data-filter="approved">Approved</button>
                                    <button class="btn btn-sm btn-outline-info filter-btn" data-filter="total">Total</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="stateNocChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- NOC Issuance Analytics -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow analytics-card animated fadeInUp delay-2">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-exchange-alt mr-2"></i>State-wise NOC Issuance
                                </h6>
                                <div class="filter-buttons">
                                    <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="sent">Sent</button>
                                    <button class="btn btn-sm btn-outline-success filter-btn" data-filter="received">Received</button>
                                    <button class="btn btn-sm btn-outline-info filter-btn" data-filter="both">Both</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="nocIssuanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Analytics Info -->
                <div class="row mb-4">
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
                    </div>
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
                primaryLight: 'rgba(78, 115, 223, 0.2)',
                successLight: 'rgba(28, 200, 138, 0.2)',
                warningLight: 'rgba(246, 194, 62, 0.2)',
                dangerLight: 'rgba(231, 74, 59, 0.2)',
                infoLight: 'rgba(54, 185, 204, 0.2)'
            };
            
            // State-wise data
            const states = ['Karnataka', 'Gujarat', 'Maharashtra', 'Tamil Nadu', 'Rajasthan', 'Punjab'];
            
            const nocData = {
                pending: [32, 28, 45, 19, 36, 23],
                approved: [68, 59, 73, 42, 54, 37],
                total: [100, 87, 118, 61, 90, 60]
            };
            
            const issuanceData = {
                sent: [42, 35, 51, 29, 38, 25],
                received: [58, 52, 67, 32, 52, 35],
                both: [100, 87, 118, 61, 90, 60]
            };
            
            // Initialize state-wise NOC Chart
            const stateNocCtx = document.getElementById('stateNocChart').getContext('2d');
            let stateNocChart = new Chart(stateNocCtx, {
                type: 'bar',
                data: {
                    labels: states,
                    datasets: [{
                        label: 'Pending NOCs',
                        data: nocData.pending,
                        backgroundColor: colorPalette.primaryLight,
                        borderColor: colorPalette.primary,
                        borderWidth: 2,
                        borderRadius: 5,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'rectRounded'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12,
                            cornerRadius: 8,
                            caretSize: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
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
                        data: issuanceData.sent,
                        backgroundColor: colorPalette.infoLight,
                        borderColor: colorPalette.info,
                        borderWidth: 2,
                        borderRadius: 5,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'rectRounded'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12,
                            cornerRadius: 8,
                            caretSize: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Monthly trend data
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const monthlyData = [15, 22, 18, 27, 30, 25];
            
            // Initialize Monthly Trend Chart
            const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            new Chart(monthlyTrendCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'NOC Applications',
                        data: monthlyData,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: colorPalette.primary,
                        borderWidth: 3,
                        pointBackgroundColor: colorPalette.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Requirements data
            const requirements = ['Identity Proof', 'Address Proof', 'Qualification', 'Experience', 'Medical'];
            const requirementsData = [85, 75, 60, 50, 40];
            
            // Initialize Requirements Chart
            const requirementsCtx = document.getElementById('requirementsChart').getContext('2d');
            new Chart(requirementsCtx, {
                type: 'horizontalBar',
                data: {
                    labels: requirements,
                    datasets: [{
                        label: 'Verification Rate (%)',
                        data: requirementsData,
                        backgroundColor: [
                            colorPalette.primaryLight,
                            colorPalette.successLight,
                            colorPalette.warningLight,
                            colorPalette.infoLight,
                            colorPalette.dangerLight
                        ],
                        borderColor: [
                            colorPalette.primary,
                            colorPalette.success,
                            colorPalette.warning,
                            colorPalette.info,
                            colorPalette.danger
                        ],
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Filter buttons functionality for State NOC Chart
            $('.filter-btn[data-filter]').click(function() {
                const filterType = $(this).data('filter');
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                
                const chart = $(this).closest('.card').find('canvas').attr('id');
                
                if (chart === 'stateNocChart') {
                    updateStateNocChart(filterType);
                } else if (chart === 'nocIssuanceChart') {
                    updateNocIssuanceChart(filterType);
                }
            });
            
            // Function to update State NOC Chart
            function updateStateNocChart(filterType) {
                let backgroundColor, borderColor, label;
                
                switch(filterType) {
                    case 'pending':
                        backgroundColor = colorPalette.primaryLight;
                        borderColor = colorPalette.primary;
                        label = 'Pending NOCs';
                        break;
                    case 'approved':
                        backgroundColor = colorPalette.successLight;
                        borderColor = colorPalette.success;
                        label = 'Approved NOCs';
                        break;
                    case 'total':
                        backgroundColor = colorPalette.infoLight;
                        borderColor = colorPalette.info;
                        label = 'Total NOCs';
                        break;
                }
                
                stateNocChart.data.datasets[0].data = nocData[filterType];
                stateNocChart.data.datasets[0].label = label;
                stateNocChart.data.datasets[0].backgroundColor = backgroundColor;
                stateNocChart.data.datasets[0].borderColor = borderColor;
                stateNocChart.update();
            }
            
            // Function to update NOC Issuance Chart
            function updateNocIssuanceChart(filterType) {
                let backgroundColor, borderColor, label;
                
                switch(filterType) {
                    case 'sent':
                        backgroundColor = colorPalette.infoLight;
                        borderColor = colorPalette.info;
                        label = 'NOCs Sent';
                        break;
                    case 'received':
                        backgroundColor = colorPalette.successLight;
                        borderColor = colorPalette.success;
                        label = 'NOCs Received';
                        break;
                    case 'both':
                        backgroundColor = colorPalette.warningLight;
                        borderColor = colorPalette.warning;
                        label = 'Total NOC Transactions';
                        break;
                }
                
                nocIssuanceChart.data.datasets[0].data = issuanceData[filterType];
                nocIssuanceChart.data.datasets[0].label = label;
                nocIssuanceChart.data.datasets[0].backgroundColor = backgroundColor;
                nocIssuanceChart.data.datasets[0].borderColor = borderColor;
                nocIssuanceChart.update();
            }
        });
    </script>
</body>
</html>
