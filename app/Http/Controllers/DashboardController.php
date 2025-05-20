<?php

namespace App\Http\Controllers;
use App\Models\Defect;
use App\Models\Department;
use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
     public function index(): View
    {
        $lastMonthDefects = Defect::where('date', '>=', now()->subMonth())->count();
        
        $topReasons = Reason::withCount('defects')
            ->orderByDesc('defects_count')
            ->take(5)
            ->get();
        
        $departmentStats = Department::withCount('defects')
            ->orderByDesc('defects_count')
            ->get();
        
        return view('dashboard', compact('lastMonthDefects', 'topReasons', 'departmentStats'));
    }
}
