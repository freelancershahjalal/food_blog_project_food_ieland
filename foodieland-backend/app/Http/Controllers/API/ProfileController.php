<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        // Get all validated data EXCEPT the image file itself.
        $dataToUpdate = $request->safe()->except('profile_image');

        // 1. Check if a new file is present in the request.
        if ($request->hasFile('profile_image')) {
            // 2. Delete the old image if it exists.
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // 3. Store the new file and get its path.
            $path = $request->file('profile_image')->store('profiles', 'public');

            // 4. Add the new path to our data array. THIS IS THE CRITICAL FIX.
            $dataToUpdate['profile_image'] = $path;
        }

        // 5. Update the user model with all the data at once.
        $user->update($dataToUpdate);

        // We use fresh() to get the updated model from the database to be 100% sure.
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user->fresh(),
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // Optional: Also delete the user's profile image from storage
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return response()->json(['message' => 'Account successfully deleted.']);
    }
}
