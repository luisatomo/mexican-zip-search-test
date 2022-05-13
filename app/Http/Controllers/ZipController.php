<?php

namespace App\Http\Controllers;

use App\Helpers\DataHelper;
use App\Models\Zip;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZipController extends Controller
{
    /**
     * Validates and return a response to the zip-codes/{searched_value}.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request, string $zip): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->route()->parameters(), [
            'zip' => [
                'required',
                'string',
                'max:6',
            ],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ],
                500);
        }

        $searchValue = sprintf("%'.05d", $zip);

        $settlements = Zip::where('d_codigo', '=', $searchValue)->get();
        $result = DataHelper::format($settlements);

        return response()->json($result);
    }
}
