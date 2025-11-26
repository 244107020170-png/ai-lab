<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchProduct;
use App\Models\ResearchPartner;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    // =======================
    // GET ALL PRODUCTS
    // =======================
    public function products()
    {
        return response()->json(ResearchProduct::all());
    }

    // =======================
    // GET ALL PARTNERS
    // =======================
    public function partners()
    {
        return response()->json(ResearchPartner::all());
    }

    // =======================
    // CREATE PRODUCT
    // =======================
    public function store(Request $request)
    {
        // VALIDATION
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // PREPARE DATA
        $data = [
            'title' => $request->title,
            'description' => $request->description
        ];

        // SAVE IMAGE IF EXISTS
        if ($request->hasFile('image')) {
            $path = $request->file('image')
                            ->store('products', 'public'); 
            $data['image'] = "/storage/" . $path;
        }

        // INSERT INTO DATABASE
        $product = ResearchProduct::create($data);

        return response()->json([
            "message" => "Product created successfully",
            "data" => $product
        ], 201);
    }
}
