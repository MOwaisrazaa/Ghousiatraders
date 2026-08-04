@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Settings')

@section('content')
<style>
    /* Single-column full width layout */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        align-items: flex-start;
        width: 100%;
        box-sizing: border-box;
    }

    .settings-main-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
    }

    .settings-sidebar-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
    }

    /* Cards */
    .settings-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        width: 100%;
    }

    .settings-card-header {
        margin-bottom: 20px;
    }

    .settings-card-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--gt-primary);
        margin: 0 0 4px 0;
    }

    .settings-card-subtitle {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--gt-text-muted);
        margin: 0;
    }

    /* Horizontal tabs */
    .settings-tabs-nav {
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1.5px solid var(--gt-border);
        padding-bottom: 2px;
        margin-bottom: 24px;
        overflow-x: auto;
        white-space: nowrap;
    }

    .settings-tab-btn {
        background: none;
        border: none;
        padding: 8px 14px;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        position: relative;
        transition: all 0.2s ease;
        border-radius: 8px;
    }

    .settings-tab-btn i {
        width: 16px;
        height: 16px;
    }

    .settings-tab-btn:hover {
        color: var(--gt-primary);
        background: var(--gt-primary-light);
    }

    .settings-tab-btn.active {
        color: var(--gt-primary);
        background: var(--gt-primary-light);
    }

    .settings-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--gt-primary);
        border-radius: 99px;
    }

    /* Form design utilities */
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group-full {
        margin-bottom: 16px;
    }

    .gt-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--gt-text);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .gt-input {
        background: #fffdf9;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
        color: var(--gt-text);
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
        min-height: 38px;
    }

    .gt-input:focus {
        border-color: #d7a64a;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(215, 166, 74, 0.1);
    }

    .gt-btn-primary {
        background: linear-gradient(135deg, var(--gt-primary), #633618);
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
        min-height: 38px;
    }

    .gt-btn-primary:hover { opacity: 0.95; }

    .gt-btn-outline {
        background: transparent;
        color: var(--gt-primary);
        border: 1.5px solid var(--gt-border);
        padding: 8px 16px;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
        min-height: 32px;
    }

    .gt-btn-outline:hover {
        background-color: var(--gt-primary-light);
        border-color: #d7a64a;
    }

    .social-row-item {
        background: #fffdf9;
        border: 1.5px solid var(--gt-border);
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .social-row-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .social-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--gt-primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gt-primary);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .social-toggle-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gt-text);
        cursor: pointer;
        user-select: none;
    }

    /* Logo previews */
    .logo-uploader-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .logo-preview-box {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        border: 1.5px solid var(--gt-border);
        background-color: #fffdf9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .logo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .badge-enabled {
        background-color: #ecfdf5;
        color: #047857;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 6px;
    }

    .badge-disabled {
        background-color: #f3f4f6;
        color: #6b7280;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 6px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<x-admin-page-header title="Settings" />

@if(session('success'))
    <div style="background: #ECFDF5; border: 1.5px solid #10B981; color: #065F46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 10px;">
        <i data-lucide="check-circle" style="width: 20px; height: 20px; color: #10B981;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div style="background: #FEF2F2; border: 1.5px solid #EF4444; color: #991B1B; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 10px;">
        <i data-lucide="alert-circle" style="width: 20px; height: 20px; color: #EF4444;"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Horizontal navigation tabs -->
<div class="settings-tabs-nav">
    <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" class="settings-tab-btn {{ $tab === 'general' ? 'active' : '' }}">
        <i data-lucide="settings"></i> General
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'store_info']) }}" class="settings-tab-btn {{ $tab === 'store_info' ? 'active' : '' }}">
        <i data-lucide="info"></i> Store Information
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'header']) }}" class="settings-tab-btn {{ $tab === 'header' ? 'active' : '' }}">
        <i data-lucide="layout"></i> Header & Top Bar
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'footer']) }}" class="settings-tab-btn {{ $tab === 'footer' ? 'active' : '' }}">
        <i data-lucide="panel-bottom"></i> Footer & Newsletter
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'social']) }}" class="settings-tab-btn {{ $tab === 'social' ? 'active' : '' }}">
        <i data-lucide="share-2"></i> Social Media
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'shipping']) }}" class="settings-tab-btn {{ $tab === 'shipping' ? 'active' : '' }}">
        <i data-lucide="truck"></i> Shipping Settings
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'payment_methods']) }}" class="settings-tab-btn {{ $tab === 'payment_methods' ? 'active' : '' }}">
        <i data-lucide="credit-card"></i> Payment Methods
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'tax']) }}" class="settings-tab-btn {{ $tab === 'tax' ? 'active' : '' }}">
        <i data-lucide="percent"></i> Tax Settings
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'notifications']) }}" class="settings-tab-btn {{ $tab === 'notifications' ? 'active' : '' }}">
        <i data-lucide="bell"></i> Notifications
    </a>
