<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\Tenant;
use App\Models\TenantSetting;
use App\Services\TenantBrandingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private TenantBrandingService $brandingService;

    public function __construct(TenantBrandingService $brandingService)
    {
        $this->brandingService = $brandingService;
    }
    public function index(Tenant $tenant): View
    {
        $user = auth()->user();

        if (!$user || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $platformSettings = PlatformSetting::first();
        $tenantSettings = TenantSetting::where('tenant_id', $tenant->id)->first();

        $canCustomize = true;

        return view('tenant.settings.branding', [
            'tenant' => $tenant,
            'platformSettings' => $platformSettings,
            'tenantSettings' => $tenantSettings,
            'canCustomize' => $canCustomize,
        ]);
    }

    public function update(Tenant $tenant, Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (!$user || $user->tenant_id !== $tenant->id || !$user->isOwner()) {
            abort(403);
        }

        $validated = $request->validate([
            'theme_color_primary' => 'nullable|string|max:7',
            'theme_color_secondary' => 'nullable|string|max:7',
            'theme_color_accent' => 'nullable|string|max:7',
            'theme_color_neutral' => 'nullable|string|max:7',
            'theme_color_info' => 'nullable|string|max:7',
            'theme_color_success' => 'nullable|string|max:7',
            'theme_color_warning' => 'nullable|string|max:7',
            'theme_color_error' => 'nullable|string|max:7',
            'sidebar_position' => 'required|in:left,right',
            'sidebar_collapsed' => 'nullable|boolean',
            'font_family_heading' => 'nullable|string|max:100',
            'font_family_body' => 'nullable|string|max:100',
            'custom_brand_name' => 'nullable|string|max:100',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'dark_logo_path' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'favicon_path' => 'nullable|image|mimes:png,ico,svg|max:512',
            'remove_logo' => 'nullable|boolean',
            'remove_dark_logo' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
        ]);

        $settings = TenantSetting::firstOrCreate(['tenant_id' => $tenant->id]);

        $settings->theme_color_primary = $validated['theme_color_primary'] ?? $settings->theme_color_primary ?? '#3b82f6';
        $settings->theme_color_secondary = $validated['theme_color_secondary'] ?? $settings->theme_color_secondary ?? '#6366f1';
        $settings->theme_color_accent = $validated['theme_color_accent'] ?? $settings->theme_color_accent ?? '#0ea5e9';
        $settings->theme_color_neutral = $validated['theme_color_neutral'] ?? $settings->theme_color_neutral ?? '#6b7280';
        $settings->theme_color_info = $validated['theme_color_info'] ?? $settings->theme_color_info ?? '#3b82f6';
        $settings->theme_color_success = $validated['theme_color_success'] ?? $settings->theme_color_success ?? '#22c55e';
        $settings->theme_color_warning = $validated['theme_color_warning'] ?? $settings->theme_color_warning ?? '#f59e0b';
        $settings->theme_color_error = $validated['theme_color_error'] ?? $settings->theme_color_error ?? '#ef4444';
        $settings->sidebar_position = $validated['sidebar_position'];
        $settings->sidebar_collapsed = isset($validated['sidebar_collapsed']) && $validated['sidebar_collapsed'];
        $settings->font_family_heading = $validated['font_family_heading'] ?? $settings->font_family_heading ?? 'Figtree';
        $settings->font_family_body = $validated['font_family_body'] ?? $settings->font_family_body ?? 'Inter';
        $settings->custom_brand_name = $validated['custom_brand_name'] ?? null;

        if ($request->hasFile('logo_path')) {
            $logoFile = $request->file('logo_path');
            if (!$tenant->hasEnoughStorage($logoFile->getSize())) {
                return redirect()->back()->with('error', 'Storage limit reached! Please upgrade your plan.');
            }
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $logoFile->store('tenant-branding', 'public');
        } elseif (isset($validated['remove_logo']) && $validated['remove_logo']) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = null;
        }

        if ($request->hasFile('dark_logo_path')) {
            $logoFile = $request->file('dark_logo_path');
            if (!$tenant->hasEnoughStorage($logoFile->getSize())) {
                return redirect()->back()->with('error', 'Storage limit reached! Please upgrade your plan.');
            }
            if ($settings->dark_logo_path) {
                Storage::disk('public')->delete($settings->dark_logo_path);
            }
            $settings->dark_logo_path = $logoFile->store('tenant-branding', 'public');
        } elseif (isset($validated['remove_dark_logo']) && $validated['remove_dark_logo']) {
            if ($settings->dark_logo_path) {
                Storage::disk('public')->delete($settings->dark_logo_path);
            }
            $settings->dark_logo_path = null;
        }

        if ($request->hasFile('favicon_path')) {
            $faviconFile = $request->file('favicon_path');
            if (!$tenant->hasEnoughStorage($faviconFile->getSize())) {
                return redirect()->back()->with('error', 'Storage limit reached! Please upgrade your plan.');
            }
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $faviconFile->store('tenant-branding', 'public');
        } elseif (isset($validated['remove_favicon']) && $validated['remove_favicon']) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = null;
        }

        $settings->save();

        $this->brandingService->clearSettingsCache($tenant);

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Branding settings updated successfully!');
    }

    public function updatePassword(Tenant $tenant, Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (!$user || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_reset_password' => false,
        ]);

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Password updated successfully.');
    }

    public function updateProfilePhoto(Tenant $tenant, Request $request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if (!$user || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $request->validate([
            'photo_data' => ['required', 'string'],
        ]);

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

        return redirect()->route('tenant.settings.index', ['tenant' => $tenant->slug])
            ->with('success', 'Profile photo updated successfully.');
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

    protected function processImageToBase64($file): string
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
}
