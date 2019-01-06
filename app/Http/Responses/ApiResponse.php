<?php
/**
 * Created by PhpStorm.
 * User: 31272
 * Date: 2018/12/15
 * Time: 10:11
 */

namespace App\Http\Responses;

use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;


trait ApiResponse
{
    private function respond($body, $statusCode, $header = [])
    {
        $response = response()->json($body, $statusCode, $header);
        \Log::debug($response);
        return $response;
    }

    private function format(int $code, string $message, $data = [], array $paginate = [])
    {
        $body['meta'] = [
            'code'=>$code,
            'message'=>$message,
        ];

        empty($data) || $body['data'] = $data;
        empty($paginate) || $body['paginate'] = $paginate;

        return $body;
    }

    public function failed(int $statusCode, int $code, string $message, $header = [])
    {
        $body = $this->format($code, $message);
        return $this->respond($body, $statusCode, $header);
    }

    public function ok($data = [], $paginate = [], $header = [])
    {   
        $body = $this->format(20000, 'Success', $data, $paginate);
        return $this->respond($body, FoundationResponse::HTTP_OK, $header);
    }

    public function noContent($header = [])
    {
        $body = $this->format(20400, 'No content');
        return $this->respond($body, FoundationResponse::HTTP_OK, $header);
    }

}