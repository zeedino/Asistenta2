<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Document;
use App\Models\Log;
use App\Models\Meeting;
use App\Models\SuratKeputusan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            default:
                abort(403, 'Unauthorized role');
        }
    }

    public function adminDashboard()
    {
        $user = Auth::user();
        if (! $user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // DATA REAL dari database - Users Only
        $totalUsers = User::count();
        $pendingUsers = User::where('status', 'pending')->count();
        $activeUsers = User::where('status', 'aktif')->count();
        $totalDosen = User::where('role', 'dosen')->where('status', 'aktif')->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->where('status', 'aktif')->count();
        $totalAdmin = User::where('role', 'admin')->where('status', 'aktif')->count();

        $totalSK = SuratKeputusan::count();

        // Recent Users untuk overview
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent SK untuk overview
        $recentSK = SuratKeputusan::with(['mahasiswa', 'admin'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'pendingUsers',
            'activeUsers',
            'totalDosen',
            'totalMahasiswa',
            'totalAdmin',
            'totalSK',
            'recentUsers',
            'recentSK'
        ));
    }

    public function dosenDashboard()
    {
        $user = Auth::user();

        if (! $user->isDosen()) {
            abort(403, 'Unauthorized access');
        }

        // ✅ Daftar Mahasiswa Bimbingan berdasarkan SK
        $mahasiswaBimbingan = $user->mahasiswaBimbingan()
            ->orderBy('username')
            ->get();
        $mahasiswaBimbinganCount = $mahasiswaBimbingan->count();

        // ✅ Meetings stats
        $totalMeetings = Meeting::where('dosen_id', $user->id)->count();
        $pendingMeetings = Meeting::where('dosen_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $confirmedMeetings = Meeting::where('dosen_id', $user->id)
            ->where('status', 'confirmed')
            ->count();
        $completedMeetings = Meeting::where('dosen_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // ✅ Availabilities count
        $totalAvailabilities = Availability::where('dosen_id', $user->id)->count();

        // ✅ Logbooks stats
        $pendingLogs = Log::where('dosen_id', $user->id)
            ->where('status', 'submitted')
            ->count();
        $validatedLogs = Log::where('dosen_id', $user->id)
            ->where('status', 'validated')
            ->count();
        $rejectedLogs = Log::where('dosen_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        // ✅ Documents stats
        $totalDocuments = Document::whereHas('meeting', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })->orWhere('user_id', $user->id)
            ->count();

        $pendingReviewDocuments = Document::whereHas('meeting', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })
            ->where('status', 'submitted')
            ->count();

        $approvedDocuments = Document::whereHas('meeting', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })
            ->where('status', 'approved')
            ->count();

        // ✅ Recent items untuk dashboard
        $recentMeetings = Meeting::with('mahasiswa')
            ->where('dosen_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPendingLogs = Log::with(['mahasiswa', 'meeting'])
            ->where('dosen_id', $user->id)
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentDocuments = Document::with(['user', 'meeting'])
            ->where(function ($query) use ($user) {
                $query->whereHas('meeting', function ($q) use ($user) {
                    $q->where('dosen_id', $user->id);
                })
                    ->orWhere('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.dosen', compact(
            // ✅ SK Integration
            'mahasiswaBimbingan',
            'mahasiswaBimbinganCount',

            // Meetings stats
            'totalMeetings',
            'pendingMeetings',
            'confirmedMeetings',
            'completedMeetings',
            'totalAvailabilities',

            // Logbooks stats
            'pendingLogs',
            'validatedLogs',
            'rejectedLogs',

            // Documents stats
            'totalDocuments',
            'pendingReviewDocuments',
            'approvedDocuments',

            // Recent items
            'recentMeetings',
            'recentPendingLogs',
            'recentDocuments'
        ));
    }

    public function mahasiswaDashboard()
    {
        $user = Auth::user();

        if (! $user->isMahasiswa()) {
            abort(403, 'Unauthorized access');
        }

        // ✅ DOSEN PEMBIMBING berdasarkan SK
        $dosenPembimbing = $user->dosenPembimbing()
            ->orderByRaw("FIELD(posisi, 'Pembimbing 1', 'Pembimbing 2')")
            ->get();

        $dosenPembimbingCount = $dosenPembimbing->count();

        // ✅ Meetings stats
        $myMeetings = Meeting::where('mahasiswa_id', $user->id)->count();
        $pendingMeetings = Meeting::where('mahasiswa_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $confirmedMeetings = Meeting::where('mahasiswa_id', $user->id)
            ->where('status', 'confirmed')
            ->count();
        $completedMeetings = Meeting::where('mahasiswa_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // ✅ Logbook stats
        $totalLogs = Log::where('mahasiswa_id', $user->id)->count();
        $draftLogs = Log::where('mahasiswa_id', $user->id)
            ->where('status', 'draft')
            ->count();
        $submittedLogs = Log::where('mahasiswa_id', $user->id)
            ->where('status', 'submitted')
            ->count();
        $validatedLogs = Log::where('mahasiswa_id', $user->id)
            ->where('status', 'validated')
            ->count();
        $rejectedLogs = Log::where('mahasiswa_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        // ✅ Document stats
        $totalDocuments = Document::where('user_id', $user->id)->count();
        $draftDocuments = Document::where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();
        $submittedDocuments = Document::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->count();
        $approvedDocuments = Document::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        // ✅ Upcoming meetings
        $upcomingMeetings = Meeting::with('dosen')
            ->where('mahasiswa_id', $user->id)
            ->where('status', 'confirmed')
            ->where(function ($query) {
                $query->whereNull('meeting_date')
                    ->orWhere('meeting_date', '>=', now());
            })
            ->orderBy('meeting_date', 'asc')
            ->limit(5)
            ->get();

        // ✅ Recent logs
        $recentLogs = Log::with(['dosen', 'meeting'])
            ->where('mahasiswa_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ✅ Recent documents
        $recentDocuments = Document::with(['user', 'meeting'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('user', function ($userQuery) {
                            $userQuery->where('role', 'dosen');
                        })
                            ->whereHas('meeting', function ($meetingQuery) use ($user) {
                                $meetingQuery->where('mahasiswa_id', $user->id);
                            });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.mahasiswa', compact(
            // ✅ SK Integration
            'dosenPembimbing',
            'dosenPembimbingCount',

            // Meetings
            'myMeetings',
            'pendingMeetings',
            'confirmedMeetings',
            'completedMeetings',

            // Logbooks
            'totalLogs',
            'draftLogs',
            'submittedLogs',
            'validatedLogs',
            'rejectedLogs',

            // Documents
            'totalDocuments',
            'draftDocuments',
            'submittedDocuments',
            'approvedDocuments',

            // Recent items
            'upcomingMeetings',
            'recentLogs',
            'recentDocuments'
        ));
    }
}
