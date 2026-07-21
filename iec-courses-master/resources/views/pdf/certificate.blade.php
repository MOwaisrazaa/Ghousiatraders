<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
            size: 297mm 210mm;
        }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            overflow: hidden;
        }
        
        /* Main Border (Blue) */
        .border-outer {
            position: fixed;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 4px solid #1a5f7a;
            z-index: 10;
        }
        
        /* Inner Border (Gold) */
        .border-inner {
            position: fixed;
            top: 13mm;
            left: 13mm;
            right: 13mm;
            bottom: 13mm;
            border: 2px solid #cfaa5e;
            z-index: 10;
            background: transparent;
        }
        
        /* Main Content */
        .content-layer {
            position: fixed;
            top: 20mm;
            left: 15mm;
            right: 15mm;
            z-index: 20;
            text-align: center;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            opacity: 0.08;
            z-index: 5;
        }

        .header-logo {
            max-width: 350px;
            height: auto;
            margin-bottom: 5mm;
        }

        .title {
            font-size: 32pt;
            font-weight: bold;
            color: #1a5f7a;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5mm;
            font-family: 'Helvetica', sans-serif;
        }

        .subtitle {
            font-size: 14pt;
            color: #555;
            margin-bottom: 5mm;
            font-style: italic;
        }

        .student-name {
            font-size: 28pt;
            font-weight: bold;
            color: #000;
            margin: 2mm auto 5mm auto;
            border-bottom: 1px solid #cfaa5e;
            display: inline-block;
            padding: 0 20mm 2mm 20mm;
            font-family: 'Helvetica', sans-serif;
        }

        .achievement-text {
            font-size: 12pt;
            color: #444;
            margin: 2mm 0;
            line-height: 1.4;
        }

        .course-name {
            font-size: 22pt;
            font-weight: bold;
            color: #1a5f7a;
            margin: 5mm 0;
            padding: 0 10mm;
        }

        /* Footer (Signatures) */
        .footer-table {
            position: fixed;
            bottom: 40mm;
            left: 25mm;
            right: 25mm;
            width: 89%; /* FORCE full width to spread columns */
            border-collapse: collapse;
            z-index: 25;
        }

        .footer-col-left {
            width: 35%;
            text-align: left;
            vertical-align: bottom;
        }
        
        .footer-col-center {
            width: 30%;
            text-align: center;
            vertical-align: bottom;
        }
        
        .footer-col-right {
            width: 35%;
            text-align: right;
            vertical-align: bottom;
        }

        .sig-line-left {
            border-top: 1px solid #333;
            width: 55mm;
            margin: 0 auto 2mm 0; /* Align left */
        }
        
        .sig-line-right {
            border-top: 1px solid #333;
            width: 55mm;
            margin: 0 0 2mm auto; /* Align right */
        }

        .sig-spacer {
            height: 6mm;
        }

        .sig-label {
            font-size: 10pt;
            color: #666;
            font-weight: bold;
        }

        .seal {
            width: 70px;
            opacity: 0.9;
        }

        /* Certificate ID (Outside Border) */
        .cert-number {
            position: fixed;
            bottom: 4mm;
            right: 10mm;
            font-size: 8pt;
            color: #999;
            font-family: 'Helvetica', sans-serif;
            text-align: right;
            z-index: 30;
        }

        @media (max-width: 768px) {
            .footer-table {
                left: 22mm;
                right: 22mm;
                width: auto;
            }

            .sig-line-left,
            .sig-line-right {
                width: 40mm;
            }

            .footer-col-left {
                padding-left: 2mm;
            }

            .footer-col-right {
                padding-right: 6mm;
            }
        }
    </style>
</head>
<body>
    @php
        $usePublicPath = $usePublicPath ?? false;

        $logoPath = $logoPath ?? ($usePublicPath
            ? public_path('assets/img/logos/iec-Logo.png')
            : asset('assets/img/logos/iec-Logo.png'));
        $headerLogoPath = $headerLogoPath ?? ($usePublicPath
            ? public_path('assets/img/logos/headerislamiceconomiccenter.png')
            : asset('assets/img/logos/headerislamiceconomiccenter.png'));
    @endphp
    <!-- Borders -->
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <!-- Watermark -->
    <img src="{{ $logoPath }}" class="watermark" alt="Watermark">

    <!-- Content -->
    <div class="content-layer">
        <div>
            <img src="{{ $headerLogoPath }}" class="header-logo" alt="Islamic Economic Center">
        </div>

        <div class="title">Certificate of Completion</div>

        <div class="subtitle">This is to certify that</div>

        <div class="student-name">{{ $user->name }}</div>

        <div class="achievement-text">
            Has successfully completed the course requirements
            @if(isset($hasQuizzes) && $hasQuizzes)
                and passed the required assessment
            @endif
            for
        </div>

        <div class="course-name">
            @if(isset($courseName))
                {{ $courseName }}
            @else
                Course Completion
            @endif
        </div>
    </div>

    <!-- Footer (Signatures) -->
    <table class="footer-table">
        <tr>
            <td class="footer-col-left">
                <div style="margin-bottom: 2mm; font-size: 12pt;">{{ isset($passingDate) ? $passingDate : now()->format('F d, Y') }}</div>
                <div class="sig-line-left"></div>
                <div class="sig-label">Date Issued</div>
            </td>
            <td class="footer-col-center">
                <img src="{{ $logoPath }}" class="seal" alt="Seal">
            </td>
            <td class="footer-col-right">
                <div class="sig-spacer"></div>
                <div class="sig-line-right"></div>
                <div class="sig-label">Director's Signature</div>
            </td>
        </tr>
    </table>

    <!-- Certificate ID (Outside Border) -->
    <div class="cert-number">
        Certificate ID: {{ $certificateNumber }}
    </div>
</body>
</html>
