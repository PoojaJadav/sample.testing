<?php

namespace App\Exceptions;

use App\Notifications\System\ErrorNotification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e) && ! app()->environment('local') && ! $this->isSlackException($e)) {
                ErrorNotification::make($e, request(), auth()->user())->send();
            }
        });
    }

    protected function isSlackException(Throwable $exception)
    {
        return $exception instanceof \GuzzleHttp\Exception\ClientException && str()->contains($exception->getMessage(), 'https://hooks.slack.com/services');
    }
}
