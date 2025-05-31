<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $page = (int) $request->get('page', 1);

            $data = Pendapatan::with('user')->paginate($limit, ['*'], 'page', $page);

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
                'tanggal' => 'required|date',
                'sumber' => 'required|max:100',
                'jumlah' => 'required|numeric',
                'metode_pembayaran' => 'in:tunai,transfer,qris',
                'keterangan' => 'nullable|string',
            ]);

            $data = Pendapatan::create($request->all());

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
            $data = Pendapatan::with('user')->find($id);

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

    public function update(Request $request, $id)
    {
        try {
            $data = Pendapatan::find($id);

            if (!$data) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ]);
            }

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal' => 'required|date',
                'sumber' => 'required|max:100',
                'jumlah' => 'required|numeric',
                'metode_pembayaran' => 'in:tunai,transfer,qris',
                'keterangan' => 'nullable|string',
            ]);

            $data->update($request->all());

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
            $data = Pendapatan::find($id);

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
