<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockIntake;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function check(Request $request)
    {
        $search = $request->get('search', '');
        $query = Product::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $allProducts = $query->orderBy('name')->get();
        return view('inventory.check', compact('allProducts', 'search'));
    }

    public function intake(Request $request)
    {
        $search = $request->get('search', '');
        $query = StockIntake::with(['product', 'creator']);

        if (!empty($search)) {
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $recentIntakes = $query->orderBy('intake_date', 'desc')->orderBy('id', 'desc')->get();
        $products = Product::orderBy('name')->get();

        return view('inventory.intake', compact('recentIntakes', 'products', 'search'));
    }

    public function storeIntake(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_cost' => 'required|numeric|min:0.01',
            'intake_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $intake = new StockIntake($validated);
            $intake->created_by = Auth::id();
            $intake->save();

            $product = Product::findOrFail($validated['product_id']);
            $product->stock += $validated['quantity'];
            $product->save();

            DB::commit();
            return redirect()->route('inventory.intake')->with('success', 'Entrada de stock registrada y agregada al inventario del producto.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar la entrada de stock: ' . $e->getMessage());
        }
    }

    public function getIntakeJson($id)
    {
        $intake = StockIntake::find($id);
        if ($intake) {
            return response()->json(['success' => true, 'data' => $intake]);
        }
        return response()->json(['success' => false, 'message' => 'Entrada no encontrada']);
    }

    public function updateIntake(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'purchase_cost' => 'required|numeric|min:0.01',
            'intake_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $intake = StockIntake::findOrFail($id);
            $product = Product::findOrFail($intake->product_id);

            // Revert old quantity
            $product->stock -= $intake->quantity;
            
            // Apply new quantity
            $product->stock += $validated['quantity'];
            $product->save();

            $intake->update($validated);

            DB::commit();
            return redirect()->route('inventory.intake')->with('success', 'Entrada de stock modificada y el inventario del producto ha sido re-calculado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al corregir la entrada de stock: ' . $e->getMessage());
        }
    }
}
