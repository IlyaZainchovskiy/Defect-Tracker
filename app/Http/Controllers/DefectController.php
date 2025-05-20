<?php

namespace App\Http\Controllers;

use App\Models\Defect;
use App\Models\Department;
use App\Models\Reason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DefectController extends Controller
{
        public function index(Request $request): View
    {
        $query = Defect::with(['department', 'reason', 'user']);
        
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('reason_id')) {
            $query->where('reason_id', $request->reason_id);
        }
        
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        
        $defects = $query->latest()->paginate(10);
        $departments = Department::all();
        $reasons = Reason::all();
        
        return view('defects.index', compact('defects', 'departments', 'reasons'));
    }
    
    public function create(): View
    {
        $departments = Department::all();
        $reasons = Reason::all();
        
        return view('defects.create', compact('departments', 'reasons'));
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'product_type' => 'required|string|max:255',
            'description' => 'required|string',
            'reason_id' => 'required|exists:reasons,id',
        ]);
        
        $validated['user_id'] = auth()->id();
        
        Defect::create($validated);
        
        return redirect()->route('defects.index')->with('success', 'Випадок браку успішно додано.');
    }
    
    public function show(Defect $defect): View
    {
        $this->authorize('view', $defect);
        
        return view('defects.show', compact('defect'));
    }
    
    public function edit(Defect $defect): View
    {
        $this->authorize('update', $defect);
        
        $departments = Department::all();
        $reasons = Reason::all();
        
        return view('defects.edit', compact('defect', 'departments', 'reasons'));
    }
    
    public function update(Request $request, Defect $defect): RedirectResponse
    {
        $this->authorize('update', $defect);
        
        $validated = $request->validate([
            'date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'product_type' => 'required|string|max:255',
            'description' => 'required|string',
            'reason_id' => 'required|exists:reasons,id',
        ]);
        
        $defect->update($validated);
        
        return redirect()->route('defects.index')->with('success', 'Випадок браку успішно оновлено.');
    }
    
    public function destroy(Defect $defect): RedirectResponse
    {
        $this->authorize('delete', $defect);
        
        $defect->delete();
        
        return redirect()->route('defects.index')->with('success', 'Випадок браку успішно видалено.');
    }
}
