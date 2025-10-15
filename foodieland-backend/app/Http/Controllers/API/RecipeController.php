<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        return Recipe::with('user', 'category')->paginate(10);
    }

    public function show($slug)
    {
        return Recipe::where('slug', $slug)->with('user', 'category')->firstOrFail();
    }

     public function store(StoreRecipeRequest $request)
    {
        
    }
}
