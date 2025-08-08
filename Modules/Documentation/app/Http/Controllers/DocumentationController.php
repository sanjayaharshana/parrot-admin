<?php

namespace Modules\Documentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Documentation\Models\DocumentationCategory;
use Modules\Documentation\Models\DocumentationPage;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = DocumentationCategory::active()->ordered()->with(['activePages' => function ($query) {
            $query->ordered();
        }])->get();

        $featuredPages = DocumentationPage::featured()->active()->with('category')->ordered()->limit(6)->get();

        return view('documentation::index', compact('categories', 'featuredPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DocumentationCategory::active()->ordered()->get();
        return view('documentation::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:documentation_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:documentation_pages',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DocumentationPage::create($validated);

        return redirect()->route('documentation.index')->with('success', 'Documentation page created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $page = DocumentationPage::with('category')->findOrFail($id);
        $relatedPages = DocumentationPage::where('category_id', $page->category_id)
            ->where('id', '!=', $page->id)
            ->active()
            ->ordered()
            ->limit(5)
            ->get();

        return view('documentation::show', compact('page', 'relatedPages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = DocumentationPage::findOrFail($id);
        $categories = DocumentationCategory::active()->ordered()->get();
        
        return view('documentation::edit', compact('page', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = DocumentationPage::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:documentation_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:documentation_pages,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $page->update($validated);

        return redirect()->route('documentation.show', $page)->with('success', 'Documentation page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = DocumentationPage::findOrFail($id);
        $page->delete();

        return redirect()->route('documentation.index')->with('success', 'Documentation page deleted successfully.');
    }

    /**
     * Show documentation by category.
     */
    public function category($slug)
    {
        $category = DocumentationCategory::where('slug', $slug)->active()->firstOrFail();
        $pages = $category->activePages()->ordered()->paginate(12);

        return view('documentation::category', compact('category', 'pages'));
    }

    /**
     * Show documentation by page slug.
     */
    public function page($categorySlug, $pageSlug)
    {
        $category = DocumentationCategory::where('slug', $categorySlug)->active()->firstOrFail();
        $page = DocumentationPage::where('slug', $pageSlug)
            ->where('category_id', $category->id)
            ->active()
            ->firstOrFail();

        $relatedPages = DocumentationPage::where('category_id', $category->id)
            ->where('id', '!=', $page->id)
            ->active()
            ->ordered()
            ->limit(5)
            ->get();

        return view('documentation::page', compact('category', 'page', 'relatedPages'));
    }
}
