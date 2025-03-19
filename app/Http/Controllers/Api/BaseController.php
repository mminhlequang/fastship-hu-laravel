<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller as Controller;


/**
 * @OA\Info(
 *     title="Fastship API V1",
 *     version="1.0.0"
 * )
 *
 * @OA\Parameter(
 *     name="Accept-Language",
 *     in="header",
 *     description="Language preference",
 *     required=true,
 *     @OA\Schema(
 *         type="string",
 *         default="vi"
 *     )
 * )
 */

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 200, $result = null)
    {
        $response = [
            'status' => false,
            'message' => $error,
            'data' => $result
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}