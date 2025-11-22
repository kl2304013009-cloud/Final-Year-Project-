<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight: 700; font-size: 1.6rem; color: #2d3748;">
            🧾 LIMS – Reports
        </h2>
    </x-slot>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: "Poppins", Arial, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* ✨ Buttons */
        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .btn-add {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-add:hover {
            background-color: #4338ca;
            transform: scale(1.05);
        }

        .btn-edit {
            background-color: #facc15;
            color: #333;
            box-shadow: 0 3px 8px rgba(250, 204, 21, 0.3);
        }

        .btn-edit:hover {
            background-color: #eab308;
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
            box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background-color: #dc2626;
            transform: scale(1.05);
        }

        /* ✅ Success Alert */
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 5px solid #10b981;
            padding: 12px 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
        }

        /* 📦 Report Card */
        .report-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 25px 30px;
            border: 2px solid #f0f0f0;
        }

        /* 📋 Table */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px 14px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
        }

        td {
            background: #ffffff;
        }

        tr:hover td {
            background: #f9fafb;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .empty-row {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }
    </style>

    <div class="container">
        {{-- 🔝 Add Button --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('report.create') }}" class="btn btn-add">Laporan Baru</a>
            <a href="{{ route('report.downloadAll') }}" class="btn btn-edit">Muat turun Semua Laporan (PDF)</a>
        </div>
        
        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- 🧾 Report Table --}}
        <div class="report-card">
            @if($reports->isEmpty())
                <p class="empty-row">Tiada laporan ditemui.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Tajuk Buku</th>
                            <th>Isu</th>
                            <th>Penerangan</th>
                            <th>Dilaporkan Oleh</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->book_title }}</td>
                                <td>{{ $report->issue_type }}</td>
                                <td>{{ $report->description }}</td>
                                <td>{{ $report->reporter->name }}</td>
                                <td style="text-align:center;">
                                    <div class="actions">
                                        <a href="{{ route('report.edit', $report->id) }}" class="btn btn-edit">Ubah Data</a>
                                        <form action="{{ route('report.destroy', $report) }}" method="POST" onsubmit="return confirm('Delete this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete">Padam</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
