<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with(['product', 'branch'])->get();
        return view('inventory.index', compact('inventories'));
    }

    public function create()
{
    $branches = Branch::all();
    return view('inventory.create', compact('branches'));
}

 public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'category'  => 'required|string|max:255',
        'price'     => 'required|numeric|min:0',
        'branch_id' => 'required|exists:branches,id',
        'stock'     => 'required|integer|min:0',
    ]);

    // Create the product first
    $product = \App\Models\Product::create([
        'name'     => $request->name,
        'category' => $request->category,
        'price'    => $request->price,
    ]);

    // Then create the inventory record linking it to the branch
    \App\Models\Inventory::create([
        'product_id' => $product->id,
        'branch_id'  => $request->branch_id,
        'stock'      => $request->stock,
    ]);

    return redirect()->route('inventory.index')
        ->with('success', 'Product added to inventory successfully!');
}

    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        $branches = Branch::all();
        return view('inventory.edit', compact('inventory', 'products', 'branches'));
    }

    public function update(Request $request, Inventory $inventory)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'category'  => 'required|string|max:255',
        'price'     => 'required|numeric|min:0',
        'branch_id' => 'required|exists:branches,id',
        'stock'     => 'required|integer|min:0',
    ]);

    // Update the product details
    $inventory->product->update([
        'name'     => $request->name,
        'category' => $request->category,
        'price'    => $request->price,
    ]);

    // Update the inventory record
    $inventory->update([
        'branch_id' => $request->branch_id,
        'stock'     => $request->stock,
    ]);

    return redirect()->route('inventory.index')
        ->with('success', 'Product updated successfully!');
}

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory record deleted successfully!');
    }
}