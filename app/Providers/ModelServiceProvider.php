<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use libphonenumber\PhoneNumberFormat;
use App\Events\ShortMessageWasRecorded;

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
            $model->from = phone_format($model->from, 'PH', PhoneNumberFormat::E164);
            $model->to   = phone_format($model->to,   'PH', PhoneNumberFormat::E164);
        });

        Contact::creating(function ($model) {
            $model->mobile = phone_format($model->mobile, 'PH', PhoneNumberFormat::E164);
            $model->handle = $model->handle ?: $model->mobile;
        });

        Contact::updating(function ($model) {
            $model->mobile = phone_format($model->mobile, 'PH', PhoneNumberFormat::E164);
            $model->handle = $model->handle ?: $model->mobile;
        });

        ShortMessage::created(function ($model) {
            event(new ShortMessageWasRecorded($model));
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
