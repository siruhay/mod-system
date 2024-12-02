<?php

namespace Module\System\Listeners;

use Illuminate\Events\Dispatcher;
use Module\System\Models\SystemUser;
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

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $member->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($member);
        }

        /** UPDATE ABILITY */
        SystemUser::updateAbility($user, $member);
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

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $official->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($official);
        }

        /** UPDATE ABILITY */
        SystemUser::updateAbility($user, $official);
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

        /** CHECK EXISTS */
        if (!$user = SystemUser::firstWhere('email', $committee->slug)) {
            /** CREATE NEW USER */
            $user = SystemUser::createUserFromEvent($committee);
        }

        /** UPDATE ABILITY */
        SystemUser::updateAbility($user, $committee);
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
    }
}
