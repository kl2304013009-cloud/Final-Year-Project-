<x-app-layout>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: "Poppins", Arial, sans-serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 35px 40px;
            border: 2px solid #f0f0f0;
        }

        label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
            background: #fafafa;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.3);
            background: #fff;
        }

        /* 🔍 Suggestions dropdown */
        #suggestions {
            list-style: none;
            margin-top: 2px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: white;
            position: absolute;
            top: 65px;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            z-index: 999;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        }

        #suggestions li {
            padding: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #suggestions li:hover {
            background-color: #f3f4f6;
        }

        /* 🟣 Buttons */
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

        .btn-submit {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-submit:hover {
            background-color: #4338ca;
            transform: scale(1.05);
        }

        .btn-cancel {
            background-color: #9ca3af;
            color: white;
            box-shadow: 0 3px 8px rgba(156, 163, 175, 0.3);
        }

        .btn-cancel:hover {
            background-color: #6b7280;
            transform: scale(1.05);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        textarea::placeholder {
            color: #9ca3af;
        }
    </style>

    <div class="container">
        <form action="{{ route('report.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px; position: relative;">
                <label>Tajuk Buku</label>
                <input type="text" id="book_title" name="book_title"
                       placeholder="Type book name..."
                       autocomplete="off" required>

                <ul id="suggestions"></ul>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Jenis Isu</label>
                <select name="issue_type" required>
                    <option value="">Pilih Isu</option>
                    <option value="Damaged">Kerosakan</option>
                    <option value="Lost">Hilang</option>
                    <option value="Fined">Denda</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Penerangan</label>
                <textarea name="description" rows="4"
                          placeholder="Contoh: Pelajar itu didenda RM0.60 kerana lewat balik 3 hari."
                          required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">Hantar</button>
                <a href="{{ route('report.index') }}" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const input = document.getElementById('book_title');
        const list = document.getElementById('suggestions');

        input.addEventListener('input', function () {
            const query = this.value.trim();
            if (query.length < 1) {
                list.style.display = 'none';
                return;
            }

            fetch(`{{ route('books.autocomplete') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(title => {
                            const li = document.createElement('li');
                            li.textContent = title;
                            li.addEventListener('click', () => {
                                input.value = title;
                                list.style.display = 'none';
                            });
                            list.appendChild(li);
                        });
                        list.style.display = 'block';
                    } else {
                        list.style.display = 'none';
                    }
                })
                .catch(() => list.style.display = 'none');
        });

        document.addEventListener('click', e => {
            if (!list.contains(e.target) && e.target !== input) {
                list.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
