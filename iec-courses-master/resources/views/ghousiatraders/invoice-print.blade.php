<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }} — Ghousia Traders</title>
    <base href="{{ url('/') }}/">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lora:ital,wght@0,400..700;1,400..700&family=Pinyon+Script&family=Playball&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        
        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            background: #ffffff !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .invoice-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 12mm;
            box-sizing: border-box;
            background: #ffffff;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        @media print {
            body, html {
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }

            .invoice-actions,
            .storefront-header,
            .storefront-footer,
            header, footer, nav {
                display: none !important;
            }

            .invoice-sheet {
                width: 100% !important;
                max-width: 210mm !important;
                min-height: 297mm !important;
                padding: 12mm !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body>
    @include('ghousiatraders.partials.invoice-card')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
