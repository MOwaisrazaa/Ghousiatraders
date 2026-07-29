@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Settings')

@section('content')
<style>
    /* Two-column layout */
    .settings-grid {
        display: grid;
        grid-template-columns: 7fr 3fr;
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
        gap: 16px;
        border-bottom: 1.5px solid var(--gt-border);
        padding-bottom: 2px;
        margin-bottom: 24px;
        overflow-x: auto;
        white-space: nowrap;
    }

    .settings-tab-btn {
        background: none;
        border: none;
        padding: 8px 12px;
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
    }

    .settings-tab-btn i {
        width: 16px;
        height: 16px;
    }

    .settings-tab-btn:hover {
        color: var(--gt-primary);
    }

    .settings-tab-btn.active {
        color: var(--gt-primary);
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

    /* Appearance theme choice */
    .theme-selection-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .theme-card-option {
        border: 1.5px solid var(--gt-border);
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        background: #fffdf9;
    }

    .theme-card-option:hover {
        border-color: #d7a64a;
        background: var(--gt-primary-light);
    }

    .theme-card-option.selected {
        border-color: var(--gt-primary);
        background: var(--gt-primary-light);
        box-shadow: 0 0 0 2px rgba(53, 27, 13, 0.05);
    }

    .theme-card-option i {
        width: 24px;
        height: 24px;
        color: var(--gt-text-muted);
        margin-bottom: 4px;
    }

    .theme-card-option.selected i {
        color: var(--gt-primary);
    }

    .theme-card-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gt-text);
    }

    /* Color picker dots */
    .color-picker-flex {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .color-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.2s;
        position: relative;
    }

    .color-dot:hover {
        transform: scale(1.15);
    }

    .color-dot.selected {
        border-color: #ffffff;
        box-shadow: 0 0 0 2px var(--gt-text);
        transform: scale(1.1);
    }

    /* Logo assets previews */
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

    /* More tabs dropdown */
    .more-dropdown-container {
        position: relative;
    }

    .more-dropdown-menu {
        position: absolute;
        right: 0;
        top: 36px;
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(53, 27, 13, 0.08);
        min-width: 160px;
        z-index: 90;
        display: none;
        flex-direction: column;
        padding: 4px;
    }

    .more-dropdown-menu.show {
        display: flex;
    }

    .more-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        color: var(--gt-text-muted);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .more-dropdown-item:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    .more-dropdown-item.active {
        color: var(--gt-primary);
        background-color: var(--gt-primary-light);
        font-weight: 700;
    }

    /* Modals */
    .gt-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(53, 27, 13, 0.4);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 500;
        padding: 20px;
        box-sizing: border-box;
    }

    .gt-modal.show {
        display: flex;
    }

    .gt-modal-dialog {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 20px 50px rgba(53, 27, 13, 0.15);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        animation: modalFadeIn 0.3s ease;
        box-sizing: border-box;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .gt-modal-header {
        padding: 16px 24px;
        border-bottom: 1.5px solid var(--gt-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .gt-modal-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--gt-primary);
    }

    .gt-modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--gt-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .gt-modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
        box-sizing: border-box;
    }

    .gt-modal-footer {
        padding: 16px 24px;
        border-top: 1.5px solid var(--gt-border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
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

<!-- Sub navigation page header -->
<div class="sub-nav-bar" style="margin-bottom: 24px;">
    <div class="sub-nav-left">
        <h1 class="page-title">Settings</h1>
        <div class="breadcrumbs-list">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i data-lucide="chevron-right"></i>
            <span>Settings</span>
            <i data-lucide="chevron-right"></i>
            <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $tab) }}</span>
        </div>
    </div>
</div>

