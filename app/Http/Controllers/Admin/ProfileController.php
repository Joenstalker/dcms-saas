<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('admin.profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'photo' => ['nullable', 'image', 'max:1024'],
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            $base64 = $this->imageToBase64($request->file('photo'));
            $user->profile_photo_data = $base64;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Password updated successfully.');
    }

    public function updatePhoto(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'photo_data' => ['required', 'string'],
        ]);

        $user = $request->user();
        $base64Data = $request->input('photo_data');

        if (!$this->isValidBase64Image($base64Data)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid image data.'], 422);
            }
            return back()->with('error', 'Invalid image data.');
        }

        $user->profile_photo_data = $base64Data;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Profile photo updated successfully!',
                'photo_url' => $base64Data
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully.');
    }

    protected function imageToBase64($file): string
    {
        $imageData = file_get_contents($file->getRealPath());
        $imageInfo = getimagesizefromstring($imageData);

        if ($imageInfo === false) {
            throw new \Exception('Invalid image file');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $type = $imageInfo[2];

        $maxSize = 400;
        $ratio = min($maxSize / $width, $maxSize / $height);

        if ($ratio < 1) {
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $srcImage = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_GIF => imagecreatefromgif($file->getRealPath()),
            default => throw new \Exception('Unsupported image type'),
        };

        if (!$srcImage) {
            throw new \Exception('Failed to create image from file');
        }

        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 0, 0, 0, 127);
            imagefill($dstImage, 0, 0, $transparent);
        }

        imagecopyresampled(
            $dstImage, $srcImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );

        imagedestroy($srcImage);

        ob_start();
        imagejpeg($dstImage, null, 85);
        $encoded = ob_get_clean();

        imagedestroy($dstImage);

        return 'data:image/jpeg;base64,' . base64_encode($encoded);
    }

    protected function isValidBase64Image(string $base64): bool
    {
        if (!preg_match('/^data:image\/(jpeg|png|gif|webp);base64,/', $base64, $matches)) {
            return false;
        }

        $base64Data = preg_replace('/^data:image\/(jpeg|png|gif|webp);base64,/', '', $base64);

        if (!$base64Data) {
            return false;
        }

        $decoded = base64_decode($base64Data, true);

        if ($decoded === false) {
            return false;
        }

        $imageInfo = @getimagesize('data://image/jpeg;base64,' . $base64Data);

        if ($imageInfo === false || !in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP])) {
            return false;
        }

        return strlen($decoded) <= 1024 * 1024;
    }
}
