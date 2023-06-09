<?php

namespace App\Notifications\System;

use App\Base\SlackNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Throwable;

class ErrorNotification extends SlackNotification
{
    public $fields = [];

    protected $content = 'Whoops! Something went wrong.';

    protected $level = 'error';

    /**
     * @param  Authenticatable  $user
     */
    public function __construct(Throwable $exception, Request $request, Authenticatable $user = null)
    {
        $fields = collect([
            'Request URL' => $request->fullUrl(),
            'Request Type' => $request->getMethod(),
            'Code' => $exception->getCode(),
            'Message' => $exception->getMessage(),
            'File' => $exception->getFile(),
            'Line' => $exception->getLine(),
        ]);

        if ($user) {
            $fields->put('User ID', $user->id);
            $fields->put('User Name', $user->name);
        }

        $this->fields = $fields->toArray();
    }

    protected function channel(): string
    {
        return config('services.slack.log-channel');
    }

    protected function fields(): array
    {
        return $this->fields;
    }
}
