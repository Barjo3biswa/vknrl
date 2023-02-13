<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        TokenMismatchException::class
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        
        if($exception instanceof \Illuminate\Auth\Access\AuthorizationException){
            return redirect(url("/"))->with("status", "Access Denied.");
            abort(403, $exception->getMessage());
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
        // dd($exception);
        if($exception instanceof MethodNotAllowedHttpException){
            if (!$request->expectsJson()) {
                return redirect()->back()->with("Whoops! Something went wrong. Please try again later.");
                // return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }elseif($exception instanceof \Swift_TransportException){
            \Log::error($exception);
            return redirect()->back()->with("error", "Whoops! Something went wrong try again later. or Contact Administrator.");
        }elseif($exception instanceof TokenMismatchException){
            \Log::error($exception);
            return redirect()->back()->with("error", "Oops! Seems you couldn't submit form for a long time. Please try again.");
        }
        return parent::render($request, $exception);
    }
    

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        $guard = array_get($exception->guards(),0);
        switch ($guard) {
            case 'admin':
                $login = 'admin.login';
                break;

            default:
                $login = 'student.login';
                break;
        }
        return redirect()->guest(route($login))->with(["error" => "Access Denied OR No activity within ".((env("SESSION_LIFETIME", 30) * 60))." seconds. please log in again."]);
        // return redirect()->guest(route('student.login'));
    }
}