</div>

<div class="settings-grid">
    <!-- Left Column: Forms content -->
    <div class="settings-main-column">
        @if($tab === 'general')
            <!-- 1. General settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">General Settings</h2>
                    <p class="settings-card-subtitle">Configure your store's basic parameters and preferences.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="general">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Store Name</label>
                            <input type="text" name="store_name" required value="{{ $settings['store_name'] ?? 'Ghousia Traders' }}" class="gt-input" style="width: 100%;">
                            @error('store_name')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="gt-label">Store Primary Email</label>
                            <input type="email" name="store_email" required value="{{ $settings['store_email'] ?? 'info@ghousiatraders.com' }}" class="gt-input" style="width: 100%;">
                            @error('store_email')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Store Phone Number</label>
                            <input type="text" name="store_phone" required value="{{ $settings['store_phone'] ?? '0321-1234567' }}" class="gt-input" style="width: 100%;">
                            @error('store_phone')<span style="color:#ef4444;font-size:0.75rem;">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="gt-label">Store Currency</label>
                            <select name="store_currency" required class="gt-input" style="width:100%;">
                                <option value="PKR" {{ ($settings['store_currency'] ?? 'PKR') === 'PKR' ? 'selected' : '' }}>PKR (Pakistani Rupee)</option>
                                <option value="USD" {{ ($settings['store_currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD (United States Dollar)</option>
                                <option value="GBP" {{ ($settings['store_currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP (British Pound)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Store Timezone</label>
                            <select name="store_timezone" required class="gt-input" style="width:100%;">
                                <option value="Asia/Karachi" {{ ($settings['store_timezone'] ?? 'Asia/Karachi') === 'Asia/Karachi' ? 'selected' : '' }}>(GMT+05:00) Pakistan Standard Time (PKT)</option>
                                <option value="UTC" {{ ($settings['store_timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC / GMT Timezone</option>
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Date Format</label>
                            <select name="date_format" required class="gt-input" style="width:100%;">
                                <option value="F d, Y" {{ ($settings['date_format'] ?? 'F d, Y') === 'F d, Y' ? 'selected' : '' }}>August 04, 2026 (F d, Y)</option>
                                <option value="d M Y" {{ ($settings['date_format'] ?? '') === 'd M Y' ? 'selected' : '' }}>04 Aug 2026 (d M Y)</option>
                                <option value="d/m/Y" {{ ($settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>04/08/2026 (d/m/Y)</option>
                                <option value="Y-m-d" {{ ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>2026-08-04 (Y-m-d)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Time Format</label>
                            <select name="time_format" required class="gt-input" style="width:100%;">
                                <option value="12h" {{ ($settings['time_format'] ?? '12h') === '12h' ? 'selected' : '' }}>12 Hours (03:45 PM)</option>
                                <option value="24h" {{ ($settings['time_format'] ?? '') === '24h' ? 'selected' : '' }}>24 Hours (15:45)</option>
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Items Per Page</label>
                            <input type="number" name="items_per_page" required value="{{ $settings['items_per_page'] ?? 20 }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save General Settings</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'store_info')
            <!-- 2. Store Information Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Business & Store Details</h2>
                    <p class="settings-card-subtitle">Manage public store name, legal business registration, contact numbers, and addresses.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="store_info">
                    
                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;border-bottom:1px solid var(--gt-border);padding-bottom:6px;">Business Details</h3>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Public Store Name</label>
                            <input type="text" name="public_store_name" value="{{ $settings['public_store_name'] ?? 'Ghousia Traders' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Legal Business Name</label>
                            <input type="text" name="legal_business_name" value="{{ $settings['legal_business_name'] ?? 'Ghousia Traders Private Ltd' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Support Email</label>
                            <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'info@ghousiatraders.com' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Sales Email</label>
                            <input type="email" name="sales_email" value="{{ $settings['sales_email'] ?? 'sales@ghousiatraders.com' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Primary Phone</label>
                            <input type="text" name="primary_phone" value="{{ $settings['primary_phone'] ?? '0321-1234567' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Secondary Phone / Hotline</label>
                            <input type="text" name="secondary_phone" value="{{ $settings['secondary_phone'] ?? '0322-9876543' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">WhatsApp Support Number</label>
                            <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '0321-1234567' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Store Tagline</label>
                            <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] ?? 'Quality you can trust, happiness they deserve.' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Short Store Description</label>
                        <textarea name="short_store_description" class="gt-input" style="width:100%;height:60px;padding:8px;">{{ $settings['short_store_description'] ?? 'Your trusted destination for premium baby care products and exciting ride-on toys.' }}</textarea>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Detailed Business Description</label>
                        <textarea name="detailed_business_description" class="gt-input" style="width:100%;height:80px;padding:8px;">{{ $settings['detailed_business_description'] ?? 'Ghousia Traders provides high-quality baby care items, ride-on bikes, and toy cars across Pakistan.' }}</textarea>
                    </div>

                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin:20px 0 12px 0;border-bottom:1px solid var(--gt-border);padding-bottom:6px;">Physical Address Details</h3>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Address Line 1</label>
                            <input type="text" name="address_line_1" value="{{ $settings['address_line_1'] ?? 'Shop # 12, Main Market' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Address Line 2</label>
                            <input type="text" name="address_line_2" value="{{ $settings['address_line_2'] ?? 'DHA Phase 6' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">City</label>
                            <input type="text" name="city" value="{{ $settings['city'] ?? 'Lahore' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">State / Province</label>
                            <input type="text" name="state" value="{{ $settings['state'] ?? 'Punjab' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Country</label>
                            <input type="text" name="country" value="{{ $settings['country'] ?? 'Pakistan' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Postal / ZIP Code</label>
                            <input type="text" name="postal_code" value="{{ $settings['postal_code'] ?? '54000' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Google Maps Link URL</label>
                        <input type="url" name="google_maps_url" value="{{ $settings['google_maps_url'] ?? 'https://maps.google.com' }}" class="gt-input" style="width: 100%;">
                    </div>

                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin:20px 0 12px 0;border-bottom:1px solid var(--gt-border);padding-bottom:6px;">Business Hours Settings</h3>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Mon - Sat Opening Time</label>
                            <input type="text" name="business_hours_mon_sat_open" value="{{ $settings['business_hours_mon_sat_open'] ?? '10:00 AM' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Mon - Sat Closing Time</label>
                            <input type="text" name="business_hours_mon_sat_close" value="{{ $settings['business_hours_mon_sat_close'] ?? '08:00 PM' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Sunday Status</label>
                            <select name="business_hours_sunday_status" class="gt-input" style="width:100%;">
                                <option value="closed" {{ ($settings['business_hours_sunday_status'] ?? 'closed') === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="open" {{ ($settings['business_hours_sunday_status'] ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Sunday Hours (If Open)</label>
                            <input type="text" name="business_hours_sunday_open" value="{{ $settings['business_hours_sunday_open'] ?? '11:00 AM - 06:00 PM' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Custom Business Hours Text (Storefront Display)</label>
                        <textarea name="business_hours_custom_text" class="gt-input" style="width:100%;height:60px;padding:8px;">{{ $settings['business_hours_custom_text'] ?? "Monday - Saturday: 10:00 AM - 8:00 PM\nSunday: Closed" }}</textarea>
                    </div>

                    <div style="text-align:right;margin-top:20px;">
                        <button type="submit" class="gt-btn-primary">Save Store Information</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'header')
            <!-- 3. Header & Top Bar Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Header & Top Bar Settings</h2>
                    <p class="settings-card-subtitle">Configure top announcement text, customer support phone, search placeholder, and action buttons.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="header">
                    
                    <div class="form-group-full" style="background:#fffdf9;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;margin-bottom:16px;">
                        <label class="social-toggle-label">
                            <input type="checkbox" name="show_top_info_bar" value="1" {{ ($settings['show_top_info_bar'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Show Top Utility Information Bar</span>
                        </label>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Top Bar Free Shipping Announcement Text</label>
                            <input type="text" name="topbar_free_shipping_text" value="{{ $settings['topbar_free_shipping_text'] ?? 'Free Shipping on Orders Over PKR 5,000' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Free Shipping Minimum Threshold (PKR)</label>
                            <input type="number" name="shipping_free_threshold" value="{{ $settings['shipping_free_threshold'] ?? 5000 }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Quality Assurance Text</label>
                            <input type="text" name="topbar_quality_text" value="{{ $settings['topbar_quality_text'] ?? '100% Genuine & Premium Quality' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Customer Support Top Bar Text</label>
                            <input type="text" name="topbar_support_text" value="{{ $settings['topbar_support_text'] ?? 'Customer Support: 0321-1234567' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Header Support Phone Number</label>
                            <input type="text" name="header_support_phone" value="{{ $settings['header_support_phone'] ?? '0321-1234567' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Track Order Button Label</label>
                            <input type="text" name="track_order_btn_label" value="{{ $settings['track_order_btn_label'] ?? 'Track Order' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>

                    <div class="form-group-full">
                        <label class="gt-label">Header Search Bar Placeholder</label>
                        <input type="text" name="header_search_placeholder" value="{{ $settings['header_search_placeholder'] ?? 'Search baby care products, ride-on bikes, toy cars...' }}" class="gt-input" style="width: 100%;">
                    </div>

                    <div style="text-align:right;margin-top:20px;">
                        <button type="submit" class="gt-btn-primary">Save Header Settings</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'footer')
            <!-- 4. Footer & Newsletter Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Footer & Newsletter Settings</h2>
                    <p class="settings-card-subtitle">Manage store descriptions, contact info overrides, newsletter copy, and copyright text.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="footer">

                    <div class="form-group-full">
                        <label class="gt-label">Footer Store Description</label>
                        <textarea name="footer_description" class="gt-input" style="width:100%;height:70px;padding:8px;">{{ $settings['footer_description'] ?? 'Your trusted destination for premium baby care products and exciting ride-on toys. Quality you can trust, happiness they deserve.' }}</textarea>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Footer Phone</label>
                            <input type="text" name="footer_phone" value="{{ $settings['footer_phone'] ?? '0321-1234567' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Footer Email</label>
                            <input type="email" name="footer_email" value="{{ $settings['footer_email'] ?? 'info@ghousiatraders.com' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>

                    <div class="form-group-full">
                        <label class="gt-label">Footer Address</label>
                        <input type="text" name="footer_address" value="{{ $settings['footer_address'] ?? 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan' }}" class="gt-input" style="width: 100%;">
                    </div>

                    <div class="form-group-full">
                        <label class="gt-label">Footer Business Hours Display</label>
                        <textarea name="footer_business_hours" class="gt-input" style="width:100%;height:50px;padding:8px;">{{ $settings['footer_business_hours'] ?? "Mon - Sat: 10:00 AM - 8:00 PM\nSunday: Closed" }}</textarea>
                    </div>

                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin:20px 0 12px 0;border-bottom:1px solid var(--gt-border);padding-bottom:6px;">Newsletter Section Settings</h3>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Newsletter Heading</label>
                            <input type="text" name="newsletter_heading" value="{{ $settings['newsletter_heading'] ?? 'Stay Updated with Ghousia Traders' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Newsletter Button Label</label>
                            <input type="text" name="newsletter_button_label" value="{{ $settings['newsletter_button_label'] ?? 'Subscribe' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Newsletter Description</label>
                        <input type="text" name="newsletter_description" value="{{ $settings['newsletter_description'] ?? 'Subscribe to our newsletter for exclusive offers, new arrivals, and parenting tips.' }}" class="gt-input" style="width: 100%;">
                    </div>

                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin:20px 0 12px 0;border-bottom:1px solid var(--gt-border);padding-bottom:6px;">Copyright & Payment Badges</h3>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Copyright Name</label>
                            <input type="text" name="copyright_name" value="{{ $settings['copyright_name'] ?? 'Ghousia Traders' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Copyright Text</label>
                            <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? 'All Rights Reserved.' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    
                    <div class="form-group-full" style="background:#fffdf9;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;margin-top:12px;">
                        <label class="social-toggle-label">
                            <input type="checkbox" name="show_payment_logos" value="1" {{ ($settings['show_payment_logos'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Show Payment Accepted Badges in Footer</span>
                        </label>
                    </div>

                    <div style="text-align:right;margin-top:20px;">
                        <button type="submit" class="gt-btn-primary">Save Footer Settings</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'social')
            <!-- 5. Social Media Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Social Media Connections</h2>
                    <p class="settings-card-subtitle">Manage URLs and enable/disable controls for each social media platform on the storefront.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="social">

                    <!-- Facebook -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-facebook-f"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">Facebook Page URL</label>
                                <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/yourpage" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="facebook_enabled" value="1" {{ ($settings['facebook_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <!-- Instagram -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-instagram"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">Instagram Profile URL</label>
                                <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/yourhandle" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="instagram_enabled" value="1" {{ ($settings['instagram_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <!-- YouTube -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-youtube"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">YouTube Channel URL</label>
                                <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? '' }}" placeholder="https://youtube.com/yourchannel" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="youtube_enabled" value="1" {{ ($settings['youtube_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <!-- TikTok -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-tiktok"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">TikTok Profile URL</label>
                                <input type="url" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}" placeholder="https://tiktok.com/@yourhandle" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="tiktok_enabled" value="1" {{ ($settings['tiktok_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <!-- WhatsApp -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-whatsapp"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">WhatsApp Direct Link or Number</label>
                                <input type="text" name="whatsapp_url" value="{{ $settings['whatsapp_url'] ?? '' }}" placeholder="https://wa.me/923211234567 or 0321-1234567" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="whatsapp_enabled" value="1" {{ ($settings['whatsapp_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <!-- Twitter / X -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-twitter"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">X / Twitter URL</label>
                                <input type="url" name="twitter_url" value="{{ $settings['twitter_url'] ?? '' }}" placeholder="https://x.com/yourhandle" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="twitter_enabled" value="1" {{ ($settings['twitter_enabled'] ?? '0') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <!-- LinkedIn -->
                    <div class="social-row-item">
                        <div class="social-row-info">
                            <div class="social-icon-box"><i class="fab fa-linkedin-in"></i></div>
                            <div style="flex:1;">
                                <label class="gt-label">LinkedIn Page URL</label>
                                <input type="url" name="linkedin_url" value="{{ $settings['linkedin_url'] ?? '' }}" placeholder="https://linkedin.com/company/yourcompany" class="gt-input" style="width:100%;">
                            </div>
                        </div>
                        <label class="social-toggle-label">
                            <input type="checkbox" name="linkedin_enabled" value="1" {{ ($settings['linkedin_enabled'] ?? '0') == '1' ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);">
                            <span>Active</span>
                        </label>
                    </div>

                    <div style="text-align:right;margin-top:20px;">
                        <button type="submit" class="gt-btn-primary">Save Social Media Settings</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'shipping')
            <!-- 6. Shipping Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Shipping Settings</h2>
                    <p class="settings-card-subtitle">Configure flat rate delivery charges, free shipping thresholds, and shipping policy notes.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="shipping">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Flat Rate Shipping Cost (PKR)</label>
                            <input type="number" name="shipping_flat_rate" value="{{ $settings['shipping_flat_rate'] ?? 250 }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Free Shipping Minimum Threshold (PKR)</label>
                            <input type="number" name="shipping_free_threshold" value="{{ $settings['shipping_free_threshold'] ?? 5000 }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Default Delivery Days Estimate</label>
                            <input type="text" name="shipping_estimate_days" value="{{ $settings['shipping_estimate_days'] ?? '3-5 Working Days' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Courier Support Phone</label>
                            <input type="text" name="courier_support_phone" value="{{ $settings['courier_support_phone'] ?? '0321-1234567' }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Shipping Coverage Text</label>
                        <textarea name="shipping_coverage_text" class="gt-input" style="width:100%;height:60px;padding:8px;">{{ $settings['shipping_coverage_text'] ?? 'We deliver across all major cities and regions in Pakistan.' }}</textarea>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Shipping Settings</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'payment_methods')
            @php
                $paymentMethods = $paymentMethods ?? \App\Models\PaymentMethod::orderBy('sort_order')->get();
            @endphp
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Payment Gateways & Methods</h2>
                    <p class="settings-card-subtitle">Enable/disable payment methods or edit display names, icons, descriptions, and instructions.</p>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($paymentMethods as $method)
                        <div class="pm-item-card" id="pm-card-{{ $method->id }}" style="border:1.5px solid var(--gt-border);border-radius:14px;background:#ffffff;overflow:hidden;box-shadow:var(--gt-shadow);">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:#fffdf9;gap:12px;">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#fff;border:1.5px solid var(--gt-border);display:flex;align-items:center;justify-content:center;color:var(--gt-primary);flex-shrink:0;">
                                        @if($method->logo_url)
                                            <img src="{{ $method->logo_url }}" alt="{{ $method->name }}" style="max-width:100%;max-height:100%;object-fit:contain;">
                                        @else
                                            <i class="{{ $method->icon ?: 'fas fa-credit-card' }}" style="font-size:1.1rem;color:var(--gt-primary);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span style="font-size:0.92rem;font-weight:800;color:var(--gt-text);">{{ $method->name }}</span>
                                        <span id="pm-inactive-badge-{{ $method->id }}" style="font-size:0.7rem;background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:10px;margin-left:6px;font-weight:700;display:{{ $method->is_active ? 'none' : 'inline-block' }};">Inactive</span>
                                    </div>
                                </div>

                                <div style="display:flex;align-items:center;gap:14px;">
                                    <label style="display:inline-flex;align-items:center;cursor:pointer;gap:6px;">
                                        <input type="checkbox" id="pm-toggle-{{ $method->id }}" onchange="togglePaymentStatus({{ $method->id }}, this)" {{ $method->is_active ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--gt-primary);cursor:pointer;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                function togglePaymentStatus(id, checkbox) {
                    const isChecked = checkbox.checked;
                    checkbox.disabled = true;

                    fetch('/admin/payment-methods/' + id + '/toggle-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ is_active: isChecked ? 1 : 0 })
                    })
                    .then(res => res.json())
                    .then(data => {
                        checkbox.disabled = false;
                        const badge = document.getElementById('pm-inactive-badge-' + id);
                        if (badge) badge.style.display = isChecked ? 'none' : 'inline-block';
                    })
                    .catch(() => {
                        checkbox.disabled = false;
                        checkbox.checked = !isChecked;
                    });
                }
            </script>

        @elseif($tab === 'tax')
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Tax Settings</h2>
                    <p class="settings-card-subtitle">Configure GST/tax percentages and pricing options.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="tax">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Default Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate_percent" value="{{ $settings['tax_rate_percent'] ?? '0.00' }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Tax Option</label>
                            <select name="tax_pricing_mode" class="gt-input" style="width:100%;">
                                <option value="exclusive" {{ ($settings['tax_pricing_mode'] ?? 'exclusive') === 'exclusive' ? 'selected' : '' }}>Prices Exclude Tax</option>
                                <option value="inclusive" {{ ($settings['tax_pricing_mode'] ?? '') === 'inclusive' ? 'selected' : '' }}>Prices Include Tax</option>
                            </select>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Tax Settings</button>
                    </div>
                </form>
            </div>

        @elseif($tab === 'notifications')
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Notification Settings</h2>
                    <p class="settings-card-subtitle">Configure automated email and administrative notification alerts.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="notifications">
                    <div style="display:flex;flex-direction:column;gap:12px;font-size:0.85rem;color:var(--gt-text);font-weight:700;">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_new_orders" value="1" {{ ($settings['notify_new_orders'] ?? '1') == '1' ? 'checked' : '' }}> Email alerts on New Customer Orders
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_low_stock" value="1" {{ ($settings['notify_low_stock'] ?? '1') == '1' ? 'checked' : '' }}> Low Stock Alerts notifications
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_new_reviews" value="1" {{ ($settings['notify_new_reviews'] ?? '1') == '1' ? 'checked' : '' }}> Moderation alerts on New Reviews
                        </label>
                    </div>
                    <div style="text-align:right;margin-top:16px;">
                        <button type="submit" class="gt-btn-primary">Save Preferences</button>
                    </div>
                </form>
            </div>
        @endif
    </div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.previewImage = function(input, previewElId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewElId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        };
    });
</script>
@endsection
