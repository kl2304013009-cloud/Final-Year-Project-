<x-app-layout>

    @php
        $studentId = auth('student')->id();

        // Ambil rekod pinjaman pelajar (sama cara dengan dashboard)
        $borrows = \App\Models\Borrow::where('student_id', $studentId)->with('book')->get();
    @endphp

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Sejarah Pinjaman Anda</h3>

                @if ($borrows->isEmpty())
                    <p class="text-gray-500">Anda belum meminjam sebarang buku lagi.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2">Bil</th>
                                    <th class="border px-4 py-2 text-left">Tajuk Buku</th>
                                    <th class="border px-4 py-2 text-left">Dipinjam Pada</th>
                                    <th class="border px-4 py-2 text-left">Pulangan yang Dijangka</th>
                                    <th class="border px-4 py-2 text-left">Dikembalikan Pada</th>
                                    <th class="border px-4 py-2 text-left">Denda (RM)</th>
                                    <th class="border px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($borrows as $index => $borrow)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2">{{ $borrow->book->title ?? 'Unknown' }}</td>
                                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</td>
                                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</td>
                                        <td class="border px-4 py-2">
                                            {{ $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('d M Y') : 'Not returned' }}
                                        </td>

                                        {{-- fine display sama macam dashboard --}}
                                        <td class="border px-4 py-2 {{ (float)($borrow->fine ?? 0) > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                            {{ number_format($borrow->fine ?? 0, 2) }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            @if (! $borrow->returned_at)
                                                <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded text-sm">Dipinjam</span>
                                            @else
                                                <span class="bg-green-200 text-green-800 px-3 py-1 rounded text-sm">Dipulangkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
