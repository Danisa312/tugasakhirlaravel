<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LaporanBulanan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class LaporanBulananController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $data = LaporanBulanan::orderBy('tahun', 'desc')
                                  ->orderBy('bulan', 'desc')
                                  ->paginate($limit);

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
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000',
                'total_pendapatan' => 'numeric|min:0',
                'total_pengeluaran' => 'numeric|min:0',
                'saldo_akhir' => 'required|numeric',
                'catatan' => 'nullable|string',
            ]);

            // Pastikan tidak ada duplikat
            if (LaporanBulanan::where('bulan', $request->bulan)->where('tahun', $request->tahun)->exists()) {
                return response()->json([
                    'success' => false,
                    'data' => ['error' => 'Laporan untuk bulan dan tahun ini sudah ada.'],
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ], 422);
            }

            $laporan = LaporanBulanan::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $laporan,
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
            $data = LaporanBulanan::find($id);

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
            $data = LaporanBulanan::find($id);

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
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000',
                'total_pendapatan' => 'numeric|min:0',
                'total_pengeluaran' => 'numeric|min:0',
                'saldo_akhir' => 'required|numeric',
                'catatan' => 'nullable|string',
            ]);

            // Cegah duplikasi pada bulan & tahun
            if (
                LaporanBulanan::where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                return response()->json([
                    'success' => false,
                    'data' => ['error' => 'Laporan untuk bulan dan tahun ini sudah ada.'],
                    'totalData' => 0,
                    'page' => 1,
                    'limit' => 1,
                ], 422);
            }

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
            $data = LaporanBulanan::find($id);

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
