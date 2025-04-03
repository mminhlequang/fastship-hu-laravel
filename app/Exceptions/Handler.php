<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeageException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use BadMethodCallException;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        LeageException::class,
        OAuthServerException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        /**
         * Render an Authentification exception when user trying to viditing a route or
         * Perform an action is not properly authenticated
         */
        // Check if the request is for an API (starts with 'api/v1' prefix)
        if ($this->isApiRequest($request)) {
            return $this->handleException($request, $exception);
        }

        // If it's a normal web request (not API), render the default error response
        return parent::render($request, $exception);
    }

    // Check if the request is for the API by checking the route prefix or URL
    private function isApiRequest($request)
    {
        // Check if the request URL starts with 'api/v1'
        return $request->is('api/v1/*');
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $exception->redirectTo()
            ? redirect()->guest($exception->redirectTo())
            : response()->json(['status' => false, 'message' => __('INVALID_SIGNATURE')], 401);
    }


    public function handleException($request, Throwable $exception)
    {
        // Kiểm tra xem lỗi có phải là QueryException (lỗi trong quá trình thực thi SQL)
        if ($exception instanceof QueryException) {
            // Kiểm tra mã lỗi 1146, đây là mã lỗi khi bảng không tồn tại
            return $this->jsonResponse('The table "' . $exception->getSql() . '" does not exist in the database.', 400);
        }

        // Kiểm tra xem lỗi có phải là lỗi ValidationException không
        if ($exception instanceof BadMethodCallException ) {
            // Bạn có thể tùy chỉnh thông điệp lỗi ở đây
            return $this->jsonResponse($exception->getMessage(), 400);
        }
        if ($exception instanceof OAuthServerException) {
            return $this->jsonResponse($exception->getMessage(), $exception->statusCode());
        }

        if ($exception instanceof PostTooLargeException) {
            // Custom handling for file upload exceeding size
            return $this->jsonResponse('The uploaded file exceeds the maximum allowed size of 5MB', 413); // HTTP 413 Payload Too Large
        }

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));

            return $this->jsonResponse("Does not exist any {$modelName} with the specified identifier", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->jsonResponse($exception->getMessage(), 403);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->jsonResponse('The specified method for the request is invalid', 405);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->jsonResponse('The specified URL cannot be found', 404);
        }

        if ($exception instanceof HttpException) {
            return $this->jsonResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];

            if ($errorCode == 1451) {
                return $this->jsonResponse('Cannot remove this resource permanently. It is related to another resource', 409);
            }
        }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        if (app()->environment('production') && app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        return $this->jsonResponse('Unexpected Exception. Try later', 500);
    }

    /**
     * Return a standardized JSON response for errors.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    private function jsonResponse($message, $statusCode)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $statusCode);
    }
}
