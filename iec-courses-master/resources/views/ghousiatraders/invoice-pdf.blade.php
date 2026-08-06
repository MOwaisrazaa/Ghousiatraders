<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        @page {
            margin: 25px 30px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #3A2518;
            font-size: 12px;
            line-height: 1.4;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .header-table td {
            vertical-align: top;
        }
        .store-title {
            font-size: 20px;
            font-weight: bold;
            color: #3A2518;
            margin: 0;
        }
        .store-tagline {
            font-size: 10px;
            color: #7A6E65;
            margin: 2px 0 6px 0;
        }
        .store-meta {
            font-size: 11px;
            color: #55483D;
            line-height: 1.4;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: 900;
            color: #3A2518;
            margin: 0;
            line-height: 1;
        }
        .invoice-no {
            font-size: 13px;
            font-weight: bold;
            color: #5C3E21;
            margin-top: 4px;
        }
        .date-pill {
            background: #FAF3EA;
            border: 1px solid #EADECF;
            color: #5C3E21;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            margin-top: 6px;
        }

        .qr-box {
            border: 1px solid #EAE1D3;
            background: #FFFDF9;
            border-radius: 8px;
            padding: 6px 10px;
            margin-top: 10px;
            display: inline-block;
        }

        .divider {
            border-bottom: 1px dashed #E2D7C7;
            margin: 15px 0;
        }

        .address-box {
            background: #FAF7F2;
            border: 1px solid #F0E8DF;
            border-radius: 10px;
            padding: 12px 14px;
            vertical-align: top;
        }
        .box-heading {
            font-size: 11px;
            font-weight: bold;
            color: #3A2518;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .info-grid {
            background: #FAF7F2;
            border: 1px solid #EAE1D3;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 18px;
        }
        .info-label {
            font-size: 9px;
            font-weight: bold;
            color: #8C7C6D;
            text-transform: uppercase;
        }
        .info-val {
            font-size: 11px;
            font-weight: bold;
            color: #3A2518;
        }

        .items-table {
            border: 1px solid #EAE1D3;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 18px;
        }
        .items-table th {
            background: #5C3E21;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 9px 12px;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #F0E8DF;
            font-size: 11px;
        }

        .thank-you-card {
            background: #FAF5EE;
            border: 1px solid #EFE4D6;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .totals-table td {
            padding: 3px 0;
            font-size: 11px;
        }

        .signature-line {
            border-top: 1px solid #D5C8B8;
            width: 130px;
            margin: 6px auto 2px auto;
        }
    </style>
</head>
<body>

    <!-- 1. HEADER ROW -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50px; vertical-align: top;">
                            <img src="{{ $storeLogoLocal }}" style="width: 44px; height: 44px; object-fit: contain;">
                        </td>
                        <td>
                            <div class="store-title">{{ $storeName }}</div>
                            <div class="store-tagline">{{ $storeTagline }}</div>
                        </td>
                    </tr>
                </table>
                <div class="store-meta" style="margin-top: 6px;">
                    <strong>{{ $storeName }}</strong><br>
                    {{ $storeAddress }}<br>
                    <strong>Phone:</strong> {{ $storePhone }} | <strong>Email:</strong> {{ $storeEmail }}<br>
                    <strong>Website:</strong> {{ $storeWebsite }}
                </div>
            </td>
            <td style="width: 45%; text-align: right;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-no">{{ $invoiceNumber }}</div>
                <div class="date-pill">{{ $invoiceDate }}</div>

                <div style="margin-top: 10px;">
                    <table style="float: right; border: 1px solid #EAE1D3; background: #FFFDF9; border-radius: 8px; padding: 4px 8px;">
                        <tr>
                            <td>
                                <div style="width: 54px; height: 54px;">
                                    {!! $qrCodeSvg !!}
                                </div>
                            </td>
                            <td style="padding-left: 6px; font-size: 10px; font-weight: bold; color: #3A2518; text-align: left;">
                                Scan to<br>View Order
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- 2. BILL TO & SHIP TO -->
    <table style="margin-bottom: 16px;">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <div class="address-box">
                    <div class="box-heading">Bill To</div>
                    <strong>{{ $custFullName }}</strong><br>
                    {{ $custEmail }}<br>
                    {{ $custPhone }}<br>
                    {!! nl2br(e($formattedAddress)) !!}
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;">
                <div class="address-box">
                    <div class="box-heading">Ship To</div>
                    <strong>{{ $custFullName }}</strong><br>
                    {!! nl2br(e($formattedAddress)) !!}
                </div>
            </td>
        </tr>
    </table>

    <!-- 3. ORDER INFO ROW -->
    <div class="info-grid">
        <table>
            <tr>
                <td style="width: 25%;">
                    <div class="info-label">Order ID</div>
                    <div class="info-val">{{ $orderId }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Order Date</div>
                    <div class="info-val">{{ $orderDateTime }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Payment Method</div>
                    <div class="info-val">{{ $paymentLabel }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Shipping Method</div>
                    <div class="info-val">{{ $shippingMethod }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 4. ITEMS TABLE -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">#</th>
                <th style="text-align: left;">PRODUCT</th>
                <th style="width: 90px; text-align: right;">PRICE</th>
                <th style="width: 45px; text-align: center;">QTY</th>
                <th style="width: 100px; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td style="text-align: center; color: #7A6E65;">{{ $item['sn'] }}</td>
                    <td>
                        <strong>{{ $item['name'] }}</strong>
                        @if(!empty($item['options']))
                            <div style="font-size: 9px; color: #7A6E65;">{{ $item['options'] }}</div>
                        @endif
                    </td>
                    <td style="text-align: right;">PKR {{ number_format($item['price']) }}</td>
                    <td style="text-align: center;">{{ $item['quantity'] }}</td>
                    <td style="text-align: right; font-weight: bold;">PKR {{ number_format($item['line_total']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 5. THANK YOU & TOTALS -->
    <table style="margin-bottom: 20px;">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div class="thank-you-card">
                    <strong style="font-size: 12px; color: #3A2518;">Thank you for shopping with {{ $storeName }}!</strong>
                    <div style="font-size: 10px; color: #7A6E65; margin-top: 3px;">We appreciate your trust in us.</div>
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="totals-table" style="width: 100%;">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right"><strong>PKR {{ number_format($subtotal) }}</strong></td>
                    </tr>
                    @if($discount > 0)
                        <tr style="color: #2E7D32;">
                            <td>Discount {{ $couponCode ? '(' . $couponCode . ')' : '' }}</td>
                            <td class="text-right"><strong>- PKR {{ number_format($discount) }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td>Shipping Charges</td>
                        <td class="text-right"><strong>{{ $shippingCost > 0 ? 'PKR ' . number_format($shippingCost) : 'FREE' }}</strong></td>
                    </tr>
                    @if($tax > 0)
                        <tr>
                            <td>Tax</td>
                            <td class="text-right"><strong>PKR {{ number_format($tax) }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2"><div style="border-bottom: 1px solid #EFEAE3; margin: 4px 0;"></div></td>
                    </tr>
                    <tr style="font-size: 13px; font-weight: bold; color: #3A2518;">
                        <td>Total Amount</td>
                        <td class="text-right" style="color: #5C3E21;">PKR {{ number_format($finalTotal) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- 6. TERMS & SIGNATURE -->
    <table>
        <tr>
            <td style="width: 65%; vertical-align: bottom;">
                <strong style="font-size: 11px; color: #3A2518;">Terms & Conditions</strong>
                <ul style="font-size: 9.5px; color: #66594E; margin: 4px 0 0 0; padding-left: 14px; line-height: 1.4;">
                    @foreach($termsList as $term)
                        <li>{{ $term }}</li>
                    @endforeach
                </ul>
            </td>
            <td style="width: 35%; text-align: center; vertical-align: bottom;">
                @if($signatureImageLocal && file_exists($signatureImageLocal))
                    <img src="{{ $signatureImageLocal }}" style="height: 38px; object-fit: contain;">
                @else
                    <div style="font-size: 18px; font-weight: bold; color: #5C3E21; font-style: italic;">{{ $storeName }}</div>
                @endif
                <div class="signature-line"></div>
                <strong style="font-size: 10px; color: #3A2518;">Authorized Signature</strong><br>
                <span style="font-size: 9px; color: #7A6E65;">{{ $storeName }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
