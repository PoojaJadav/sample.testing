<?php

namespace App\Base;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

abstract class SlackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $level = 'info';

    /** @var string */
    protected $content;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->to($this->getChannel())
            ->{$this->level}()
            ->content($this->content)
            ->attachment(function ($attachment) {
                $attachment->title($this->title(), $this->url())
                    ->fields($this->fields());
            });
    }

    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    public function send()
    {
        \Notification::route('slack', config('services.slack.webhook'))->notify($this);
    }

    private function getChannel()
    {
        if (app()->environment('local')) {
            return config('services.slack.test-channel');
        }

        return $this->channel();
    }

    protected function channel(): string
    {
        return config('services.slack.notification-channel');
    }

    protected function title(): string
    {
        return '';
    }

    protected function url(): string
    {
        return '';
    }

    protected function fields(): array
    {
        return [];
    }
}
