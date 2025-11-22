<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b;">
            🌟 LIMS Dashboard
        </h2>
    </x-slot>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            border: 1.5px solid #f3f4f6;
        }

        h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 10px;
        }

        th, td {
            padding: 14px 18px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e5e7eb;
        }

        tbody tr {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease-in-out;
        }

        tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            background: #f1f5f9;
        }

        tbody td {
            border-top: 1px solid #f1f5f9;
        }

        .fine {
            color: #dc2626;
            font-weight: 700;
        }

        .no-fine {
            color: #16a34a;
            font-weight: 700;
        }

        .status {
            font-weight: 700;
            color: #2563eb;
        }

        /* Pagination style sama dengan page buku */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 7px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            font-weight: 500;
            color: #1f2937;
            transition: all 0.2s ease-in-out;
        }
        .pagination a:hover {
            background: #f3f4f6;
        }
        .pagination .active span {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            th, td {
                font-size: 13px;
                padding: 10px 12px;
            }
        }
    </style>

    <div class="container">
        <h3>📋 Laporan Peminjam Aktif</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Bil</th>
                        <th>Tajuk Buku</th>
                        <th>Pelajar</th>
                        <th>Kelas</th>
                        <th>Tarikh Pinjam</th>
                        <th>Tarikh Pulang</th>
                        <th>Denda (RM)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activeBorrows as $borrow)
                        @php
                            $borrowedDate = \Carbon\Carbon::parse($borrow->borrow_date);
                            $returnDate = \Carbon\Carbon::parse($borrow->return_date);
                            $today = \Carbon\Carbon::now();
                            $overdueDays = $today->greaterThan($returnDate) ? $returnDate->diffInDays($today) : 0;
                            $fine = $overdueDays * 0.20;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($activeBorrows->currentPage() - 1) * $activeBorrows->perPage() }}</td>
                            <td>{{ $borrow->book->title ?? '-' }}</td>
                            <td>{{ $borrow->student->name ?? 'Unknown' }}</td>
                            <td>{{ $borrow->student->class ?? '-' }}</td>
                            <td>{{ $borrowedDate->format('d M Y') }}</td>
                            <td>{{ $returnDate->format('d M Y') }}</td>
                            <td class="{{ $fine > 0 ? 'fine' : 'no-fine' }}">{{ number_format($fine, 2) }}</td>
                            <td class="status">Borrowed</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; color:#6b7280; font-style:italic;">
                                Tiada rekod pinjaman aktif ditemui.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination sama dengan buku -->
        <div class="pagination">
            {{-- Previous Page Link --}}
            @if ($activeBorrows->onFirstPage())
                <span class="disabled">&laquo;</span>
            @else
                <a href="{{ $activeBorrows->previousPageUrl() }}">&laquo;</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($activeBorrows->getUrlRange(1, $activeBorrows->lastPage()) as $page => $url)
                @if ($page == $activeBorrows->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($activeBorrows->hasMorePages())
                <a href="{{ $activeBorrows->nextPageUrl() }}">&raquo;</a>
            @else
                <span class="disabled">&raquo;</span>
            @endif
        </div>
    </div>
</x-app-layout>
