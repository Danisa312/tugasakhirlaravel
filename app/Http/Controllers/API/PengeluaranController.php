<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $page = (int) $request->get('page', 1);

            $data = Pengeluaran::with(['user', 'kategori'])->paginate($limit, ['*'], 'page', $page);

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
                'user_id' => 'required|exists:users,id',
                'kategori_id' => 'required|exists:kategori_pengeluaran,id',
                'tanggal' => 'required|date',
                'jumlah' => 'required|numeric',
                'metode_pembayaran' => 'in:tunai,transfer,qris',
                'penerima' => 'nullable|string|max:100',
                'keterangan' => 'nullable|string',
            ]);

            $data = Pengeluaran::create($request->only([
                'user_id', 'kategori_id', 'tanggal', 'jumlah', 'metode_pembayaran', 'penerima', 'keterangan'
            ]));

            return response()->json([
                'success' => true,
                'data' => $data,
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
            $data = Pengeluaran::with(['user', 'kategori'])->find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'totalData' => 1,
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
            $data = Pengeluaran::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ], 404);
            }

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'kategori_id' => 'required|exists:kategori_pengeluaran,id',
                'tanggal' => 'required|date',
                'jumlah' => 'required|numeric',
                'metode_pembayaran' => 'in:tunai,transfer,qris',
                'penerima' => 'nullable|string|max:100',
                'keterangan' => 'nullable|string',
            ]);

            $data->update($request->only([
                'user_id', 'kategori_id', 'tanggal', 'jumlah', 'metode_pembayaran', 'penerima', 'keterangan'
            ]));

            return response()->json([
                'success' => true,
                'data' => $data,
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
            $data = Pengeluaran::find($id);

            if ($data) {
                $data->delete();
            }

            return response()->json([
                'success' => true,
                'data' => $data,
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
