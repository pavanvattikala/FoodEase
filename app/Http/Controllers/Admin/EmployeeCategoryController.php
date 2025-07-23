<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCategory;

use Illuminate\Http\Request;

class EmployeeCategoryController extends Controller
{
    public function index()
    {
        $categories = EmployeeCategory::all();
        return response()->json(['data' => $categories]);
    }

    public function show($id)
    {
        $category = EmployeeCategory::findOrFail($id);
        return response()->json(['data' => $category]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $category = EmployeeCategory::create($request->all());
        return response()->json(['data' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $category = EmployeeCategory::findOrFail($id);
        $category->update($request->all());

        return response()->json(['data' => $category]);
    }

    public function destroy($id)
    {
        $category = EmployeeCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
