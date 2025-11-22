<x-app-layout>
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px 35px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1.5px solid #f3f4f6;
        }

        h3 {
            font-size: 19px;
            font-weight: 700;
            color: #1e293b;
            border-bottom: 3px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }

        thead {
            background: #f9fafb;
        }

        th {
            text-align: left;
            padding: 14px;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            color: #475569;
        }

        tr:hover td {
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
        }

        .btn-return {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        }

        .btn-return:hover {
            background-color: #2563eb;
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: #f87171;
            color: white;
            box-shadow: 0 2px 6px rgba(248, 113, 113, 0.3);
        }

        .btn-delete:hover {
            background-color: #ef4444;
            transform: scale(1.05);
        }

        .delete-all-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(248, 113, 113, 0.3);
        }

        .delete-all-btn:hover {
            background-color: #dc2626;
            transform: scale(1.05);
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
        }

        .status.borrowed {
            color: #dc2626;
        }

        .status.returned {
            color: #16a34a;
        }

        .success-msg {
            background-color: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
            border-left: 5px solid #10b981;
        }

        .section {
            margin-top: 50px;
        }

        .search-box {
            margin-bottom: 15px;
        }

        .search-box input {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            width: 200px;
        }

        .search-box button {
            margin-left: 5px;
            padding: 8px 12px;
            border-radius: 6px;
            background-color: #3b82f6;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #2563eb;
        }

        .pagination {
            margin-top: 15px;
        }

        /* Pagination style custom */
        .pagination .page-item .page-link {
            color: #fff;
            background-color: #3b82f6;
            border: 1px solid #3b82f6;
            border-radius: 6px;
        }

        .pagination .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .pagination .page-item .page-link:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }
    </style>

    <div class="container">
        @if (session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif

        <!-- 🟢 Borrowed Books -->
        <h3>Buku Yang Dipinjam</h3>

        <form method="GET" action="{{ route('borrow_return') }}" class="search-box">
            <input type="text" name="active_search" value="{{ request('active_search') }}" placeholder="Cari nama pelajar...">
            <button type="submit">Cari</button>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Bil</th>
                        <th>Tajuk Buku</th>
                        <th>Pelajar</th>
                        <th>Kelas</th>
                        <th>Tarikh Pinjam</th>
                        <th>Tarikh Pulangkan</th>
                        <th>Denda (RM)</th>
                        <th>Status</th>
                        <th style="text-align:center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activeBorrows as $borrow)
                        @php
                            $borrowedDate = \Carbon\Carbon::parse($borrow->borrow_date);
                            $returnDate = \Carbon\Carbon::parse($borrow->return_date);
                            $today = \Carbon\Carbon::now();
                            $overdueDays = $today->greaterThan($returnDate) ? $returnDate->diffInDays($today) : 0;
                            $fine = round($overdueDays * 0.20, 2); // denda tepat RM0.20/hari
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($activeBorrows->currentPage()-1)*$activeBorrows->perPage() }}</td>
                            <td>{{ $borrow->book->title ?? '-' }}</td>
                            <td>{{ $borrow->student->name ?? 'Unknown' }}</td>
                            <td>{{ $borrow->student->class ?? '-' }}</td>
                            <td>{{ $borrowedDate->format('d M Y') }}</td>
                            <td>{{ $returnDate->format('d M Y') }}</td>
                            <td class="{{ $fine > 0 ? 'fine' : 'no-fine' }}">{{ number_format($fine, 2) }}</td>
                            <td class="status borrowed">Dipinjam</td>
                            <td style="text-align:center;">
                                <form action="{{ route('borrow_return_mark', $borrow->id) }}" method="POST" onsubmit="return confirm('Tandai buku ini sebagai dikembalikan?')">
                                    @csrf
                                    <button type="submit" class="btn btn-return">Pulangkan Buku</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" style="text-align:center; color:#6b7280;">Tiada Laporan Peminjam.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $activeBorrows->appends(['active_search' => request('active_search'), 'returned_search' => request('returned_search')])->links() }}
        </div>

        <!-- 🔵 Returned Books -->
        <div class="section">
            <h3>
                Buku Yang Dipulangkan
                @if($returnedBorrows->count())
                    <form action="{{ route('returned_delete_all') }}" method="POST" onsubmit="return confirm('Padam semua rekod yang dikembalikan?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-all-btn">Padam Semua</button>
                    </form>
                @endif
            </h3>

            <form method="GET" action="{{ route('borrow_return') }}" class="search-box">
                <input type="text" name="returned_search" value="{{ request('returned_search') }}" placeholder="Cari nama pelajar...">
                <button type="submit">Cari</button>
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Bil</th>
                            <th>Tajuk Buku</th>
                            <th>Pelajar</th>
                            <th>Kelas</th>
                            <th>Tarikh Pinjam</th>
                            <th>Tarikh Pulangkan</th>
                            <th>Denda (RM)</th>
                            <th>Status</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnedBorrows as $borrow)
                            @php
                                $borrowedDate = \Carbon\Carbon::parse($borrow->borrow_date);
                                $returnedDate = \Carbon\Carbon::parse($borrow->returned_at);
                                $returnDate = \Carbon\Carbon::parse($borrow->return_date);
                                $overdueDays = $returnedDate->greaterThan($returnDate) ? $returnDate->diffInDays($returnedDate) : 0;
                                $fine = round($overdueDays * 0.20, 2); // denda tepat RM0.20/hari
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($returnedBorrows->currentPage()-1)*$returnedBorrows->perPage() }}</td>
                                <td>{{ $borrow->book->title ?? '-' }}</td>
                                <td>{{ $borrow->student->name ?? '-' }}</td>
                                <td>{{ $borrow->student->class ?? '-' }}</td>
                                <td>{{ $borrowedDate->format('d M Y') }}</td>
                                <td>{{ $borrow->returned_at ? $borrow->returned_at->format('d M Y') : '-' }}</td>
                                <td class="{{ $fine > 0 ? 'fine' : 'no-fine' }}">{{ number_format($fine, 2) }}</td>
                                <td class="status returned">Dipulangkan</td>
                                <td style="text-align:center;">
                                    <form action="{{ route('returned_delete_one', $borrow->id) }}" method="POST" onsubmit="return confirm('Padamkan rekod yang dikembalikan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Padam</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" style="text-align:center; color:#6b7280;">Tiada Laporan Pemulangan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $returnedBorrows->appends(['active_search' => request('active_search'), 'returned_search' => request('returned_search')])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
