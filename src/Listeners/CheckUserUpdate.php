<?php

namespace Module\System\Listeners;

use Module\System\Models\SystemUser;
use Illuminate\Queue\InteractsWithQueue;
use Module\Profile\Events\BiodataUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckUserUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BiodataUpdated $event): void
    {
        /** GET CURRENT MODEL */
        $biodata = $event->model;

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $biodata->nip)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromBiodata($biodata);
        }

        /**
         * TODO:
         * struktural eselon 1 theme = brown
         * struktural eselon 2 theme = red
         * struktural eselon 3 theme = blue
         * struktural eselon 4 theme = green
         * fungsional theme = blue-grey
         * pelaksana theme = orange
         */

        /** UPDATE ABILITY */
        SystemUser::updateAbility($user);

        /** UPDATE AVATAR */
        SystemUser::updateUserAvatar($user, $biodata->getBiodataPhoto());
    }
}
