<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>Ticket Confirmation</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Reset styles for email clients */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        /* Main styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3px;
            border-radius: 12px;
        }

        .email-content {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .header-gradient {
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .email-body {
            padding: 30px;
        }

        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #667eea;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .detail-row {
            display: table-row;
        }

        .detail-label {
            display: table-cell;
            padding: 8px 15px 8px 0;
            font-weight: bold;
            color: #495057;
            font-size: 14px;
            vertical-align: top;
            width: 30%;
        }

        .detail-value {
            display: table-cell;
            padding: 8px 0;
            color: #2c3e50;
            font-size: 14px;
            vertical-align: top;
        }

        .ticket-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
        }

        .ticket-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #f093fb, #f5576c);
            border-radius: 6px 6px 0 0;
        }

        .ticket-type {
            font-weight: bold;
            color: #667eea;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .ticket-details {
            font-size: 14px;
            color: #6c757d;
        }

        .price-highlight {
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
        }

        .payment-section {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left-color: #28a745;
        }

        .total-amount {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 15px 0;
        }

        .total-amount .amount {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .total-amount .label {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .footer-message {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }

        .footer-message p {
            margin: 0;
            color: #1565c0;
            font-size: 16px;
        }

        .app-footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        .app-footer p {
            margin: 5px 0;
        }

        /* Mobile responsiveness */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
            }

            .email-body {
                padding: 20px !important;
            }

            .email-header {
                padding: 20px !important;
            }

            .email-header h1 {
                font-size: 24px !important;
            }

            .section {
                padding: 15px !important;
            }

            .detail-label,
            .detail-value {
                display: block !important;
                width: 100% !important;
                padding: 5px 0 !important;
            }

            .detail-label {
                font-weight: bold !important;
                color: #667eea !important;
            }
        }

        /* Outlook specific fixes */
        .outlook-spacer {
            mso-line-height-rule: exactly;
            line-height: 20px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding: 20px 0;">
                <div class="email-container">
                    <div class="email-wrapper">
                        <div class="email-content">
                            <div class="header-gradient"></div>

                            <div class="email-header">
                                <h1>🎫 Ticket Confirmed!</h1>
                                <p>Your purchase was successful</p>
                            </div>

                            <div class="email-body">
                                <div class="greeting">
                                    <strong>Hey there! 👋</strong>
                                </div>

                                <p style="color: #6c757d; font-size: 16px; margin-bottom: 30px;">
                                    Thank you for your purchase! Here are the details of your ticket order:
                                </p>

                                <!-- Event Details Section -->
                                <div class="section">
                                    <h3 class="section-title">🎪 Event Details</h3>
                                    <div class="detail-grid">
                                        <div class="detail-row">
                                            <div class="detail-label">Event Name:</div>
                                            <div class="detail-value"><strong>{{ $data['event']->name }}</strong></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Date:</div>
                                            <div class="detail-value">{{ \Carbon\Carbon::parse($data['event']->date)->toFormattedDateString() }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Time:</div>
                                            <div class="detail-value">{{ $data['event']->start_time }} - {{ $data['event']->end_time }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Location:</div>
                                            <div class="detail-value">{{ $data['event']->location }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tickets Section -->
                                <div class="section">
                                    <h3 class="section-title">🎟️ Your Tickets</h3>
                                    @foreach ($data['tickets'] as $ticket)
                                        <div class="ticket-item">
                                            <div class="ticket-type">{{ $ticket['type'] }}</div>
                                            <div class="ticket-details">
                                                <strong>Ticket #:</strong> {{ $ticket['number'] }}<br>
                                                <strong>Price:</strong>
                                                <span class="price-highlight">
                                                    {{ $ticket['is_free'] ? 'Free' : 'UGX ' . number_format($ticket['price']) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Payment Details Section -->
                                <div class="section payment-section">
                                    <h3 class="section-title">💳 Payment Details</h3>

                                    <div class="total-amount">
                                        <p class="label">Total Amount Paid</p>
                                        <p class="amount">UGX {{ number_format($data['payment']->amount_paid) }}</p>
                                    </div>

                                    <div class="detail-grid">
                                        <div class="detail-row">
                                            <div class="detail-label">Reference:</div>
                                            <div class="detail-value" style="font-family: 'Courier New', monospace; background: #f8f9fa; padding: 4px 8px; border-radius: 4px;">{{ $data['payment']->system_reference_number }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Phone:</div>
                                            <div class="detail-value">{{ $data['payment']->msisdn }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Description:</div>
                                            <div class="detail-value">{{ $data['payment']->narration }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="footer-message">
                                    <p><strong>📧 Need Help?</strong></p>
                                    <p style="margin-top: 10px; font-size: 14px;">
                                        If you have any questions, simply reply to this email or contact our support team. We're here to help!
                                    </p>
                                </div>
                            </div>

                            <div class="app-footer">
                                <p><strong>Thank you for choosing {{ config('app.name') }}!</strong></p>
                                <p style="opacity: 0.8;">Your trusted event ticketing partner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>