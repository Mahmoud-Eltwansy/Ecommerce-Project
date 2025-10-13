<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($name = $request->input('name')) {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if ($status = $request->input('status')) {
            $query->whereStatus($status);
        }

        $categories = $query->paginate(2);
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();

        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Request Validation
        $request->validate(Category::rules());

        // Handle New Image
        $data = $request->except('image');
        $data['image'] = $this->uploadImage($request);

        // INSERT INTO categories
        $category = Category::create($data);


        // Redirect With Message
        return redirect(route('dashboard.categories.index'))->with('success', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')->with('info', 'Page Not Found');
        }



        // SELECT * FROM categories WHERE id <> $id AND (parent_id IS NULL OR parent_id <> $id)
        // get all categories except the one we want to edit and its children
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', '<>', $id);
            })->get();

        // Return Edit View with data
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Request Validation
        $request->validate(Category::rules($id));

        // Getting Category and its Old Image
        $category = Category::findOrFail($id);
        $old_image = $category->image;

        // Prepare Data
        $data = $request->except('image');

        // Handle New Image
        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['image'] = $this->uploadImage($request);
        }

        // Update
        $category->update($data);

        // Delete the old image if exists and there is a new one uploaded
        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        // Redirect With Message
        return redirect()->route('dashboard.categories.index')->with('success', 'Category Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the category and delete
        $category = Category::findOrFail($id);
        $category->delete();

        // Delete Image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Redirect With Message
        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category Deleted!');
    }

    public function uploadImage(Request $request)
    {
        // Check If Request Has File
        if (!$request->hasFile('image')) {
            return;
        }
        // Upload File s the uploads Folder and return path
        $file = $request->file('image');
        $path = $file->store('uploads', ['disk' => 'public']);
        return $path;
    }
}
