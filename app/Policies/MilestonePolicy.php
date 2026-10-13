<?php

namespace App\Policies;

use App\Models\Milestone;
use App\Models\User;

class MilestonePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the milestone.
     */
    public function view(User $user, Milestone $milestone): bool
    {
        $project = $milestone->project;
        if (!$project) {
            return false;
        }

        return $user->can('view', $project);
    }

    /**
     * Determine whether the user can create milestones.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the milestone.
     */
    public function update(User $user, Milestone $milestone): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the milestone.
     */
    public function delete(User $user, Milestone $milestone): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can upload images to milestone.
     */
    public function upload(User $user, Milestone $milestone): bool
    {
        return $this->view($user, $milestone);
    }

    /**
     * Determine whether the user can chat on the milestone.
     */
    public function chat(User $user, Milestone $milestone): bool
    {
        return $this->view($user, $milestone);
    }
}
