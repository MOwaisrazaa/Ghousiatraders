<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Course;
use App\Models\PaymentMethod;
use App\Services\StoreSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display the Ghousia Traders invoice preview page.
     */
    public function show(Order $order)
    {
        $this->authorizeInvoiceAccess($order);

        $data = $this->buildInvoiceData($order);

        return view('ghousiatraders.invoice', $data);
    }

    /**
     * Display the Ghousia Traders invoice print view (A4 sheet only).
     */
    public function print(Order $order, Request $request)
    {
        if (!$request->hasValidSignature()) {
            $this->authorizeInvoiceAccess($order);
        }

        $data = $this->buildInvoiceData($order);

        return view('ghousiatraders.invoice-print', $data);
    }

    /**
     * Download the Ghousia Traders invoice as a PDF file using headless Chrome.
     */
    public function pdf(Order $order, Request $request)
    {
        $this->authorizeInvoiceAccess($order);

        $data = $this->buildInvoiceData($order, true);
        $invoiceNumber = $data['invoiceNumber'];
        $filename = 'Ghousia_Traders_Invoice_' . str_replace(['#', '/'], '', $invoiceNumber) . '.pdf';

        $tmpDirectory = storage_path('app/tmp');
        if (!file_exists($tmpDirectory)) {
            mkdir($tmpDirectory, 0755, true);
        }

        $timestamp = microtime(true);
        $pdfPath = $tmpDirectory . '/invoice_' . $order->id . '_' . $timestamp . '.pdf';
        $htmlPath = $tmpDirectory . '/invoice_' . $order->id . '_' . $timestamp . '.html';
        $scriptPath = base_path('scripts/generate-invoice-pdf.mjs');

        // Render HTML file directly to avoid single-threaded PHP web server loopback deadlock
        file_put_contents($htmlPath, view('ghousiatraders.invoice-print', $data)->render());

        $command = sprintf('node %s %s %s 2>&1', escapeshellarg($scriptPath), escapeshellarg($htmlPath), escapeshellarg($pdfPath));
        exec($command, $output, $returnCode);

        @unlink($htmlPath);

        if ($returnCode === 0 && file_exists($pdfPath)) {
            return response()->download($pdfPath, $filename)->deleteFileAfterSend(true);
        }

        // Fallback to DomPDF if headless Chrome fails
        $pdf = Pdf::loadView('ghousiatraders.invoice-print', $data)
            ->setPaper('a4', 'portrait')
            ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download($filename);
    }

    /**
     * Check if current user is authorized to view/download this order invoice.
     */
    private function authorizeInvoiceAccess(Order $order): void
    {
        $user = Auth::user();

        // Admin override
        if ($user && ($user->role === 'admin' || !empty($user->is_admin))) {
            return;
        }

        // Customer ownership
        if ($user && (int) $order->user_id === (int) $user->id) {
            return;
        }

        // Guest / session checkout authorization
        $billingData = json_decode($order->billing_address ?? '{}', true) ?: [];
        $billingEmail = strtolower($billingData['email'] ?? '');
        $userEmail = strtolower($user->email ?? '');

        if ($userEmail && $billingEmail && $userEmail === $billingEmail) {
            return;
        }

        if (session('last_order_id') == $order->id) {
            return;
        }

        if (!$user && session('recent_checkout_order_id') == $order->id) {
            return;
        }

        abort(403, 'Unauthorized access to this order invoice.');
    }

    /**
     * Build invoice data context array.
     */
    private function buildInvoiceData(Order $order, bool $isPdf = false): array
    {
        $year = optional($order->created_at)->format('Y') ?? now()->format('Y');
        $invoiceNumber = sprintf('#GT-INV-%s-%06d', $year, $order->id);
        $orderId = sprintf('#GT-%s-%05d', $year, $order->id);
        $invoiceDate = optional($order->created_at)->format('F j, Y') ?? now()->format('F j, Y');
        $orderDateTime = optional($order->created_at)->format('M j, Y g:i A') ?? now()->format('M j, Y g:i A');

        // Store Settings
        $rawStoreName = StoreSettingsService::get('public_store_name', StoreSettingsService::get('legal_business_name', 'Ghousia Traders'));
        $storeName = 'Ghousia Traders';
        $storeTagline = StoreSettingsService::get('store_tagline', 'Quality You Can Trust');
        $storeAddress = StoreSettingsService::getFormattedAddress();
        $storePhone = StoreSettingsService::get('primary_phone', '+92 300 1234567');
        $storeEmail = StoreSettingsService::get('support_email', StoreSettingsService::get('sales_email', 'support@ghousiatraders.com'));
        $storeWebsite = StoreSettingsService::get('store_website_url', 'www.ghousiatraders.com');
        $storeLogo = StoreSettingsService::get('store_logo', 'ghousiatraders/assets/logo.png');

        $termsText = StoreSettingsService::get('invoice_terms', "Prices include applicable taxes where relevant.\nThis is a computer-generated invoice.\nNo signature is required.\nFor any queries, contact our support team.");
        $termsList = array_filter(array_map('trim', explode("\n", $termsText)));

        $signatureImage = StoreSettingsService::get('authorized_signature');

        // Customer Details
        $billingData = json_decode($order->billing_address ?? '{}', true) ?: [];
        $custFirstName = $billingData['first_name'] ?? '';
        $custLastName = $billingData['last_name'] ?? '';
        $custFullName = trim($custFirstName . ' ' . $custLastName) ?: ($order->user->name ?? 'Valued Customer');
        $custEmail = $billingData['email'] ?? ($order->user->email ?? 'customer@example.com');
        $custPhone = $billingData['phone'] ?? ($order->user->phone ?? '+92 321 4567890');

        $streetAddr = $billingData['address'] ?? ($billingData['address_line_1'] ?? '');
        $city = $billingData['city'] ?? 'Lahore';
        $state = $billingData['state'] ?? 'Punjab';
        $country = $billingData['country'] ?? 'Pakistan';
        $postalCode = $billingData['postal_code'] ?? '';

        $fullAddrLines = array_filter([$streetAddr, trim($city . ', ' . $state), trim($country . ($postalCode ? ' ' . $postalCode : ''))]);
        $formattedAddress = implode("\n", $fullAddrLines) ?: 'Lahore, Punjab, Pakistan';

        // Payment Method Label
        $pmModel = PaymentMethod::where('key', $order->payment_method)->first();
        $paymentLabel = $pmModel ? $pmModel->name : match ($order->payment_method) {
            'cash' => 'Cash on Delivery',
            'jazzcash' => 'Jazz Cash',
            'easypaisa' => 'Easypaisa',
            'banktransfer' => 'Bank Transfer',
            default => ucwords(str_replace(['_', '-'], ' ', $order->payment_method ?? 'Cash on Delivery')),
        };

        $shippingMethod = !empty($billingData['delivery_method'])
            ? ucwords(str_replace(['_', '-'], ' ', $billingData['delivery_method']))
            : (($order->shipping_cost > 0) ? 'Standard Delivery' : 'Free Delivery');

        // Order Items
        $cartItems = json_decode($order->cart_items ?? '[]', true) ?: [];
        $items = [];
        $itemCounter = 1;

        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                $product = isset($item['course_id']) ? Course::find($item['course_id']) : null;
                $name = !empty($item['name']) ? $item['name'] : ($product ? $product->name : 'Ghousia Traders Product');
                $price = (float) ($item['price'] ?? ($product ? $product->weekly_price : 0));
                $quantity = (int) ($item['quantity'] ?? 1);
                $lineTotal = $price * $quantity;
                $imagePath = $product ? $product->image_path : ($item['image_path'] ?? null);

                $options = [];
                if (!empty($item['variation'])) $options[] = $item['variation'];
                if (!empty($item['color'])) $options[] = 'Color: ' . $item['color'];
                if (!empty($item['size'])) $options[] = 'Size: ' . $item['size'];

                $items[] = [
                    'sn' => $itemCounter++,
                    'name' => $name,
                    'options' => implode(' | ', $options),
                    'price' => $price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                    'image' => (!empty($imagePath) && file_exists(public_path(ltrim($imagePath, '/')))) ? asset(ltrim($imagePath, '/')) : asset('ghousiatraders/assets/baby_products.png'),
                    'image_local' => (!empty($imagePath) && file_exists(public_path(ltrim($imagePath, '/')))) ? public_path(ltrim($imagePath, '/')) : public_path('ghousiatraders/assets/baby_products.png'),
                ];
            }
        }

        // Financial Totals
        $subtotal = (float) ($order->total ?? 0);
        $discount = (float) ($order->discount ?? 0);
        $shippingCost = (float) ($order->shipping_cost ?? 0);
        $tax = (float) ($order->tax ?? 0);
        $finalTotal = (float) ($order->final_total ?? $order->total ?? 0);
        $couponCode = $order->coupon_code ?? null;

        // Dynamic QR Code Data & SVG Generator
        $trackingUrl = route('polani.track-order', array_filter(['order_number' => $orderId, 'email' => $custEmail]));
        $qrCodeSvg = $this->generateQrCodeSvg($trackingUrl);

        return [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => $invoiceDate,
            'orderId' => $orderId,
            'orderDateTime' => $orderDateTime,
            'storeName' => $storeName,
            'storeTagline' => $storeTagline,
            'storeAddress' => $storeAddress,
            'storePhone' => $storePhone,
            'storeEmail' => $storeEmail,
            'storeWebsite' => $storeWebsite,
            'storeLogo' => file_exists(public_path($storeLogo)) ? asset($storeLogo) : asset('ghousiatraders/assets/logo.png'),
            'storeLogoLocal' => file_exists(public_path($storeLogo)) ? public_path($storeLogo) : public_path('ghousiatraders/assets/logo.png'),
            'termsList' => $termsList,
            'signatureImage' => $signatureImage ? (file_exists(public_path($signatureImage)) ? asset($signatureImage) : null) : null,
            'signatureImageLocal' => $signatureImage ? (file_exists(public_path($signatureImage)) ? public_path($signatureImage) : null) : null,
            'custFullName' => $custFullName,
            'custEmail' => $custEmail,
            'custPhone' => $custPhone,
            'formattedAddress' => $formattedAddress,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'paymentLabel' => $paymentLabel,
            'shippingMethod' => $shippingMethod,
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'couponCode' => $couponCode,
            'shippingCost' => $shippingCost,
            'tax' => $tax,
            'finalTotal' => $finalTotal,
            'trackingUrl' => $trackingUrl,
            'qrCodeSvg' => $qrCodeSvg,
            'isPdf' => $isPdf,
        ];
    }

    /**
     * Generate inline SVG QR code for Dompdf and web rendering without GD dependencies.
     */
    private function generateQrCodeSvg(string $url): string
    {
        $svgUrl = 'https://quickchart.io/qr?text=' . urlencode($url) . '&size=100&format=svg';
        $svgContent = @file_get_contents($svgUrl);
        if ($svgContent && str_contains($svgContent, '<svg')) {
            $svgContent = preg_replace('/<\?xml[^>]*\?>/i', '', $svgContent);
            return trim($svgContent);
        }

        return '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <rect width="60" height="60" fill="#FFFDF9" stroke="#EAE1D3" rx="6"/>
            <rect x="6" y="6" width="18" height="18" fill="#5C3E21"/>
            <rect x="10" y="10" width="10" height="10" fill="#FFFDF9"/>
            <rect x="12" y="12" width="6" height="6" fill="#5C3E21"/>
            <rect x="36" y="6" width="18" height="18" fill="#5C3E21"/>
            <rect x="40" y="10" width="10" height="10" fill="#FFFDF9"/>
            <rect x="42" y="12" width="6" height="6" fill="#5C3E21"/>
            <rect x="6" y="36" width="18" height="18" fill="#5C3E21"/>
            <rect x="10" y="40" width="10" height="10" fill="#FFFDF9"/>
            <rect x="12" y="42" width="6" height="6" fill="#5C3E21"/>
            <rect x="28" y="28" width="8" height="8" fill="#5C3E21"/>
            <rect x="38" y="38" width="14" height="14" fill="#5C3E21"/>
        </svg>';
    }
}
