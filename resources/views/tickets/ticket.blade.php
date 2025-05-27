<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>A6 Event Ticket</title>
    <style>
        /* Print-specific styles for A6 paper */
        @page {
            size: 148mm 105mm;
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            width: 148mm;
            height: 105mm;
            overflow: hidden;
        }

        body {
            -webkit-print-color-adjust: exact;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            background: white;
            line-height: 1.2;
            width: 148mm;
            height: 105mm;
            margin: 0;
            padding: 3mm;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ticket-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 6px;
            padding: 2px;
            width: 142mm;
            height: 99mm;
            box-sizing: border-box;
            overflow: hidden;
        }

        .ticket-inner {
            background: white;
            border-radius: 4px;
            padding: 8px;
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ticket-inner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #e0e0e0;
            z-index: 2;
            position: relative;
        }

        .event-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.1;
        }

        .event-details {
            font-size: 9px;
            color: #7f8c8d;
            margin: 0;
            font-weight: 500;
        }

        .qr-section {
            text-align: center;
            margin: 6px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 2;
            position: relative;
        }

        .qr-instruction {
            font-size: 8px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .qr-code {
            border: 1px solid #f8f9fa;
            border-radius: 3px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        /* Print styles */
        /* @media print {
            html, body {
                width: 148mm !important;
                height: 105mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                overflow: hidden !important;
            }

            body {
                padding: 3mm !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
            }

            .ticket-container {
                box-shadow: none !important;
                page-break-inside: avoid !important;
                width: 142mm !important;
                height: 99mm !important;
                overflow: hidden !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: 148mm 105mm !important;
                margin: 0 !important;
            }
        } */

        /* Compact layout adjustments */
        .compact-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
            margin-bottom: 6px;
            z-index: 2;
            position: relative;
        }

        .compact-detail {
            background: #f8f9fa;
            padding: 3px 4px;
            border-radius: 3px;
            text-align: center;
        }

        .compact-label {
            font-size: 7px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
        }

        .compact-value {
            font-size: 9px;
            color: #2c3e50;
            font-weight: 600;
        }

        .price-detail {
            grid-column: 1 / -1;
            background: #d4edda;
        }

        .price-detail .compact-value {
            color: #155724;
            font-size: 12px;
            font-weight: bold;
        }

        .footer-message {
            text-align: center;
            padding: 4px;
            background: #e3f2fd;
            border-radius: 3px;
            border-left: 2px solid #2196f3;
            z-index: 2;
            position: relative;
        }

        .footer-text {
            color: #1565c0;
            font-weight: 500;
            margin: 0;
            font-size: 7px;
            line-height: 1.2;
        }

        .ticket-reference {
            font-family: 'Courier New', monospace;
            background: #ffffff;
            padding: 1px 3px;
            border-radius: 2px;
            border: 1px solid #dee2e6;
            font-size: 8px;
        }

        /* Left side decorative pattern */
        .left-pattern {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 15px;
            background:
                repeating-linear-gradient(
                    45deg,
                    rgba(255,255,255,0.1) 0px,
                    rgba(255,255,255,0.1) 1px,
                    transparent 1px,
                    transparent 3px
                ),
                linear-gradient(180deg,
                    rgba(102,126,234,0.8) 0%,
                    rgba(118,75,162,0.8) 25%,
                    rgba(240,147,251,0.8) 50%,
                    rgba(245,87,108,0.8) 75%,
                    rgba(79,172,254,0.8) 100%
                );
            border-radius: 4px 0 0 4px;
            z-index: 0;
        }

        .left-pattern::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 10px;
            bottom: 10px;
            width: 1px;
            background: repeating-linear-gradient(
                to bottom,
                rgba(255,255,255,0.6) 0px,
                rgba(255,255,255,0.6) 2px,
                transparent 2px,
                transparent 4px
            );
            transform: translateX(-50%);
        }

        /* Right side decorative pattern */
        .right-pattern {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 15px;
            background:
                repeating-linear-gradient(
                    -45deg,
                    rgba(255,255,255,0.1) 0px,
                    rgba(255,255,255,0.1) 1px,
                    transparent 1px,
                    transparent 3px
                ),
                linear-gradient(180deg,
                    rgba(79,172,254,0.8) 0%,
                    rgba(245,87,108,0.8) 25%,
                    rgba(240,147,251,0.8) 50%,
                    rgba(118,75,162,0.8) 75%,
                    rgba(102,126,234,0.8) 100%
                );
            border-radius: 0 4px 4px 0;
            z-index: 0;
        }

        .right-pattern::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 10px;
            bottom: 10px;
            width: 1px;
            background: repeating-linear-gradient(
                to bottom,
                rgba(255,255,255,0.6) 0px,
                rgba(255,255,255,0.6) 2px,
                transparent 2px,
                transparent 4px
            );
            transform: translateX(-50%);
        }

        /* Decorative corner elements */
        .corner-decoration {
            position: absolute;
            width: 12px;
            height: 12px;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 30%, transparent 70%);
            z-index: 1;
        }

        .corner-decoration.top-left {
            top: 18px;
            left: 18px;
            border-radius: 0 0 12px 0;
        }

        .corner-decoration.top-right {
            top: 18px;
            right: 18px;
            border-radius: 0 0 0 12px;
        }

        .corner-decoration.bottom-left {
            bottom: 18px;
            left: 18px;
            border-radius: 0 12px 0 0;
        }

        .corner-decoration.bottom-right {
            bottom: 18px;
            right: 18px;
            border-radius: 12px 0 0 0;
        }

        /* Adjust main content to accommodate patterns */
        .header,
        .qr-section,
        .compact-details,
        .footer-message {
            margin-left: 18px;
            margin-right: 18px;
        }

        /* Add subtle pattern overlay to main content area */
        .content-overlay {
            position: absolute;
            top: 2px;
            left: 18px;
            right: 18px;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(102,126,234,0.02) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245,87,108,0.02) 0%, transparent 50%);
            pointer-events: none;
            border-radius: 4px;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-inner">
            <div class="left-pattern"></div>
            <div class="right-pattern"></div>
            <div class="content-overlay"></div>

            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>

            <div class="header">
                <h1 class="event-title">{{ $ticket['event_name'] }}</h1>
                <p class="event-details">{{ \Carbon\Carbon::parse($ticket['date'])->toFormattedDateString() }}<br>{{ $ticket['location'] }}</p>
            </div>

            <div class="qr-section">
                <p class="qr-instruction">Scan at Entrance</p>
                <img src="data:image/png;base64, {!! $qrCode !!}" alt="QR Code" class="qr-code" width="60" height="60">
            </div>

            <div class="compact-details">
                <div class="compact-detail">
                    <span class="compact-label">Type</span>
                    <span class="compact-value">{{ $ticket['type'] }}</span>
                </div>

                <div class="compact-detail">
                    <span class="compact-label">Ref</span>
                    <span class="compact-value ticket-reference">{{ $ticket['ticket_reference'] }}</span>
                </div>

                <div class="compact-detail price-detail">
                    <span class="compact-label">Price</span>
                    <span class="compact-value">{{ 'UGX ' . number_format($ticket['price']) }}</span>
                </div>
            </div>

            <div class="footer-message">
                <p class="footer-text">Present at entrance • Keep safe</p>
            </div>
        </div>
    </div>
</body>
</html>