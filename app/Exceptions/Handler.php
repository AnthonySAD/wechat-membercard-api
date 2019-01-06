<?php
namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof HttpException){
            \Log::warning(get_class($exception) . 'Msg:' . $exception->getMessage());
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpException){
            if (!$exception instanceof ApiException){
                $exception = $this->formatApiException($exception);
            }
            return $this->failed($exception->getStatusCode(), $exception->getCode(), $exception->getMessage());
        }

        if (env('APP_DEBUG', false)) {
            return $this->prepareResponse($request, $exception);
        }

        return $this->failed(
            ErrorCodes::getStatusCode(ErrorCodes::INTERNAL_SERVER_ERROR),
            ErrorCodes::INTERNAL_SERVER_ERROR,
            ErrorCodes::getMessage(ErrorCodes::INTERNAL_SERVER_ERROR)
        );
    }

    public function formatApiException(HttpException $exception)
    {
        $errorCode = ErrorCodes::getErrorCode($exception->getStatusCode());
        return new ApiException($errorCode, $exception->getMessage(), $exception);
    }
}
