<div class="app-menu navbar-menu">
    <style>
        h6{
            color: #fff!important;
        }
    </style>
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark mt-4">
            <span class="logo-sm text-white">
                <h6>Bedouin</h6>>
            </span>
            <span class="logo-lg">
                <h6>Bedouin</h6>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light mt-4">
            <span class="logo-sm">
                <h6>Bedouin</h6>
              
            </span>
            <span class="logo-lg">
                {{-- <img width="50px" src="{{ asset('frontend') }}/assets/images/logo/techtouchlogo.png" alt=""> --}}
                <h6>Bedouin</h6>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            @php
                // Determine active states for different sections
                $isProductManagementActive = request()->is('admin/categories*') ||
                                            request()->is('admin/sub-categories*') ||
                                            request()->is('admin/products*') ||
                                            request()->is('admin/sizes*') ||
                                            request()->is('admin/nutritions*') ||
                                            request()->is('admin/optiontitles*') ||
                                            request()->is('admin/topings*') ||
                                            request()->is('admin/product-sizes*') ||
                                            request()->is('admin/product-topings*');

                $isOrderManagementActive = request()->is('admin/orders*');

                $isSettingsActive = request()->is('admin/delivery_charges*') ||
                                    request()->is('admin/currency*') ||
                                    request()->is('admin/schedule*') ||
                                    request()->is('admin/location*') ||
                                    request()->is('admin/coupons*');

                $isContentManagementActive = request()->is('admin/slider*') ||
                                            request()->is('admin/home-ad*');

                $isAdministrationActive = request()->is('users*') ||
                                        request()->is('role*') ||
                                        request()->is('permission*');
            @endphp
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>  
                @can('content-management')
                    <li class="nav-item {{ $isContentManagementActive ? 'show' : '' }}">
                        <a class="nav-link menu-link {{ $isContentManagementActive ? '' : 'collapsed' }}" href="#contentManagement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                            <i class="ri-folder-settings-line"></i> <span data-key="t-advance-ui">Content Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isContentManagementActive ? 'show' : '' }}" id="contentManagement">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('slider.index') }}" class="nav-link {{ request()->is('admin/slider*') ? 'active' : '' }}">Sliders</a>
                                </li> 
                                <li class="nav-item">
                                    <a href="{{ route('home-ad.index') }}" class="nav-link {{ request()->is('admin/home-ad*') ? 'active' : '' }}">Ads</a>
                                </li>                                                         
                            </ul>
                        </div>
                    </li>
                @endcan 
                @can('book-management')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#bookManagement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="bookManagement">
                            <i class="ri-shopping-bag-line"></i> <span data-key="t-advance-ui">Book Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="bookManagement">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('book-categories.index') }}" class="nav-link ">Categories</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('book-sub-categories.index') }}" class="nav-link">Sub Categories</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('publisher.index') }}" class="nav-link">Publisher</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('writer.index') }}" class="nav-link">Writer</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('subject.index') }}" class="nav-link ">Subject</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('books.index') }}" class="nav-link ">Book</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('books.package') }}" class="nav-link ">Package</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan 
                @can('product-management')
                    <li class="nav-item {{ $isProductManagementActive ? 'show' : '' }}">
                        <a class="nav-link menu-link {{ $isProductManagementActive ? '' : 'collapsed' }}" href="#sidebarAdvanceUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                            <i class="ri-shopping-bag-line"></i> <span data-key="t-advance-ui">Product Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isProductManagementActive ? 'show' : '' }}" id="sidebarAdvanceUI">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('sizes.index') }}" class="nav-link">Size</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('brand.index') }}" class="nav-link ">Brand</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">Categories</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('sub-categories.index') }}" class="nav-link {{ request()->is('admin/sub-categories*') ? 'active' : '' }}">Sub Categories</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('sizes.index') }}" class="nav-link {{ request()->is('admin/sizes*') ? 'active' : '' }}">Size</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('nutritions.index') }}" class="nav-link {{ request()->is('admin/nutritions*') ? 'active' : '' }}">Nutrition</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('optiontitles.index') }}" class="nav-link {{ request()->is('admin/optiontitles*') ? 'active' : '' }}">Option Title</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('topings.index') }}" class="nav-link {{ request()->is('admin/topings*') ? 'active' : '' }}" data-key="t-nestable-list">Topings</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" data-key="t-nestable-list">Products</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan                            
                @can('order-management')
                    <li class="nav-item {{ $isOrderManagementActive ? 'show' : '' }}">
                        <a class="nav-link menu-link {{ $isOrderManagementActive ? '' : 'collapsed' }}" href="#orderManagement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                            <i class="ri-file-list-3-line"></i> <span data-key="t-advance-ui">Order Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isOrderManagementActive ? 'show' : '' }}" id="orderManagement">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('orders.index') }}" class="nav-link">Orders</a>
                                </li>                         
                            </ul>
                        </div>
                    </li> 
                @endcan
                @can('settings')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarCouponUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                            <i class="ri-settings-3-line"></i> <span data-key="t-advance-ui">Settings</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarCouponUI">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('menu.index') }}" class="nav-link">Menu</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('settings.home_page') }}" class="nav-link">Home Page</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('settings.nav_item') }}" class="nav-link">Nav Item</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('delivery_charges.index') }}" class="nav-link">Delivery Type</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('delivery_percentage.index') }}" class="nav-link">Delivery Percentage</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('coupons.index') }}" class="nav-link">Coupons</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('settings.district') }}" class="nav-link">District</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('settings.area') }}" class="nav-link">Area</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('delivery_charges.index') }}" class="nav-link {{ request()->is('admin/delivery_charges*') ? 'active' : '' }}">Delivery Charge</a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('Administration')
                    <li class="nav-item {{ $isAdministrationActive ? 'show' : '' }}">
                        <a class="nav-link menu-link {{ $isAdministrationActive ? '' : 'collapsed' }}" href="#sidebarAuthorizition" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuthorizition">
                            <i class="ri-shield-keyhole-line"></i> <span data-key="t-advance-ui">Authorization</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isAdministrationActive ? 'show' : '' }}" id="sidebarAuthorizition">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('permission.index') }}" class="nav-link {{ request()->is('admin/permission*') ? 'active' : '' }}" data-key="t-nestable-list">Permissions</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}" class="nav-link {{ request()->is('admin/role*') ? 'active' : '' }}" data-key="t-nestable-list">Roles</a>
                                </li>                           
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">Users</a>
                                </li>                    
                            </ul>
                        </div>
                    </li>  
                @endcan
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
