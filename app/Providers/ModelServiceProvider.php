<?php

namespace App\Providers;

use App\Listeners\Notify\ContactAboutGroupMembershipProcessing;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Events\GroupMembershipsWereProcessed;
use Illuminate\Support\ServiceProvider;
use App\Events\ShortMessageWasRecorded;
use App\Events\ContactWasCreated;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Group;
use App\Mobile;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        ShortMessage::creating(function ($model) {
            $model->from = Mobile::number($model->from);
            $model->to   = Mobile::number($model->to);
        });

        Contact::creating(function ($model) {
            $model->mobile = Mobile::number($model->mobile);
            $model->handle = $model->handle ?: $model->mobile;
        });

        Contact::updating(function ($model) {
            $model->mobile = Mobile::number($model->mobile);
            $model->handle = $model->handle ?: $model->mobile;
        });

        ShortMessage::created(function ($model) {
            event(new ShortMessageWasRecorded($model));
        });

        Contact::created(function ($model) {
            event(new ContactWasCreated($model));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
