<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\CategoryDataTable;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-category')) {
            return $dataTable->render('admin.category.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-category')) {
            $category = Category::all();
            return view('admin.category.create', compact('category'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create-category')) {
            request()->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            Category::create([
                'name' => $request->name,
                'status' => $request->status
            ]);
            return redirect()->route('category.index')->with('success', __('Category created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-category')) {
            $category = Category::find($id);
            return view('admin.category.edit', compact('category'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-category')) {
            request()->validate([
                'name' => 'required',
                'status' => 'required',
            ]);
            $category = Category::find($id);
            $category->name = $request->name;
            $category->status = $request->status;
            $category->update();
            return redirect()->route('category.index')->with('success', __('Category updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-category')) {
            $category = Category::find($id);
            $category->delete();
            return redirect()->route('category.index')->with('success', __('Category deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function categorystatus($id)
    {
        $category = Category::find($id);
        if ($category->status != 1) {
            $category->status = 1;
            $category->save();
            return redirect()->route('category.index')->with('success', __('Category activate successfully.'));
        } else {
            $category->status = 0;
            $category->save();
            return redirect()->route('category.index')->with('success', __('Category deactivate successfully.'));
        }
    }
}
