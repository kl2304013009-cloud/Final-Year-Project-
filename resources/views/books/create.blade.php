<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight:700; font-size:1.6rem; color:#1e293b;">
            📚 LIMS – New Book
        </h2>
    </x-slot>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 950px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            padding: 30px 40px;
            border: 1.5px solid #f3f4f6;
        }

        h2 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 700;
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px 25px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 13px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fafafa;
            transition: all 0.25s ease;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;
            background: #f0f9ff;
            box-shadow: 0 0 5px rgba(37,99,235,0.3);
        }

        .form-actions {
            grid-column: span 2;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-save {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 3px 8px rgba(37,99,235,0.3);
        }

        .btn-save:hover { transform: scale(1.05); background: #1d4ed8; }

        .btn-back {
            background: #9ca3af;
            color: white;
        }

        .btn-back:hover { background: #6b7280; transform: scale(1.05); }

        .alert-success {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #dcfce7;
            border-left: 5px solid #16a34a;
            color: #065f46;
            border-radius: 8px;
            font-size: 14px;
        }

        hr {
            margin: 25px 0;
            border-color: #e5e7eb;
        }

        .import-section {
            grid-column: span 2;
            text-align: center;
            margin-top: 10px;
        }

        .import-section h3 {
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .import-section input[type="file"] {
            padding: 7px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-right: 10px;
        }

        .btn-upload {
            background: #2563eb;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-upload:hover { background: #1d4ed8; }

        /* Responsive - single column on small screens */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            .form-actions { justify-content: center; }
        }
    </style>

    <div class="container">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('books.store') }}">
            @csrf

            <div>
                <label>Tajuk</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <p style="color: #dc2626; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Penulis</label>
                <input type="text" name="author" value="{{ old('author') }}" required>
                @error('author')
                    <p style="color: #dc2626; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}" required>
                @error('isbn')
                    <p style="color: #dc2626; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Tahun</label>
                <input type="number" name="year" value="{{ old('year') }}" required>
                @error('year')
                    <p style="color: #dc2626; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Kuantiti</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                @error('quantity')
                    <p style="color: #dc2626; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Kategori</label>
                <select name="category" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p style="color: #dc2626; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-save">Tambah Buku</button>
                <a href="{{ route('books.index') }}" class="btn btn-back">Kembali</a>
            </div>

            <hr>

            <div class="import-section">
                <h3>Import Buku melalui Excel</h3>
                <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel_file" accept=".xlsx,.xls" required>
                    <button type="submit" class="btn-upload">Muat Naik & Import</button>
                </form>
            </div>
        </form>
    </div>
</x-app-layout>
