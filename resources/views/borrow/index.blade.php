<x-app-layout>

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* ALERT BOXES */
        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 5px solid #10b981; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 5px solid #ef4444; }

        h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }

        /* MAIN SECTION GRID */
        .main-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        @media(max-width: 900px) {
            .main-section {
                grid-template-columns: 1fr;
            }
        }

        /* SEARCH BAR */
        .search-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .search-bar input {
            flex: 1 1 200px;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            box-sizing: border-box;
        }

        .search-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-buttons button,
        .search-buttons a.btn {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 18px;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }

        .search-buttons button:hover,
        .search-buttons a.btn:hover {
            background: #1e40af;
        }

        /* BOOKS GRID */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: transform 0.2s ease, box-shadow 0.2s;
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.1);
        }

        .book-card p {
            margin: 3px 0;
            font-size: 15px;
            color: #374151;
        }

        .book-card a {
            background: #2563eb;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
            align-self: flex-start;
        }

        .book-card a:hover {
            background: #1e40af;
        }

        .out-of-stock {
            background: gray !important;
            pointer-events: none;
            opacity: 0.7;
        }

        /* BORROW FORM */
        .borrow-form {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .borrow-form h2 {
            margin-bottom: 16px;
            font-size: 1.3rem;
            color: #1f2937;
        }

        .fine-info {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #111827;
            font-size: 15px;
        }

        input[type="date"], select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            margin-bottom: 18px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
            display: block;
            margin: 0 auto;
        }

        button[type="submit"]:hover {
            background: #4338ca;
            transform: scale(1.03);
        }
    </style>

    <div class="container">
        {{-- ALERTS --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="main-section">
            <!-- LEFT PANEL -->
            <div>
                <h2>Buku Tersedia</h2>

                @if($totalFine > 0)
                    <div style="background:#fef3c7; color:#92400e; border-left:5px solid #f59e0b; padding:12px 16px; border-radius:10px; margin-bottom:20px;">
                        💰 <strong>Jumlah Denda:</strong> RM{{ number_format($totalFine, 2) }}
                    </div>
                @endif

                <!-- SEARCH AREA -->
                <form method="GET" action="{{ route('borrow.index') }}" class="search-bar">
                    <input type="text" name="search" placeholder="Cari tajuk buku atau penulis..." value="{{ request('search') }}">
                    <div class="search-buttons">
                        <button type="submit">Cari</button>
                        <a href="{{ route('borrow.index', ['refresh' => true]) }}" class="btn">Kemaskini Paparan</a>
                    </div>
                </form>

                @php
                    $filteredBooks = $books;
                    if(request('search')) {
                        $search = strtolower(request('search'));
                        $filteredBooks = $books->filter(function($book) use ($search) {
                            return str_contains(strtolower($book->title), $search)
                                || str_contains(strtolower($book->author), $search);
                        });
                    }
                    $filteredBooks = $filteredBooks->shuffle()->take(6);
                @endphp

                @if($filteredBooks->isEmpty())
                    <p style="color:#777;">Tiada buku ditemui sepadan dengan carian anda.</p>
                @else
                    <div class="books-grid">
                        @foreach($filteredBooks as $book)
                            <div class="book-card">
                                <div>
                                    <p><strong>Tajuk Buku:</strong> {{ $book->title }}</p>
                                    <p><strong>Penulis:</strong> {{ $book->author }}</p>
                                    <p><strong>Ketagori:</strong> {{ $book->category ?? 'N/A' }}</p>
                                    <p><strong>Tersedia:</strong> {{ $book->quantity }} Salinan</p>
                                </div>

                                @if($book->quantity > 0)
                                    <a href="{{ route('borrow.index', ['book' => $book->id]) }}">Pinjam</a>
                                @else
                                    <a href="#" class="out-of-stock">Tiada Salinan Sekarang</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- RIGHT PANEL -->
            <div>
                @if($selectedBook)
                    <div class="borrow-form">
                        <h2>Pinjam: {{ $selectedBook->title }}</h2>

                        <div class="fine-info">
                            <strong>Peringatan:</strong> Anda akan dikenakan bayaran <b>RM0.20 sehari</b> jika buku itu dipulangkan lewat.
                        </div>

                        <form method="POST" action="{{ route('borrow.store', $selectedBook->id) }}">
                            @csrf

                            <label for="borrow_date">Tarikh Pinjam</label>
                            <input type="date" id="borrow_date" name="borrow_date" value="{{ now()->toDateString() }}" required>

                            <label for="duration">Duration</label>
                            <select id="duration" name="duration" required>
                                <option value="">Pilih Tempoh</option>
                                <option value="1">1 Hari</option>
                                <option value="3">3 Hari</option>
                                <option value="7">7 Hari</option>
                                <option value="14">14 Hari (Max)</option>
                            </select>

                            <label for="return_date">Tarikh Kembali</label>
                            <input type="date" id="return_date" name="return_date" readonly required>

                            <button type="submit">Sahkan Pinjam</button>
                        </form>
                    </div>

                    <script>
                        const borrowInput = document.getElementById('borrow_date');
                        const durationSelect = document.getElementById('duration');
                        const returnInput = document.getElementById('return_date');

                        function updateReturnDate() {
                            const borrowDate = new Date(borrowInput.value);
                            const days = parseInt(durationSelect.value);
                            if (!isNaN(borrowDate.getTime()) && days) {
                                borrowDate.setDate(borrowDate.getDate() + days);
                                returnInput.value = borrowDate.toISOString().split('T')[0];
                            } else {
                                returnInput.value = '';
                            }
                        }

                        borrowInput.addEventListener('change', updateReturnDate);
                        durationSelect.addEventListener('change', updateReturnDate);
                        updateReturnDate();
                    </script>
                @else
                    <div class="borrow-form" style="opacity:0.7; text-align:center;">
                        <p>Pilih buku untuk dipinjam.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
