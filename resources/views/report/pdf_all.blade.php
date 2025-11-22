<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Library Reports Summary</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #111827;
        }
        h2, h3 {
            text-align: center;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .summary {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 6px;
            background: #f9fafb;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 30px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <h2>📚 Library Issue Report Summary</h2>
    <p style="text-align:center;">Generated on {{ now()->format('d M Y, h:i A') }}</p>

    <div class="summary">
        <h3>📊 Summary</h3>
        <p><strong>Total Damaged Books:</strong> {{ $totalDamaged }}</p>
        <p><strong>Total Lost Books:</strong> {{ $totalLost }}</p>
        <p><strong>Total Fined Reports:</strong> {{ $totalFined }}</p>
        <p><strong>Total Reports:</strong> {{ $reports->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Issue Type</th>
                <th>Description</th>
                <th>Reported By</th>
                <th>Date Reported</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->book_title }}</td>
                    <td>{{ $report->issue_type }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->reporter->name ?? 'N/A' }}</td>
                    <td>{{ $report->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated automatically by LIMS (Library Information Management System)</p>
        <p>© {{ date('Y') }} School Library Department</p>
    </div>
</body>
</html>
