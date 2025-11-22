<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 26px; font-weight: 700; color: #1e293b;">
            ✏️ Edit Book Information
        </h2>
    </x-slot>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: "Inter", sans-serif;
        }

        .edit-card {
            max-width: 950px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            padding: 40px 50px;
            border: 1.5px solid #f3f4f6;
        }

        h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 28px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px 30px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fafafa;
            transition: all 0.25s ease;
        }

        input:focus, select:focus {
            border-color: #60a5fa;
            background: #f0f9ff;
            box-shadow: 0 0 5px rgba(96, 165, 250, 0.4);
        }

        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: 3px;
        }

        .btn {
            display: inline-block;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .btn-cancel {
            background-color: #9ca3af;
            color: white;
            margin-right: 10px;
            box-shadow: 0 3px 8px rgba(156, 163, 175, 0.3);
        }

        .btn-cancel:hover {
            background-color: #6b7280;
            transform: scale(1.05);
        }

        .btn-submit {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-submit:hover {
            background-color: #4338ca;
            transform: scale(1.05);
        }

        .form-actions {
            text-align: right;
            margin-top: 25px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="edit-card">
        <h3>Ubah Maklumat Buku</h3>

        <form action="{{ route('books.update', $book->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div>
                    <label for="title">Tajuk</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required>
                    @error('title') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="author">Penulis</label>
                    <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required>
                    @error('author') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                    @error('isbn') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="year">Tahun</label>
                    <input type="number" name="year" id="year"
                           value="{{ old('year', $book->year) }}"
                           min="1000" max="{{ date('Y') }}">
                    @error('year') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="quantity">Kuantiti</label>
                    <input type="number" name="quantity" id="quantity"
                           value="{{ old('quantity', $book->quantity) }}" min="1" required>
                    @error('quantity') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category">Kategori</label>
                    <select name="category" id="category" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $book->category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('books.index') }}" class="btn btn-cancel">Kembali</a>
                <button type="submit" class="btn btn-submit">Kemas Kini</button>
            </div>
        </form>
    </div>
</x-app-layout>
