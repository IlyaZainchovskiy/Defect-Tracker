<?php

namespace App\Http\Controllers;

use App\Models\Defect;
use App\Models\Department;
use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
        public function index(Request $request): View
    {
        $departments = Department::all();
        $reasons = Reason::all();
        
        $dateFrom = $request->date_from ?? now()->subMonths(6)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        
        $monthlyDefects = Defect::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $topReasons = Reason::withCount(['defects' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo);
        }])
            ->having('defects_count', '>', 0)
            ->orderByDesc('defects_count')
            ->take(5)
            ->get();
        
        $departmentStats = Department::withCount(['defects' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo);
        }])
            ->having('defects_count', '>', 0)
            ->orderByDesc('defects_count')
            ->get();
        
        return view('analytics.index', compact(
            'departments', 
            'reasons', 
            'dateFrom', 
            'dateTo', 
            'monthlyDefects', 
            'topReasons', 
            'departmentStats'
        ));
    }
    
    public function exportPdf(Request $request)
    {
        // Функція для pdf
    }
    
    public function exportExcel(Request $request)
    {
        // Функція для exel 
    }
}
