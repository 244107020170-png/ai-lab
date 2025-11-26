<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // GET /api/news -> all news (with optional ?category=)
    public function index(Request $request)
    {
        $q = News::query();

        if ($request->has('category') && $request->category !== 'all') {
            $q->where('category', $request->category);
        }

        // paginate or return all — use 100 to be safe
        $items = $q->orderBy('date','desc')->get();

        return response()->json($items);
    }

    // GET /api/news/latest -> 3 latest
    public function latest()
    {
        $items = News::orderBy('date','desc')->limit(3)->get();
        return response()->json($items);
    }

    // GET /api/news/{id}
    public function show($id)
    {
        $item = News::find($id);
        if (!$item) return response()->json(['message'=>'Not found'], 404);
        return response()->json($item);
    }

    // (optional) admin store route
    public function store(Request $request)
    {
        // you should protect this route with auth in real app
        $data = $request->validate([
            'title'=>'required|string',
            'category'=>'nullable|string',
            'date'=>'nullable|date',
            'image_thumb'=>'nullable|string',
            'image_detail'=>'nullable|string',
            'excerpt'=>'nullable|string',
            'content'=>'nullable|string',
            'quote'=>'nullable|string',
        ]);
        $news = News::create($data);
        return response()->json($news, 201);
    }
}
