<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appeal;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // --- 1. KIRISH (LOGIN) ---
    public function loginPage()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Login yoki parol xato.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    // --- 2. MONITORING (DASHBOARD) ---
    public function dashboard()
    {
        $stats = [
            'total' => Appeal::count(),
            'new' => Appeal::where('status', 'new')->count(),
            'processing' => Appeal::where('status', 'processing')->count(),
            'closed' => Appeal::where('status', 'closed')->count(),
        ];

        $latestAppeals = Appeal::with('assignedUser')->latest()->take(5)->get();
        $todayCount = Appeal::whereDate('created_at', today())->count();

        return view('admin.dashboard', compact('stats', 'latestAppeals', 'todayCount'));
    }

    // --- 3. STATISTIKA ---
    public function statistics()
    {
        $total = Appeal::count();
        $new = Appeal::where('status', 'new')->count();
        $closed = Appeal::where('status', 'closed')->count();
        $processing = Appeal::where('status', 'processing')->count();

        $topProblems = Appeal::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $regions = Appeal::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->orderByDesc('total')
            ->get();

        $chartLabels = $regions->pluck('region');
        $chartData = $regions->pluck('total');

        return view('admin.statistics', compact('total', 'new', 'closed', 'processing', 'topProblems', 'chartLabels', 'chartData'));
    }

    // --- 4. ARIZALAR (TUZATILGAN QISM) ---
    public function appeals(Request $request)
    {
        $query = Appeal::with('assignedUser')->latest();

        // 1. Qidiruv
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('track_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        // 2. Monitoringdan kelgan 'status' filtri (BU QAYTARILDI)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // 3. Sahifadagi tugmalar orqali 'filter'
        if ($request->has('filter')) {
            if ($request->filter == 'new') {
                $query->where('status', 'new');
            } elseif ($request->filter == 'serious') {
                // Kategoriya nomlarini bazadagiga moslash kerak
                $query->whereIn('type', ['Korrupsiya', 'Pora olish', 'Pora talab qilish', 'Mansab suiiste\'molligi']);
            }
        }

        $appeals = $query->paginate(10);
        return view('admin.appeals', compact('appeals'));
    }

    public function show($id)
    {
        $appeal = Appeal::findOrFail($id);
        $users = User::where('id', '!=', Auth::id())->where('is_active', true)->get();
        $assignedUser = $appeal->assigned_to ? User::find($appeal->assigned_to) : null;

        return view('admin.show', compact('appeal', 'users', 'assignedUser'));
    }

    public function update(Request $request, $id)
    {
        $appeal = Appeal::findOrFail($id);
        $appeal->status = $request->status;
        $appeal->admin_note = $request->admin_note;
        $appeal->save();

        return back()->with('success', 'Status yangilandi');
    }

    public function assign(Request $request, $id)
    {
        $appeal = Appeal::findOrFail($id);
        
        if ($request->user_id == 'null') {
            $appeal->assigned_to = null;
        } else {
            $appeal->assigned_to = $request->user_id;
        }
        
        $appeal->save();

        return back()->with('success', 'Mas\'ul xodim o\'zgartirildi');
    }

    // --- 5. KATEGORIYALAR ---
    public function categories()
    {
        $categories = Category::latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create(['name' => $request->name]);
        return back()->with('success', 'Kategoriya qo\'shildi');
    }
    
    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'O\'chirildi');
    }

    // --- 6. MAS'UL XODIMLAR ---
    public function users()
    {
        $users = User::where('id', '!=', Auth::id())->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'department' => 'required',
            'phone' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department' => $request->department,
            'phone' => $request->phone,
            'is_active' => true
        ]);

        return back()->with('success', 'Yangi xodim qo\'shildi');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Xodim o\'chirildi');
    }

    // --- 7. ARXIV ---
    public function archive()
    {
        $years = Appeal::whereIn('status', ['closed', 'rejected'])
            ->selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = [date('Y')];
        }

        return view('admin.archive', compact('years'));
    }

    public function archiveShow($year)
    {
        $appeals = Appeal::whereIn('status', ['closed', 'rejected'])
            ->whereYear('created_at', $year)
            ->latest()
            ->paginate(10);

        return view('admin.appeals', compact('appeals'));
    }

    // --- 8. CHOP ETISH (PRINT) ---
    public function printAppeal($id)
    {
        $appeal = Appeal::findOrFail($id);
        
        // Biriktirilgan xodimni ham olamiz
        $assignedUser = $appeal->assigned_to ? User::find($appeal->assigned_to) : null;

        return view('admin.print', compact('appeal', 'assignedUser'));
    }
}