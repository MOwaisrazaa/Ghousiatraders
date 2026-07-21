<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">Admin Area</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarNav" aria-controls="adminNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                        <i class="fas fa-box-open me-1"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.lectures') ? 'active' : '' }}" href="{{ route('admin.lectures') }}">
                        <i class="fas fa-video me-1"></i> Lectures
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                        <i class="fas fa-tags me-1"></i> Coupons
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-1"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.user-courses.*') ? 'active' : '' }}" href="{{ route('admin.user-courses.index') }}">
                        <i class="fas fa-graduation-cap me-1"></i> User Courses
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
