<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ShortMessageWasRecorded::class => [
            \App\Listeners\Capture\Contact::class,
            'App\Listeners\Capture\GroupMemberships',
            'App\Listeners\Capture\Alerts',
            'App\Listeners\Capture\FieldData',
            'App\Listeners\Capture\TaskAccomplishments',
            'App\Listeners\Capture\PollResults',
        ],
        'App\Events\ContactWasCreated' => [
            'App\Listeners\Notify\ContactAboutContactCreation',
            'App\Listeners\Relay\ToOthersAboutContactCreation',
        ],
        'App\Events\GroupMembershipsWereProcessed' => [
            'App\Listeners\Notify\ContactAboutGroupMembershipProcessing',
            'App\Listeners\Relay\ToOthersAboutGroupMembershipProcessing',
        ],
        'App\Events\AlertsWereProcessed' => [
            'App\Listeners\Notify\ContactAboutAlertsProcessing',
            'App\Listeners\Relay\ToOthersAboutAlertsProcessing',
        ],
        'App\Events\FieldDataWasProcessed' => [
            'App\Listeners\Notify\ContactAboutFieldDataProcessing',
            'App\Listeners\Relay\ToOthersAboutFieldDataProcessing',
        ],
        'App\Events\TaskAccomplishmentsWereProcessed' => [
            'App\Listeners\Notify\ContactAboutTaskAccomplishmentsProcessing',
            'App\Listeners\Relay\ToOthersAboutTaskAccomplishmentsProcessing',
        ],
        'App\Events\PollResultsWereProcessed' => [
            'App\Listeners\Notify\ContactAboutPollResultsProcessing',
            'App\Listeners\Relay\ToOthersAboutPollResultsProcessing',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
