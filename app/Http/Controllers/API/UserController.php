<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $page = (int) $request->get('page', 1);

            $users = User::paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'totalData' => $users->total(),
                'page' => $users->currentPage(),
                'limit' => $users->perPage(),
            ]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:100',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|max:50|unique:users,username',
                'password' => 'required|min:6',
                'role' => 'required|in:admin,user,directure'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'status' => $request->status ?? true,
                'role' => $request->role
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'totalData' => 1,
                'page' => 1,
                'limit' => 1,
            ], 201);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function show($id)
    {
        try {
            $user = User::find($id);

            return response()->json([
                'success' => true,
                'data' => $user,
                'totalData' => $user ? 1 : 0,
                'page' => 1,
                'limit' => 1,
            ]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ]);
            }

            $request->validate([
                'name' => 'required|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'username' => 'required|max:50|unique:users,username,' . $user->id,
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'status' => $request->status ?? true,
                'role' => $request->role ?? $user->role
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'totalData' => 1,
                'page' => 1,
                'limit' => 1,
            ]);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->delete();
            }

            return response()->json([
                'success' => true,
                'data' => $user,
                'totalData' => $user ? 1 : 0,
                'page' => 1,
                'limit' => 1,
            ]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function sync()
{
    try {
        // Ganti URL API eksternal sesuai kebutuhan
        $externalApiUrl = 'https://example.com/api/users';

        // Panggil API eksternal
        $response = Http::get($externalApiUrl);

        if (!$response->successful()) {
            throw new Exception('Gagal mengambil data dari API eksternal.');
        }

        $usersData = $response->json(); // Pastikan ini adalah array of users

        $syncedUsers = [];
        foreach ($usersData as $externalUser) {
            // Validasi minimal field yang dibutuhkan
            if (!isset($externalUser['email']) || !isset($externalUser['username'])) {
                continue;
            }

            $user = User::where('email', $externalUser['email'])
                        ->orWhere('username', $externalUser['username'])
                        ->first();

            if ($user) {
                // Update jika user sudah ada
                $user->update([
                    'name' => $externalUser['name'] ?? $user->name,
                    'username' => $externalUser['username'],
                    'email' => $externalUser['email'],
                    'status' => $externalUser['status'] ?? $user->status,
                    'role' => $externalUser['role'] ?? $user->role,
                ]);
            } else {
                // Insert jika user belum ada
                $user = User::create([
                    'name' => $externalUser['name'] ?? 'Unknown',
                    'username' => $externalUser['username'],
                    'email' => $externalUser['email'],
                    'password' => Hash::make($externalUser['password'] ?? 'password123'), // default password
                    'status' => $externalUser['status'] ?? true,
                    'role' => $externalUser['role'] ?? 'user',
                ]);
            }

            $syncedUsers[] = $user;
        }

        return response()->json([
            'success' => true,
            'data' => $syncedUsers,
            'totalData' => count($syncedUsers),
            'page' => 1,
            'limit' => count($syncedUsers),
        ]);
    } catch (Exception $e) {
        return $this->errorResponse($e);
    }
}

    // 🔻 Helper: Tangani validasi gagal
    protected function validationErrorResponse(ValidationException $e)
    {
        return response()->json([
            'success' => false,
            'data' => $e->errors(),
            'totalData' => 0,
            'page' => 1,
            'limit' => 1,
        ], 422);
    }

    // 🔻 Helper: Tangani error umum
    protected function errorResponse(Exception $e)
    {
        return response()->json([
            'success' => false,
            'data' => ['error' => $e->getMessage()],
            'totalData' => 0,
            'page' => 1,
            'limit' => 1,
        ], 500);
    }
}
