<x-app-layout>
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #fef3c7);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 40px 45px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        .container::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 140px;
            height: 140px;
            background: rgba(59, 130, 246, 0.15);
            border-radius: 50%;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1e3a8a;
            font-size: 22px;
            letter-spacing: 0.5px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 20px;
            outline: none;
            background-color: #f9fafb;
            transition: all 0.25s ease;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 6px rgba(59, 130, 246, 0.3);
            background-color: #ffffff;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
            font-size: 15px;
        }

        .btn-save {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 3px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-save:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.4);
        }

        .btn-back {
            background-color: #fbbf24;
            color: #1f2937;
            margin-left: 10px;
            box-shadow: 0 3px 8px rgba(251, 191, 36, 0.3);
        }

        .btn-back:hover {
            background-color: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(251, 191, 36, 0.4);
        }

        .form-actions {
            text-align: center;
            margin-top: 15px;
        }

        /* Import Section */
        .import-section {
            margin-top: 35px;
            text-align: center;
        }

        .import-section h3 {
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 15px;
        }

        .import-section input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9fafb;
        }

        .btn-upload {
            background-color: #22c55e;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            margin-left: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-upload:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.4);
        }

        .template-link {
            display: inline-block;
            margin-top: 15px;
            color: #2563eb;
            font-weight: 500;
            text-decoration: none;
        }

        .template-link:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="container">
        <h2>Tambah Pelajar Baru</h2>

        <form method="POST" action="{{ route('students.store') }}">
            @csrf

            <div>
                <label>Pelajar ID</label>
                <input type="text" name="student_id" value="{{ old('student_id') }}" required>
                @error('student_id')
                    <p style="color:red; font-size: 13px; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <p style="color:red; font-size: 13px; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Kelas</label>
                <select name="class" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($allClasses as $c)
                        <option value="{{ $c }}" {{ old('class') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                @error('class')
                    <p style="color:red; font-size: 13px; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-save">Simpan</button>
                <a href="{{ route('students.index') }}" class="btn btn-back">Kembali</a>
            </div>
        </form>

        <hr style="margin: 35px 0; border-color: #ddd;">

        <div class="import-section">
            <h3>Import Pelajar melalui Excel</h3>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="excel_file" accept=".xlsx,.xls" required>
                <button type="submit" class="btn-upload">Muat Naik & Import</button>
            </form>
        </div>
    </div>
</x-app-layout>
