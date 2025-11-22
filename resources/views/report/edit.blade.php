<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight: 700; font-size: 1.6rem; color: #2d3748;">
            🛠️ Edit Report – LIMS
        </h2>
    </x-slot>

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: "Poppins", Arial, sans-serif;
        }

        .container {
            max-width: 750px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 35px 40px;
            border: 2px solid #f0f0f0;
        }

        label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
            margin-bottom: 18px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.4);
        }

        textarea {
            resize: vertical;
        }

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

        .btn-update {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-update:hover {
            background-color: #4338ca;
            transform: scale(1.05);
        }

        .btn-cancel {
            background-color: #9ca3af;
            color: white;
            margin-left: 10px;
            box-shadow: 0 3px 8px rgba(156, 163, 175, 0.3);
        }

        .btn-cancel:hover {
            background-color: #6b7280;
            transform: scale(1.05);
        }
    </style>

    <div class="container">
        <form action="{{ route('report.update', $bookReport) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 📘 Book Title --}}
            <div>
                <label>Tajuk Buku</label>
                <input type="text" name="book_title" value="{{ $bookReport->book_title }}" required>
            </div>

            {{-- ⚠️ Issue Type --}}
            <div>
                <label>Jenis Isu</label>
                <select name="issue_type" required>
                    <option value="Damaged" {{ $bookReport->issue_type == 'Damaged' ? 'selected' : '' }}>Kerosakan</option>
                    <option value="Lost" {{ $bookReport->issue_type == 'Lost' ? 'selected' : '' }}>Hilang</option>
                    <option value="Fined" {{ $bookReport->issue_type == 'Fined' ? 'selected' : '' }}>Denda</option>
                </select>
            </div>

            {{-- 📝 Description --}}
            <div>
                <label>Penerangan</label>
                <textarea name="description" rows="4" required>{{ $bookReport->description }}</textarea>
            </div>

            {{-- 🔘 Buttons --}}
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-update">Kemas Kini</button>
                <a href="{{ route('report.index') }}" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
