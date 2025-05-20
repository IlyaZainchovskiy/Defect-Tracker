<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
        public function index(): View
    {
        $departments = Department::paginate(10);
        
        return view('departments.index', compact('departments'));
    }
    
    public function create(): View
    {
        return view('departments.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
        ]);
        
        Department::create($validated);
        
        return redirect()->route('departments.index')->with('success', 'Підрозділ успішно додано.');
    }
    
    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }
    
    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);
        
        $department->update($validated);
        
        return redirect()->route('departments.index')->with('success', 'Підрозділ успішно оновлено.');
    }
    
    public function destroy(Department $department): RedirectResponse
    {
        if ($department->defects()->exists() || $department->users()->exists()) {
            return redirect()->route('departments.index')->with('error', 'Неможливо видалити підрозділ, який використовується в записах про брак або має працівників.');
        }
        
        $department->delete();
        
        return redirect()->route('departments.index')->with('success', 'Підрозділ успішно видалено.');
    }
}
