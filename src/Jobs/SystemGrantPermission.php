<?php

namespace Module\System\Jobs;

use Illuminate\Bus\Queueable;
use Module\System\Models\SystemUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SystemGrantPermission implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $userId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($user = SystemUser::find($this->userId)) {
            foreach ($user->licenses as $license) {
                if ($user->hasLicenseAs($license->name)) {
                    continue;
                }

                $user->addLicense($license->name);
            }

            if (!$user->hasLicenseAs('account-administrator')) {
                $user->addLicense('account-administrator');
            }
        }
    }
}
