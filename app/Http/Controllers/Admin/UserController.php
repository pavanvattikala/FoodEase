<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ModuleHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Models\EmployeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get Employee Categories ( Only Enabled Modules )
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getEmployeeCategories()
    {
        // Fetch employee categories for the dropdown dont include disabled modules
        $disabledModules = ModuleHelper::getDiabledModules();

        $categories = EmployeeCategory::whereNotIn('name', [...$disabledModules])->get();

        return $categories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch users along with their category name
        $users = User::join('employee_categories', 'users.category_id', '=', 'employee_categories.id')
            ->select('users.*', 'employee_categories.name as category')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $categories = $this->getEmployeeCategories();

        return view('admin.users.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        // if password is not provided, set it to default value
        if (!$request->filled('password')) {
            $request->merge(['password' => env('DEFAULT_USER_PASSWORD')]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'pin' => $request->pin,
            'password' => Hash::make($request->password),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $categories = $this->getEmployeeCategories();

        return view('admin.users.edit', compact('user', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserStoreRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'pin' => $request->pin,
            'category_id' => $request->category_id,
        ];

        // Update password if it is provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('danger', 'User deleted successfully.');
    }
}
