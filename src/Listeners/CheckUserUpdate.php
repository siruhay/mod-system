<?php

namespace Module\System\Listeners;

use Illuminate\Events\Dispatcher;
use Module\System\Models\SystemUser;
use Module\Foundation\Events\MemberUpdated;
use Module\Foundation\Events\OfficialUpdated;

class CheckUserUpdate
{
    /**
     * handleMemberUpdate function
     *
     * @param MemberUpdated $event
     * @return void
     */
    public function handleMemberUpdate(MemberUpdated $event): void
    {
        /** GET CURRENT MODEL */
        $member = $event->model;

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $member->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($member);
        }

        /** UPDATE ABILITY */
        SystemUser::updateAbility($user);
    }

    /**
     * handleMemberUpdate function
     *
     * @param MemberUpdated $event
     * @return void
     */
    public function handleOfficialUpdate(OfficialUpdated $event): void
    {
        /** GET CURRENT MODEL */
        $official = $event->model;

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $official->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($official);
        }

        /** UPDATE ABILITY */
        SystemUser::updateAbility($user);
    }

    /**
     * subscribe function
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            MemberUpdated::class,
            [CheckUserUpdate::class, 'handleMemberUpdate']
        );

        $events->listen(
            OfficialUpdated::class,
            [CheckUserUpdate::class, 'handleOfficialUpdate']
        );
    }

    // public function handle(MemberUpdated $event): void
    // {
    //     /** GET CURRENT MODEL */
    //     $biodata = $event->model;

    //     /** CHECK EXISTS */
    //     if (!$user = SystemUser::firstWhere('email', $biodata->nip)) {
    //         /** CREATE NEW USER */
    //         $user = SystemUser::createUserFromBiodata($biodata);
    //     }

    //     /**
    //      * TODO:
    //      * struktural eselon 1 theme = brown
    //      * struktural eselon 2 theme = red
    //      * struktural eselon 3 theme = blue
    //      * struktural eselon 4 theme = green
    //      * fungsional theme = blue-grey
    //      * pelaksana theme = orange
    //      */

    //     /** UPDATE ABILITY */
    //     SystemUser::updateAbility($user);

    //     /** UPDATE AVATAR */
    //     SystemUser::updateUserAvatar($user, $biodata->getBiodataPhoto());
    // }
}
