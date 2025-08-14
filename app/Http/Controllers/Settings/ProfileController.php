<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'status' => $request->session()->get('status'),
            'user' => $request->user()->only(['name', 'email', 'signature']),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Handle signature if provided and not empty
        if ($request->has('signature') && $request->signature && $request->signature !== '') {
            $signatureData = $request->signature;
            if (strpos($signatureData, 'data:image/') === 0) {
                // Delete old signature if exists
                if ($request->user()->signature) {
                    \Storage::disk('public')->delete($request->user()->signature);
                }
                
                // Convert base64 to file and store
                $imageData = base64_decode(explode(',', $signatureData)[1]);
                $filename = 'signature_' . $request->user()->id . '_' . time() . '.png';
                $path = 'signatures/' . $filename;
                
                // Store file
                \Storage::disk('public')->put($path, $imageData);
                $data['signature'] = $path;
            }
        } else {
            // If signature is empty or not provided, remove it from data to keep existing
            unset($data['signature']);
        }
        
        $request->user()->fill($data);
        $request->user()->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
