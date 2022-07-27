<?php

namespace App\Exceptions;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use App\Traits\dataResponse;

class Handler extends ExceptionHandler
{
  use dataResponse;
  /**
   * A list of the exception types that are not reported.
   *
   * @var array<int, class-string<Throwable>>
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   *
   * @return void
   */
  public function register()
  {
    $this->reportable(function (Throwable $e) {
      //
    });

//    $this->renderable(function (Throwable $e) {
//      return $this->handleException($e);
//    });
  }

  public function handleException(Throwable $e)
  {
    if ($e instanceof HttpException) {
      $code = $e->getStatusCode();
      $defaultMessage = \Symfony\Component\HttpFoundation\Response::$statusTexts[$code];
      $message = $e->getMessage() == "" ? $defaultMessage : $e->getMessage();
      return $this->errorResponse($message, $code);
    } else if ($e instanceof ModelNotFoundException) {
      $model = strtolower(class_basename($e->getModel()));
      return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
    } else if ($e instanceof AuthenticationException) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
    } else if ($e instanceof AuthenticationException) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
    } else if ($e instanceof ValidationException) {
      $errors = $e->validator->errors()->getMessages();
      return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    } else {
      if (config('app.debug'))
        return $this->dataResponse($e->getMessage());
      else {
        return $this->errorResponse('Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  protected function unauthenticated($request, AuthenticationException $exception)
  {
    if ($request->expectsJson()) {
      return response()->json(['error' => 'unauthenticated']);
    }
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
      switch ($guard) {
        case 'user' :
          return redirect()->route('login');
          break;
        default:
          return redirect(RouteServiceProvider::LOGIN);
      }
    }
  }

}
