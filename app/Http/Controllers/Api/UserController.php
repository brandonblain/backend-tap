<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::orderBy('created_at', 'desc')->get(['code', 'username', 'name', 'created_at']);
        return response()->json($users, 200);
    }
   
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|email|unique:users,username',
            'profile_picture' => 'required|string',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'profile_ids' => 'nullable|array'
        ], [
            'username.unique' => 'El correo electrónico ya se encuentra registrado.',
            'profile_picture.required' => 'La foto de perfil es obligatoria.'
        ]);

        $generatedCode = 'USR-' . strtoupper(substr(uniqid(), -5));

        $user = User::create([
            'code' => $generatedCode,
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'profile_picture' => $request->profile_picture,
            'password' => Hash::make($request->password),
            'profile_ids' => $request->profile_ids ?? [],
        ]);

        return response()->json([
            'message' => 'Usuario registrado con éxito.',
            'user' => $user
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json([
            'code' => $user->code,
            'username' => $user->username,
            'name' => $user->name,
            'phone' => $user->phone,
            'profile_picture' => $user->profile_picture,
            'profile_ids' => $user->profile_ids
        ], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'username' => 'required|email|unique:users,username,' . $user->id,
            'phone' => 'nullable|string',
            'profile_picture' => 'nullable|string',
            'profile_ids' => 'nullable|array'
        ]);

        $oldData = $user->toArray();
        
        $user->name = $request->name;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->profile_ids = $request->profile_ids ?? [];
        
        if ($request->filled('profile_picture')) {
            $user->profile_picture = $request->profile_picture;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // BITÁCORA
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_username' => Auth::user()->username,
            'module' => 'users',
            'action' => 'update',
            'target_id' => $user->id,
            'old_data' => $oldData,
            'new_data' => $user->toArray()
        ]);

        return response()->json([
            'message' => 'Usuario actualizado con éxito y registrado en bitácora.',
            'user' => $user
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'No puedes eliminar tu propio usuario en sesión.'], 400);
        }

        // BITÁCORA
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_username' => Auth::user()->username,
            'module' => 'users',
            'action' => 'delete',
            'target_id' => $user->id,
            'old_data' => $user->toArray(),
            'new_data' => null
        ]);

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado con éxito y registrado en bitácora.'
        ], 200);
    }

    public function exportPdf()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('pdf.users', ['data' => $users]);
        return $pdf->download('reporte-usuarios.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'reporte-usuarios.xlsx');
    }
}