<!-- Horizontal navigation tabs -->
<div class="settings-tabs-nav">
    <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" class="settings-tab-btn {{ $tab === 'general' ? 'active' : '' }}">
        <i data-lucide="settings"></i> General
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'store_info']) }}" class="settings-tab-btn {{ $tab === 'store_info' ? 'active' : '' }}">
        <i data-lucide="info"></i> Store Information
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'payment_methods']) }}" class="settings-tab-btn {{ $tab === 'payment_methods' ? 'active' : '' }}">
        <i data-lucide="credit-card"></i> Payment Methods
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'shipping']) }}" class="settings-tab-btn {{ $tab === 'shipping' ? 'active' : '' }}">
        <i data-lucide="truck"></i> Shipping Settings
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'tax']) }}" class="settings-tab-btn {{ $tab === 'tax' ? 'active' : '' }}">
        <i data-lucide="percent"></i> Tax Settings
    </a>
    <a href="{{ route('admin.settings.index', ['tab' => 'notifications']) }}" class="settings-tab-btn {{ $tab === 'notifications' ? 'active' : '' }}">
        <i data-lucide="bell"></i> Notifications
    </a>
    
    <div class="more-dropdown-container">
        <button class="settings-tab-btn {{ in_array($tab, ['roles', 'api', 'backup']) ? 'active' : '' }}" onclick="toggleMoreMenu(event)">
            <i data-lucide="more-horizontal"></i> More <i data-lucide="chevron-down" style="width:12px;height:12px;"></i>
        </button>
        <div class="more-dropdown-menu" id="moreDropdownMenu">
            <a href="{{ route('admin.settings.index', ['tab' => 'roles']) }}" class="more-dropdown-item {{ $tab === 'roles' ? 'active' : '' }}">Roles & Permissions</a>
            <a href="{{ route('admin.settings.index', ['tab' => 'api']) }}" class="more-dropdown-item {{ $tab === 'api' ? 'active' : '' }}">API Settings</a>
            <a href="{{ route('admin.settings.index', ['tab' => 'backup']) }}" class="more-dropdown-item {{ $tab === 'backup' ? 'active' : '' }}">Backup & Restore</a>
        </div>
    </div>
</div>

