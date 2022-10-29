<?php

namespace App\Jobs;

use App\Exceptions\ProfilePictureException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class DeleteProfilePicture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected string $filePath,
        protected ?string $disk = null,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deleted = Storage::disk($this->disk ?: config('filesystems.default'))->delete($this->filePath);

        if (!$deleted) {
            throw new ProfilePictureException('Error deleting file', 1);
        }
    }
}
