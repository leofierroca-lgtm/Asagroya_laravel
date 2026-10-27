<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIntake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function check(Request $request)
    {
        $search = $request->get('search', '');
        $query = Product::query();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
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

        if (! empty($search)) {
            $query->whereHas('product', function ($q) use ($search) {
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

            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();
            $product->stock += $validated['quantity'];
            $product->save();

            DB::commit();

            return redirect()->route('inventory.intake')->with('success', 'Entrada de stock registrada y agregada al inventario del producto.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error al procesar la entrada de stock: '.$e->getMessage());
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

            $intake = StockIntake::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();
            $product = Product::whereKey($intake->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            $stockDifference = $validated['quantity'] - $intake->quantity;

            if ($stockDifference < 0 && $product->stock < abs($stockDifference)) {
                throw new \RuntimeException('No se puede reducir la entrada por debajo del stock disponible.');
            }

            $product->stock += $stockDifference;
            $product->save();

            $intake->update($validated);

            DB::commit();

            return redirect()->route('inventory.intake')->with('success', 'Entrada de stock modificada y el inventario del producto ha sido re-calculado.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error al corregir la entrada de stock: '.$e->getMessage());
        }
    }
}
