<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function create()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0.01'
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            
            $sale = new Sale();
            $sale->customer_id = $validated['customer_id'];
            $sale->seller_id = Auth::id();
            $sale->sale_date = now();
            $sale->payment_method = $validated['payment_method'];
            $sale->total_amount = 0; // will update
            $sale->save();

            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                }

                $product->stock -= $itemData['quantity'];
                $product->save();

                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                $totalAmount += $subtotal;

                $saleItem = new SaleItem();
                $saleItem->sale_id = $sale->id;
                $saleItem->product_id = $product->id;
                $saleItem->quantity = $itemData['quantity'];
                $saleItem->unit_price = $itemData['unit_price'];
                $saleItem->subtotal = $subtotal;
                $saleItem->save();
            }

            $sale->total_amount = $totalAmount;
            $sale->save();

            DB::commit();
            return redirect()->route('sales.history')->with('success', 'Venta registrada exitosamente. Factura #' . $sale->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar la venta: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $sales = Sale::with(['customer', 'user', 'items.product'])->orderBy('sale_date', 'desc')->get();
        return view('sales.history', compact('sales'));
    }

    public function detailsJson($id)
    {
        $sale = Sale::with(['customer', 'user', 'items.product'])->find($id);
        if ($sale) {
            return response()->json(['success' => true, 'data' => $sale]);
        }
        return response()->json(['success' => false, 'message' => 'Venta no encontrada']);
    }

    public function generateInvoice($id)
    {
        $sale = Sale::with(['customer', 'user', 'items.product'])->findOrFail($id);
        
        $itemCount = count($sale->items);
        $paperHeight = max(500, 370 + ($itemCount * 36));
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sales.invoice', compact('sale'));
        $pdf->setPaper([0, 0, 226.77, $paperHeight], 'portrait');
        
        return $pdf->download('factura_ASAGROYA_' . str_pad($sale->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function sendInvoiceEmail($id)
    {
        // Mock sending email
        return redirect()->back()->with('success', 'Factura enviada al correo del cliente (Simulación).');
    }
}
