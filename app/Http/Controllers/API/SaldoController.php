<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class SaldoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $page = (int) $request->get('page', 1);

            $data = Saldo::orderBy('tanggal', 'desc')->paginate($limit, ['*'], 'page', $page);

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
                'tanggal' => 'required|date|unique:saldo,tanggal',
                'saldo_awal' => 'numeric|min:0',
                'total_pendapatan' => 'numeric|min:0',
                'total_pengeluaran' => 'numeric|min:0',
                'saldo_akhir' => 'required|numeric',
            ]);

            $data = Saldo::create($request->only([
                'tanggal', 'saldo_awal', 'total_pendapatan', 'total_pengeluaran', 'saldo_akhir'
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
            $data = Saldo::find($id);

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
            $data = Saldo::find($id);

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
                'tanggal' => 'required|date|unique:saldo,tanggal,' . $id,
                'saldo_awal' => 'numeric|min:0',
                'total_pendapatan' => 'numeric|min:0',
                'total_pengeluaran' => 'numeric|min:0',
                'saldo_akhir' => 'required|numeric',
            ]);

            $data->update($request->only([
                'tanggal', 'saldo_awal', 'total_pendapatan', 'total_pengeluaran', 'saldo_akhir'
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
            $data = Saldo::find($id);

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
