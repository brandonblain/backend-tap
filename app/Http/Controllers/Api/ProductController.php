<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json($products, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string',
            'marca' => 'required|string',
            'precio' => 'required|numeric|between:0,999.99'
        ]);

        $generatedCode = 'PRD-' . strtoupper(substr(uniqid(), -5));

        $product = Product::create([
            'codigo' => $generatedCode,
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'precio' => (float) $request->precio,
        ]);

        return response()->json(['message' => 'Producto creado con éxito.', 'product' => $product], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string',
            'marca' => 'required|string',
            'precio' => 'required|numeric|between:0,999.99'
        ]);

        $oldData = $product->toArray();

        $product->update([
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'precio' => (float) $request->precio,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_username' => Auth::user()->username,
            'module' => 'products',
            'action' => 'update',
            'target_id' => $product->id,
            'old_data' => $oldData,
            'new_data' => $product->toArray()
        ]);

        return response()->json(['message' => 'Producto actualizado con éxito.', 'product' => $product], 200);
    }

    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_username' => Auth::user()->username,
            'module' => 'products',
            'action' => 'delete',
            'target_id' => $product->id,
            'old_data' => $product->toArray(),
            'new_data' => null
        ]);

        $product->delete();
        return response()->json(['message' => 'Producto eliminado con éxito.'], 200);
    }

    public function exportPdf()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('pdf.products', ['data' => $products]);
        return $pdf->download('reporte-productos.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'reporte-productos.xlsx');
    }
}