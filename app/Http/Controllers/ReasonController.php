<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReasonController extends Controller
{
        public function index(Request $request): View
    {
        $query = Reason::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $reasons = $query->paginate(10);
        
        return view('reasons.index', compact('reasons'));
    }
    
    public function create(): View
    {
        return view('reasons.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:reasons',
            'description' => 'nullable|string',
        ]);
        
        Reason::create($validated);
        
        return redirect()->route('reasons.index')->with('success', 'Причину браку успішно додано.');
    }
    
    public function edit(Reason $reason): View
    {
        return view('reasons.edit', compact('reason'));
    }
    
    public function update(Request $request, Reason $reason): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:reasons,name,' . $reason->id,
            'description' => 'nullable|string',
        ]);
        
        $reason->update($validated);
        
        return redirect()->route('reasons.index')->with('success', 'Причину браку успішно оновлено.');
    }
    
    public function destroy(Reason $reason): RedirectResponse
    {
        if ($reason->defects()->exists()) {
            return redirect()->route('reasons.index')->with('error', 'Неможливо видалити причину, яка використовується в записах про брак.');
        }
        
        $reason->delete();
        
        return redirect()->route('reasons.index')->with('success', 'Причину браку успішно видалено.');
    }
}
