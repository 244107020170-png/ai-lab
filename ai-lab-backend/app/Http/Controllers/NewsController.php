<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // GET /api/news -> all news (with optional ?category=)
    public function index(Request $request)
    {
        $q = News::where('status', 'main'); // <-- FILTER HANYA MAIN

        if ($request->has('category') && $request->category !== 'all') {
            $q->where('category', $request->category);
        }

        $items = $q->orderBy('date', 'desc')->get();

        return response()->json($items);
    }

    // GET /api/news/latest -> 3 latest
    public function latest()
    {
        $items = News::where('status', 'main') // <-- FILTER MAIN
            ->orderBy('date', 'desc')
            ->limit(3)
            ->get();

        return response()->json($items);
    }

    // GET /api/news/{id}
    public function show($id)
    {
        $item = News::where('status', 'main') // <-- Tidak tampilkan none
            ->find($id);

        if (!$item)
            return response()->json(['message' => 'Not found'], 404);

        return response()->json($item);
    }

    // (optional) admin store (tidak dipakai web profile)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'category'=>'nullable|string',
            'date'=>'nullable|date',
            'image_thumb'=>'nullable|string',
            'image_detail'=>'nullable|string',
            'excerpt'=>'nullable|string',
            'content'=>'nullable|string',
            'quote'=>'nullable|string',
            'status'=>'nullable|string',
        ]);

        $news = News::create($data);
        return response()->json($news, 201);
    }
}
