<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use App\Models\{
    User,
    Role,
};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        // Check and fix role_user table structure if needed
        $this->checkRoleUserTable();
    }

    /**
     * Check and fix role_user table structure if needed
     */
    private function checkRoleUserTable()
    {
        try {
            // Check if role_user table exists
            if (!\Schema::hasTable('role_user')) {
                \Schema::create('role_user', function ($table) {
                    $table->unsignedBigInteger('user_id');
                    $table->unsignedBigInteger('role_id');
                    $table->timestamps();

                    $table->primary(['user_id', 'role_id']);

                    $table->foreign('user_id')
                          ->references('id')
                          ->on('users')
                          ->onDelete('cascade');

                    $table->foreign('role_id')
                          ->references('id')
                          ->on('roles')
                          ->onDelete('cascade');
                });

                \Log::info('Created role_user table');
            }

            // Check if timestamp columns exist
            if (!\Schema::hasColumn('role_user', 'created_at')) {
                \Schema::table('role_user', function ($table) {
                    $table->timestamps();
                });
                \Log::info('Added timestamps to role_user table');
            }
        } catch (\Exception $e) {
            \Log::error('Error checking/fixing role_user table: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index',['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create',['roles'=>$roles]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:100', 'regex:/^[A-Za-z\s\.\-]+$/'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => [
                'required',
                'min:6',
                'max:15',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#+=~])[A-Za-z\d@$!%*?&#+=~\.]{6,15}$/'
            ],
            'roles' => ['required', 'array']
        ]);

        // Ensure password is properly hashed
        $userData = $request->only(['name', 'email']);
        $userData['password'] = Hash::make($request->password);

        $user = User::create($userData);
        $user->roles()->attach($request->input('roles'));
        return redirect()->back()->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show',['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit',['user'=>$user,'roles'=>$roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        // Check if current user is a Super Admin
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser && $currentUser->isSuperAdmin();

        // If not a super admin, prevent changing roles
        if (!$isSuperAdmin) {
            // Redirect back with error message
            return redirect()->back()->with('error', 'Only Super Admins can update user roles.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:5',
            'roles' => 'required|array'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password only if it's provided
        if($request->filled('password')){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Just use sync directly like in the working project
        $user->roles()->sync($request->input('roles'));

        // Add some logging for debugging purposes
        \Log::info('User roles updated', [
            'user_id' => $user->id,
            'roles' => $request->input('roles')
        ]);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();
        return redirect()->back()->with('success','User deleted successfully');
    }
}
