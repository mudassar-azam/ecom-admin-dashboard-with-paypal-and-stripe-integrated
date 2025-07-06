    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="/" class="logo">
                    <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                        height="20" />
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li class="nav-item active">
                        <a class="collapsed">
                            <i class="fas fa-home"></i>
                            <p>Admin Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#currencies">
                            <i class="fas fa-layer-group"></i>
                            <p>Currencies</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="currencies">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('currencies.index') }}">
                                        <span class="sub-item">All Currencies</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('currencies.create') }}">
                                        <span class="sub-item">Add Currencie</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#base">
                            <i class="fas fa-layer-group"></i>
                            <p>Categories</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="base">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('categories.index') }}">
                                        <span class="sub-item">All Catgories</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('categories.create') }}">
                                        <span class="sub-item">Add Catgory</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#brand">
                            <i class="fas fa-layer-group"></i>
                            <p>Brand</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="brand">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('brands.index') }}">
                                        <span class="sub-item">All Brands</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('brands.create') }}">
                                        <span class="sub-item">Add Brand</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#product">
                            <i class="fas fa-layer-group"></i>
                            <p>Product</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="product">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('products.index') }}">
                                        <span class="sub-item">All Products</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('products.create') }}">
                                        <span class="sub-item">Add Product</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('variations.index')}}">
                                        <span class="sub-item">All Variations</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#order">
                            <i class="fas fa-layer-group"></i>
                            <p>Orders</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="order">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('orders.paypal') }}">
                                        <span class="sub-item">Paypal Order</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('orders.stripe') }}">
                                        <span class="sub-item">Stripe Order</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('shipping-settings.index') }}">
                            <i class="fas fa-shipping-fast"></i>
                            <p>Shipping Settings</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->
