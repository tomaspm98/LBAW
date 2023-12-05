<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Moderator;
use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function create(Request $request)
{
    $this->authorize('create', Admin::class);
    $validatedData = $request->validate([
        'tag_name' => 'required|unique:tag|max:255',
        'tag_description' => 'required',
    ]);

    $tag = Tag::create([
        'tag_name' => $validatedData['tag_name'],
        'tag_description' => $validatedData['tag_description'],
    ]);

    $this->authorize('create', Admin::class);

    return redirect()->route('tags.show')->with('success','Tag created Successfully');

}
    

}