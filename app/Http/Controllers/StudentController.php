<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $allClasses = Student::select('class')->distinct()->orderBy('class')->pluck('class');

        $selectedClass = $request->class ?? null;
        $search = $request->search ?? null;

        // Jika tiada filter dipilih → jangan return student apa-apa (empty)
        $students = collect();
        if ($selectedClass || $search) {
            $query = Student::query();
            if ($selectedClass) $query->where('class', $selectedClass);
            if ($search) $query->where(function($q) use($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('student_id', 'like', "%$search%");
            });
            $students = $query->paginate(8);
        }

        return view('students.index', compact('students','allClasses','selectedClass','search'));
    }

    public function create()
{
    $allClasses = Student::select('class')->distinct()->orderBy('class')->pluck('class');
    return view('students.create', compact('allClasses'));
}

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:students,student_id',
            'name' => 'required|string|max:255',
            'class' => 'required|string',
        ]);

        Student::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'class' => $request->class,
            'password' => Hash::make('12345678'), // default password
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function edit($id)
{
    $student = Student::findOrFail($id);
    $allClasses = Student::select('class')->distinct()->orderBy('class')->pluck('class');
    return view('students.edit', compact('student', 'allClasses'));
}

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $student->student_id . ',student_id',
            'name' => 'required|string|max:255',
            'class' => 'required|string',
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'class' => $request->class,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function deleteAll()
{
    $students = Student::all();

    if ($students->isEmpty()) {
        return redirect()->route('students.index')
            ->with('success', 'No students to delete.');
    }

    foreach ($students as $student) {
        $student->delete(); // guna delete satu-satu (trigger model events)
    }

    return redirect()->route('students.index')
        ->with('success', 'All students have been deleted successfully.');
}

    public function importExcel(Request $request)
    {
        set_time_limit(300); // bagi PHP masa lebih untuk 200+ row

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        // Load semua sheet dalam fail Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheetNames = $spreadsheet->getSheetNames();

        $totalImported = 0;

        foreach ($sheetNames as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);

            // Ambil tajuk kelas dari sel A1
            $classTitle = trim($sheet->getCell('A1')->getValue());
            if (empty($classTitle)) continue;

            $className = strtoupper($sheetName);

            // Ambil semua row sebagai numeric array
            $allRows = $sheet->toArray();

            // Data sebenar bermula dari row ke-3 (row 0 = tajuk, row 1 = header)
            for ($i = 2; $i < count($allRows); $i++) {
                $row = $allRows[$i];

                // Pastikan ID & Nama wujud
                if (!isset($row[1]) || !isset($row[2])) continue;

                $student_id = trim($row[1]); // Column B
                $name       = trim($row[2]); // Column C

                if (empty($student_id) || empty($name)) continue;

                // Skip jika student sudah wujud
                if (Student::where('student_id', $student_id)->exists()) continue;

                // Gunakan create() macam store()
                Student::create([
                    'student_id' => $student_id,
                    'name'       => $name,
                    'class'      => strtoupper($className),
                    'password'   => Hash::make('12345678'), // default password sama macam store()
                ]);

                $totalImported++;
            }
        }

        return redirect()->route('students.index')
            ->with('success', "{$totalImported} students imported successfully from Excel!");
    }
}
