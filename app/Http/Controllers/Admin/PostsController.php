<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\PostDataTable;
use App\Models\Category;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ReCaptcha\RequestMethod\Post;

class PostsController extends Controller
{
    public function index(PostDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-blog')) {
            return $dataTable->render('admin.posts.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('create-blog')) {
            $category = Category::where('status', 1)->pluck('name', 'id');
            return  view('admin.posts.create', compact('category'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('create-blog')) {
            request()->validate([
                'title' => 'required',
                'slug' => 'required',
                'photo' => 'required',
                'description' => 'required',
                'category_id' => 'required',

            ]);

            if ($request->hasFile('photo')) {
                $request->validate([
                    'photo' => 'required',
                ]);
                $path = $request->file('photo')->store('posts');
            }
            $slug = str_replace(' ', '-', $request->slug);
            $post = Posts::create([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'photo' => $path,
                'short_description' => $request->short_description
            ]);

            return redirect()->route('blogs.index')->with('success', __('Post created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->can('edit-blog')) {
            $posts = Posts::find($id);
            $category = Category::where('status', 1)->pluck('name', 'id');
            return  view('admin.posts.edit', compact('posts', 'category'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-blog')) {
            request()->validate([
                'title' => 'required',
                'slug' => 'required',
                'description' => 'required',
                'category_id' => 'required',
            ]);
            $slug = str_replace(' ', '-', $request->slug);
            $post = Posts::find($id);
            if ($request->hasFile('photo')) {
                $request->validate([
                    'photo' => 'required',
                ]);
                $path = $request->file('photo')->store('posts');
                $post->photo = $path;
            }
            $post->title = $request->title;
            $post->slug = $slug;
            $post->description = $request->description;
            $post->category_id = $request->category_id;
            $post->short_description = $request->short_description;
            $post->save();
            return redirect()->route('blogs.index')->with('success', __('Posts updated successfully'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete-blog')) {
            $post = Posts::find($id);
            $post->delete();
            return redirect()->route('blogs.index')->with('success', __('Posts deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('images'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function all_post()
    {
        $categories = Category::all();
        $category = [];
        $category['0'] = __('Select category');
        foreach ($categories as $cate) {
            $category[$cate->id] = $cate->name;
        }
        $posts = Posts::all();
        return view('admin.posts.view', compact('posts', 'category'));
    }
}
