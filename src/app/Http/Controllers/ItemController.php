<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function update(Request $request)
    {
        return redirect()->route('home');
    }

    public function show()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $path = $validated['item_image']->store('public/item_images');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $validated['item_name'],
            'brand_name' => $request->input('item_brand'),
            'condition_id' => $validated['item_condition'],
            'description' => $validated['item_description'],
            'price' => $validated['item_price'],
            'image_path' => $path,
        ]);

        $item->categories()->sync($validated['item_category']);

        return redirect()->route('top');
    }
}
