<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name ?? $user->name;
        $user->bio = $request->bio ?? $user->bio;
        $user->save();

        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();

        return response()->json(['message' => 'Account deleted']);
    }
}
