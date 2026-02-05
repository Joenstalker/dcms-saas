<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $isMongo = User::query()->getConnection()->getDriverName() === 'mongodb';
        $query = User::with($isMongo ? ['tenant'] : ['tenant', 'roles']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('tenant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Role
        if ($request->filled('role')) {
            switch ($request->role) {
                case 'superadmin':
                    $query->where('role', User::ROLE_SYSTEM_ADMIN);
                    break;
                case 'tenant_owner':
                    $query->where('role', User::ROLE_TENANT);
                    break;
                case 'dentist':
                    $query->where('role', User::ROLE_DENTIST);
                    break;
                case 'assistant':
                    $query->where('role', User::ROLE_ASSISTANT);
                    break;
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $isMongo = $user->getConnection()->getDriverName() === 'mongodb';
        $user->load($isMongo ? ['tenant'] : ['tenant', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(User $user): RedirectResponse
    {
        // Prevent disabling self
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot disable your own account.');
        }

        // We don't have an is_active column on users table yet based on previous file reads, 
        // but often it's on the user or we rely on the tenant status.
        // Checking User model again... it didn't show is_active in fillable.
        // Let's re-check User model before adding this. 
        // For now, I will omit toggleActive until confirmed, or just return back.
        
        return redirect()->back()->with('warning', 'User activation toggling not yet implemented.');
    }

    public function destroy(User $user): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting system admins
        if ($user->is_system_admin) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'System administrators cannot be deleted.'], 403);
            }
            return redirect()->back()->with('error', 'System administrators cannot be deleted.');
        }

        // Prevent deleting tenant owners unless tenant is terminated
        if ($user->isOwner()) {
            $tenant = $user->tenant;
            if ($tenant && !$tenant->isTerminated()) {
                $message = 'Cannot delete tenant owner. Please suspend or terminate the clinic first.';
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 403);
                }
                return redirect()->back()->with('error', $message);
            }
        }

        $userName = $user->name;
        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "User '{$userName}' has been deleted."]);
        }

        return redirect()->back()->with('success', "User '{$userName}' has been deleted.");
    }
}

