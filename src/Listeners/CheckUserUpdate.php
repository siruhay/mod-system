<?php

namespace Module\System\Listeners;

use Illuminate\Events\Dispatcher;
use Module\System\Models\SystemUser;
use Module\Training\Events\TrainingSettingUpdate;
use Module\Foundation\Events\TrainingMemberUpdated;
use Module\Training\Events\TrainingCommitteeUpdate;
use Module\Foundation\Events\TrainingOfficialUpdated;

class CheckUserUpdate
{
    /**
     * handleMemberUpdate function
     *
     * @param TrainingMemberUpdated $event
     * @return void
     */
    public function handleMemberUpdate(TrainingMemberUpdated $event): void
    {
        /** GET CURRENT MODEL */
        $member = $event->model;

        /** GET ABILITIES */
        $abilities = $event->abilities;

        if (!$member->slug) {
            return;
        }

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $member->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($member);
        }

        foreach ($abilities as $ability) {
            if ($user->hasLicenseAs($ability)) {
                continue;
            }

            $user->addLicense($ability);
        }

        if (!$user->hasLicenseAs('account-administrator')) {
            $user->addLicense('account-administrator');
        }
    }

    /**
     * handleOfficialUpdate function
     *
     * @param TrainingOfficialUpdated $event
     * @return void
     */
    public function handleOfficialUpdate(TrainingOfficialUpdated $event): void
    {
        /** GET CURRENT MODEL */
        $official = $event->model;

        /** GET ABILITIES */
        $abilities = $event->abilities;

        if (!$official->slug) {
            return;
        }

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $official->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($official);
        }

        foreach ($abilities as $ability) {
            if ($user->hasLicenseAs($ability)) {
                continue;
            }

            $user->addLicense($ability);
        }

        if (!$user->hasLicenseAs('account-administrator')) {
            $user->addLicense('account-administrator');
        }
    }

    /**
     * handleTrainingCommitteeUpdate function
     *
     * @param TrainingCommitteeUpdate $event
     * @return void
     */
    public function handleTrainingCommitteeUpdate(TrainingCommitteeUpdate $event): void
    {
        /** GET CURRENT MODEL */
        $committee = $event->model;

        /** GET ABILITIES */
        $abilities = $event->abilities;

        if (!$committee->slug) {
            return;
        }

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $committee->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($committee);
        }

        foreach ($abilities as $ability) {
            if ($user->hasLicenseAs($ability)) {
                continue;
            }

            $user->addLicense($ability);
        }

        if (!$user->hasLicenseAs('account-administrator')) {
            $user->addLicense('account-administrator');
        }
    }

    /**
     * handleTrainingSettingUpdate function
     *
     * @param TrainingSettingUpdate $event
     * @return void
     */
    public function handleTrainingSettingUpdate(TrainingSettingUpdate $event): void
    {
        /** GET CURRENT MODEL */
        $committee = $event->model;

        /** GET ABILITIES */
        $abilities = $event->abilities;

        if (!$committee->slug) {
            return;
        }

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $committee->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($committee);
        }

        foreach ($abilities as $ability) {
            if ($user->hasLicenseAs($ability)) {
                continue;
            }

            $user->addLicense($ability);
        }

        if (!$user->hasLicenseAs('account-administrator')) {
            $user->addLicense('account-administrator');
        }
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
            TrainingMemberUpdated::class,
            [CheckUserUpdate::class, 'handleMemberUpdate']
        );

        $events->listen(
            TrainingOfficialUpdated::class,
            [CheckUserUpdate::class, 'handleOfficialUpdate']
        );

        $events->listen(
            TrainingCommitteeUpdate::class,
            [CheckUserUpdate::class, 'handleTrainingCommitteeUpdate']
        );

        $events->listen(
            TrainingSettingUpdate::class,
            [CheckUserUpdate::class, 'handleTrainingSettingUpdate']
        );
    }
}
