@extends('admin.ghousia-layout')

@section('title', 'Users & Roles')

@push('head')
<style>
    /* Theme Tokens for Ghousia Traders Users & Roles Page */
    :root {
        --ur-brown-primary: #44240f;
        --ur-brown-hover: #351b0d;
        --ur-brown-light: #fff3df;
        --ur-brown-border: rgba(215, 166, 74, 0.25);
        --ur-card-bg: #ffffff;
        --ur-bg: #fffcf8;
        --ur-text: #351b0d;
        --ur-text-muted: #8a7355;
    }

    /* Outer Wrapper Grid */
    .ur-main-layout {
        display: grid;
        grid-template-columns: minmax(0, 78%) minmax(0, 22%);
        gap: 24px;
        width: 100%;
        box-sizing: border-box;
        align-items: start;
    }

    /* Flex / Grid Child Min-Width Reset */
    .ur-main-layout > * {
        min-width: 0;
        box-sizing: border-box;
    }

    /* Page Header Bar */
    .ur-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .ur-page-title-wrap h1 {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--ur-text);
        letter-spacing: -0.02em;
        margin: 0 0 4px 0;
    }

    .ur-breadcrumb {
        font-size: 0.8rem;
        color: var(--ur-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    .ur-breadcrumb a {
        color: var(--ur-text-muted);
        text-decoration: none;
        transition: color 0.2s;
    }

    .ur-breadcrumb a:hover {
        color: var(--ur-brown-primary);
    }

    /* Split Button + Add New User */
    .ur-btn-split {
        display: inline-flex;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(68, 36, 15, 0.15);
        position: relative;
    }

    .ur-btn-primary {
        background: var(--ur-brown-primary);
        color: #ffffff !important;
        border: none;
        padding: 10px 18px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .ur-btn-primary:hover {
        background: var(--ur-brown-hover);
    }

    .ur-btn-split-toggle {
        background: #351b0d;
        color: #ffffff;
        border: none;
        padding: 10px 12px;
        cursor: pointer;
        border-left: 1px solid rgba(255, 255, 255, 0.15);
        transition: background 0.2s;
    }

    .ur-btn-split-toggle:hover {
        background: #241107;
    }

    /* Navigation Tabs */
    .ur-nav-tabs {
        display: flex;
        align-items: center;
        gap: 32px;
        border-bottom: 2px solid var(--ur-brown-border);
        margin-bottom: 24px;
        padding-bottom: 0;
    }

    .ur-tab-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 4px;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ur-text-muted);
        text-decoration: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .ur-tab-item:hover {
        color: var(--ur-brown-primary);
    }

    .ur-tab-item.active {
        color: var(--ur-brown-primary);
        border-bottom-color: var(--ur-brown-primary);
    }

    /* Stat Cards Row */
    .ur-stats-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .ur-stat-card {
        background: #ffffff;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 16px rgba(53, 27, 13, 0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .ur-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(53, 27, 13, 0.06);
    }

    .ur-stat-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .ur-stat-label {
        font-size: 0.76rem;
        font-weight: 700;
        color: var(--ur-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .ur-stat-val {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ur-text);
        line-height: 1.1;
    }

    .ur-stat-meta {
        font-size: 0.73rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ur-stat-meta.positive { color: #10b981; }
    .ur-stat-meta.negative { color: #ef4444; }
    .ur-stat-meta.neutral { color: #8b5cf6; }

    .ur-stat-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ur-stat-icon-wrap.users { background: #fff5eb; color: var(--ur-brown-primary); }
    .ur-stat-icon-wrap.active { background: #ecfdf5; color: #10b981; }
    .ur-stat-icon-wrap.inactive { background: #fef2f2; color: #ef4444; }
    .ur-stat-icon-wrap.roles { background: #f3e8ff; color: #8b5cf6; }

    /* Management Card Container */
    .ur-card {
        background: #ffffff;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(53, 27, 13, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }

    /* Filters Header inside Card */
    .ur-filters-bar {
        padding: 18px 20px;
        border-bottom: 1.5px solid var(--ur-brown-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        background: #ffffff;
    }

    .ur-filters-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 280px;
        flex-wrap: wrap;
    }

    .ur-select {
        background: #fffdf9;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ur-text);
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ur-select:focus {
        border-color: var(--ur-brown-primary);
        box-shadow: 0 0 0 3px rgba(68, 36, 15, 0.1);
    }

    .ur-search-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .ur-search-input {
        width: 100%;
        background: #fffdf9;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 10px;
        padding: 9px 38px 9px 14px;
        font-size: 0.85rem;
        color: var(--ur-text);
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .ur-search-input:focus {
        border-color: var(--ur-brown-primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(68, 36, 15, 0.1);
    }

    .ur-search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ur-text-muted);
        pointer-events: none;
        width: 16px;
        height: 16px;
    }

    .ur-filters-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ur-btn-outline {
        background: #ffffff;
        border: 1.5px solid var(--ur-brown-border);
        color: var(--ur-text);
        padding: 9px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .ur-btn-outline:hover {
        background: var(--ur-brown-light);
        border-color: var(--ur-brown-primary);
        color: var(--ur-brown-primary);
    }

    /* Table Styles */
    .ur-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .ur-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.87rem;
    }

    .ur-table th {
        background: #faf6f0;
        color: var(--ur-text-muted);
        font-size: 0.74rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 18px;
        border-bottom: 1.5px solid var(--ur-brown-border);
        white-space: nowrap;
    }

    .ur-table td {
        padding: 14px 18px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.15);
        vertical-align: middle;
        color: var(--ur-text);
    }

    .ur-table tbody tr {
        transition: background 0.15s;
    }

    .ur-table tbody tr:hover {
        background: #fffdf7;
    }

    /* User Profile Column Cell */
    .ur-user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ur-avatar-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid var(--ur-brown-border);
    }

    .ur-avatar-initials {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #fdf5e6;
        color: var(--ur-brown-primary);
        font-weight: 800;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--ur-brown-border);
        flex-shrink: 0;
    }

    .ur-user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .ur-user-name {
        font-weight: 700;
        color: var(--ur-text);
        font-size: 0.88rem;
    }

    .ur-user-username {
        font-size: 0.74rem;
        color: var(--ur-text-muted);
        font-weight: 500;
    }

    /* Badges */
    .ur-role-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    /* Role Color Palettes */
    .ur-role-super-admin { background: #fdf2f0; color: #44240f; border: 1px solid rgba(68, 36, 15, 0.25); }
    .ur-role-administrator { background: #e0f2fe; color: #0369a1; border: 1px solid rgba(3, 105, 161, 0.25); }
    .ur-role-manager { background: #f3e8ff; color: #7e22ce; border: 1px solid rgba(126, 34, 206, 0.25); }
    .ur-role-editor { background: #fef3c7; color: #b45309; border: 1px solid rgba(180, 83, 9, 0.25); }
    .ur-role-customer-support { background: #ccfbf1; color: #0f766e; border: 1px solid rgba(15, 118, 110, 0.25); }
    .ur-role-sales-agent { background: #ffe4e6; color: #be123c; border: 1px solid rgba(190, 18, 60, 0.25); }
    .ur-role-inventory-manager { background: #dcfce7; color: #15803d; border: 1px solid rgba(21, 128, 61, 0.25); }
    .ur-role-default { background: #f3f4f6; color: #374151; border: 1px solid rgba(55, 65, 81, 0.2); }

    /* Status Badges */
    .ur-status-dot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .ur-status-dot::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .ur-status-active { color: #10b981; }
    .ur-status-active::before { background: #10b981; }
    .ur-status-inactive { color: #ef4444; }
    .ur-status-inactive::before { background: #ef4444; }
    .ur-status-suspended { color: #6b7280; }
    .ur-status-suspended::before { background: #6b7280; }

    /* Action Buttons */
    .ur-action-btns {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ur-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--ur-brown-border);
        background: #ffffff;
        color: var(--ur-text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .ur-icon-btn:hover {
        background: var(--ur-brown-light);
        color: var(--ur-brown-primary);
        border-color: var(--ur-brown-primary);
    }

    /* Dropdown menu */
    .ur-dropdown {
        position: relative;
    }

    .ur-dropdown-menu {
        position: absolute;
        right: 0;
        top: 36px;
        background: #ffffff;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(53, 27, 13, 0.12);
        width: 190px;
        z-index: 120;
        display: none;
        flex-direction: column;
        padding: 6px;
    }

    .ur-dropdown-menu.show {
        display: flex;
    }

    .ur-dropdown-item {
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ur-text);
        text-decoration: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
        transition: background 0.15s;
    }

    .ur-dropdown-item:hover {
        background: var(--ur-brown-light);
        color: var(--ur-brown-primary);
    }

    .ur-dropdown-item.danger {
        color: #ef4444;
    }

    .ur-dropdown-item.danger:hover {
        background: #fef2f2;
    }

    /* Footer Pagination Bar */
    .ur-pagination-bar {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1.5px solid var(--ur-brown-border);
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
        font-size: 0.83rem;
        color: var(--ur-text-muted);
    }

    /* Right Sidebar Cards */
    .ur-right-col {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .ur-side-card {
        background: #ffffff;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(53, 27, 13, 0.02);
    }

    .ur-side-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .ur-side-card-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--ur-text);
    }

    .ur-side-add-link {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ur-brown-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ur-side-add-link:hover {
        text-decoration: underline;
    }

    /* Roles Overview List */
    .ur-roles-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ur-role-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border-radius: 10px;
        transition: background 0.15s;
    }

    .ur-role-item:hover {
        background: var(--ur-brown-light);
    }

    .ur-role-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ur-role-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .ur-role-name {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--ur-text);
    }

    .ur-role-count {
        font-size: 0.74rem;
        font-weight: 800;
        color: var(--ur-brown-primary);
        background: #fdf5e6;
        padding: 2px 8px;
        border-radius: 99px;
        border: 1px solid rgba(215, 166, 74, 0.3);
    }

    /* Quick Actions List */
    .ur-quick-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ur-quick-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--ur-brown-border);
        background: #fffdf9;
        text-decoration: none;
        color: var(--ur-text);
        font-size: 0.84rem;
        font-weight: 700;
        transition: all 0.2s;
    }

    .ur-quick-item:hover {
        background: var(--ur-brown-light);
        border-color: var(--ur-brown-primary);
        color: var(--ur-brown-primary);
        transform: translateX(3px);
    }

    .ur-quick-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Need Help Card */
    .ur-help-card {
        background: #fffdf9;
        border: 1.5px dashed var(--ur-brown-border);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .ur-help-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--ur-brown-light);
        color: var(--ur-brown-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ur-help-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--ur-text);
    }

    .ur-help-desc {
        font-size: 0.78rem;
        color: var(--ur-text-muted);
        line-height: 1.4;
    }

    /* Modals styling */
    .ur-modal-backdrop {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(53, 27, 13, 0.45);
        backdrop-filter: blur(3px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .ur-modal-backdrop.show {
        display: flex;
    }

    .ur-modal-box {
        background: #ffffff;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(53, 27, 13, 0.2);
        width: 100%;
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalSlideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .ur-modal-head {
        padding: 20px 24px;
        border-bottom: 1.5px solid var(--ur-brown-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ur-modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--ur-text);
    }

    .ur-modal-close {
        background: none;
        border: none;
        color: var(--ur-text-muted);
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .ur-modal-close:hover {
        background: var(--ur-brown-light);
        color: var(--ur-brown-primary);
    }

    .ur-modal-body {
        padding: 24px;
    }

    .ur-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .ur-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .ur-form-group.full-width {
        grid-column: 1 / -1;
    }

    .ur-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ur-text);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .ur-input, .ur-textarea, .ur-form-select {
        width: 100%;
        background: #fffdf9;
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.86rem;
        color: var(--ur-text);
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .ur-input:focus, .ur-textarea:focus, .ur-form-select:focus {
        border-color: var(--ur-brown-primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(68, 36, 15, 0.1);
    }

    .ur-checkbox-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 6px;
    }

    .ur-checkbox-wrap input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--ur-brown-primary);
        cursor: pointer;
    }

    .ur-checkbox-label {
        font-size: 0.84rem;
        color: var(--ur-text);
        font-weight: 600;
    }

    .ur-modal-foot {
        padding: 16px 24px;
        border-top: 1.5px solid var(--ur-brown-border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        background: #faf6f0;
    }

    /* Permissions Matrix Matrix */
    .ur-perm-matrix {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 12px;
    }

    .ur-perm-module-card {
        border: 1.5px solid var(--ur-brown-border);
        border-radius: 12px;
        padding: 14px 16px;
        background: #fffdf9;
    }

    .ur-perm-module-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.2);
    }

    .ur-perm-module-title {
        font-size: 0.88rem;
        font-weight: 800;
        color: var(--ur-brown-primary);
        text-transform: capitalize;
    }

    .ur-perm-checkboxes {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }

    .ur-perm-check-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ur-text);
    }

    /* Responsive Breakpoints */
    @media (max-width: 1200px) {
        .ur-main-layout {
            grid-template-columns: 1fr;
        }
        .ur-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .ur-stats-row {
            grid-template-columns: 1fr;
        }
        .ur-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div style="width: 100%; box-sizing: border-box;">
    
    <!-- 1. Page Header Bar -->
    <x-admin-page-header title="Users & Roles">
        <div class="ur-btn-split">
            <button type="button" class="ur-btn-primary" onclick="openAddUserModal()">
                <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i>
                <span>+ Add New User</span>
            </button>
            <button type="button" class="ur-btn-split-toggle" onclick="toggleHeaderSplitDropdown(event)">
                <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
            </button>
            <div class="ur-dropdown-menu" id="headerSplitDropdown">
                <button type="button" class="ur-dropdown-item" onclick="openAddUserModal()">
                    <i data-lucide="user-plus" style="width: 14px; height: 14px;"></i> Add New User
                </button>
                <button type="button" class="ur-dropdown-item" onclick="openAddRoleModal()">
                    <i data-lucide="shield-plus" style="width: 14px; height: 14px;"></i> Add New Role
                </button>
                <button type="button" class="ur-dropdown-item" onclick="openActivityLogModal()">
                    <i data-lucide="key" style="width: 14px; height: 14px;"></i> View Activity Logs
                </button>
            </div>
        </div>
    </x-admin-page-header>

    <!-- 2. Navigation Tabs -->
    <div class="ur-nav-tabs">
        <a href="{{ route('admin.users-roles', ['tab' => 'users']) }}" class="ur-tab-item {{ $activeTab === 'users' ? 'active' : '' }}">
            <i data-lucide="users" style="width: 18px; height: 18px;"></i>
            <span>Users</span>
        </a>
        <a href="{{ route('admin.users-roles', ['tab' => 'roles']) }}" class="ur-tab-item {{ $activeTab === 'roles' ? 'active' : '' }}">
            <i data-lucide="shield" style="width: 18px; height: 18px;"></i>
            <span>Roles</span>
        </a>
    </div>

    <!-- 3. Statistics Cards Row -->
    <div class="ur-stats-row">
        <!-- Card 1: Total Users -->
        <div class="ur-stat-card">
            <div class="ur-stat-content">
                <span class="ur-stat-label">Total Users</span>
                <span class="ur-stat-val">{{ number_format($totalUsersCount) }}</span>
                <span class="ur-stat-meta {{ $userGrowthPct >= 0 ? 'positive' : 'negative' }}">
                    <i data-lucide="{{ $userGrowthPct >= 0 ? 'trending-up' : 'trending-down' }}" style="width: 14px; height: 14px;"></i>
                    {{ $userGrowthPct >= 0 ? '+' : '' }}{{ $userGrowthPct }}% <span style="color: var(--ur-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="ur-stat-icon-wrap users">
                <i data-lucide="users" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 2: Active Users -->
        <div class="ur-stat-card">
            <div class="ur-stat-content">
                <span class="ur-stat-label">Active Users</span>
                <span class="ur-stat-val">{{ number_format($activeUsersCount) }}</span>
                <span class="ur-stat-meta {{ $activeGrowthPct >= 0 ? 'positive' : 'negative' }}">
                    <i data-lucide="{{ $activeGrowthPct >= 0 ? 'trending-up' : 'trending-down' }}" style="width: 14px; height: 14px;"></i>
                    {{ $activeGrowthPct >= 0 ? '+' : '' }}{{ $activeGrowthPct }}% <span style="color: var(--ur-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="ur-stat-icon-wrap active">
                <i data-lucide="user-check" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 3: Inactive Users -->
        <div class="ur-stat-card">
            <div class="ur-stat-content">
                <span class="ur-stat-label">Inactive Users</span>
                <span class="ur-stat-val">{{ number_format($inactiveUsersCount) }}</span>
                <span class="ur-stat-meta {{ $inactiveGrowthPct <= 0 ? 'negative' : 'positive' }}">
                    <i data-lucide="{{ $inactiveGrowthPct <= 0 ? 'trending-down' : 'trending-up' }}" style="width: 14px; height: 14px;"></i>
                    {{ $inactiveGrowthPct }}% <span style="color: var(--ur-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="ur-stat-icon-wrap inactive">
                <i data-lucide="user-x" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 4: Total Roles -->
        <div class="ur-stat-card">
            <div class="ur-stat-content">
                <span class="ur-stat-label">Total Roles</span>
                <span class="ur-stat-val">{{ number_format($totalRolesCount) }}</span>
                <span class="ur-stat-meta neutral">
                    <i data-lucide="minus" style="width: 14px; height: 14px;"></i>
                    No change <span style="color: var(--ur-text-muted); font-weight: 500;">this month</span>
                </span>
            </div>
            <div class="ur-stat-icon-wrap roles">
                <i data-lucide="shield-check" style="width: 22px; height: 22px;"></i>
            </div>
        </div>
    </div>

    <!-- 4. Two-Column Main Content Layout -->
    <div class="ur-main-layout">
        
        <!-- Left Column: Content Table -->
        <div class="ur-left-col">
            
            @if($activeTab === 'users')
                <!-- USERS TAB CONTENT -->
                <div class="ur-card">
                    
                    <!-- Filters Bar -->
                    <form action="{{ route('admin.users-roles') }}" method="GET" id="usersFilterForm" class="ur-filters-bar">
                        <input type="hidden" name="tab" value="users">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">

                        <div class="ur-filters-left">
                            <!-- Role Dropdown -->
                            <select name="role" class="ur-select" onchange="document.getElementById('usersFilterForm').submit()">
                                <option value="all" {{ $roleFilter === 'all' || !$roleFilter ? 'selected' : '' }}>All Roles</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}" {{ $roleFilter === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>

                            <!-- Status Dropdown -->
                            <select name="status" class="ur-select" onchange="document.getElementById('usersFilterForm').submit()">
                                <option value="all" {{ $statusFilter === 'all' || !$statusFilter ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $statusFilter === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>

                            <!-- Search Input -->
                            <div class="ur-search-wrap">
                                <input type="text" name="search" class="ur-search-input" placeholder="Search users by name, email or phone..." value="{{ $search }}">
                                <i data-lucide="search" class="ur-search-icon"></i>
                            </div>
                        </div>

                        <div class="ur-filters-right">
                            <button type="submit" class="ur-btn-outline">
                                <i data-lucide="sliders-horizontal" style="width: 14px; height: 14px;"></i>
                                <span>Filters</span>
                            </button>
                            <a href="{{ route('admin.users-roles.export', ['search' => $search, 'role' => $roleFilter, 'status' => $statusFilter]) }}" class="ur-btn-outline">
                                <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                                <span>Export</span>
                            </a>
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="ur-table-wrap">
                        <table class="ur-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                    @php
                                        $primaryRole = $u->roles->first()?->name ?? 'User';
                                        $roleSlug = Str::slug($primaryRole);
                                        $badgeClass = match($primaryRole) {
                                            'Super Admin' => 'ur-role-super-admin',
                                            'Administrator', 'Admin' => 'ur-role-administrator',
                                            'Manager' => 'ur-role-manager',
                                            'Editor' => 'ur-role-editor',
                                            'Customer Support' => 'ur-role-customer-support',
                                            'Sales Agent' => 'ur-role-sales-agent',
                                            'Inventory Manager' => 'ur-role-inventory-manager',
                                            default => 'ur-role-default'
                                        };
                                        $statusSlug = strtolower($u->status ?: 'active');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="ur-user-cell">
                                                @if($u->profile_image_url)
                                                    <img src="{{ $u->profile_image_url }}" alt="{{ $u->name }}" class="ur-avatar-img">
                                                @else
                                                    <div class="ur-avatar-initials">{{ $u->initials }}</div>
                                                @endif
                                                <div class="ur-user-info">
                                                    <span class="ur-user-name">{{ $u->name }}</span>
                                                    <span class="ur-user-username">{{ $u->username ?: Str::slug($u->name, '_') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="ur-role-badge {{ $badgeClass }}">{{ $primaryRole }}</span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 500; font-size: 0.85rem;">{{ $u->email }}</span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 500; font-size: 0.84rem; color: var(--ur-text-muted);">{{ $u->phone ?: '+92 300 0000000' }}</span>
                                        </td>
                                        <td>
                                            <span class="ur-status-dot ur-status-{{ $statusSlug }}">
                                                {{ ucfirst($statusSlug) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($u->last_login_at)
                                                <div style="display:flex; flex-direction:column; line-height:1.2;">
                                                    <span style="font-weight:600; font-size:0.83rem;">{{ $u->last_login_at->format('M d, Y') }}</span>
                                                    <span style="font-size:0.72rem; color:var(--ur-text-muted);">{{ $u->last_login_at->format('h:i A') }}</span>
                                                </div>
                                            @else
                                                <span style="color:var(--ur-text-muted); font-size:0.82rem;">Never</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="ur-action-btns" style="justify-content: flex-end;">
                                                <button type="button" class="ur-icon-btn" title="Edit User" onclick='openEditUserModal(@json($u), {{ $u->roles->first()?->id ?? 0 }})'>
                                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                                </button>
                                                
                                                <div class="ur-dropdown">
                                                    <button type="button" class="ur-icon-btn" onclick="toggleDropdown(event, 'userMenu_{{ $u->id }}')">
                                                        <i data-lucide="more-vertical" style="width: 14px; height: 14px;"></i>
                                                    </button>
                                                    <div class="ur-dropdown-menu" id="userMenu_{{ $u->id }}">
                                                        <button type="button" class="ur-dropdown-item" onclick='openEditUserModal(@json($u), {{ $u->roles->first()?->id ?? 0 }})'>
                                                            <i data-lucide="edit-3" style="width: 13px; height: 13px;"></i> Edit User
                                                        </button>
                                                        
                                                        <form method="POST" action="{{ route('admin.users-roles.user.status', $u->id) }}">
                                                            @csrf
                                                            @if($u->status === 'active')
                                                                <input type="hidden" name="status" value="inactive">
                                                                <button type="submit" class="ur-dropdown-item">
                                                                    <i data-lucide="user-x" style="width: 13px; height: 13px;"></i> Deactivate User
                                                                </button>
                                                            @else
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="ur-dropdown-item">
                                                                    <i data-lucide="user-check" style="width: 13px; height: 13px;"></i> Activate User
                                                                </button>
                                                            @endif
                                                        </form>

                                                        <form method="POST" action="{{ route('admin.users-roles.user.status', $u->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="status" value="suspended">
                                                            <button type="submit" class="ur-dropdown-item">
                                                                <i data-lucide="shield-alert" style="width: 13px; height: 13px;"></i> Suspend User
                                                            </button>
                                                        </form>

                                                        <button type="button" class="ur-dropdown-item" onclick='openResetPasswordModal({{ $u->id }}, "{{ $u->name }}")'>
                                                            <i data-lucide="key" style="width: 13px; height: 13px;"></i> Reset Password
                                                        </button>

                                                        <form method="POST" action="{{ route('admin.users-roles.user.destroy', $u->id) }}" onsubmit="return confirm('Are you sure you want to delete user {{ $u->name }}? This action cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="ur-dropdown-item danger">
                                                                <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i> Delete User
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--ur-text-muted);">
                                            <i data-lucide="users" style="width: 36px; height: 36px; opacity: 0.3; margin-bottom: 8px;"></i>
                                            <p>No admin users found matching criteria.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Footer -->
                    <div class="ur-pagination-bar">
                        <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
                        
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <!-- Rows per page -->
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span>Rows per page</span>
                                <select class="ur-select" style="padding: 4px 8px; font-size: 0.8rem;" onchange="changePerPage(this.value)">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            <!-- Links -->
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>

                </div>

            @else
                <!-- ROLES TAB CONTENT -->
                <div class="ur-card">
                    <div class="ur-filters-bar">
                        <span style="font-size: 1.05rem; font-weight: 800; color: var(--ur-text);">Roles Management</span>
                        <button type="button" class="ur-btn-primary" onclick="openAddRoleModal()">
                            <i data-lucide="shield-plus" style="width: 16px; height: 16px;"></i>
                            <span>+ Add Role</span>
                        </button>
                    </div>

                    <div class="ur-table-wrap">
                        <table class="ur-table">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Assigned Users</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $r)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div class="ur-role-icon" style="background: #fdf5e6; color: var(--ur-brown-primary);">
                                                    <i data-lucide="shield" style="width: 16px; height: 16px;"></i>
                                                </div>
                                                <strong style="font-size: 0.9rem;">{{ $r->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color: var(--ur-text-muted); font-size: 0.84rem;">
                                                {{ $r->description ?: 'Standard administrative role.' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="ur-role-count">{{ $r->users_count }} users</span>
                                        </td>
                                        <td>
                                            <span class="ur-status-dot ur-status-{{ strtolower($r->status ?: 'active') }}">
                                                {{ ucfirst($r->status ?: 'active') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="color: var(--ur-text-muted); font-size: 0.83rem;">
                                                {{ $r->created_at ? $r->created_at->format('M d, Y') : 'System Default' }}
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="ur-action-btns" style="justify-content: flex-end;">
                                                <button type="button" class="ur-icon-btn" title="Edit Role & Permissions" onclick='openEditRoleModal(@json($r))'>
                                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                                </button>

                                                @if(!in_array($r->name, ['Super Admin', 'Admin']))
                                                    <form method="POST" action="{{ route('admin.users-roles.role.destroy', $r->id) }}" onsubmit="return confirm('Are you sure you want to delete the role {{ $r->name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="ur-icon-btn" title="Delete Role" style="color: #ef4444;">
                                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--ur-text-muted);">
                                            No roles found in system.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Column: Sidebar Information Cards -->
        <div class="ur-right-col">
            
            <!-- Card 1: Roles Overview -->
            <div class="ur-side-card">
                <div class="ur-side-card-head">
                    <span class="ur-side-card-title">Roles Overview</span>
                    <a href="javascript:void(0)" onclick="openAddRoleModal()" class="ur-side-add-link">
                        <i data-lucide="plus" style="width: 14px; height: 14px;"></i> Add Role
                    </a>
                </div>

                <div class="ur-roles-list">
                    @foreach($rolesOverview as $ro)
                        @php
                            $iconColor = match($ro->name) {
                                'Super Admin' => '#44240f',
                                'Administrator', 'Admin' => '#0369a1',
                                'Manager' => '#7e22ce',
                                'Editor' => '#b45309',
                                'Customer Support' => '#0f766e',
                                'Sales Agent' => '#be123c',
                                'Inventory Manager' => '#15803d',
                                default => '#374151'
                            };
                        @endphp
                        <div class="ur-role-item">
                            <div class="ur-role-left">
                                <div class="ur-role-icon" style="background: rgba(215,166,74,0.12); color: {{ $iconColor }};">
                                    <i data-lucide="shield" style="width: 15px; height: 15px;"></i>
                                </div>
                                <span class="ur-role-name">{{ $ro->name }}</span>
                            </div>
                            <span class="ur-role-count">{{ $ro->users_count }}</span>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 16px; padding-top: 14px; border-top: 1.5px solid var(--ur-brown-border); display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 0.82rem; font-weight: 700; color: var(--ur-text-muted);">Total Roles</span>
                    <strong style="font-size: 0.95rem; color: var(--ur-text);">{{ count($rolesOverview) }}</strong>
                </div>
            </div>

            <!-- Card 2: Quick Actions -->
            <div class="ur-side-card">
                <div class="ur-side-card-head">
                    <span class="ur-side-card-title">Quick Actions</span>
                </div>
                <div class="ur-quick-list">
                    <a href="javascript:void(0)" onclick="openAddUserModal()" class="ur-quick-item">
                        <div class="ur-quick-left">
                            <i data-lucide="user-plus" style="width: 16px; height: 16px; color: var(--ur-brown-primary);"></i>
                            <span>Add New User</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--ur-text-muted);"></i>
                    </a>

                    <a href="javascript:void(0)" onclick="openAddRoleModal()" class="ur-quick-item">
                        <div class="ur-quick-left">
                            <i data-lucide="shield-plus" style="width: 16px; height: 16px; color: var(--ur-brown-primary);"></i>
                            <span>Add New Role</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--ur-text-muted);"></i>
                    </a>

                    <a href="javascript:void(0)" onclick="openAddRoleModal()" class="ur-quick-item">
                        <div class="ur-quick-left">
                            <i data-lucide="lock" style="width: 16px; height: 16px; color: var(--ur-brown-primary);"></i>
                            <span>Permission Manager</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--ur-text-muted);"></i>
                    </a>

                    <a href="{{ route('admin.users-roles', ['tab' => 'roles']) }}" class="ur-quick-item">
                        <div class="ur-quick-left">
                            <i data-lucide="shield-check" style="width: 16px; height: 16px; color: var(--ur-brown-primary);"></i>
                            <span>Role Permissions</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--ur-text-muted);"></i>
                    </a>

                    <a href="javascript:void(0)" onclick="openActivityLogModal()" class="ur-quick-item">
                        <div class="ur-quick-left">
                            <i data-lucide="file-text" style="width: 16px; height: 16px; color: var(--ur-brown-primary);"></i>
                            <span>Activity Log</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--ur-text-muted);"></i>
                    </a>
                </div>
            </div>

            <!-- Card 3: Need Help? -->
            <div class="ur-help-card">
                <div class="ur-help-icon">
                    <i data-lucide="help-circle" style="width: 22px; height: 22px;"></i>
                </div>
                <span class="ur-help-title">Need Help?</span>
                <p class="ur-help-desc">Control user access and roles to keep your store secure.</p>
                <a href="#" class="ur-btn-outline" style="width: 100%; justify-content: center;">View Documentation</a>
            </div>

        </div>

    </div>
</div>

<!-- ==========================================
     MODALS SECTION
========================================== -->

<!-- 1. Add New User Modal -->
<div class="ur-modal-backdrop" id="addUserModal">
    <div class="ur-modal-box">
        <form method="POST" action="{{ route('admin.users-roles.user.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="ur-modal-head">
                <span class="ur-modal-title">Add New User</span>
                <button type="button" class="ur-modal-close" onclick="closeModal('addUserModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="ur-modal-body">
                <div class="ur-form-grid">
                    <div class="ur-form-group">
                        <label class="ur-label">Full Name *</label>
                        <input type="text" name="name" class="ur-input" required placeholder="e.g. M. Abdullah">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Username</label>
                        <input type="text" name="username" class="ur-input" placeholder="e.g. abdullah">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Email Address *</label>
                        <input type="email" name="email" class="ur-input" required placeholder="abdullah@ghousiatraders.com">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Phone Number</label>
                        <input type="text" name="phone" class="ur-input" placeholder="+92 301 2345678">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Password *</label>
                        <input type="password" name="password" class="ur-input" required placeholder="••••••••">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="ur-input" required placeholder="••••••••">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Assigned Role *</label>
                        <select name="role_id" class="ur-form-select" required>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Account Status *</label>
                        <select name="status" class="ur-form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="ur-form-group full-width">
                        <label class="ur-label">Profile Image</label>
                        <input type="file" name="profile_image" class="ur-input" accept="image/*">
                    </div>
                    <div class="ur-form-group full-width">
                        <div class="ur-checkbox-wrap">
                            <input type="checkbox" name="require_password_change" id="reqPassCheck" value="1">
                            <label for="reqPassCheck" class="ur-checkbox-label">Require password change on first login</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ur-modal-foot">
                <button type="button" class="ur-btn-outline" onclick="closeModal('addUserModal')">Cancel</button>
                <button type="submit" class="ur-btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Edit User Modal -->
<div class="ur-modal-backdrop" id="editUserModal">
    <div class="ur-modal-box">
        <form method="POST" id="editUserForm" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="ur-modal-head">
                <span class="ur-modal-title">Edit User</span>
                <button type="button" class="ur-modal-close" onclick="closeModal('editUserModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="ur-modal-body">
                <div class="ur-form-grid">
                    <div class="ur-form-group">
                        <label class="ur-label">Full Name *</label>
                        <input type="text" name="name" id="edit_name" class="ur-input" required>
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Username</label>
                        <input type="text" name="username" id="edit_username" class="ur-input">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Email Address *</label>
                        <input type="email" name="email" id="edit_email" class="ur-input" required>
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Phone Number</label>
                        <input type="text" name="phone" id="edit_phone" class="ur-input">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">New Password (optional)</label>
                        <input type="password" name="password" class="ur-input" placeholder="Leave blank to keep unchanged">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="ur-input" placeholder="Confirm new password">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Assigned Role *</label>
                        <select name="role_id" id="edit_role_id" class="ur-form-select" required>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Account Status *</label>
                        <select name="status" id="edit_status" class="ur-form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="ur-form-group full-width">
                        <label class="ur-label">Replace Profile Image</label>
                        <input type="file" name="profile_image" class="ur-input" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="ur-modal-foot">
                <button type="button" class="ur-btn-outline" onclick="closeModal('editUserModal')">Cancel</button>
                <button type="submit" class="ur-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Reset Password Modal -->
<div class="ur-modal-backdrop" id="resetPasswordModal">
    <div class="ur-modal-box" style="max-width: 480px;">
        <form method="POST" id="resetPasswordForm" action="">
            @csrf
            <div class="ur-modal-head">
                <span class="ur-modal-title">Reset Password</span>
                <button type="button" class="ur-modal-close" onclick="closeModal('resetPasswordModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="ur-modal-body">
                <p style="font-size: 0.86rem; color: var(--ur-text-muted); margin-bottom: 16px;">
                    Resetting password for <strong id="resetUserName" style="color: var(--ur-text);">User</strong>.
                </p>
                <div class="ur-form-group" style="margin-bottom: 12px;">
                    <label class="ur-label">New Password *</label>
                    <input type="password" name="password" class="ur-input" required minlength="6" placeholder="Enter new password">
                </div>
                <div class="ur-form-group">
                    <label class="ur-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="ur-input" required minlength="6" placeholder="Confirm new password">
                </div>
            </div>
            <div class="ur-modal-foot">
                <button type="button" class="ur-btn-outline" onclick="closeModal('resetPasswordModal')">Cancel</button>
                <button type="submit" class="ur-btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Add / Edit Role Modal with Permissions Matrix -->
<div class="ur-modal-backdrop" id="roleModal">
    <div class="ur-modal-box" style="max-width: 750px;">
        <form method="POST" id="roleForm" action="{{ route('admin.users-roles.role.store') }}">
            @csrf
            <input type="hidden" name="_method" id="roleFormMethod" value="POST">
            <div class="ur-modal-head">
                <span class="ur-modal-title" id="roleModalTitle">Add New Role</span>
                <button type="button" class="ur-modal-close" onclick="closeModal('roleModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="ur-modal-body">
                <div class="ur-form-grid">
                    <div class="ur-form-group">
                        <label class="ur-label">Role Name *</label>
                        <input type="text" name="name" id="role_name" class="ur-input" required placeholder="e.g. Sales Manager">
                    </div>
                    <div class="ur-form-group">
                        <label class="ur-label">Status *</label>
                        <select name="status" id="role_status" class="ur-form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="ur-form-group full-width">
                        <label class="ur-label">Description</label>
                        <textarea name="description" id="role_description" class="ur-textarea" rows="2" placeholder="Brief explanation of responsibilities..."></textarea>
                    </div>
                </div>

                <!-- Permissions Matrix -->
                <div style="margin-top: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <span class="ur-label" style="font-size: 0.88rem;">Module Permissions Matrix</span>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="ur-btn-outline" style="padding: 4px 10px; font-size: 0.76rem;" onclick="selectAllPermissions(true)">Select All</button>
                            <button type="button" class="ur-btn-outline" style="padding: 4px 10px; font-size: 0.76rem;" onclick="selectAllPermissions(false)">Clear All</button>
                        </div>
                    </div>

                    <div class="ur-perm-matrix">
                        @foreach($permissionModules as $mod => $actions)
                            <div class="ur-perm-module-card">
                                <div class="ur-perm-module-head">
                                    <span class="ur-perm-module-title">{{ str_replace('_', ' ', $mod) }}</span>
                                </div>
                                <div class="ur-perm-checkboxes">
                                    @foreach($actions as $act)
                                        @php $permKey = "{$mod}.{$act}"; @endphp
                                        <label class="ur-perm-check-item">
                                            <input type="checkbox" name="permissions[]" value="{{ $permKey }}" class="perm-checkbox">
                                            <span>{{ ucfirst($act) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="ur-modal-foot">
                <button type="button" class="ur-btn-outline" onclick="closeModal('roleModal')">Cancel</button>
                <button type="submit" class="ur-btn-primary">Save Role</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Activity Log Modal -->
<div class="ur-modal-backdrop" id="activityLogModal">
    <div class="ur-modal-box" style="max-width: 750px;">
        <div class="ur-modal-head">
            <span class="ur-modal-title">Security Activity Log</span>
            <button type="button" class="ur-modal-close" onclick="closeModal('activityLogModal')">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>
        <div class="ur-modal-body" style="padding: 0;">
            <div class="ur-table-wrap">
                <table class="ur-table">
                    <thead>
                        <tr>
                            <th>Administrator</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>IP Address</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs as $log)
                            <tr>
                                <td><strong>{{ $log->user?->name ?? 'System' }}</strong></td>
                                <td><span class="ur-role-badge ur-role-administrator">{{ $log->action }}</span></td>
                                <td><span style="font-size: 0.82rem; color: var(--ur-text-muted);">{{ $log->details }}</span></td>
                                <td><code style="font-size: 0.78rem;">{{ $log->ip_address }}</code></td>
                                <td><span style="font-size: 0.78rem;">{{ $log->created_at?->format('M d, Y h:i A') }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: var(--ur-text-muted);">
                                    No activity logs recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ur-modal-foot">
            <button type="button" class="ur-btn-outline" onclick="closeModal('activityLogModal')">Close</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });

    function toggleDropdown(e, menuId) {
        e.stopPropagation();
        document.querySelectorAll('.ur-dropdown-menu').forEach(m => {
            if (m.id !== menuId) m.classList.remove('show');
        });
        const target = document.getElementById(menuId);
        if (target) target.classList.toggle('show');
    }

    function toggleHeaderSplitDropdown(e) {
        toggleDropdown(e, 'headerSplitDropdown');
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.ur-dropdown-menu').forEach(m => m.classList.remove('show'));
    });

    function openModal(modalId) {
        const el = document.getElementById(modalId);
        if (el) el.classList.add('show');
    }

    function closeModal(modalId) {
        const el = document.getElementById(modalId);
        if (el) el.classList.remove('show');
    }

    function openAddUserModal() {
        openModal('addUserModal');
    }

    function openEditUserModal(user, roleId) {
        document.getElementById('edit_name').value = user.name || '';
        document.getElementById('edit_username').value = user.username || '';
        document.getElementById('edit_email').value = user.email || '';
        document.getElementById('edit_phone').value = user.phone || '';
        document.getElementById('edit_role_id').value = roleId || 0;
        document.getElementById('edit_status').value = user.status || 'active';

        const form = document.getElementById('editUserForm');
        form.action = `/admin/users-roles/user/${user.id}`;
        openModal('editUserModal');
    }

    function openResetPasswordModal(userId, userName) {
        document.getElementById('resetUserName').innerText = userName;
        const form = document.getElementById('resetPasswordForm');
        form.action = `/admin/users-roles/user/${userId}/reset-password`;
        openModal('resetPasswordModal');
    }

    function openAddRoleModal() {
        document.getElementById('roleModalTitle').innerText = 'Add New Role';
        document.getElementById('roleFormMethod').value = 'POST';
        document.getElementById('role_name').value = '';
        document.getElementById('role_description').value = '';
        document.getElementById('role_status').value = 'active';
        selectAllPermissions(false);

        const form = document.getElementById('roleForm');
        form.action = `{{ route('admin.users-roles.role.store') }}`;
        openModal('roleModal');
    }

    function openEditRoleModal(role) {
        document.getElementById('roleModalTitle').innerText = 'Edit Role & Permissions';
        document.getElementById('roleFormMethod').value = 'PUT';
        document.getElementById('role_name').value = role.name || '';
        document.getElementById('role_description').value = role.description || '';
        document.getElementById('role_status').value = role.status || 'active';

        const form = document.getElementById('roleForm');
        form.action = `/admin/users-roles/role/${role.id}`;
        openModal('roleModal');
    }

    function openActivityLogModal() {
        openModal('activityLogModal');
    }

    function selectAllPermissions(checked) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
    }

    function changePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        window.location.href = url.toString();
    }
</script>
@endpush
@endsection
