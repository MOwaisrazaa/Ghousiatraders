<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [],
    ];

    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $user = $event->user;
            $sessionCart = session()->get('polani_cart', []);
            if (!empty($sessionCart)) {
                foreach ($sessionCart as $item) {
                    $courseId = $item['course_id'] ?? $item['id'] ?? null;
                    $lectureId = $item['lecture_id'] ?? null;
                    if ($courseId || $lectureId) {
                        \App\Models\Shoppingcart::updateOrCreate(
                            ['user_id' => $user->id, 'course_id' => $courseId, 'lecture_id' => $lectureId],
                            ['price' => (float)$item['price'], 'quantity' => (int)($item['quantity'] ?? 1)]
                        );
                    }
                }
                // Clear session cart since it is now synced to database
                session()->forget('polani_cart');
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
