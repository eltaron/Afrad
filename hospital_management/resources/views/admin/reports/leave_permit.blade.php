<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.leave_permit') }} - {{ $leaveData['personnel_name'] ?? '' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Ensure Arabic characters render correctly */
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: right; /* RTL */
            font-size: 14px;
        }
        .content-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            width: 30%;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-around; /* Distribute space for signatures */
        }
        .signature-block {
            text-align: center;
            width: 40%; /* Each signature block takes about 40% */
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        @media print {
            body { margin: 0; background-color: #fff; }
            .container { border: none; box-shadow: none; width: 100%; margin: 0 auto; padding: 10px 0;}
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('app.leave_permit') }}</h1>
        </div>

        <table class="content-table">
            <tr>
                <th>{{ __('app.personnel') }}</th>
                <td>{{ $leaveData['personnel_name'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('validation.attributes.military_id') }}</th>
                <td>{{ $leaveData['military_id'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('validation.attributes.rank') }}</th>
                <td>{{ $leaveData['rank'] ?? 'N/A' }}</td>
            </tr>
             <tr>
                <th>{{ __('validation.attributes.job_title') }}</th>
                <td>{{ $leaveData['job_title'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('app.hospital_force') }}</th>
                <td>{{ $leaveData['hospital_force'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('app.department') }}</th>
                <td>{{ $leaveData['current_department'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('app.leave_type') }}</th>
                <td>{{ $leaveData['leave_type'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('validation.attributes.start_date') }}</th>
                <td>{{ $leaveData['start_date'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('validation.attributes.end_date') }}</th>
                <td>{{ $leaveData['end_date'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('validation.attributes.days_taken') }}</th>
                <td>{{ $leaveData['days_taken'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('validation.attributes.approved_by') }}</th>
                <td>{{ $leaveData['approved_by'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ __('app.requested_on') }}</th>
                <td>{{ $leaveData['request_date'] ?? 'N/A' }}</td>
            </tr>
            @if(!empty($leaveData['notes']))
            <tr>
                <th>{{ __('validation.attributes.notes') }}</th>
                <td>{{ $leaveData['notes'] }}</td>
            </tr>
            @endif
        </table>

        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p>{{ __('app.officer_signature') }}</p> {{-- Add to lang --}}
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <p>{{ __('app.commander_signature') }}</p> {{-- Add to lang --}}
            </div>
        </div>

        <div class="footer">
            {{ __('app.generated_on') }}: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }} {{-- Add to lang --}}
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print();" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">{{ __('app.print') }}</button> {{-- Add to lang --}}
    </div>
</body>
</html>
