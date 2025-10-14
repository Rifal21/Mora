<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::where('status', 'active')
            ->select('id', 'name', 'slug', 'image')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
