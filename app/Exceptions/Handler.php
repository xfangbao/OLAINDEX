<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        // 接口json错误响应
        if ($request->wantsJson() || $request->is('api/*')) {
            $response = [
                'code' => 200,
                'message' => '',
                'data' => [],
            ];
            $error = $this->convertExceptionToResponse($exception);
            $response['code'] = $error->getStatusCode();
            $response['message'] = $exception->getMessage();
            if (config('app.debug')) {
                $response['message'] = empty($exception->getMessage()) ? 'something error' : $exception->getMessage();
                if ($error->getStatusCode() >= 500) {
                    $response['trace'] = $exception->getTrace();
                    $response['code'] = $exception->getCode();
                }
            }
            return response()->json($response, $error->getStatusCode());
        }
        return parent::render($request, $exception);
    }
}
