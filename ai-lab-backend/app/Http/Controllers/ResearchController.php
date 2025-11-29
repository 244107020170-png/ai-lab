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
}
