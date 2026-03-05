<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Danh sách users
     */
    public function index()
    {
        $users = User::where('role', 'user')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Danh sách owners
     */
    public function owners()
    {
        $owners = User::where('role', 'owner')->withCount('fields')->paginate(20);
        return view('admin.users.owners', compact('owners'));
    }

    /**
     * Duyệt owner
     */
    public function approveOwner($id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'owner']);

        return back()->with('success', 'Đã duyệt chủ sân.');
    }

    /**
     * Chuyển user thành owner
     */
    public function convertToOwner($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'user') {
            return back()->withErrors(['error' => 'Chỉ có thể chuyển user thành owner.']);
        }

        $user->update(['role' => 'owner']);

        return back()->with('success', 'Đã chuyển user thành owner.');
    }

    /**
     * Xóa user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Không cho xóa admin
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Không thể xóa admin.']);
        }

        $user->delete();

        return back()->with('success', 'Đã xóa người dùng.');
    }
}
