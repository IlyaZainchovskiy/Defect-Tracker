<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
        public function index(): View
    {
        $users = User::with('department')->paginate(10);
        
        return view('users.index', compact('users'));
    }
    
    public function create(): View
    {
        $departments = Department::all();
        
        return view('users.create', compact('departments'));
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'role' => 'required|in:user,admin',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        
        User::create($validated);
        
        return redirect()->route('users.index')->with('success', 'Користувача успішно створено.');
    }
    
    public function edit(User $user): View
    {
        $departments = Department::all();
        
        return view('users.edit', compact('user', 'departments'));
    }
    
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'department_id' => 'nullable|exists:departments,id',
            'role' => 'required|in:user,admin',
            'is_active' => 'required|boolean',
        ]);
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            
            $validated['password'] = Hash::make($request->password);
        }
        
        $user->update($validated);
        
        return redirect()->route('users.index')->with('success', 'Інформацію про користувача успішно оновлено.');
    }
    
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Ви не можете видалити свій аккаунт.');
        }
        
        if ($user->defects()->exists()) {
            return redirect()->route('users.index')->with('error', "Неможливо видалити користувача, який має пов'язані записи про брак.");
        }

        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'Користувача успішно видалено.');
    }
    
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Ви не можете деактивувати свій аккаунт.');
        }
        
        $user->update([
            'is_active' => !$user->is_active,
        ]);
        
        $status = $user->is_active ? 'активовано' : 'деактивовано';
        
        return redirect()->route('users.index')->with('success', "Користувача успішно {$status}.");
    }
}
