<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search') ?: null;
        $users = User::when($search, function ($query) use ($search) {
                        return $query->where('name', 'LIKE', '%' . $search . '%')
                                    ->orWhere('email', 'LIKE', '%' . $search . '%');
                    })
                    ->where('is_admin', 0)
                    ->orderByDesc('created_at')
                    ->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
			'name'	=> 'required|max:255',
            'email' => 'required|unique:users,email|email',
		]);

        DB::beginTransaction();

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->is_admin = 0;
            $user->password = Hash::make($request->password);
            $user->save();
            
            DB::commit();

            return redirect()->route('admin.user.index')->withSuccess('User created');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.user.index')->withError('Failed to create User');
        } 
    }

    public function show($id)
    {
        $user = User::where('is_admin', 0)->find($id);

        if (!$user) {
            return redirect()->route('admin.user.index')->withError('User not found');
        }

        return view('admin.user.show', compact('user'));
    }

    public function getUser($id)
    {
        $user = User::where('is_admin', 0)->where('id', $id)->first();
        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        if (!$user)
            return redirect()->back()->withError('User not found');

        DB::beginTransaction();

        try {
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password)
                $user->password = Hash::make($request->password);

            $user->save();
            
            DB::commit();

            return redirect()->back()->withSuccess('User updated');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withError('Failed to update user');
        } 
    }

    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->route('admin.user.index')->withSuccess('User deleted');
        } else {
            return redirect()->route('admin.user.index')->withErrors('Failed to delete user');
        }
    }
}
