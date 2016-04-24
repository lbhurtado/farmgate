<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use libphonenumber\PhoneNumberFormat;

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
