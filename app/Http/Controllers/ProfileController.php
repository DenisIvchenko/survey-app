<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // 1. Обновляем текстовые поля (явно, чтобы избежать проблем с mass assignment)
        $user->name = $data['name'];
        $user->email = $data['email'];

        // 2. Обработка загрузки фото
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            
            if ($file->isValid()) {
                // Удаляем старое фото с диска, если оно было
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                
                // Сохраняем новое фото и получаем путь
                $path = $file->store('avatars', 'public');
                
                // Явно записываем путь в модель
                $user->profile_photo_path = $path;
                
                \Log::info('Photo saved', ['path' => $path]);
            } else {
                \Log::error('Photo upload failed', ['error' => $file->getErrorMessage()]);
            }
        }

        // 3. Сброс верификации при смене email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Отладка перед сохранением
        \Log::info('Saving user', [
            'name' => $user->name,
            'email' => $user->email,
            'photo_path' => $user->profile_photo_path,
            'is_dirty' => $user->isDirty(),
        ]);

        // 5. Сохраняем изменения в БД
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Удаляем фото с диска при удалении аккаунта
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}