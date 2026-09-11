<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Status Updated</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        table, td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
        .employee-name,
        .requested-by-name {
            font-weight: bold;
            color: #333333;
        }
        .contact-link {
             color: #007bff;
             text-decoration: none;
        }
         .contact-link:hover {
             text-decoration: underline;
         }

        @media screen and (max-width: 600px) {
             .email-container {
                 width: 100% !important;
             }
             .content-cell {
                 padding: 20px !important;
             }
             .body-text {
                font-size: 12px !important;
             }
        }
    </style>
    </head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f7f7f7;">

    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7;">
        <tr>
            <td align="center" style="padding: 40px 20px;"> <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: left;">
                    <tr>
                        <td class="content-cell" style="padding: 30px; text-align: left;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                <tr>
                                    <td style="text-align: left; padding-bottom: 10px;">
                                        <img src="{{ $message->embed(public_path() . '/images/logo/miescor_light_mode.png') }}" alt="MIESCOR Logo"
                                            style="height: auto; max-height: 30px; display: block; outline: none; text-decoration: none; border: 0;">
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                <tr>
                                     <td style="padding: 20px 0;"> <div style="border-top: 1px solid #e0e0e0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</div>
                                     </td>
                                </tr>
                             </table>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                <tr>
                                    <td style="text-align: left;">
                                        <p style="font-size: 16px; color: #333333; margin: 0 0 15px 0; padding: 0; line-height: 1.5;">
                                            Dear <span class="employee-name">{{ $notifiable->name ?? 'User' }}</span>,
                                        </p>

                                        <p style="font-size: 16px; color: #333333; margin: 0 0 15px 0; padding: 0; line-height: 1.5;">
                                            The status for the contract Ref: {{ $contract->reference_no }} has been changed.
                                        </p>

                                        <p style="font-size: 16px; color: #333333; margin: 0 0 15px 0; padding: 0; line-height: 1.5;">
                                            New Status: <strong>{{ $contract->status }}</strong>
                                        </p>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                            style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 10px 0 0 0;">
                                            <tr>
                                                <td align="left"
                                                    style="border-radius: 4px; background-color: #f97316;">
                                                    <a href="{{ url('/services/login') }}"
                                                        class="button"
                                                        style="display: inline-block; background-color: #f97316; color: #ffffff; padding: 12px 24px; border-radius: 4px; font-size: 16px; font-weight: bold; text-decoration: none;">
                                                        View Contract
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="font-size: 16px; color: #333333; margin-top: 10px; padding: 0; line-height: 1.5;">
                                            Thank you for using our application!
                                        </p>

                                        <p class="body-text" style="font-size: 14px; color: #666666; margin: 25px 0 0 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.5;">
                                            If you encounter any technical issues with this notification or the employee portal, please contact our
                                            <a href="mailto:ict.servicedesk@miescor.ph" class="contact-link">ICT Service Desk</a>.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
