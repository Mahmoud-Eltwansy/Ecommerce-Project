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
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::with(['parent'])
            ->withCount('products')
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate();
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
    public function show(Category $category)
    {

        // $category = Category::with(['products.store'])->findOrFail($id);
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // SELECT * FROM categories WHERE id <> $id AND (parent_id IS NULL OR parent_id <> $id)
        // get all categories except the one we want to edit and its children
        $parents = Category::where('id', '<>', $id = $category->id)
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
    public function update(Request $request, Category $category)
    {

        // Request Validation
        $request->validate(Category::rules($category->id));

        // Getting Category and its Old Image
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
    public function destroy(Category $category)
    {

        $category->delete();

        // Redirect With Message
        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category Deleted!');
    }

    /**
     * Get all trashed categories.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function trash(Request $request)
    {

        $categories = Category::onlyTrashed()->filter($request->query())->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    /**
     * Restore a trashed category.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, $id)
    {

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')->with('success', 'Category Restored Succussfully!');
    }

    /**
     * Permanently delete a category.
     *
     * This method will delete a category without the ability to restore it.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        // Delete Image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }


        return redirect()->route('dashboard.categories.trash')->with('success', 'Category Deleted Forever!');
    }



    /**
     * Uploads a file to the uploads folder and returns the path.
     *
     * @param \Illuminate\Http\Request $request
     */
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
