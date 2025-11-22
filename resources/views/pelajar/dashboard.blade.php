<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            LIMS
        </h2>
    </x-slot>

    @php
        $studentId = auth('student')->id();
        $borrows = \App\Models\Borrow::where('student_id', $studentId)->with('book')->get();
        $totalFine = (float) $borrows->sum(fn($b) => (float) ($b->fine ?? 0));
        $totalUnpaid = (float) $borrows->reduce(function ($carry, $b) {
            $fine = (float) ($b->fine ?? 0);
            $isPaid = method_exists($b, 'trashed') && $b->trashed();
            return $carry + (($fine > 0 && ! $isPaid) ? $fine : 0);
        }, 0);
    @endphp

    <div class="py-6" style="background: linear-gradient(135deg, #e0f7fa, #fef3c7); min-height:100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Card -->
            <div class="bg-white shadow-md rounded-xl p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::guard('student')->user()->name }}</h3>
                        <p class="mt-1 text-gray-500">Class: {{ Auth::guard('student')->user()->class }}</p>
                    </div>
                </div>
            </div>

            <!-- Borrow Summary -->
            <div class="bg-white shadow-md rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Ringkasan Pinjaman Semasa</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-500">Jumlah Buku yang Dipinjam</p>
                        <p class="text-xl font-bold text-gray-800">{{ $borrows->count() }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-500">Jumlah Denda</p>
                        @if ($totalFine > 0)
                            <p class="text-xl font-bold text-red-600">RM{{ number_format($totalFine, 2) }}</p>
                        @else
                            <p class="text-xl font-bold text-green-600">Tiada Denda</p>
                        @endif
                    </div>
                    @if($totalUnpaid > 0)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                            <p class="text-gray-500">Unpaid</p>
                            <p class="text-xl font-bold text-red-600">RM{{ number_format($totalUnpaid, 2) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Borrow Records Table -->
            @if($borrows->count() > 0)
                <div class="bg-white shadow-md rounded-xl p-6 overflow-x-auto">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">Rekod Pinjaman Anda</h4>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Bil</th>
                                <th class="px-4 py-2 text-left">Tajuk Buku</th>
                                <th class="px-4 py-2 text-left">Tarikh Pinjaman</th>
                                <th class="px-4 py-2 text-left">Pulangan yang Dijangka</th>
                                <th class="px-4 py-2 text-left">Dipulangkan</th>
                                <th class="px-4 py-2 text-left">Denda (RM)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($borrows as $borrow)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2">{{ $borrow->book->title ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-2">
                                        {{ $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 font-bold {{ $borrow->fine > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($borrow->fine, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
