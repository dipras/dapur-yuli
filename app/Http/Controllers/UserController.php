<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // Profile methods untuk user edit profil sendiri
    public function profile() {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }
    
    public function editProfile() {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function updateProfile(Request $request) {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'birth_date' => 'required|date',
                'gender' => 'required|in:male,female',
                'address' => 'required|string',
                'phone' => 'required|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                
                if (!$avatar->isValid()) {
                    return back()->withErrors(['avatar' => 'File gambar tidak valid'])->withInput();
                }
                
                // Delete old avatar if exists
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    try {
                        unlink(public_path($user->avatar));
                    } catch (\Exception $e) {
                        // Continue even if old avatar deletion fails
                    }
                }
                
                $avatarName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $avatar->getClientOriginalName());
                
                // Create directory if not exists
                $directory = public_path('images/avatars');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $avatar->move($directory, $avatarName);
                $validated['avatar'] = '/images/avatars/' . $avatarName;
            }
            
            $user->fill($validated);
            $user->save();
            
            return redirect()->route('profile.show')->with('success', 'Profile berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
    
    // Management methods untuk admin
    public function index() {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }
    
    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user) {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:admin,cashier',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                
                if (!$avatar->isValid()) {
                    return back()->withErrors(['avatar' => 'File gambar tidak valid'])->withInput();
                }
                
                // Delete old avatar if exists
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    try {
                        unlink(public_path($user->avatar));
                    } catch (\Exception $e) {
                        // Continue
                    }
                }
                
                $avatarName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $avatar->getClientOriginalName());
                
                $directory = public_path('images/avatars');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $avatar->move($directory, $avatarName);
                $validated['avatar'] = '/images/avatars/' . $avatarName;
            }
            
            $user->fill($validated);
            $user->save();
            
            return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function destroy(User $user) {
        try {
            // Prevent deleting self
            if ($user->id === Auth::id()) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri']);
            }
            
            // Delete avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                try {
                    unlink(public_path($user->avatar));
                } catch (\Exception $e) {
                    // Continue
                }
            }
            
            $user->delete();
            
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
