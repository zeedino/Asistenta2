<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user->isDosen()) {
            abort(403, 'Unauthorized access.');
        }

        if (! $user->hasMahasiswaBimbinganAktif()) {
            return redirect()->route('dosen.dashboard')
                ->with('warning', 'Anda belum memiliki mahasiswa bimbingan aktif.
                        Silakan hubungi admin untuk mendapatkan Surat Keputusan.');
        }

        $availabilities = Availability::where('dosen_id', $user->id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('availability.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (! $user->isDosen()) {
            abort(403, 'Unauthorized access.');
        }

        if (! $user->hasMahasiswaBimbinganAktif()) {
            return redirect()->route('availability.index')
                ->with('warning', 'Anda belum memiliki mahasiswa bimbingan aktif.');
        }

        return view('availability.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->isDosen()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        // Cek apakah sudah ada availability di waktu yang sama
        $existingAvailability = Availability::where('dosen_id', $user->id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->first();

        if ($existingAvailability) {
            return redirect()->back()
                ->with('error', 'Sudah ada jadwal di waktu yang sama!')
                ->withInput();
        }

        Availability::create([
            'dosen_id' => $user->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
            'status' => 'available',
        ]);

        return redirect()->route('availability.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Availability $availability)
    {
        $user = Auth::user();

        if ($availability->dosen_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('availability.show', compact('availability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Availability $availability)
    {
        $user = Auth::user();

        if ($availability->dosen_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Cek jika availability sudah dibooking
        if ($availability->status === 'booked') {
            return redirect()->route('availability.index')
                ->with('error', 'Tidak dapat mengedit jadwal yang sudah dibooking!');
        }

        return view('availability.edit', compact('availability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Availability $availability)
    {
        $user = Auth::user();

        if ($availability->dosen_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Cek jika availability sudah dibooking
        if ($availability->status === 'booked') {
            return redirect()->route('availability.index')
                ->with('error', 'Tidak dapat mengupdate jadwal yang sudah dibooking!');
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        // Cek konflik dengan availability lain (kecuali dirinya sendiri)
        $existingAvailability = Availability::where('dosen_id', $user->id)
            ->where('id', '!=', $availability->id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->first();

        if ($existingAvailability) {
            return redirect()->back()
                ->with('error', 'Sudah ada jadwal di waktu yang sama!')
                ->withInput();
        }

        $availability->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
        ]);

        return redirect()->route('availability.index')
            ->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Availability $availability)
    {
        $user = Auth::user();

        if ($availability->dosen_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Cek jika availability sudah dibooking
        if ($availability->status === 'booked') {
            return redirect()->route('availability.index')
                ->with('error', 'Tidak dapat menghapus jadwal yang sudah dibooking!');
        }

        $availability->delete();

        return redirect()->route('availability.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Get available slots for mahasiswa view
     */
    public function availableSlots()
    {
        $user = Auth::user();

        // 1. Validasi User
        if (! $user->isMahasiswa()) {
            abort(403, 'Unauthorized access.');
        }

        // 2. AMBIL ID DOSEN PEMBIMBING DARI PIVOT TABLE & SK
        // Kita bypass helper model user untuk memastikan akurasi data langsung dari DB
        $dosenIds = \Illuminate\Support\Facades\DB::table('dosen_mahasiswa')
            ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
            ->where('dosen_mahasiswa.mahasiswa_id', $user->id)
            ->where('surat_keputusan.status', 'active') // Pastikan status SK lowercase 'active'
            ->pluck('dosen_mahasiswa.dosen_id');

        // 3. Ambil Object User Dosen (Untuk ditampilkan infonya di View)
        $dosenPembimbing = \App\Models\User::whereIn('id', $dosenIds)->get();

        // 4. Jika tidak ada dosen (berarti tidak ada SK aktif)
        if ($dosenIds->isEmpty()) {
            return view('availability.available-slots', [
                'groupedAvailabilities' => collect(),
                'dosenPembimbing' => collect(),
            ])->with('warning', 'Anda belum memiliki dosen pembimbing dengan SK aktif.');
        }

        // 5. Ambil Availability
        $availabilities = Availability::with('dosen')
            ->whereIn('dosen_id', $dosenIds) // Filter: Hanya punya dosen pembimbing
            ->where('status', 'available')   // Filter: Hanya yang statusnya available
            ->where('date', '>=', Carbon::today()) // Filter: Hanya hari ini ke depan
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // 6. Grouping
        $groupedAvailabilities = $availabilities->groupBy('dosen_id');

        return view('availability.available-slots', compact('groupedAvailabilities', 'dosenPembimbing'));
    }
}
