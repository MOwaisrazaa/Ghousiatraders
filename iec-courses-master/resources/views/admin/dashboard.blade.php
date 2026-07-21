@extends('admin.layout')

@section('title', 'Dashboard')

@section('header')
    Polani Admin Dashboard
@endsection

@section('content')
    <div class="polani-dashboard">
        <div class="polani-dashboard__hero">
            <div>
                <div class="polani-dashboard__eyebrow">POLANI FRAGRANCE</div>
                <h2 class="polani-dashboard__headline">Store control center</h2>
                <p class="polani-dashboard__copy">
                    Manage fragrance products, categories, orders, payments and store settings from one luxury dashboard.
                </p>
            </div>
            <div class="polani-dashboard__pill">
                <span>Signed in as</span>
                <strong>{{ Auth::user()->email }}</strong>
            </div>
        </div>

        <div class="polani-stats-grid">
            <div class="polani-stat-card polani-stat-card--gold">
                <div class="polani-stat-card__label">Products</div>
                <div class="polani-stat-card__value">{{ $stats['products'] }}</div>
                <a href="{{ route('admin.products') }}" class="polani-stat-card__link">Manage Products</a>
            </div>

            <div class="polani-stat-card polani-stat-card--emerald">
                <div class="polani-stat-card__label">Orders</div>
                <div class="polani-stat-card__value">{{ $stats['orders'] }}</div>
                <div class="polani-stat-card__meta">{{ $stats['pending_orders'] }} pending</div>
            </div>

            <div class="polani-stat-card polani-stat-card--blue">
                <div class="polani-stat-card__label">Users</div>
                <div class="polani-stat-card__value">{{ $stats['users'] }}</div>
                <a href="{{ route('admin.users') }}" class="polani-stat-card__link">View Users</a>
            </div>

            <div class="polani-stat-card polani-stat-card--violet">
                <div class="polani-stat-card__label">Categories</div>
                <div class="polani-stat-card__value">{{ $stats['categories'] }}</div>
                <a href="{{ route('admin.categories.index') }}" class="polani-stat-card__link">Manage Categories</a>
            </div>

            <div class="polani-stat-card polani-stat-card--amber">
                <div class="polani-stat-card__label">Coupons</div>
                <div class="polani-stat-card__value">{{ $stats['coupons'] }}</div>
                <a href="{{ route('admin.coupons.index') }}" class="polani-stat-card__link">Manage Coupons</a>
            </div>

            <div class="polani-stat-card polani-stat-card--rose">
                <div class="polani-stat-card__label">Payment Methods</div>
                <div class="polani-stat-card__value">{{ $stats['payment_methods'] }}</div>
                <a href="{{ route('admin.payment-methods.index') }}" class="polani-stat-card__link">Edit Methods</a>
            </div>

            <div class="polani-stat-card polani-stat-card--midnight">
                <div class="polani-stat-card__label">Footer Settings</div>
                <div class="polani-stat-card__value">{{ $stats['footer_settings'] }}</div>
                <a href="{{ route('admin.footer.index') }}" class="polani-stat-card__link">Update Footer</a>
            </div>

            <div class="polani-stat-card polani-stat-card--gold">
                <div class="polani-stat-card__label">Revenue</div>
                <div class="polani-stat-card__value">Rs {{ number_format((float) $stats['total_revenue'], 0) }}</div>
                <div class="polani-stat-card__meta">{{ $stats['completed_orders'] }} completed</div>
            </div>
        </div>

        <div class="polani-quick-actions">
            <div class="polani-section-title">Quick Actions</div>
            <div class="polani-actions-grid">
                <a href="{{ route('admin.products.create') }}" class="polani-action-card">
                    <span>Create Product</span>
                    <small>Add a new fragrance item</small>
                </a>
                <a href="{{ route('admin.orders') }}" class="polani-action-card">
                    <span>Review Orders</span>
                    <small>Check pending and completed orders</small>
                </a>
                <a href="{{ route('admin.payment-methods.index') }}" class="polani-action-card">
                    <span>Payment Setup</span>
                    <small>Enable COD and online payments</small>
                </a>
                <a href="{{ route('admin.footer.index') }}" class="polani-action-card">
                    <span>Store Footer</span>
                    <small>Update contact and social links</small>
                </a>
            </div>
        </div>
    </div>
@endsection
