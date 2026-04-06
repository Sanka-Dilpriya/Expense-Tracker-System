<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 24px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 680px; margin: auto; background: #ffffff; border-radius: 16px; overflow: hidden;">
        <tr style="background: #0d6efd; color: white;">
            <td style="padding: 24px;">
                <h1 style="margin: 0; font-size: 24px;">Monthly Finance Report</h1>
                <p style="margin: 8px 0 0;">{{ $reportData['month'] }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="margin: 0 0 16px;">Here is your expense and income summary for the selected period.</p>
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e9ecef;"><strong>Total Income</strong></td>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e9ecef; text-align: right;">LKR {{ number_format($reportData['total_income'], 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e9ecef;"><strong>Total Expense</strong></td>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e9ecef; text-align: right;">LKR {{ number_format($reportData['total_expenses'], 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e9ecef;"><strong>Net Balance</strong></td>
                        <td style="padding: 12px 0; border-bottom: 1px solid #e9ecef; text-align: right;">LKR {{ number_format($reportData['net_balance'], 2) }}</td>
                    </tr>
                </table>
                <p style="margin: 24px 0 0; color: #6c757d;">Thank you for using ExpenseTrack Pro.</p>
            </td>
        </tr>
    </table>
</body>
</html>
