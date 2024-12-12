<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userservice;
    protected $roles = ['admin', 'staff', 'procurement', 'logistic'];
    public function __construct(UserService $userservice)
    {
        $this->userservice = $userservice;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanitize = handleSanitize(request()->input('search', ''));
        if (isset($sanitize) && $sanitize !== '') {
            $title = 'User List';
            $users = $this->userservice->searchUser($sanitize);
            return view('pages.user.index', compact('title', 'users'));
        } else {
            $title = 'User List';
            $users = $this->userservice->getAllUsers();
            return view('pages.user.index', compact('title', 'users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New User';
        $roles = $this->roles;
        return view('pages.user.create', compact('title', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $this->userservice->createUser($request->all());
            return redirect()->route('user.index')->with('success', 'User created successfully');
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $title = 'Edit User';
        $roles = $this->roles;
        $user = $this->userservice->getUserById($user->id);
        return view('pages.user.edit', compact('title', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $this->userservice->updateUser($user->id, $request->all());
            return redirect()->route('user.index')->with('success', 'User updated successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userservice->deleteUser($user->id);
            return redirect()->route('user.index')->with('success', 'User deleted successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
}
