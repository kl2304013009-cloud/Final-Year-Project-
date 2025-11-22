<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700; font-size:1.6rem; color:#1e3a8a;">📚 LIMS – Student Management</h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            padding: 25px;
            animation: fadeIn 0.6s ease-out;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            font-size: 14px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .btn-add {
            background: #3b82f6;
            color: white;
        }

        .btn-add:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-edit {
            background: #facc15;
            color: #1f2937;
        }

        .btn-edit:hover {
            background: #eab308;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .btn-delete-all {
            background: #b91c1c;
            color: white;
        }

        .btn-delete-all:hover {
            background: #991b1b;
            transform: translateY(-2px);
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 6px solid #22c55e;
            padding: 12px 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-weight: 500;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: center;
        }

        .search-bar input,
        .search-bar select {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            background-color: #f9fafb;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-bar input:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* Remove dropdown arrow */
        .search-bar select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
        }

        .search-bar button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
        }

        .search-bar button:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        /* Table Design */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 10px;
        }

        th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
            text-align: left;
            padding: 14px;
            font-size: 14px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 14px;
        }

        tr:hover td {
            background: #f0f9ff;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .pagination {
            margin-top: 25px;
        }

        .no-results {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin-top: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .top-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .search-bar {
                width: 100%;
                justify-content: flex-start;
            }

            table, th, td {
                font-size: 13px;
            }
        }
    </style>

    <div class="container">
        <div class="top-actions">
            <div class="action-buttons">
                <a href="{{ route('students.create') }}" class="btn btn-add">Pelajar Baru</a>

                @if(!$students->isEmpty())
                    <form action="{{ route('students.deleteAll') }}" method="POST"
                        onsubmit="return confirm('⚠️ Adakah anda pasti mahu memadamkan semua pelajar? Tindakan ini tidak boleh dibuat asal!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete-all">Padam Semua</button>
                    </form>
                @endif
            </div>

            <form method="GET" action="{{ route('students.index') }}" class="search-bar">
                <input type="text" name="search" placeholder="🔍 Cari nama atau ID..." value="{{ $search ?? '' }}">
                <select name="class" onchange="this.form.submit()">
                    <option value="">Pilih Kelas</option>
                    @foreach($allClasses as $c)
                        <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <button type="submit">Cari</button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            @if(!$selectedClass && !$search)
                <table>
                    <thead>
                        <tr>
                            <th>Pelajar ID</th>
                            <th>Nama</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" style="text-align:center; color:#9ca3af; font-style:italic;">
                                Sila pilih kelas untuk melihat pelajar.
                            </td>
                        </tr>
                    </tbody>
                </table>
            @elseif($students->isEmpty())
                <p class="no-results">
                    Tiada pelajar ditemui
                    @if($search)
                        untuk "{{ $search }}"
                    @endif
                    @if($selectedClass)
                        dalam {{ $selectedClass }}
                    @endif
                    .
                </p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Pelajar ID</th>
                            <th>Nama</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->name }}</td>
                                <td style="text-align:center;">
                                    <div class="actions">
                                        <a href="{{ route('students.edit', $student->student_id) }}" class="btn btn-edit">Ubah</a>
                                        <form action="{{ route('students.destroy', $student->student_id) }}" method="POST"
                                            onsubmit="return confirm('Padam pelajar ini?');">
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

                <div class="pagination">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
