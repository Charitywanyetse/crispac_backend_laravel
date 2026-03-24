<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crispac Logistics - Dashboard</title>
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    
    <style>
        .main-sidebar {
            background-color: #5B3B9F !important;
        }
        .brand-link {
            background-color: #4A2C8C !important;
            border-bottom: 1px solid #7B5BFF !important;
            padding: 15px !important;
        }
        .nav-sidebar > .nav-item > .nav-link {
            color: white !important;
            transition: all 0.3s;
        }
        .nav-sidebar > .nav-item > .nav-link:hover {
            background-color: #7B5BFF !important;
            transform: translateX(5px);
        }
        .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #7B5BFF !important;
            font-weight: bold;
        }
        .nav-treeview > .nav-item > .nav-link {
            color: #ddd !important;
            padding-left: 45px !important;
        }
        .nav-treeview > .nav-item > .nav-link:hover {
            color: white !important;
            background-color: #7B5BFF !important;
        }
        .user-panel {
            border-bottom: 1px solid #7B5BFF !important;
        }
        .badge-info { background-color: #17a2b8 !important; }
        .badge-warning { background-color: #ffc107 !important; }
        .badge-success { background-color: #28a745 !important; }
        .badge-danger { background-color: #dc3545 !important; }
        .badge-primary { background-color: #007bff !important; }
        .nav-header {
            color: rgba(255,255,255,0.5) !important;
            font-size: 12px !important;
            padding: 20px 15px 5px !important;
            letter-spacing: 1px;
        }
        .info-box {
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(91, 59, 159, 0.2);
        }
        .btn-primary {
            background-color: #5B3B9F !important;
            border-color: #5B3B9F !important;
        }
        .btn-primary:hover {
            background-color: #7B5BFF !important;
            border-color: #7B5BFF !important;
        }
        .btn-outline-primary {
            color: #5B3B9F !important;
            border-color: #5B3B9F !important;
        }
        .btn-outline-primary:hover {
            background-color: #5B3B9F !important;
            color: white !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-bell"></i>
                    <span class="badge badge-warning navbar-badge" id="notificationCount">7</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">7 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-shopping-cart mr-2"></i> New order received
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-truck mr-2"></i> Order #1234 delivered
                        <span class="float-right text-muted text-sm">2 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center">View all notifications</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'User' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ url('/profile') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="{{ url('/settings') }}" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('/logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #5B3B9F !important;">
        <!-- Brand Logo with Company Logo -->
        <a href="{{ url('/dashboard') }}" class="brand-link">
            <img src="{{ asset('images/logo.svg') }}" alt="Crispac Logistics Logo" style="width: 35px; height: 35px; margin-right: 10px;">
            <span class="brand-text font-weight-light" style="color: white; font-size: 1.3rem; font-weight: bold;">Crispac Logistics</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- User Panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="border-bottom: 1px solid #7B5BFF; padding: 0 15px 15px 15px;">
                <div class="image">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&color=5B3B9F&background=FFFFFF" 
                         class="img-circle elevation-2" 
                         style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid white;">
                </div>
                <div class="info" style="margin-left: 10px;">
                    <a href="{{ url('/profile') }}" class="d-block" style="color: white; font-weight: 600;">
                        {{ Auth::user()->name ?? 'User' }}
                    </a>
                    <small style="color: #FFD700;">
                        <i class="fas fa-circle" style="color: #2ecc71; font-size: 8px;"></i>
                        {{ ucfirst(Auth::user()->role ?? 'Admin') }}
                    </small>
                </div>
            </div>

            <!-- Quick Stats -->
            <div style="background: rgba(255,255,255,0.1); margin: 10px 15px; padding: 12px; border-radius: 8px;">
                <div class="row text-center">
                    <div class="col-4">
                        <div style="color: #FFD700; font-size: 18px; font-weight: bold;">24</div>
                        <small style="color: rgba(255,255,255,0.8);">Orders</small>
                    </div>
                    <div class="col-4">
                        <div style="color: #FFD700; font-size: 18px; font-weight: bold;">42</div>
                        <small style="color: rgba(255,255,255,0.8);">Products</small>
                    </div>
                    <div class="col-4">
                        <div style="color: #FFD700; font-size: 18px; font-weight: bold;">18</div>
                        <small style="color: rgba(255,255,255,0.8);">Users</small>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    
                    <!-- MAIN MENU -->
                    <li class="nav-header">MAIN MENU</li>
                    
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <!-- My Profile -->
                    <li class="nav-item">
                        <a href="{{ url('/profile') }}" class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>My Profile</p>
                        </a>
                    </li>

                    <!-- ORDER MANAGEMENT -->
                    <li class="nav-header">ORDER MANAGEMENT</li>
                    
                    <!-- Orders with Submenu -->
                    <li class="nav-item has-treeview {{ request()->is('orders*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Orders
                                <i class="right fas fa-angle-left"></i>
                                <span class="badge badge-info right">12</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/orders') }}" class="nav-link {{ request()->is('orders') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Orders</p>
                                    <span class="badge badge-primary right">24</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/orders/pending') }}" class="nav-link {{ request()->is('orders/pending') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pending</p>
                                    <span class="badge badge-warning right">5</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/orders/processing') }}" class="nav-link {{ request()->is('orders/processing') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Processing</p>
                                    <span class="badge badge-info right">3</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/orders/completed') }}" class="nav-link {{ request()->is('orders/completed') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Completed</p>
                                    <span class="badge badge-success right">12</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/orders/cancelled') }}" class="nav-link {{ request()->is('orders/cancelled') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cancelled</p>
                                    <span class="badge badge-danger right">4</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- PRODUCT MANAGEMENT -->
                    <li class="nav-header">PRODUCT MANAGEMENT</li>
                    
                    <!-- Products with Submenu -->
                    <li class="nav-item has-treeview {{ request()->is('products*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Products
                                <i class="right fas fa-angle-left"></i>
                                <span class="badge badge-info right">42</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/products') }}" class="nav-link {{ request()->is('products') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Products</p>
                                    <span class="badge badge-primary right">42</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/categories') }}" class="nav-link {{ request()->is('categories') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Categories</p>
                                    <span class="badge badge-info right">8</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/products/create') }}" class="nav-link {{ request()->is('products/create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/products/low-stock') }}" class="nav-link {{ request()->is('products/low-stock') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Low Stock</p>
                                    <span class="badge badge-danger right">3</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- USER MANAGEMENT -->
                    <li class="nav-header">USER MANAGEMENT</li>
                    
                    <!-- Users with Submenu -->
                    <li class="nav-item has-treeview {{ request()->is('users*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                                <span class="badge badge-info right">18</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/users') }}" class="nav-link {{ request()->is('users') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Users</p>
                                    <span class="badge badge-primary right">18</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/users/customers') }}" class="nav-link {{ request()->is('users/customers') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Customers</p>
                                    <span class="badge badge-success right">12</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/users/delivery') }}" class="nav-link {{ request()->is('users/delivery') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Delivery Personnel</p>
                                    <span class="badge badge-info right">4</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/users/admins') }}" class="nav-link {{ request()->is('users/admins') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Admins</p>
                                    <span class="badge badge-warning right">2</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/users/create') }}" class="nav-link {{ request()->is('users/create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New User</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- REPORTS -->
                    <li class="nav-header">REPORTS</li>
                    
                    <!-- Reports with Submenu -->
                    <li class="nav-item has-treeview {{ request()->is('reports*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/reports/sales') }}" class="nav-link {{ request()->is('reports/sales') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sales Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/reports/delivery') }}" class="nav-link {{ request()->is('reports/delivery') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Delivery Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/reports/user-activity') }}" class="nav-link {{ request()->is('reports/user-activity') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User Activity</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- COMMUNICATIONS -->
                    <li class="nav-header">COMMUNICATIONS</li>
                    
                    <!-- Notifications -->
                    <li class="nav-item">
                        <a href="{{ url('/notifications') }}" class="nav-link {{ request()->is('notifications') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>Notifications</p>
                            <span class="badge badge-danger right">7</span>
                        </a>
                    </li>

                    <!-- CONFIGURATION -->
                    <li class="nav-header">CONFIGURATION</li>
                    
                    <!-- Settings with Submenu -->
                    <li class="nav-item has-treeview {{ request()->is('settings*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/settings/general') }}" class="nav-link {{ request()->is('settings/general') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>General Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/settings/company') }}" class="nav-link {{ request()->is('settings/company') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Company Info</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/notifications/settings') }}" class="nav-link {{ request()->is('notifications/settings') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Notification Settings</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- SUPPORT -->
                    <li class="nav-header">SUPPORT</li>
                    
                    <!-- Support with Submenu -->
                    <li class="nav-item has-treeview {{ request()->is('support*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-headset"></i>
                            <p>
                                Support
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/support/help') }}" class="nav-link {{ request()->is('support/help') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Help Center</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/support/faq') }}" class="nav-link {{ request()->is('support/faq') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>FAQ</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/support/contact') }}" class="nav-link {{ request()->is('support/contact') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Contact Support</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- ABOUT -->
                    <li class="nav-header">ABOUT</li>
                    
                    <!-- About Company -->
                    <li class="nav-item">
                        <a href="{{ url('/about/company') }}" class="nav-link {{ request()->is('about/company') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>About Crispac Logistics</p>
                        </a>
                    </li>
                    
                    <!-- About App -->
                    <li class="nav-item">
                        <a href="{{ url('/about/app') }}" class="nav-link {{ request()->is('about/app') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mobile-alt"></i>
                            <p>About the App</p>
                            <span class="badge badge-success right">v1.0</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="background-color: #f4f6f9;">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Orders</span>
                                <span class="info-box-number">24</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Delivered</span>
                                <span class="info-box-number">12</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number">5</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-box"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Products</span>
                                <span class="info-box-number">42</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #5B3B9F; color: white;">
                                <h3 class="card-title">Welcome back, {{ Auth::user()->name ?? 'User' }}!</h3>
                            </div>
                            <div class="card-body">
                                <p>Here's what's happening with your account today.</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5><i class="fas fa-user-circle"></i> Your Account</h5>
                                                <hr>
                                                <p><strong>Name:</strong> {{ Auth::user()->name ?? 'User' }}</p>
                                                <p><strong>Email:</strong> {{ Auth::user()->email ?? 'user@example.com' }}</p>
                                                <p><strong>Role:</strong> <span class="badge badge-success">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5><i class="fas fa-chart-line"></i> Quick Actions</h5>
                                                <hr>
                                                <a href="{{ url('/orders/create') }}" class="btn btn-primary">Create New Order</a>
                                                <a href="{{ url('/products') }}" class="btn btn-outline-primary" style="margin-left: 10px;">View Products</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Orders</h3>
                                <div class="card-tools">
                                    <a href="{{ url('/orders') }}" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#ORD-001</td>
                                            <td>John Doe</td>
                                            <td><span class="badge badge-success">Delivered</span></td>
                                            <td>$120.00</td>
                                        </tr>
                                        <tr>
                                            <td>#ORD-002</td>
                                            <td>Jane Smith</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td>$85.50</td>
                                        </tr>
                                        <tr>
                                            <td>#ORD-003</td>
                                            <td>Bob Johnson</td>
                                            <td><span class="badge badge-info">Processing</span></td>
                                            <td>$210.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Top Products</h3>
                                <div class="card-tools">
                                    <a href="{{ url('/products') }}" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Sales</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Product A</td>
                                            <td>24</td>
                                            <td><span class="badge badge-success">45</span></td>
                                        </tr>
                                        <tr>
                                            <td>Product B</td>
                                            <td>18</td>
                                            <td><span class="badge badge-warning">8</span></td>
                                        </tr>
                                        <tr>
                                            <td>Product C</td>
                                            <td>12</td>
                                            <td><span class="badge badge-danger">3</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2026 Crispac Logistics.</strong> All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>