<div class="settings-grid">
    <!-- Left Column: Forms content -->
    <div class="settings-main-column">
        @if($tab === 'general')
            <!-- 1. General settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">General Settings</h2>
                    <p class="settings-card-subtitle">Configure your store's basic settings and preferences.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="general">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Store Name</label>
                            <input type="text" name="store_name" required value="{{ $settings['store_name'] }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Store Email</label>
                            <input type="email" name="store_email" required value="{{ $settings['store_email'] }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Store Phone</label>
                            <input type="text" name="store_phone" required value="{{ $settings['store_phone'] }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Store Currency</label>
                            <select name="store_currency" required class="gt-input" style="width:100%;">
                                <option value="PKR" {{ $settings['store_currency'] === 'PKR' ? 'selected' : '' }}>PKR (Pakistani Rupee)</option>
                                <option value="USD" {{ $settings['store_currency'] === 'USD' ? 'selected' : '' }}>USD (United States Dollar)</option>
                                <option value="GBP" {{ $settings['store_currency'] === 'GBP' ? 'selected' : '' }}>GBP (British Pound)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Store Timezone</label>
                            <select name="store_timezone" required class="gt-input" style="width:100%;">
                                <option value="Asia/Karachi" {{ $settings['store_timezone'] === 'Asia/Karachi' ? 'selected' : '' }}>(GMT+05:00) Pakistan Standard Time (PKT)</option>
                                <option value="UTC" {{ $settings['store_timezone'] === 'UTC' ? 'selected' : '' }}>UTC / GMT Timezone</option>
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Date Format</label>
                            <select name="date_format" required class="gt-input" style="width:100%;">
                                <option value="F d, Y" {{ $settings['date_format'] === 'F d, Y' ? 'selected' : '' }}>May 31, 2024 (F d, Y)</option>
                                <option value="d M Y" {{ $settings['date_format'] === 'd M Y' ? 'selected' : '' }}>31 May 2024 (d M Y)</option>
                                <option value="d/m/Y" {{ $settings['date_format'] === 'd/m/Y' ? 'selected' : '' }}>31/05/2024 (d/m/Y)</option>
                                <option value="Y-m-d" {{ $settings['date_format'] === 'Y-m-d' ? 'selected' : '' }}>2024-05-31 (Y-m-d)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Time Format</label>
                            <select name="time_format" required class="gt-input" style="width:100%;">
                                <option value="12h" {{ $settings['time_format'] === '12h' ? 'selected' : '' }}>12 Hours (03:45 PM)</option>
                                <option value="24h" {{ $settings['time_format'] === '24h' ? 'selected' : '' }}>24 Hours (15:45)</option>
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Items Per Page</label>
                            <input type="number" name="items_per_page" required value="{{ $settings['items_per_page'] }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- 2. Store Address card -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Store Address</h2>
                    <p class="settings-card-subtitle">This address will be used on invoices, emails and store contact information.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="address">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Address Line 1</label>
                            <input type="text" name="address_line_1" required value="{{ $settings['address_line_1'] }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Address Line 2 (Optional)</label>
                            <input type="text" name="address_line_2" value="{{ $settings['address_line_2'] }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">City</label>
                            <input type="text" name="city" required value="{{ $settings['city'] }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">State / Province</label>
                            <input type="text" name="state" required value="{{ $settings['state'] }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Country</label>
                            <select name="country" required class="gt-input" style="width:100%;">
                                <option value="Pakistan" {{ $settings['country'] === 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                <option value="United States" {{ $settings['country'] === 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="United Kingdom" {{ $settings['country'] === 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Postal / ZIP Code</label>
                            <input type="text" name="postal_code" required value="{{ $settings['postal_code'] }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        @elseif($tab === 'store_info')
            <!-- Store Information form -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Store Information</h2>
                    <p class="settings-card-subtitle">Manage legal business registrations, social connections, and contact details.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="store_info">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Business Name</label>
                            <input type="text" name="business_name" value="{{ App\Models\Setting::get('business_name', 'Ghousia Traders Private Ltd') }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Legal Business Name</label>
                            <input type="text" name="legal_name" value="{{ App\Models\Setting::get('legal_name', 'Ghousia Traders Legal') }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Business Registration Information</label>
                            <input type="text" name="registration_info" value="{{ App\Models\Setting::get('registration_info', 'REG-123456-PAK') }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Business Hours</label>
                            <input type="text" name="business_hours" value="{{ App\Models\Setting::get('business_hours', '9:00 AM - 6:00 PM') }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label class="gt-label">Store Description</label>
                        <textarea name="store_description" class="gt-input" style="width:100%;height:80px;padding:8px;">{{ App\Models\Setting::get('store_description', 'Premium toy and fragrance catalog provider.') }}</textarea>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Information</button>
                    </div>
                </form>
            </div>
        @elseif($tab === 'payment_methods')
            <!-- Payment methods configs -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Payment Methods</h2>
                    <p class="settings-card-subtitle">Configure gateways, display orders, and settings.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="payment_methods">
                    
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        <label style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;background:#fffdf9;cursor:pointer;">
                            <span style="font-size:0.85rem;font-weight:700;color:var(--gt-text);">Cash on Delivery (COD)</span>
                            <input type="checkbox" name="payment_cod_active" value="1" {{ App\Models\Setting::get('payment_cod_active', '1') == '1' ? 'checked' : '' }}>
                        </label>
                        <label style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;background:#fffdf9;cursor:pointer;">
                            <span style="font-size:0.85rem;font-weight:700;color:var(--gt-text);">EasyPaisa Gateway</span>
                            <input type="checkbox" name="payment_easypaisa_active" value="1" {{ App\Models\Setting::get('payment_easypaisa_active', '1') == '1' ? 'checked' : '' }}>
                        </label>
                        <label style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;background:#fffdf9;cursor:pointer;">
                            <span style="font-size:0.85rem;font-weight:700;color:var(--gt-text);">JazzCash Gateway</span>
                            <input type="checkbox" name="payment_jazzcash_active" value="1" {{ App\Models\Setting::get('payment_jazzcash_active', '1') == '1' ? 'checked' : '' }}>
                        </label>
                        <label style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;background:#fffdf9;cursor:pointer;">
                            <span style="font-size:0.85rem;font-weight:700;color:var(--gt-text);">Direct Bank Transfer</span>
                            <input type="checkbox" name="payment_bank_active" value="1" {{ App\Models\Setting::get('payment_bank_active', '1') == '1' ? 'checked' : '' }}>
                        </label>
                        <label style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1.5px solid var(--gt-border);border-radius:10px;background:#fffdf9;cursor:pointer;">
                            <span style="font-size:0.85rem;font-weight:700;color:var(--gt-text);">Credit / Debit Card (Stripe)</span>
                            <input type="checkbox" name="payment_card_active" value="1" {{ App\Models\Setting::get('payment_card_active', '1') == '1' ? 'checked' : '' }}>
                        </label>
                    </div>

                    <div style="text-align:right;margin-top:16px;">
                        <button type="submit" class="gt-btn-primary">Save Gateways</button>
                    </div>
                </form>
            </div>
        @elseif($tab === 'shipping')
            <!-- Shipping Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Shipping Settings</h2>
                    <p class="settings-card-subtitle">Set flat rates, free shipping thresholds, and courier estimates.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="shipping">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Flat Rate Shipping Cost (PKR)</label>
                            <input type="number" name="shipping_flat_rate" value="{{ App\Models\Setting::get('shipping_flat_rate', '250') }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Free Shipping Minimum Threshold (PKR)</label>
                            <input type="number" name="shipping_free_threshold" value="{{ App\Models\Setting::get('shipping_free_threshold', '5000') }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Default Delivery Days Estimate</label>
                            <input type="text" name="shipping_estimate_days" value="{{ App\Models\Setting::get('shipping_estimate_days', '3-5 Working Days') }}" class="gt-input" style="width: 100%;">
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Shipping</button>
                    </div>
                </form>
            </div>
        @elseif($tab === 'tax')
            <!-- Tax Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Tax Settings</h2>
                    <p class="settings-card-subtitle">Configure GST/tax percentages, and pricing preferences.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="tax">
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Default Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate_percent" value="{{ App\Models\Setting::get('tax_rate_percent', '17.00') }}" class="gt-input" style="width: 100%;">
                        </div>
                        <div>
                            <label class="gt-label">Tax Option</label>
                            <select name="tax_pricing_mode" class="gt-input" style="width:100%;">
                                <option value="exclusive" {{ App\Models\Setting::get('tax_pricing_mode') === 'exclusive' ? 'selected' : '' }}>Prices Exclude Tax</option>
                                <option value="inclusive" {{ App\Models\Setting::get('tax_pricing_mode') === 'inclusive' ? 'selected' : '' }}>Prices Include Tax</option>
                            </select>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="gt-btn-primary">Save Tax</button>
                    </div>
                </form>
            </div>
        @elseif($tab === 'notifications')
            <!-- Notifications -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">Notification Settings</h2>
                    <p class="settings-card-subtitle">Enable emails, push notifications, and admin notification alerts.</p>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="notifications">
                    <div style="display:flex;flex-direction:column;gap:12px;font-size:0.85rem;color:var(--gt-text);font-weight:700;">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_new_orders" value="1" {{ App\Models\Setting::get('notify_new_orders', '1') == '1' ? 'checked' : '' }}> Email alerts on New Customer Orders
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_low_stock" value="1" {{ App\Models\Setting::get('notify_low_stock', '1') == '1' ? 'checked' : '' }}> Low Stock Alerts notifications
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_new_reviews" value="1" {{ App\Models\Setting::get('notify_new_reviews', '1') == '1' ? 'checked' : '' }}> Moderation alerts on New Reviews
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="notify_refunds" value="1" {{ App\Models\Setting::get('notify_refunds', '1') == '1' ? 'checked' : '' }}> Notifications on Customer Refund requests
                        </label>
                    </div>
                    <div style="text-align:right;margin-top:16px;">
                        <button type="submit" class="gt-btn-primary">Save Preferences</button>
                    </div>
                </form>
            </div>
        @else
            <!-- Roles, API, Backup fallback wrapper -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2 class="settings-card-title" style="text-transform: capitalize;">{{ str_replace('_', ' ', $tab) }} Settings</h2>
                    <p class="settings-card-subtitle">Manage advanced data operations and parameters.</p>
                </div>
                <div style="background-color:#fffdf9; border:1.5px solid var(--gt-border); padding:20px; border-radius:12px; font-weight:700; color:var(--gt-text-muted); font-size:0.8rem;">
                    @if($tab === 'roles')
                        Role assignments and group permission configurations can be managed under the <a href="{{ route('admin.user.assignment') }}" style="color:var(--gt-primary);">Users & Roles</a> control panels directly.
                    @elseif($tab === 'api')
                        Generate webhook secrets, inspect authorization tokens, and integrate third-party courier channels securely.
                    @else
                        Create local zip compressed snapshots of SQL files and store media files to easily backup and restore the store state.
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Profile, Security, Appearance sidebars -->
    <div class="settings-sidebar-column">
        <!-- 1. Store Profile (Logo/Favicon) -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2 class="settings-card-title">Store Profile</h2>
                <p class="settings-card-subtitle">Update your store logo and favicon.</p>
            </div>
            <form action="{{ route('admin.settings.upload-logo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <span class="gt-label">Current Logo</span>
                    <div class="logo-uploader-row">
                        <div class="logo-preview-box">
                            <img id="logoPreviewEl" src="{{ asset($settings['store_logo']) }}" alt="Logo">
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;line-height:1.2;">
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);font-weight:600;">Recommended size:<br>512 x 512 px (PNG)</span>
                            <label class="gt-btn-outline" style="cursor:pointer;padding:4px 8px;min-height:auto;font-size:0.7rem;">
                                Choose File
                                <input type="file" name="logo" style="display:none;" onchange="previewImage(this, 'logoPreviewEl')">
                            </label>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <span class="gt-label">Favicon</span>
                    <div class="logo-uploader-row">
                        <div class="logo-preview-box" style="width:40px;height:40px;">
                            <img id="faviconPreviewEl" src="{{ asset($settings['store_favicon']) }}" alt="Fav">
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;line-height:1.2;">
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);font-weight:600;">Recommended size:<br>32 x 32 px (ICO, PNG)</span>
                            <label class="gt-btn-outline" style="cursor:pointer;padding:4px 8px;min-height:auto;font-size:0.7rem;">
                                Choose File
                                <input type="file" name="favicon" style="display:none;" onchange="previewImage(this, 'faviconPreviewEl')">
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="gt-btn-primary" style="width:100%; justify-content:center;">Save Profile Assets</button>
            </form>
        </div>

        <!-- 2. Security Card -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2 class="settings-card-title">Security</h2>
                <p class="settings-card-subtitle">Manage your password and security preferences.</p>
            </div>
            <div style="display:flex;flex-direction:column;gap:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;line-height:1.2;">
                    <div>
                        <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Admin Email</strong>
                        <div style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:600;">{{ auth()->user()->email }}</div>
                    </div>
                    <button class="gt-btn-outline" style="padding:4px 8px;min-height:auto;font-size:0.7rem;" onclick="openModal('changeEmailModal')">Change Email</button>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;line-height:1.2;">
                    <div>
                        <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Password</strong>
                        <div style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:600;">*****************</div>
                    </div>
                    <button class="gt-btn-outline" style="padding:4px 8px;min-height:auto;font-size:0.7rem;" onclick="openModal('changePasswordModal')">Change Password</button>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;line-height:1.2;padding-top:12px;border-top:1px solid rgba(215, 166, 74, 0.1);">
                    <div>
                        <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Two-Factor Authentication</strong>
                        <div style="font-size:0.65rem;color:var(--gt-text-muted);font-weight:600;">Add an extra layer of security.</div>
                    </div>
                    <form action="{{ route('admin.settings.security') }}" method="POST" id="toggle2faForm">
                        @csrf
                        <input type="hidden" name="security_action" value="toggle_2fa">
                        <span class="badge-status {{ $settings['two_factor_enabled'] ? 'badge-enabled' : 'badge-disabled' }}" style="cursor:pointer;" onclick="document.getElementById('toggle2faForm').submit()">
                            {{ $settings['two_factor_enabled'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. Email Settings Card -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2 class="settings-card-title">Email Settings</h2>
                <p class="settings-card-subtitle">Configure how emails are sent from your store.</p>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
                <div style="line-height:1.2;">
                    <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">From Name</strong>
                    <div style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:600;">{{ $settings['from_name'] }}</div>
                </div>
                <div style="line-height:1.2;">
                    <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">From Email</strong>
                    <div style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:600;">{{ $settings['from_email'] }}</div>
                </div>
            </div>
            <button class="gt-btn-outline" style="width:100%;justify-content:center;" onclick="openModal('configureSmtpModal')">Configure SMTP</button>
        </div>

        <!-- 4. Appearance card -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2 class="settings-card-title">Appearance</h2>
                <p class="settings-card-subtitle">Choose your preferred admin panel theme.</p>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="tab" value="appearance">
                
                <div class="theme-selection-row">
                    <div class="theme-card-option {{ $settings['theme'] === 'light' ? 'selected' : '' }}" onclick="selectTheme('light')">
                        <i data-lucide="sun"></i>
                        <div class="theme-card-title">Light</div>
                    </div>
                    <div class="theme-card-option {{ $settings['theme'] === 'dark' ? 'selected' : '' }}" onclick="selectTheme('dark')">
                        <i data-lucide="moon"></i>
                        <div class="theme-card-title">Dark</div>
                    </div>
                    <div class="theme-card-option {{ $settings['theme'] === 'system' ? 'selected' : '' }}" onclick="selectTheme('system')">
                        <i data-lucide="monitor"></i>
                        <div class="theme-card-title">System</div>
                    </div>
                </div>
                <input type="hidden" name="theme" id="selectedThemeInput" value="{{ $settings['theme'] }}">

                <div style="margin-bottom: 16px;">
                    <span class="gt-label" style="margin-bottom:8px;">Primary Color</span>
                    <div class="color-picker-flex">
                        @foreach(['brown' => '#351b0d', 'blue' => '#1d4ed8', 'purple' => '#7c3aed', 'green' => '#047857', 'orange' => '#ea580c', 'red' => '#b91c1c', 'slate' => '#475569'] as $color => $hex)
                            <div class="color-dot {{ $settings['primary_color'] === $color ? 'selected' : '' }}" style="background-color: {{ $hex }};" onclick="selectColor(this, '{{ $color }}')"></div>
                        @endforeach
                    </div>
                    <input type="hidden" name="primary_color" id="selectedColorInput" value="{{ $settings['primary_color'] }}">
                </div>

                <button type="submit" class="gt-btn-primary" style="width:100%; justify-content:center;">Apply Style Preferences</button>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Change Email Modal -->
<div class="gt-modal" id="changeEmailModal" onclick="closeModalOnOutsideClick(event, 'changeEmailModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Change Admin Email</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('changeEmailModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.settings.security') }}" method="POST">
            @csrf
            <input type="hidden" name="security_action" value="email">
            <div class="gt-modal-body">
                <div class="form-group">
                    <label class="gt-label">New Email Address</label>
                    <input type="email" name="email" required value="{{ auth()->user()->email }}" class="gt-input" style="width:100%;">
                </div>
                <div class="form-group">
                    <label class="gt-label">Current Password</label>
                    <input type="password" name="password" required class="gt-input" style="width:100%;">
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('changeEmailModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Change Email</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Change Password Modal -->
<div class="gt-modal" id="changePasswordModal" onclick="closeModalOnOutsideClick(event, 'changePasswordModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Change Password</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('changePasswordModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.settings.security') }}" method="POST">
            @csrf
            <input type="hidden" name="security_action" value="password">
            <div class="gt-modal-body">
                <div class="form-group">
                    <label class="gt-label">Current Password</label>
                    <input type="password" name="current_password" required class="gt-input" style="width:100%;">
                </div>
                <div class="form-group">
                    <label class="gt-label">New Password</label>
                    <input type="password" name="new_password" required class="gt-input" style="width:100%;">
                </div>
                <div class="form-group">
                    <label class="gt-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" required class="gt-input" style="width:100%;">
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('changePasswordModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Configure SMTP Modal -->
<div class="gt-modal" id="configureSmtpModal" onclick="closeModalOnOutsideClick(event, 'configureSmtpModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Configure SMTP</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('configureSmtpModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <div class="gt-modal-body">
            <!-- Test connection row -->
            <form action="{{ route('admin.settings.smtp') }}" method="POST" style="margin-bottom:16px; border-bottom:1px solid var(--gt-border); padding-bottom:16px;">
                @csrf
                <input type="hidden" name="send_test" value="1">
                <label class="gt-label">Send Test Email To</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="email" name="test_email" required placeholder="email@example.com" class="gt-input" style="flex:1;">
                    <button type="submit" class="gt-btn-outline">Send Test</button>
                </div>
            </form>

            <form action="{{ route('admin.settings.smtp') }}" method="POST">
                @csrf
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">From Name</label>
                        <input type="text" name="from_name" value="{{ $settings['from_name'] }}" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">From Email</label>
                        <input type="email" name="from_email" value="{{ $settings['from_email'] }}" required class="gt-input" style="width:100%;">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Mail Driver</label>
                        <input type="text" name="mail_driver" value="{{ $settings['mail_driver'] }}" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] }}" required class="gt-input" style="width:100%;">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">SMTP Port</label>
                        <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] }}" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Encryption</label>
                        <input type="text" name="smtp_encryption" value="{{ $settings['smtp_encryption'] }}" required class="gt-input" style="width:100%;">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Username</label>
                        <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] }}" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Password</label>
                        <input type="password" name="smtp_password" placeholder="Leave blank to keep current" class="gt-input" style="width:100%;">
                    </div>
                </div>
                
                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
                    <button type="button" class="gt-btn-outline" onclick="closeModal('configureSmtpModal')">Cancel</button>
                    <button type="submit" class="gt-btn-primary">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle More Submenu Dropdown
        window.toggleMoreMenu = function(event) {
            event.stopPropagation();
            document.getElementById('moreDropdownMenu').classList.toggle('show');
        };

        document.addEventListener('click', () => {
            const dropdown = document.getElementById('moreDropdownMenu');
            if (dropdown) dropdown.classList.remove('show');
        });

        // Modal triggers
        window.openModal = function(id) {
            document.getElementById(id).classList.add('show');
        };

        window.closeModal = function(id) {
            document.getElementById(id).classList.remove('show');
        };

        window.closeModalOnOutsideClick = function(event, id) {
            if (event.target === document.getElementById(id)) {
                closeModal(id);
            }
        };

        // File upload image preview
        window.previewImage = function(input, previewElId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewElId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        // Appearance theme selections
        window.selectTheme = function(themeValue) {
            document.querySelectorAll('.theme-card-option').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('selectedThemeInput').value = themeValue;
        };

        // Color selection picker dots
        window.selectColor = function(dotElement, colorName) {
            document.querySelectorAll('.color-dot').forEach(dot => {
                dot.classList.remove('selected');
            });
            dotElement.classList.add('selected');
            document.getElementById('selectedColorInput').value = colorName;
        };
    });
</script>
@endsection
