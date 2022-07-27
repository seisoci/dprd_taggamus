<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait dataResponse
{
  /**
   * Data Response
   * @param $data
   * @return JsonResponse
   */
  public function dataResponse($data): JsonResponse
  {
    return response()->json(['content' => $data], Response::HTTP_OK);
  }

  /**
   * Success Response
   * @param string $message
   * @param int $code
   * @return JsonResponse
   */
  public function successResponse(string $message, $code = Response::HTTP_OK): JsonResponse
  {
    return response()->json(['success' => $message, 'code' => $code], $code);
  }

  /**
   * Error Response
   * @param $message
   * @param int $code
   * @return JsonResponse
   *
   */
  public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST): JsonResponse
  {
    return response()->json(['error' => $message, 'code' => $code], $code);
  }
}
