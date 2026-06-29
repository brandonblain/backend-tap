<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfilesExport;

class ProfileController extends Controller
{
   
    public function index(): JsonResponse
    {
        $profiles = Profile::orderBy('created_at', 'desc')->get();
        return response()->json($profiles, 200);
    }
 
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:profiles,name',
            'sections' => 'nullable|array' // Arreglo de strings con los permisos de los middlewares
        ], [
            'name.unique' => 'El nombre de este perfil ya existe en el sistema.'
        ]);

        // Generar código único de perfil (Ej: PRF-87A2C)
        $generatedCode = 'PRF-' . strtoupper(substr(uniqid(), -5));

        $profile = Profile::create([
            'code' => $generatedCode,
            'name' => $request->name,
            'sections' => $request->sections ?? []
        ]);

        return response()->json(['message' => 'Perfil creado con éxito.', 'profile' => $profile], 201);
    }

   
    public function update(Request $request, $id): JsonResponse
    {
        $profile = Profile::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:profiles,name,' . $profile->id,
            'sections' => 'nullable|array'
        ]);

        $oldData = $profile->toArray();

        $profile->update([
            'name' => $request->name,
            'sections' => $request->sections ?? []
        ]);

        //BITÁCORA
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_username' => Auth::user()->username,
            'module' => 'profiles',
            'action' => 'update',
            'target_id' => $profile->id,
            'old_data' => $oldData,
            'new_data' => $profile->toArray()
        ]);

        return response()->json(['message' => 'Perfil actualizado con éxito y guardado en bitácora.', 'profile' => $profile], 200);
    }
   
    public function destroy($id): JsonResponse
    {
        $profile = Profile::findOrFail($id);

        //BITÁCORA
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_username' => Auth::user()->username,
            'module' => 'profiles',
            'action' => 'delete',
            'target_id' => $profile->id,
            'old_data' => $profile->toArray(),
            'new_data' => null
        ]);

        $profile->delete();
        return response()->json(['message' => 'Perfil removido con éxito y registrado en bitácora.'], 200);
    }

    public function exportPdf()
    {
        $profiles = Profile::orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('pdf.profiles', ['data' => $profiles]);
        return $pdf->download('reporte-perfiles.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ProfilesExport, 'reporte-perfiles.xlsx');
    }
}