<?php

namespace App\Http\Controllers;

use App\Helpers\DataHelper;
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

        return response()->json(DataHelper::search($zip));
    }
}
