<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.url')) {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }

        if (env('APP_ENV') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Pagination\Paginator::defaultView('partials.pagination');

        view()->composer('partials.navbar', function ($view) {
            if (!session()->has('user_id')) {
                $view->with('navbarNotifications', collect());
                return;
            }

            $userId = session('user_id');
            
            $notifications = \App\Models\Notification::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(function ($notif) {
                    $type = 'notification';
                    if (str_contains(strtolower($notif->title), 'replied') || str_contains(strtolower($notif->title), 'reply')) {
                        $type = 'inquiry_reply';
                    } elseif (str_contains(strtolower($notif->title), 'inquiry')) {
                        $type = 'inquiry';
                    } elseif (str_contains(strtolower($notif->title), 'review')) {
                        $type = 'review';
                    }
                    return [
                        'id' => $notif->id,
                        'type' => $type,
                        'title' => $notif->title,
                        'message' => $notif->message,
                        'time' => $notif->created_at,
                        'url' => route('profile.index', ['tab' => 'notifications']),
                        'unread' => is_null($notif->read_at),
                    ];
                });

            $view->with('navbarNotifications', $notifications);
        });
    }
}
