<div class="invoice-sheet gt-invoice-card" id="printable-invoice" style="background: #FFFFFF; border: 1px solid #EAE1D3; border-radius: 20px; padding: 40px 48px; box-shadow: 0 8px 30px rgba(92, 62, 33, 0.05); position: relative; width: 210mm; min-height: 297mm; margin: 0 auto; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; color: #3A2518;">
    
    <!-- 1. HEADER ROW: STORE BRANDING & INVOICE META -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 30px; margin-bottom: 24px;">
        <!-- Left: Logo & Store Address -->
        <div style="max-width: 480px;">
            <div style="margin-bottom: 12px;">
                <h2 style="font-family: 'Lora', serif; font-size: 1.65rem; font-weight: 800; color: #3A2518; margin: 0; line-height: 1.15;">Ghousia Traders</h2>
                <p style="font-size: 0.82rem; color: #7A6E65; margin: 4px 0 0 0; font-weight: 500;">{{ $storeTagline }}</p>
            </div>
            
            <div style="font-size: 0.84rem; color: #55483D; line-height: 1.45;">
                <p style="margin: 0; font-weight: 700; color: #3A2518;">Ghousia Traders</p>
                <p style="margin: 2px 0;">{{ $storeAddress }}</p>
                <p style="margin: 2px 0;"><strong>Phone:</strong> {{ $storePhone }}</p>
                <p style="margin: 2px 0;"><strong>Email:</strong> {{ $storeEmail }}</p>
                <p style="margin: 2px 0;"><strong>Website:</strong> {{ $storeWebsite }}</p>
            </div>
        </div>

        <!-- Right: INVOICE Title, Date & QR Code -->
        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end;">
            <h1 style="font-size: 2.2rem; font-weight: 900; color: #3A2518; margin: 0 0 4px 0; letter-spacing: -0.02em;">INVOICE</h1>
            <div style="font-size: 1rem; font-weight: 800; color: #5C3E21; margin-bottom: 6px;">{{ $invoiceNumber }}</div>
            <div style="background: #FAF3EA; border: 1px solid #EADECF; color: #5C3E21; padding: 4px 14px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-block; margin-bottom: 14px;">
                {{ $invoiceDate }}
            </div>

            <!-- QR Code Block -->
            <div style="border: 1px solid #EAE1D3; border-radius: 12px; padding: 10px 14px; background: #FFFDF9; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 8px rgba(92, 62, 33, 0.03);">
                <div style="width: 68px; height: 68px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    {!! $qrCodeSvg !!}
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 0.78rem; font-weight: 800; color: #3A2518; line-height: 1.2;">Scan to<br>View Order</div>
                </div>
            </div>
        </div>
    </div>

    <div style="border-top: 1px dotted #E2D7C7; margin: 20px 0;"></div>

    <!-- 2. CUSTOMER DETAILS GRID: BILL TO & SHIP TO -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 24px;">
        <!-- Bill To -->
        <div style="background: #FAF7F2; border: 1px solid #F0E8DF; border-radius: 12px; padding: 16px 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #5C3E21;">
                <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                <span style="font-size: 0.88rem; font-weight: 800; color: #3A2518; text-transform: uppercase; letter-spacing: 0.02em;">Bill To</span>
            </div>
            <div style="font-size: 0.88rem; color: #4A3C30; line-height: 1.5;">
                <p style="margin: 0; font-weight: 800; color: #3A2518;">{{ $custFullName }}</p>
                <p style="margin: 2px 0;">{{ $custEmail }}</p>
                <p style="margin: 2px 0;">{{ $custPhone }}</p>
                <p style="margin: 2px 0; white-space: pre-line;">{{ $formattedAddress }}</p>
            </div>
        </div>

        <!-- Ship To -->
        <div style="background: #FAF7F2; border: 1px solid #F0E8DF; border-radius: 12px; padding: 16px 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #5C3E21;">
                <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                <span style="font-size: 0.88rem; font-weight: 800; color: #3A2518; text-transform: uppercase; letter-spacing: 0.02em;">Ship To</span>
            </div>
            <div style="font-size: 0.88rem; color: #4A3C30; line-height: 1.5;">
                <p style="margin: 0; font-weight: 800; color: #3A2518;">{{ $custFullName }}</p>
                <p style="margin: 2px 0; white-space: pre-line;">{{ $formattedAddress }}</p>
            </div>
        </div>
    </div>

    <!-- 3. ORDER INFORMATION ROW (4 EQUAL BLOCKS) -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; background: #FAF7F2; border: 1px solid #EAE1D3; border-radius: 14px; padding: 16px; margin-bottom: 24px;">
        <div>
            <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #8C7C6D; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px;">Order ID</span>
            <strong style="font-size: 0.9rem; color: #3A2518;">{{ $orderId }}</strong>
        </div>
        <div>
            <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #8C7C6D; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px;">Order Date</span>
            <strong style="font-size: 0.88rem; color: #3A2518;">{{ $orderDateTime }}</strong>
        </div>
        <div>
            <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #8C7C6D; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px;">Payment Method</span>
            <strong style="font-size: 0.88rem; color: #3A2518;">{{ $paymentLabel }}</strong>
        </div>
        <div>
            <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #8C7C6D; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px;">Shipping Method</span>
            <strong style="font-size: 0.88rem; color: #3A2518;">{{ $shippingMethod }}</strong>
        </div>
    </div>

    <!-- 4. INVOICE ITEMS TABLE -->
    <div style="border-radius: 12px; overflow: hidden; border: 1px solid #EAE1D3; margin-bottom: 24px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
            <thead>
                <tr style="background: #5C3E21; color: #FFFFFF; font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;">
                    <th style="padding: 12px 16px; width: 40px; text-align: center;">#</th>
                    <th style="padding: 12px 16px;">PRODUCT</th>
                    <th style="padding: 12px 16px; text-align: right; width: 120px;">PRICE</th>
                    <th style="padding: 12px 16px; text-align: center; width: 60px;">QTY</th>
                    <th style="padding: 12px 16px; text-align: right; width: 130px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr style="border-bottom: 1px solid #F0E8DF; background: {{ $loop->even ? '#FAF7F2' : '#FFFFFF' }};">
                        <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #7A6E65;">{{ $item['sn'] }}</td>
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid #EFEAE3; background: #FFFFFF; flex-shrink: 0; padding: 2px; box-sizing: border-box; overflow: hidden;">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; display: block; margin: 0 auto;">
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: #3A2518; line-height: 1.3;">{{ $item['name'] }}</div>
                                    @if(!empty($item['options']))
                                        <div style="font-size: 0.76rem; color: #7A6E65; margin-top: 2px;">{{ $item['options'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px; text-align: right; font-weight: 700; color: #3A2518;">PKR {{ number_format($item['price']) }}</td>
                        <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #3A2518;">{{ $item['quantity'] }}</td>
                        <td style="padding: 14px 16px; text-align: right; font-weight: 800; color: #3A2518;">PKR {{ number_format($item['line_total']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center; color: #7A6E65;">No items found for this invoice.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 5. TOTALS & THANK YOU SECTION -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 30px; margin-bottom: 28px;">
        <!-- Left: Thank You Card -->
        <div style="flex: 1; max-width: 440px; background: #FAF5EE; border: 1.5px solid #EFE4D6; border-radius: 14px; padding: 18px 20px; display: flex; align-items: flex-start; gap: 14px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: #5C3E21; color: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(92, 62, 33, 0.15);">
                <i data-lucide="shopping-bag" style="width: 18px; height: 18px;"></i>
            </div>
            <div>
                <h4 style="font-size: 0.92rem; font-weight: 800; color: #3A2518; margin: 0 0 4px 0;">Thank you for shopping with Ghousia Traders!</h4>
                <p style="font-size: 0.8rem; color: #7A6E65; margin: 0;">We appreciate your trust in us.</p>
            </div>
        </div>

        <!-- Right: Calculations Table -->
        <div style="width: 300px; font-size: 0.9rem;">
            <div style="display: flex; justify-content: space-between; padding: 4px 0; color: #55483D;">
                <span>Subtotal</span>
                <strong style="color: #3A2518;">PKR {{ number_format($subtotal) }}</strong>
            </div>

            @if($discount > 0)
                <div style="display: flex; justify-content: space-between; padding: 4px 0; color: #2E7D32;">
                    <span>Discount {{ $couponCode ? '(' . $couponCode . ')' : '' }}</span>
                    <strong>- PKR {{ number_format($discount) }}</strong>
                </div>
            @endif

            <div style="display: flex; justify-content: space-between; padding: 4px 0; color: #55483D;">
                <span>Shipping Charges</span>
                <strong style="color: #3A2518;">{{ $shippingCost > 0 ? 'PKR ' . number_format($shippingCost) : 'FREE' }}</strong>
            </div>

            @if($tax > 0)
                <div style="display: flex; justify-content: space-between; padding: 4px 0; color: #55483D;">
                    <span>Tax</span>
                    <strong style="color: #3A2518;">PKR {{ number_format($tax) }}</strong>
                </div>
            @endif

            <div style="border-top: 1px solid #EFEAE3; margin: 8px 0;"></div>

            <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 1.2rem; font-weight: 900; color: #3A2518;">
                <span>Total Amount</span>
                <span style="color: #5C3E21;">PKR {{ number_format($finalTotal) }}</span>
            </div>
        </div>
    </div>

    <div style="border-top: 1px dotted #E2D7C7; margin: 20px 0 24px 0;"></div>

    <!-- 6. TERMS & CONDITIONS AND AUTHORIZED SIGNATURE -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 30px;">
        <!-- Left: Terms & Conditions -->
        <div style="flex: 1; max-width: 520px;">
            <h4 style="font-size: 0.88rem; font-weight: 800; color: #3A2518; margin: 0 0 6px 0;">Terms & Conditions</h4>
            <ul style="font-size: 0.78rem; color: #66594E; margin: 0; padding-left: 16px; line-height: 1.5;">
                @foreach($termsList as $term)
                    <li>{{ $term }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Right: Signature -->
        <div style="text-align: center; min-width: 180px;">
            @if($signatureImage)
                <img src="{{ $signatureImage }}" alt="Authorized Signature" style="height: 48px; width: auto; object-fit: contain; margin-bottom: 4px;">
            @else
                <div style="font-family: 'Great Vibes', cursive, 'Playball', sans-serif; font-size: 1.6rem; color: #5C3E21; margin-bottom: 2px;">
                    Ghousia Traders
                </div>
            @endif
            <div style="border-top: 1px solid #D5C8B8; width: 140px; margin: 4px auto;"></div>
            <div style="font-size: 0.78rem; font-weight: 800; color: #3A2518; margin-top: 2px;">Authorized Signature</div>
            <div style="font-size: 0.72rem; color: #7A6E65;">Ghousia Traders</div>
        </div>
    </div>

</div>
