<?php

namespace App\Exceptions;

use App\Traits\ApiResponder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponder;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->invalidRequest($exception, $request);
        }
        if ($exception instanceof ModelNotFoundException) {
            return $this->modelNotFound($exception);
        }
        if ($exception instanceof AuthenticationException){
            return $this->unauthenticatedRequest($request);
        }
        if ($exception instanceof AuthorizationException){
            return $this->unauthorizedRequest($exception);
        }
        if ($exception instanceof NotFoundHttpException){
            return $this->notFoundHttp();
        }
        if ($exception instanceof MethodNotAllowedHttpException){
            return $this->methodNotAllowedHttp();
        }
        if ($exception instanceof HttpException){
            return $this->httpException($exception);
        }
        if ($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            if($errorCode == 1451) {
                return $this->queryException();
            }
        }
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }
        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Unexpected Exception. Try later', 500);
    }

    private function invalidRequest(ValidationException $exception, $request){
        $errors = $exception->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ?
                response()->json($errors, 422) :
                redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    private function modelNotFound(ModelNotFoundException $exception)
    {
        $modelName = strtolower(class_basename($exception->getModel()));
        return $this->errorResponse("Does not exist any {$modelName} "
            . "with the specified identification", 404);
    }

    private function unauthenticatedRequest($request)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse('Unauthenticated', 401);
    }

    private function unauthorizedRequest(AuthorizationException $exception)
    {
        return $this->errorResponse($exception->getMessage(), 403);
    }

    private function notFoundHttp()
    {
        return $this->errorResponse('The specified url cannot be found', 404);
    }

    private function methodNotAllowedHttp()
    {
        return $this->errorResponse('The specified method for the request is invalid', 404);
    }

    private function httpException(HttpException $exception)
    {
        return $this->errorResponse($exception->getMessage(), $exception->getStatusCode())
            ->withHeaders($exception->getHeaders());
    }

    private function queryException()
    {
        return $this->errorResponse('Cannot remove this resource permanently. '
            . 'It is related with any other resource', 409);
    }

    public function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())
                ->contains('web');
    }
}
