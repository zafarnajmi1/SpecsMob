<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::latest()->paginate(10);
        return view('admin-views.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-views.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('tags')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                }),
            ],
            'type' => 'required|in:review,news,article,device,brand',
        ]);

        Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'is_popular' => $request->has('is_popular') ? true : false,
        ]);

        ToastMagic::success('Tag created successfully.');
        return redirect()->route('admin.tags.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return redirect()->route('admin.tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin-views.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('tags')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                })->ignore($tag->id),
            ],
            'type' => 'required|in:review,news,article,device,brand',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'is_popular' => $request->has('is_popular') ? true : false,
        ]);

        ToastMagic::success('Tag updated successfully.');
        return redirect()->route('admin.tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        ToastMagic::success('Tag deleted successfully.');
        return redirect()->route('admin.tags.index');
    }
}
