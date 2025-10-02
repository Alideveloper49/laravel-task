<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ResponseTrait
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message = 'Fetch Successfully')
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,

        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {

        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * return validator response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendValidatorError($error, $errorMessages = [], $code = 422)
    {

        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * return Exception response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleException($e)
    {
        Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

        return response()->json([
            'success' => 0,
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
            'Message' => $e->getMessage(),
        ], 500);
    }
}
