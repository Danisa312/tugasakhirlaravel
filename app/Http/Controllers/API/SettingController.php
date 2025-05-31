<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $page = (int) $request->get('page', 1);

            $query = Setting::query();
            $data = $query->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'totalData' => $data->total(),
                'page' => $data->currentPage(),
                'limit' => $data->perPage(),
            ]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_perusahaan' => 'nullable|string|max:255',
                'alamat' => 'nullable|string',
                'logo_path' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
                'kontak' => 'nullable|string|max:50',
                'email_perusahaan' => 'nullable|email|max:100',
            ]);

            $input = $request->except('logo_path');

            if ($request->hasFile('logo_path')) {
                $path = $request->file('logo_path')->store('settings', 'public');
                $input['logo_path'] = $path;
            }

            $data = Setting::create($input);

            return response()->json([
                'success' => true,
                'data' => [$data],
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
            $data = Setting::find($id);

            return response()->json([
                'success' => true,
                'data' => $data ? [$data] : [],
                'totalData' => $data ? 1 : 0,
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
            $data = Setting::find($id);

            if (!$data) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ]);
            }

            $request->validate([
                'nama_perusahaan' => 'nullable|string|max:255',
                'alamat' => 'nullable|string',
                'logo_path' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
                'kontak' => 'nullable|string|max:50',
                'email_perusahaan' => 'nullable|email|max:100',
            ]);

            $input = $request->except('logo_path');

            if ($request->hasFile('logo_path')) {
                // Hapus file lama jika ada
                if ($data->logo_path && Storage::disk('public')->exists($data->logo_path)) {
                    Storage::disk('public')->delete($data->logo_path);
                }

                $path = $request->file('logo_path')->store('settings', 'public');
                $input['logo_path'] = $path;
            }

            $data->update($input);

            return response()->json([
                'success' => true,
                'data' => [$data],
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
            $data = Setting::find($id);

            if ($data) {
                $data->delete();
            }

            return response()->json([
                'success' => true,
                'data' => $data ? [$data] : [],
                'totalData' => $data ? 1 : 0,
                'page' => 1,
                'limit' => 1,
            ]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

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
