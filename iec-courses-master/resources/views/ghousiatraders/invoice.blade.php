@extends('ghousiatraders.layouts.app')

@section('title', 'Invoice ' . $invoiceNumber . ' — Ghousia Traders')

@section('content')
<div class="gt-invoice-page-wrapper" style="background-color: #F8F5F0; padding: 40px 0 60px; font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="section-container" style="max-width: 960px; margin: 0 auto; padding: 0 20px;">

        <!-- MAIN INVOICE CARD (A4 PREVIEW) -->
        @include('ghousiatraders.partials.invoice-card')

        <!-- BOTTOM ACTION BUTTONS BAR (Excluded from print) -->
        <div class="gt-invoice-actions-bar invoice-actions" style="margin-top: 28px; display: flex; align-items: center; justify-content: flex-end; gap: 14px;">
            <a href="{{ route('orders.invoice.pdf', $order->id) }}" class="btn-invoice-action btn-outline" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; background: #FFFFFF; border: 1.5px solid #D5C8B8; color: #5C3E21; font-weight: 700; font-size: 0.9rem; border-radius: 12px; text-decoration: none; transition: all 0.2s; box-shadow: 0 2px 8px rgba(92, 62, 33, 0.04);">
                <i data-lucide="download" style="width: 18px; height: 18px;"></i>
                Download Invoice (PDF)
            </a>

            <button type="button" onclick="window.print()" class="btn-invoice-action btn-outline" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; background: #FFFFFF; border: 1.5px solid #D5C8B8; color: #5C3E21; font-weight: 700; font-size: 0.9rem; border-radius: 12px; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(92, 62, 33, 0.04);">
                <i data-lucide="printer" style="width: 18px; height: 18px;"></i>
                Print Invoice
            </button>

            <a href="{{ $trackingUrl }}" class="btn-invoice-action btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #5C3E21; color: #FFFFFF; font-weight: 700; font-size: 0.9rem; border-radius: 12px; text-decoration: none; transition: background 0.2s; box-shadow: 0 4px 14px rgba(92, 62, 33, 0.15);">
                Track Your Order
                <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </a>
        </div>

    </div>
</div>

<style>
    @media print {
        header, footer, nav, .site-header, .site-footer, .gt-invoice-actions-bar, .invoice-actions, #sf-toast-sound-toggle, #storefront-toast-container {
            display: none !important;
        }
        body, html, main, #main-content {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .gt-invoice-page-wrapper {
            background: #ffffff !important;
            padding: 0 !important;
        }
        .section-container {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .gt-invoice-card, .invoice-sheet {
            border: none !important;
            box-shadow: none !important;
            padding: 20px !important;
            border-radius: 0 !important;
        }
        tr {
            page-break-inside: avoid !important;
        }
    }
</style>
@endsection
