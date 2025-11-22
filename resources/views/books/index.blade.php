<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b;">
            📚 LIMS – Book Management
        </h2>
    </x-slot>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 30px 35px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1.5px solid #f3f4f6;
        }

        h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            font-size: 14px;
            border: none;
        }

        .btn-add {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 3px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-add:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .btn-edit {
            background: #fde047;
            color: #1e293b;
        }

        .btn-edit:hover {
            background: #facc15;
        }

        .btn-delete {
            background: #ef4444;
            color: #fff;
        }

        .btn-delete:hover {
            background: #dc2626;
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

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            max-width: 700px;
            background: #f9fafb;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .search-bar input, 
        .search-bar select {
            padding: 9px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            background-color: #fff;
            flex: 1;
        }

        .search-bar button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-bar button:hover {
            background: #1d4ed8;
        }

        .alphabet-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            justify-content: center;
            margin: 15px 0 20px;
        }

        .alphabet-filter button {
            border: none;
            padding: 7px 11px;
            border-radius: 6px;
            background: #f1f5f9;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .alphabet-filter button:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }

        .alphabet-filter .active {
            background: #2563eb;
            color: white;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #334155;
        }

        tr {
            transition: all 0.2s ease-in-out;
        }

        tr:hover td {
            background-color: #f1f5f9;
        }

        .no-results {
            text-align: center;
            color: #6b7280;
            padding: 20px;
            font-style: italic;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination {
            margin-top: 20px;
        }
    </style>

    <div class="container">
        <div class="top-bar">
            <a href="{{ route('books.create') }}" class="btn btn-add">Buku Baru</a>

            <form method="GET" action="{{ route('books.index') }}" class="search-bar">
                <input type="text" name="search" placeholder="🔍 Cari mengikut tajuk atau penulis..."
                       value="{{ old('search', $search ?? '') }}">
                <select name="category" onchange="this.form.submit()">
                    <option value="">Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ (request('category') == $cat) ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="alphabet-filter">
            @foreach(range('A','Z') as $letter)
                <form method="GET" action="{{ route('books.index') }}" style="display:inline;">
                    <input type="hidden" name="alphabet" value="{{ $letter }}">
                    <button type="submit" 
                        class="{{ request('alphabet') == $letter ? 'active' : '' }}">
                        {{ $letter }}
                    </button>
                </form>
            @endforeach
            <form method="GET" action="{{ route('books.index') }}" style="display:inline;">
                <button type="submit" style="background:#ef4444; color:white;">Reset</button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrapper">
            @if ($books->isEmpty())
                <p class="no-results">
                    Tiada buku dijumpai{{ isset($search) && $search ? " for \"{$search}\"" : '.' }}
                </p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Tajuk Buku</th>
                            <th>Penulis</th>
                            <th>ISBN</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Kuantiti</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->isbn }}</td>
                                <td>{{ $book->year ?? '-' }}</td>
                                <td>{{ $book->category }}</td>
                                <td>{{ $book->quantity }}</td>
                                <td style="text-align:center;">
                                    <div class="actions">
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-edit">Ubah Data</a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this book?');">
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
                    {{ $books->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
