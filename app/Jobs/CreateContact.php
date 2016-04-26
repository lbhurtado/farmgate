<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\ContactRepository;

class CreateContact extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $mobile;

    private $handle;

    /**
     * CreateContact constructor.
     * @param $mobile
     * @param $handle
     */
    public function __construct($mobile, $handle)
    {
        $this->mobile = $mobile;
        $this->handle = $handle;
    }

    /**
     * @param ContactRepository $contactRepository
     */
    public function handle(ContactRepository $contactRepository)
    {
        $mobile = $this->mobile;
        $handle = $this->handle;

        $contactRepository->updateOrCreate(compact('mobile'), compact('mobile', 'handle'));
    }
}
