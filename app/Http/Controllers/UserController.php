<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\branchDel;
use App\Models\Userrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── Show all users ──────────────────────────────────────────────────────
    public function showUsers()
    {
        $branch_code = auth()->user()->BC;
        $user_name   = auth()->user()->username;
        $branch      = branchDel::all();
        $userrole    = Userrole::all();

        if (in_array($user_name, ['admin', 'developer'])) {
            $data = User::latest()->paginate(200);
        } else {
            $data = User::latest()->where('BC', $branch_code)->paginate(100);
        }

        return view('users')
            ->with('Branch',   $branch)
            ->with('Userrole', $userrole)
            ->with('users',    $data);
    }

    // ── Show add-user form (standalone page, kept for fallback) ─────────────
    public function showAddUser()
    {
        $branch   = branchDel::all();
        $userrole = Userrole::all();

        return view('addUserForm')
            ->with('Userrole', $userrole)
            ->with('Branch',   $branch);
    }

    public function showRoles()
    {
        return view('roles');
    }

    // ── Add user (form submit, redirect) ────────────────────────────────────
    public function AddUser(Request $request)
    {
        $request->validate([
            'user_name' => 'required|max:50|unique:users,username',
            'name'      => 'required|max:100',
            'password'  => 'required|min:5',
            'role'      => 'required',
            'Branch'    => 'required',
        ]);

        $data           = new User;
        $data->email    = $request->email;
        $data->name     = $request->name;
        $data->username = $request->user_name;
        $data->password = Hash::make($request->password);
        $data->role     = $request->role;
        $data->Branch   = $request->Branch;
        $data->BC       = $request->BC;
        $data->save();

        return redirect('/users')->with('added', 'User Added Successfully');
    }

    // ── Add user via AJAX (modal form) ───────────────────────────────────────
    public function addUserAjax(Request $request)
    {
        $request->validate([
            'user_name' => 'required|max:50|unique:users,username',
            'name'      => 'required|max:100',
            'password'  => 'required|min:5',
            'role'      => 'required',
            'Branch'    => 'required',
        ]);

        $data           = new User;
        $data->email    = $request->email;
        $data->name     = $request->name;
        $data->username = $request->user_name;
        $data->password = Hash::make($request->password);
        $data->role     = $request->role;
        $data->Branch   = $request->Branch;
        $data->BC       = $request->BC;
        $data->save();

        return response()->json(['status' => 'success']);
    }

    // ── Delete user (redirect) ───────────────────────────────────────────────
    public function deleteUsers($id)
    {
        User::find($id)->delete();
        return redirect()->back()->with('delete', 'User deleted');
    }

    // ── Edit user (standalone page) ──────────────────────────────────────────
    public function editUsers($id)
    {
        $data = User::find($id);
        return view('editUser')->with('users', $data);
    }

    // ── Delete user via AJAX ─────────────────────────────────────────────────
    public function delete(Request $request)
    {
        User::find($request->user_id)->delete();
        return response()->json(['status' => 'success']);
    }

    // ── Update user via AJAX ─────────────────────────────────────────────────
    public function update(Request $request)
    {
        $request->validate([
            'up_user_name' => 'required|max:50|unique:users,username,' . $request->up_id,
            'up_name'      => 'required|max:100',
            'up_role'      => 'required',
            'up_Branch'    => 'required',
        ]);

        $updateData = [
            'name'     => $request->up_name,
            'username' => $request->up_user_name,
            'email'    => $request->up_email,
            'role'     => $request->up_role,
            'Branch'   => $request->up_Branch,
            'BC'       => $request->up_BC,
        ];

        // Only update password if provided
        if (!empty($request->up_password)) {
            $updateData['password'] = Hash::make($request->up_password);
        }

        User::where('id', $request->up_id)->update($updateData);

        return response()->json(['status' => 'success']);
    }

    // ── Search users via AJAX ────────────────────────────────────────────────
    public function search(Request $request)
    {
        $data = User::where('name',     'like', '%' . $request->search_string . '%')
            ->orWhere('username', 'like', '%' . $request->search_string . '%')
            ->orWhere('email',    'like', '%' . $request->search_string . '%')
            ->orWhere('role',     'like', '%' . $request->search_string . '%')
            ->orderBy('id', 'desc')
            ->paginate(100);

        if ($data->count() >= 1) {
            return view('customer_pagination')->with('customers', $data)->render();
        }

        return response()->json(['status' => 'not_found']);
    }

    // ── Get branch code for selected branch ──────────────────────────────────
    public function getUser(Request $request)
    {
        $data = branchDel::where('name', $request->category)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    // ── Add new Role via AJAX ─────────────────────────────────────────────────
    // Saves to: userroles (id, role_code, role_name, BC, OC, created_at, updated_at)
    public function addRole(Request $request)
    {
        $request->validate([
            'role_name' => 'required|max:100|unique:userroles,role_name',
        ]);

        $role            = new Userrole;
        $role->role_code = strtoupper(trim($request->role_code));
        $role->role_name = $request->role_name;
        $role->BC        = $request->BC ?? null;
        $role->OC        = $request->OC ?? null;
        $role->save();

        return response()->json([
            'status'    => 'success',
            'role_name' => $role->role_name,
            'role_code' => $role->role_code,
        ]);
    }
}