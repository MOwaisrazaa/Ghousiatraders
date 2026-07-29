@extends('admin.ghousia-layout')

@section('title', 'Support & Tickets')

@push('head')
<style>
    /* Theme Tokens for Support & Tickets Page */
    :root {
        --st-brown-primary: #44240f;
        --st-brown-hover: #351b0d;
        --st-brown-light: #fff3df;
        --st-brown-border: rgba(215, 166, 74, 0.25);
        --st-card-bg: #ffffff;
        --st-bg: #fffcf8;
        --st-text: #351b0d;
        --st-text-muted: #8a7355;
    }

    /* Main Desktop 2-Column Grid */
    .st-main-layout {
        display: grid;
        grid-template-columns: minmax(0, 76%) minmax(0, 24%);
        gap: 24px;
        width: 100%;
        box-sizing: border-box;
        align-items: start;
    }

    .st-main-layout > * {
        min-width: 0;
        box-sizing: border-box;
    }

    /* Page Header Bar */
    .st-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .st-page-title-wrap h1 {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--st-text);
        letter-spacing: -0.02em;
        margin: 0 0 4px 0;
    }

    .st-breadcrumb {
        font-size: 0.8rem;
        color: var(--st-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    .st-breadcrumb a {
        color: var(--st-text-muted);
        text-decoration: none;
    }

    .st-breadcrumb a:hover {
        color: var(--st-brown-primary);
    }

    /* Split Button */
    .st-btn-split {
        display: inline-flex;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(68, 36, 15, 0.15);
        position: relative;
    }

    .st-btn-primary {
        background: var(--st-brown-primary);
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

    .st-btn-primary:hover {
        background: var(--st-brown-hover);
    }

    .st-btn-split-toggle {
        background: #351b0d;
        color: #ffffff;
        border: none;
        padding: 10px 12px;
        cursor: pointer;
        border-left: 1px solid rgba(255, 255, 255, 0.15);
        transition: background 0.2s;
    }

    .st-btn-split-toggle:hover {
        background: #241107;
    }

    /* 5 Statistics Cards Row */
    .st-stats-row {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .st-stat-card {
        background: #ffffff;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 16px rgba(53, 27, 13, 0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .st-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(53, 27, 13, 0.06);
    }

    .st-stat-content {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .st-stat-label {
        font-size: 0.74rem;
        font-weight: 700;
        color: var(--st-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .st-stat-val {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--st-text);
        line-height: 1.1;
    }

    .st-stat-meta {
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .st-stat-meta.positive { color: #10b981; }
    .st-stat-meta.negative { color: #ef4444; }

    .st-stat-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .st-stat-icon-wrap.total { background: #fff5eb; color: var(--st-brown-primary); }
    .st-stat-icon-wrap.open { background: #ecfdf5; color: #10b981; }
    .st-stat-icon-wrap.pending { background: #fef3c7; color: #f59e0b; }
    .st-stat-icon-wrap.resolved { background: #f3e8ff; color: #8b5cf6; }
    .st-stat-icon-wrap.satisfaction { background: #ffe4e6; color: #be123c; }

    /* Main Container Card */
    .st-card {
        background: #ffffff;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(53, 27, 13, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }

    /* Filters Bar */
    .st-filters-bar {
        padding: 16px 20px;
        border-bottom: 1.5px solid var(--st-brown-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        background: #ffffff;
    }

    .st-filters-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 280px;
        flex-wrap: wrap;
    }

    .st-select {
        background: #fffdf9;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--st-text);
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .st-select:focus {
        border-color: var(--st-brown-primary);
        box-shadow: 0 0 0 3px rgba(68, 36, 15, 0.1);
    }

    .st-search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .st-search-input {
        width: 100%;
        background: #fffdf9;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 10px;
        padding: 8px 36px 8px 12px;
        font-size: 0.84rem;
        color: var(--st-text);
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .st-search-input:focus {
        border-color: var(--st-brown-primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(68, 36, 15, 0.1);
    }

    .st-search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--st-text-muted);
        pointer-events: none;
        width: 16px;
        height: 16px;
    }

    .st-btn-outline {
        background: #ffffff;
        border: 1.5px solid var(--st-brown-border);
        color: var(--st-text);
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.83rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .st-btn-outline:hover {
        background: var(--st-brown-light);
        border-color: var(--st-brown-primary);
        color: var(--st-brown-primary);
    }

    /* Status Navigation Tabs */
    .st-status-tabs {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 0 20px;
        border-bottom: 1.5px solid var(--st-brown-border);
        background: #fffdf9;
        overflow-x: auto;
    }

    .st-status-tab {
        padding: 12px 4px;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--st-text-muted);
        text-decoration: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -1.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .st-status-tab:hover {
        color: var(--st-brown-primary);
    }

    .st-status-tab.active {
        color: var(--st-brown-primary);
        border-bottom-color: var(--st-brown-primary);
    }

    /* Table Styles */
    .st-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .st-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.86rem;
    }

    .st-table th {
        background: #faf6f0;
        color: var(--st-text-muted);
        font-size: 0.73rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 13px 16px;
        border-bottom: 1.5px solid var(--st-brown-border);
        white-space: nowrap;
    }

    .st-table td {
        padding: 13px 16px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.15);
        vertical-align: middle;
        color: var(--st-text);
    }

    .st-table tbody tr {
        transition: background 0.15s;
    }

    .st-table tbody tr:hover {
        background: #fffdf7;
    }

    /* Ticket ID */
    .st-tkt-id {
        font-weight: 800;
        color: var(--st-text);
        text-decoration: none;
        font-size: 0.86rem;
        cursor: pointer;
    }

    .st-tkt-id:hover {
        color: var(--st-brown-primary);
        text-decoration: underline;
    }

    /* Customer Cell */
    .st-cust-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .st-avatar-initials {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #fdf5e6;
        color: var(--st-brown-primary);
        font-weight: 800;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--st-brown-border);
        flex-shrink: 0;
    }

    .st-cust-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .st-cust-name {
        font-weight: 700;
        color: var(--st-text);
        font-size: 0.85rem;
    }

    .st-cust-email {
        font-size: 0.73rem;
        color: var(--st-text-muted);
    }

    /* Subject Cell */
    .st-subject-wrap {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
        max-width: 260px;
    }

    .st-subject-title {
        font-weight: 700;
        color: var(--st-text);
        font-size: 0.86rem;
    }

    .st-subject-preview {
        font-size: 0.74rem;
        color: var(--st-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Department Badges */
    .st-dept-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.73rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .st-dept-orders { background: #e0f2fe; color: #0369a1; }
    .st-dept-returns { background: #f3e8ff; color: #7e22ce; }
    .st-dept-shipping { background: #e0f7fa; color: #00838f; }
    .st-dept-payments { background: #dcfce7; color: #15803d; }
    .st-dept-products { background: #fef3c7; color: #b45309; }
    .st-dept-support { background: #ffe4e6; color: #be123c; }
    .st-dept-account { background: #f3f4f6; color: #374151; }

    /* Priority Badges */
    .st-priority-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.73rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .st-prio-low { background: #dcfce7; color: #15803d; }
    .st-prio-medium { background: #fef3c7; color: #b45309; }
    .st-prio-high { background: #fee2e2; color: #dc2626; }
    .st-prio-urgent { background: #fca5a5; color: #7f1d1d; }

    /* Status Badges */
    .st-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.73rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .st-status-open { background: #dcfce7; color: #15803d; }
    .st-status-pending { background: #fef3c7; color: #b45309; }
    .st-status-resolved { background: #e0f2fe; color: #0369a1; }
    .st-status-closed { background: #f3f4f6; color: #4b5563; }

    /* Action Buttons */
    .st-action-btns {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .st-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--st-brown-border);
        background: #ffffff;
        color: var(--st-text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .st-icon-btn:hover {
        background: var(--st-brown-light);
        color: var(--st-brown-primary);
        border-color: var(--st-brown-primary);
    }

    /* Dropdown menu */
    .st-dropdown {
        position: relative;
    }

    .st-dropdown-menu {
        position: absolute;
        right: 0;
        top: 36px;
        background: #ffffff;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(53, 27, 13, 0.12);
        width: 190px;
        z-index: 120;
        display: none;
        flex-direction: column;
        padding: 6px;
    }

    .st-dropdown-menu.show {
        display: flex;
    }

    .st-dropdown-item {
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--st-text);
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

    .st-dropdown-item:hover {
        background: var(--st-brown-light);
        color: var(--st-brown-primary);
    }

    .st-dropdown-item.danger {
        color: #ef4444;
    }

    .st-dropdown-item.danger:hover {
        background: #fef2f2;
    }

    /* Footer Pagination */
    .st-pagination-bar {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1.5px solid var(--st-brown-border);
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
        font-size: 0.83rem;
        color: var(--st-text-muted);
    }

    /* Right Sidebar Cards */
    .st-right-col {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .st-side-card {
        background: #ffffff;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(53, 27, 13, 0.02);
    }

    .st-side-card-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--st-text);
        margin-bottom: 16px;
    }

    /* Donut Chart Component */
    .st-donut-wrap {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .st-donut-chart {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: conic-gradient(
            #10b981 0% {{ $chartStatusData['open']['pct'] }}%,
            #f59e0b {{ $chartStatusData['open']['pct'] }}% {{ $chartStatusData['open']['pct'] + $chartStatusData['pending']['pct'] }}%,
            #3b82f6 {{ $chartStatusData['open']['pct'] + $chartStatusData['pending']['pct'] }}% {{ $chartStatusData['open']['pct'] + $chartStatusData['pending']['pct'] + $chartStatusData['resolved']['pct'] }}%,
            #9ca3af {{ $chartStatusData['open']['pct'] + $chartStatusData['pending']['pct'] + $chartStatusData['resolved']['pct'] }}% 100%
        );
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .st-donut-hole {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1.1;
    }

    .st-donut-hole small {
        font-size: 0.65rem;
        color: var(--st-text-muted);
        text-transform: uppercase;
        font-weight: 700;
    }

    .st-donut-hole strong {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--st-text);
    }

    .st-donut-legend {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    .st-legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .st-legend-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .st-legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* Department List */
    .st-dept-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .st-dept-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.84rem;
    }

    .st-dept-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .st-dept-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    /* Quick Actions */
    .st-quick-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .st-quick-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--st-brown-border);
        background: #fffdf9;
        text-decoration: none;
        color: var(--st-text);
        font-size: 0.84rem;
        font-weight: 700;
        transition: all 0.2s;
    }

    .st-quick-item:hover {
        background: var(--st-brown-light);
        border-color: var(--st-brown-primary);
        color: var(--st-brown-primary);
        transform: translateX(3px);
    }

    /* Recent Activity */
    .st-activity-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .st-act-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.8rem;
    }

    .st-act-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--st-brown-light);
        color: var(--st-brown-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .st-act-content {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .st-act-desc {
        color: var(--st-text);
        font-weight: 600;
    }

    .st-act-time {
        font-size: 0.72rem;
        color: var(--st-text-muted);
    }

    /* Modals */
    .st-modal-backdrop {
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

    .st-modal-backdrop.show {
        display: flex;
    }

    .st-modal-box {
        background: #ffffff;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(53, 27, 13, 0.2);
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalSlideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .st-modal-head {
        padding: 18px 24px;
        border-bottom: 1.5px solid var(--st-brown-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .st-modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--st-text);
    }

    .st-modal-close {
        background: none;
        border: none;
        color: var(--st-text-muted);
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .st-modal-body {
        padding: 24px;
    }

    .st-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .st-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .st-form-group.full-width {
        grid-column: 1 / -1;
    }

    .st-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--st-text);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .st-input, .st-textarea, .st-form-select {
        width: 100%;
        background: #fffdf9;
        border: 1.5px solid var(--st-brown-border);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.86rem;
        color: var(--st-text);
        outline: none;
        box-sizing: border-box;
    }

    .st-modal-foot {
        padding: 16px 24px;
        border-top: 1.5px solid var(--st-brown-border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        background: #faf6f0;
    }

    /* Conversation Message Thread */
    .st-msg-thread {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 20px;
        max-height: 380px;
        overflow-y: auto;
        padding-right: 8px;
    }

    .st-msg-bubble {
        padding: 14px 16px;
        border-radius: 14px;
        max-width: 85%;
        font-size: 0.86rem;
        line-height: 1.45;
        position: relative;
    }

    .st-msg-bubble.customer {
        background: #fdf5e6;
        border: 1.5px solid var(--st-brown-border);
        align-self: flex-start;
    }

    .st-msg-bubble.admin {
        background: #e0f2fe;
        border: 1.5px solid rgba(3, 105, 161, 0.25);
        align-self: flex-end;
    }

    .st-msg-bubble.internal {
        background: #fef3c7;
        border: 1.5px dashed rgba(180, 83, 9, 0.4);
        align-self: center;
        width: 100%;
        max-width: 100%;
    }

    .st-msg-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 1280px) {
        .st-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1100px) {
        .st-main-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .st-stats-row {
            grid-template-columns: 1fr;
        }
        .st-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div style="width: 100%; box-sizing: border-box;">
    
    <!-- 1. Page Header Bar -->
    <div class="st-page-header">
        <div class="st-page-title-wrap">
            <h1>Support & Tickets</h1>
            <div class="st-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
                <span>Support & Tickets</span>
            </div>
        </div>

        <!-- Split Button + New Ticket -->
        <div class="st-btn-split">
            <button type="button" class="st-btn-primary" onclick="openNewTicketModal()">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                <span>+ New Ticket</span>
            </button>
            <button type="button" class="st-btn-split-toggle" onclick="toggleHeaderSplitDropdown(event)">
                <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
            </button>
            <div class="st-dropdown-menu" id="headerSplitDropdown">
                <button type="button" class="st-dropdown-item" onclick="openNewTicketModal()">
                    <i data-lucide="plus-circle" style="width: 14px; height: 14px;"></i> Create New Ticket
                </button>
                <button type="button" class="st-dropdown-item" onclick="openDeptModal()">
                    <i data-lucide="layers" style="width: 14px; height: 14px;"></i> Manage Departments
                </button>
                <button type="button" class="st-dropdown-item" onclick="openCannedModal()">
                    <i data-lucide="message-square" style="width: 14px; height: 14px;"></i> Canned Responses
                </button>
                <button type="button" class="st-dropdown-item" onclick="openKbModal()">
                    <i data-lucide="book-open" style="width: 14px; height: 14px;"></i> View Knowledge Base
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Five Statistics Cards Row -->
    <div class="st-stats-row">
        <!-- Card 1: Total Tickets -->
        <div class="st-stat-card">
            <div class="st-stat-content">
                <span class="st-stat-label">Total Tickets</span>
                <span class="st-stat-val">{{ number_format($totalTicketsCount) }}</span>
                <span class="st-stat-meta positive">
                    <i data-lucide="trending-up" style="width: 13px; height: 13px;"></i>
                    +{{ $totalGrowthPct }}% <span style="color: var(--st-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="st-stat-icon-wrap total">
                <i data-lucide="ticket" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 2: Open Tickets -->
        <div class="st-stat-card">
            <div class="st-stat-content">
                <span class="st-stat-label">Open Tickets</span>
                <span class="st-stat-val">{{ number_format($openTicketsCount) }}</span>
                <span class="st-stat-meta positive">
                    <i data-lucide="trending-up" style="width: 13px; height: 13px;"></i>
                    +{{ $openGrowthPct }}% <span style="color: var(--st-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="st-stat-icon-wrap open">
                <i data-lucide="message-square" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 3: Pending Tickets -->
        <div class="st-stat-card">
            <div class="st-stat-content">
                <span class="st-stat-label">Pending Tickets</span>
                <span class="st-stat-val">{{ number_format($pendingTicketsCount) }}</span>
                <span class="st-stat-meta negative">
                    <i data-lucide="trending-down" style="width: 13px; height: 13px;"></i>
                    {{ $pendingGrowthPct }}% <span style="color: var(--st-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="st-stat-icon-wrap pending">
                <i data-lucide="clock" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 4: Resolved Tickets -->
        <div class="st-stat-card">
            <div class="st-stat-content">
                <span class="st-stat-label">Resolved Tickets</span>
                <span class="st-stat-val">{{ number_format($resolvedTicketsCount) }}</span>
                <span class="st-stat-meta positive">
                    <i data-lucide="trending-up" style="width: 13px; height: 13px;"></i>
                    +{{ $resolvedGrowthPct }}% <span style="color: var(--st-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="st-stat-icon-wrap resolved">
                <i data-lucide="check-circle" style="width: 22px; height: 22px;"></i>
            </div>
        </div>

        <!-- Card 5: Satisfaction Rate -->
        <div class="st-stat-card">
            <div class="st-stat-content">
                <span class="st-stat-label">Satisfaction Rate</span>
                <span class="st-stat-val">{{ $satisfactionRate }}%</span>
                <span class="st-stat-meta positive">
                    <i data-lucide="trending-up" style="width: 13px; height: 13px;"></i>
                    +{{ $satisfactionGrowthPct }}% <span style="color: var(--st-text-muted); font-weight: 500;">vs last month</span>
                </span>
            </div>
            <div class="st-stat-icon-wrap satisfaction">
                <i data-lucide="star" style="width: 22px; height: 22px;"></i>
            </div>
        </div>
    </div>

    <!-- 3. Two-Column Desktop Layout -->
    <div class="st-main-layout">
        
        <!-- Left Column: Tickets Card & Table -->
        <div class="st-left-col">
            
            <div class="st-card">
                
                <!-- Filters Bar -->
                <form action="{{ route('admin.support-tickets') }}" method="GET" id="ticketFilterForm" class="st-filters-bar">
                    <input type="hidden" name="status_tab" value="{{ $statusTab }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                    <div class="st-filters-left">
                        <!-- Status Dropdown -->
                        <select name="status" class="st-select" onchange="document.getElementById('ticketFilterForm').submit()">
                            <option value="all" {{ $statusFilter === 'all' || !$statusFilter ? 'selected' : '' }}>All Status</option>
                            <option value="open" {{ $statusFilter === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ $statusFilter === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $statusFilter === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>

                        <!-- Priority Dropdown -->
                        <select name="priority" class="st-select" onchange="document.getElementById('ticketFilterForm').submit()">
                            <option value="all" {{ $priorityFilter === 'all' || !$priorityFilter ? 'selected' : '' }}>All Priorities</option>
                            <option value="low" {{ $priorityFilter === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $priorityFilter === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $priorityFilter === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $priorityFilter === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>

                        <!-- Department Dropdown -->
                        <select name="department" class="st-select" onchange="document.getElementById('ticketFilterForm').submit()">
                            <option value="all" {{ $departmentFilter === 'all' || !$departmentFilter ? 'selected' : '' }}>All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->code }}" {{ $departmentFilter === $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>

                        <!-- Search Field -->
                        <div class="st-search-wrap">
                            <input type="text" name="search" class="st-search-input" placeholder="Search tickets..." value="{{ $search }}">
                            <i data-lucide="search" class="st-search-icon"></i>
                        </div>
                    </div>

                    <div class="st-filters-right">
                        <button type="submit" class="st-btn-outline">
                            <i data-lucide="sliders-horizontal" style="width: 14px; height: 14px;"></i>
                            <span>Filters</span>
                        </button>
                        <a href="{{ route('admin.support-tickets.export', ['search' => $search, 'status' => $statusFilter, 'priority' => $priorityFilter, 'department' => $departmentFilter]) }}" class="st-btn-outline">
                            <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                            <span>Export</span>
                        </a>
                    </div>
                </form>

                <!-- Status Tabs -->
                <div class="st-status-tabs">
                    <a href="{{ route('admin.support-tickets', ['status_tab' => 'all']) }}" class="st-status-tab {{ $statusTab === 'all' ? 'active' : '' }}">
                        All Tickets ({{ $totalTicketsCount }})
                    </a>
                    <a href="{{ route('admin.support-tickets', ['status_tab' => 'open']) }}" class="st-status-tab {{ $statusTab === 'open' ? 'active' : '' }}">
                        Open ({{ $openTicketsCount }})
                    </a>
                    <a href="{{ route('admin.support-tickets', ['status_tab' => 'pending']) }}" class="st-status-tab {{ $statusTab === 'pending' ? 'active' : '' }}">
                        Pending ({{ $pendingTicketsCount }})
                    </a>
                    <a href="{{ route('admin.support-tickets', ['status_tab' => 'resolved']) }}" class="st-status-tab {{ $statusTab === 'resolved' ? 'active' : '' }}">
                        Resolved ({{ $resolvedTicketsCount }})
                    </a>
                    <a href="{{ route('admin.support-tickets', ['status_tab' => 'closed']) }}" class="st-status-tab {{ $statusTab === 'closed' ? 'active' : '' }}">
                        Closed ({{ $closedTicketsCount }})
                    </a>
                </div>

                <!-- Tickets Table -->
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Department</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $t)
                                @php
                                    $deptCode = $t->department?->code ?? 'support';
                                    $deptName = $t->department?->name ?? 'Support';
                                    $deptClass = 'st-dept-' . $deptCode;
                                    $prioClass = 'st-prio-' . strtolower($t->priority);
                                    $statusClass = 'st-status-' . strtolower($t->status);
                                    $firstMsg = $t->firstMessage?->message ?? 'No preview available.';
                                @endphp
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" class="st-tkt-id" onclick="openConversationModal({{ $t->id }})">
                                            {{ $t->ticket_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="st-cust-cell">
                                            <div class="st-avatar-initials">{{ $t->initials }}</div>
                                            <div class="st-cust-info">
                                                <span class="st-cust-name">{{ $t->customer_name }}</span>
                                                <span class="st-cust-email">{{ $t->customer_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="st-subject-wrap">
                                            <span class="st-subject-title">{{ $t->subject }}</span>
                                            <span class="st-subject-preview" title="{{ $firstMsg }}">{{ $firstMsg }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="st-dept-badge {{ $deptClass }}">{{ $deptName }}</span>
                                    </td>
                                    <td>
                                        <span class="st-priority-badge {{ $prioClass }}">{{ ucfirst($t->priority) }}</span>
                                    </td>
                                    <td>
                                        <span class="st-status-badge {{ $statusClass }}">{{ ucfirst($t->status) }}</span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.82rem; color: var(--st-text-muted);" title="{{ $t->updated_at->format('M d, Y h:i A') }}">
                                            {{ $t->relative_updated }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="st-action-btns" style="justify-content: flex-end;">
                                            <button type="button" class="st-icon-btn" title="View Ticket Details" onclick="openConversationModal({{ $t->id }})">
                                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                            </button>

                                            <div class="st-dropdown">
                                                <button type="button" class="st-icon-btn" onclick="toggleDropdown(event, 'tktMenu_{{ $t->id }}')">
                                                    <i data-lucide="more-vertical" style="width: 14px; height: 14px;"></i>
                                                </button>
                                                <div class="st-dropdown-menu" id="tktMenu_{{ $t->id }}">
                                                    <button type="button" class="st-dropdown-item" onclick="openConversationModal({{ $t->id }})">
                                                        <i data-lucide="message-square" style="width: 13px; height: 13px;"></i> View & Reply
                                                    </button>

                                                    <form method="POST" action="{{ route('admin.support-tickets.status', $t->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="resolved">
                                                        <button type="submit" class="st-dropdown-item">
                                                            <i data-lucide="check-circle" style="width: 13px; height: 13px;"></i> Mark Resolved
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('admin.support-tickets.status', $t->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="closed">
                                                        <button type="submit" class="st-dropdown-item">
                                                            <i data-lucide="x-circle" style="width: 13px; height: 13px;"></i> Close Ticket
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('admin.support-tickets.destroy', $t->id) }}" onsubmit="return confirm('Are you sure you want to delete ticket {{ $t->ticket_number }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="st-dropdown-item danger">
                                                            <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i> Delete Ticket
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--st-text-muted);">
                                        <i data-lucide="ticket" style="width: 36px; height: 36px; opacity: 0.3; margin-bottom: 8px;"></i>
                                        <p>No support tickets found matching criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="st-pagination-bar">
                    <span>Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets</span>
                    
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <!-- Rows per page -->
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span>Rows per page</span>
                            <select class="st-select" style="padding: 4px 8px; font-size: 0.8rem;" onchange="changePerPage(this.value)">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <!-- Page Links -->
                        <div>
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Right Column: Sidebar Insight Cards -->
        <div class="st-right-col">
            
            <!-- Card 1: Ticket by Status -->
            <div class="st-side-card">
                <span class="st-side-card-title" style="display: block;">Ticket by Status</span>
                <div class="st-donut-wrap">
                    <div class="st-donut-chart">
                        <div class="st-donut-hole">
                            <small>Total</small>
                            <strong>{{ $totalTicketsCount }}</strong>
                        </div>
                    </div>

                    <div class="st-donut-legend">
                        <div class="st-legend-item">
                            <div class="st-legend-left">
                                <span class="st-legend-dot" style="background: #10b981;"></span>
                                <span>Open</span>
                            </div>
                            <span style="color: var(--st-text-muted);">{{ $chartStatusData['open']['count'] }} ({{ $chartStatusData['open']['pct'] }}%)</span>
                        </div>

                        <div class="st-legend-item">
                            <div class="st-legend-left">
                                <span class="st-legend-dot" style="background: #f59e0b;"></span>
                                <span>Pending</span>
                            </div>
                            <span style="color: var(--st-text-muted);">{{ $chartStatusData['pending']['count'] }} ({{ $chartStatusData['pending']['pct'] }}%)</span>
                        </div>

                        <div class="st-legend-item">
                            <div class="st-legend-left">
                                <span class="st-legend-dot" style="background: #3b82f6;"></span>
                                <span>Resolved</span>
                            </div>
                            <span style="color: var(--st-text-muted);">{{ $chartStatusData['resolved']['count'] }} ({{ $chartStatusData['resolved']['pct'] }}%)</span>
                        </div>

                        <div class="st-legend-item">
                            <div class="st-legend-left">
                                <span class="st-legend-dot" style="background: #9ca3af;"></span>
                                <span>Closed</span>
                            </div>
                            <span style="color: var(--st-text-muted);">{{ $chartStatusData['closed']['count'] }} ({{ $chartStatusData['closed']['pct'] }}%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Ticket by Department -->
            <div class="st-side-card">
                <span class="st-side-card-title" style="display: block;">Ticket by Department</span>
                <div class="st-dept-list">
                    @foreach($departments as $dept)
                        @php
                            $deptIcon = $dept->icon ?: 'folder';
                            $deptColor = match($dept->code) {
                                'orders' => '#0369a1',
                                'returns' => '#7e22ce',
                                'shipping' => '#00838f',
                                'payments' => '#15803d',
                                'products' => '#b45309',
                                'support' => '#be123c',
                                default => '#374151'
                            };
                        @endphp
                        <div class="st-dept-item">
                            <div class="st-dept-left">
                                <div class="st-dept-icon" style="background: rgba(215,166,74,0.12); color: {{ $deptColor }};">
                                    <i data-lucide="{{ $deptIcon }}" style="width: 14px; height: 14px;"></i>
                                </div>
                                <span style="font-weight: 700; color: var(--st-text);">{{ $dept->name }}</span>
                            </div>
                            <strong style="font-size: 0.88rem; color: var(--st-text);">{{ $dept->tickets_count }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Card 3: Quick Actions -->
            <div class="st-side-card">
                <span class="st-side-card-title" style="display: block;">Quick Actions</span>
                <div class="st-quick-list">
                    <a href="javascript:void(0)" onclick="openNewTicketModal()" class="st-quick-item">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="plus" style="width: 15px; height: 15px; color: var(--st-brown-primary);"></i>
                            <span>Create New Ticket</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--st-text-muted);"></i>
                    </a>

                    <a href="javascript:void(0)" onclick="openKbModal()" class="st-quick-item">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="book-open" style="width: 15px; height: 15px; color: var(--st-brown-primary);"></i>
                            <span>View Knowledge Base</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--st-text-muted);"></i>
                    </a>

                    <a href="javascript:void(0)" onclick="openCannedModal()" class="st-quick-item">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="message-square" style="width: 15px; height: 15px; color: var(--st-brown-primary);"></i>
                            <span>Canned Responses</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--st-text-muted);"></i>
                    </a>

                    <a href="javascript:void(0)" onclick="openDeptModal()" class="st-quick-item">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="layers" style="width: 15px; height: 15px; color: var(--st-brown-primary);"></i>
                            <span>Manage Departments</span>
                        </div>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: var(--st-text-muted);"></i>
                    </a>
                </div>
            </div>

            <!-- Card 4: Recent Activity -->
            <div class="st-side-card">
                <span class="st-side-card-title" style="display: block;">Recent Activity</span>
                <div class="st-activity-list">
                    @forelse($recentActivity as $act)
                        <div class="st-act-item">
                            <div class="st-act-icon">
                                <i data-lucide="{{ $act->is_admin_reply ? 'reply' : 'message-circle' }}" style="width: 14px; height: 14px;"></i>
                            </div>
                            <div class="st-act-content">
                                <span class="st-act-desc">
                                    <strong>{{ $act->ticket?->ticket_number ?? '#TKT' }}</strong> updated by {{ $act->sender_name ?: 'User' }}
                                </span>
                                <span class="st-act-time">{{ $act->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <span style="font-size: 0.8rem; color: var(--st-text-muted);">No recent ticket activities.</span>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ==========================================
     MODALS SECTION
========================================== -->

<!-- 1. Ticket Conversation & Reply Modal -->
<div class="st-modal-backdrop" id="conversationModal">
    <div class="st-modal-box" style="max-width: 780px;">
        <div class="st-modal-head">
            <span class="st-modal-title" id="convTicketTitle">Ticket Conversation</span>
            <button type="button" class="st-modal-close" onclick="closeModal('conversationModal')">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>
        <div class="st-modal-body">
            
            <!-- Meta header info -->
            <div style="background: #faf6f0; border: 1.5px solid var(--st-brown-border); border-radius: 12px; padding: 14px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div>
                    <strong id="convCustName" style="font-size: 0.92rem; color: var(--st-text);">Customer Name</strong>
                    <div id="convCustEmail" style="font-size: 0.78rem; color: var(--st-text-muted);">customer@email.com</div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <span id="convDeptBadge" class="st-dept-badge st-dept-orders">Department</span>
                    <span id="convPriorityBadge" class="st-priority-badge st-prio-medium">Priority</span>
                    <span id="convStatusBadge" class="st-status-badge st-status-open">Status</span>
                </div>
            </div>

            <!-- Messages Thread -->
            <div class="st-msg-thread" id="convMsgThread">
                <div style="text-align: center; color: var(--st-text-muted); padding: 20px;">
                    Loading ticket messages...
                </div>
            </div>

            <!-- Reply Form -->
            <form method="POST" id="convReplyForm" action="" enctype="multipart/form-data">
                @csrf
                <div class="st-form-group" style="margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label class="st-label">Write Reply / Note</label>
                        <select class="st-select" style="padding: 4px 8px; font-size: 0.76rem;" onchange="insertCannedResponse(this.value)">
                            <option value="">Insert Canned Response...</option>
                            @foreach($cannedResponses as $cr)
                                <option value="{{ $cr->content }}">{{ $cr->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <textarea name="message" id="replyMsgInput" class="st-textarea" rows="3" required placeholder="Type your response or internal note here..."></textarea>
                </div>

                <div class="st-form-grid" style="align-items: center; margin-bottom: 16px;">
                    <div class="st-form-group">
                        <label class="st-label">Update Status</label>
                        <select name="new_status" class="st-form-select">
                            <option value="open">Open</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="st-form-group">
                        <label class="st-label">Assign Agent</label>
                        <select name="assigned_agent_id" class="st-form-select">
                            <option value="">Assign Agent...</option>
                            @foreach($agentsList as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="st-form-group full-width" style="display: flex; justify-content: space-between; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; font-weight: 700; color: #b45309; cursor: pointer;">
                            <input type="checkbox" name="is_internal_note" value="1">
                            Internal Note (Hidden from customer)
                        </label>
                        <input type="file" name="attachments[]" multiple style="font-size: 0.8rem;">
                    </div>
                </div>

                <div class="st-modal-foot" style="padding: 0; background: none; border: none;">
                    <button type="button" class="st-btn-outline" onclick="closeModal('conversationModal')">Close</button>
                    <button type="submit" class="st-btn-primary">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Create New Ticket Modal -->
<div class="st-modal-backdrop" id="newTicketModal">
    <div class="st-modal-box">
        <form method="POST" action="{{ route('admin.support-tickets.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="st-modal-head">
                <span class="st-modal-title">Create New Support Ticket</span>
                <button type="button" class="st-modal-close" onclick="closeModal('newTicketModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="st-modal-body">
                <div class="st-form-grid">
                    <div class="st-form-group">
                        <label class="st-label">Customer Name *</label>
                        <input type="text" name="customer_name" class="st-input" required placeholder="e.g. Ali Raza">
                    </div>
                    <div class="st-form-group">
                        <label class="st-label">Customer Email *</label>
                        <input type="email" name="customer_email" class="st-input" required placeholder="ali.raza@email.com">
                    </div>
                    <div class="st-form-group">
                        <label class="st-label">Department *</label>
                        <select name="department_id" class="st-form-select" required>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="st-form-group">
                        <label class="st-label">Priority *</label>
                        <select name="priority" class="st-form-select" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="st-form-group full-width">
                        <label class="st-label">Subject *</label>
                        <input type="text" name="subject" class="st-input" required placeholder="Brief summary of the issue...">
                    </div>
                    <div class="st-form-group full-width">
                        <label class="st-label">Ticket Message *</label>
                        <textarea name="message" class="st-textarea" rows="3" required placeholder="Detailed message description..."></textarea>
                    </div>
                    <div class="st-form-group">
                        <label class="st-label">Linked Order (Optional)</label>
                        <select name="order_id" class="st-form-select">
                            <option value="">None / Not Applicable</option>
                            @foreach($ordersList as $ord)
                                <option value="{{ $ord->id }}">Order #ORD-{{ $ord->id }} (PKR {{ number_format($ord->final_total) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="st-form-group">
                        <label class="st-label">Assign Agent</label>
                        <select name="assigned_agent_id" class="st-form-select">
                            @foreach($agentsList as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="st-form-group full-width">
                        <label class="st-label">Attachments</label>
                        <input type="file" name="attachments[]" multiple class="st-input">
                    </div>
                </div>
            </div>
            <div class="st-modal-foot">
                <button type="button" class="st-btn-outline" onclick="closeModal('newTicketModal')">Cancel</button>
                <button type="submit" class="st-btn-primary">Create Ticket</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Manage Departments Modal -->
<div class="st-modal-backdrop" id="deptModal">
    <div class="st-modal-box" style="max-width: 520px;">
        <form method="POST" action="{{ route('admin.support-tickets.department.store') }}">
            @csrf
            <div class="st-modal-head">
                <span class="st-modal-title">Manage Departments</span>
                <button type="button" class="st-modal-close" onclick="closeModal('deptModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="st-modal-body">
                <div class="st-form-group" style="margin-bottom: 12px;">
                    <label class="st-label">Department Name *</label>
                    <input type="text" name="name" class="st-input" required placeholder="e.g. Warranty Claim">
                </div>
                <div class="st-form-group" style="margin-bottom: 12px;">
                    <label class="st-label">Color Theme *</label>
                    <select name="color" class="st-form-select" required>
                        <option value="blue">Blue</option>
                        <option value="purple">Purple</option>
                        <option value="green">Green</option>
                        <option value="amber">Amber</option>
                        <option value="pink">Pink</option>
                    </select>
                </div>
                <div class="st-form-group">
                    <label class="st-label">Default Priority *</label>
                    <select name="default_priority" class="st-form-select" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="st-modal-foot">
                <button type="button" class="st-btn-outline" onclick="closeModal('deptModal')">Cancel</button>
                <button type="submit" class="st-btn-primary">Save Department</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Canned Responses Modal -->
<div class="st-modal-backdrop" id="cannedModal">
    <div class="st-modal-box" style="max-width: 580px;">
        <form method="POST" action="{{ route('admin.support-tickets.canned.store') }}">
            @csrf
            <div class="st-modal-head">
                <span class="st-modal-title">Canned Responses</span>
                <button type="button" class="st-modal-close" onclick="closeModal('cannedModal')">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="st-modal-body">
                <div class="st-form-group" style="margin-bottom: 12px;">
                    <label class="st-label">Response Title *</label>
                    <input type="text" name="title" class="st-input" required placeholder="e.g. Refund Processing Time">
                </div>
                <div class="st-form-group" style="margin-bottom: 12px;">
                    <label class="st-label">Shortcut</label>
                    <input type="text" name="shortcut" class="st-input" placeholder="e.g. !refund-time">
                </div>
                <div class="st-form-group">
                    <label class="st-label">Response Message *</label>
                    <textarea name="content" class="st-textarea" rows="4" required placeholder="Pre-written answer template..."></textarea>
                </div>
            </div>
            <div class="st-modal-foot">
                <button type="button" class="st-btn-outline" onclick="closeModal('cannedModal')">Cancel</button>
                <button type="submit" class="st-btn-primary">Save Response</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Knowledge Base Preview Modal -->
<div class="st-modal-backdrop" id="kbModal">
    <div class="st-modal-box" style="max-width: 650px;">
        <div class="st-modal-head">
            <span class="st-modal-title">Knowledge Base Articles</span>
            <button type="button" class="st-modal-close" onclick="closeModal('kbModal')">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>
        <div class="st-modal-body">
            <div style="display: flex; flex-direction: column; gap: 14px;">
                @forelse($knowledgeArticles as $kb)
                    <div style="background: #fffdf9; border: 1.5px solid var(--st-brown-border); border-radius: 12px; padding: 14px 16px;">
                        <h4 style="margin: 0 0 6px 0; font-size: 0.92rem; color: var(--st-brown-primary);">{{ $kb->title }}</h4>
                        <span style="font-size: 0.74rem; background: #fdf5e6; color: var(--st-brown-primary); padding: 2px 6px; border-radius: 4px; font-weight: 700;">{{ $kb->category }}</span>
                        <p style="margin: 8px 0 0 0; font-size: 0.82rem; color: var(--st-text-muted); font-weight: 500;">{{ $kb->content }}</p>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--st-text-muted);">No knowledge base articles published yet.</p>
                @endforelse
            </div>
        </div>
        <div class="st-modal-foot">
            <button type="button" class="st-btn-outline" onclick="closeModal('kbModal')">Close</button>
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
        document.querySelectorAll('.st-dropdown-menu').forEach(m => {
            if (m.id !== menuId) m.classList.remove('show');
        });
        const target = document.getElementById(menuId);
        if (target) target.classList.toggle('show');
    }

    function toggleHeaderSplitDropdown(e) {
        toggleDropdown(e, 'headerSplitDropdown');
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.st-dropdown-menu').forEach(m => m.classList.remove('show'));
    });

    function openModal(modalId) {
        const el = document.getElementById(modalId);
        if (el) el.classList.add('show');
    }

    function closeModal(modalId) {
        const el = document.getElementById(modalId);
        if (el) el.classList.remove('show');
    }

    function openNewTicketModal() { openModal('newTicketModal'); }
    function openDeptModal() { openModal('deptModal'); }
    function openCannedModal() { openModal('cannedModal'); }
    function openKbModal() { openModal('kbModal'); }

    function openConversationModal(ticketId) {
        const thread = document.getElementById('convMsgThread');
        thread.innerHTML = '<div style="text-align:center; padding:20px; color:var(--st-text-muted);">Loading ticket conversation...</div>';
        openModal('conversationModal');

        fetch(`/admin/support-tickets/${ticketId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.ticket) {
                    const t = data.ticket;
                    document.getElementById('convTicketTitle').innerText = `${t.ticket_number} - ${t.subject}`;
                    document.getElementById('convCustName').innerText = t.customer_name;
                    document.getElementById('convCustEmail').innerText = t.customer_email;
                    document.getElementById('convDeptBadge').innerText = t.department ? t.department.name : 'Support';
                    document.getElementById('convPriorityBadge').innerText = t.priority.toUpperCase();
                    document.getElementById('convStatusBadge').innerText = t.status.toUpperCase();

                    const replyForm = document.getElementById('convReplyForm');
                    replyForm.action = `/admin/support-tickets/${t.id}/reply`;

                    let html = '';
                    if (t.messages && t.messages.length > 0) {
                        t.messages.forEach(m => {
                            let typeClass = 'customer';
                            let roleTitle = m.sender_name || 'Customer';
                            if (m.is_internal_note) {
                                typeClass = 'internal';
                                roleTitle = 'Internal Note by ' + roleTitle;
                            } else if (m.is_admin_reply) {
                                typeClass = 'admin';
                                roleTitle = 'Support Agent (' + roleTitle + ')';
                            }

                            html += `
                                <div class="st-msg-bubble ${typeClass}">
                                    <div class="st-msg-head">
                                        <span>${roleTitle}</span>
                                        <span style="opacity:0.75; font-size:0.7rem;">${new Date(m.created_at).toLocaleString()}</span>
                                    </div>
                                    <div>${m.message}</div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div style="text-align:center; padding:20px; color:var(--st-text-muted);">No messages found in thread.</div>';
                    }
                    thread.innerHTML = html;
                }
            })
            .catch(err => {
                thread.innerHTML = '<div style="text-align:center; color:#ef4444; padding:20px;">Failed to load conversation.</div>';
            });
    }

    function insertCannedResponse(content) {
        if (content) {
            const input = document.getElementById('replyMsgInput');
            input.value += (input.value ? '\n' : '') + content;
        }
    }

    function changePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        window.location.href = url.toString();
    }
</script>
@endpush
@endsection
