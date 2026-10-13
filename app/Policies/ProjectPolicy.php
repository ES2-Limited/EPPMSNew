<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
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
     * Determine whether the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        // Contractor assigned to the project
        if ($user->contractor && $project->contractor_id === $user->contractor->id) {
            return true;
        }

        // Consultant assigned to the project
        if ($user->consultant && $project->consultant_id === $user->consultant->id) {
            return true;
        }

        // Directorate admin for project department's directorate
        if ($user->hasRole('directorate_admin') && $user->personnel?->directorate_id) {
            if ($project->directorate_id === $user->personnel->directorate_id ||
                $project->department?->directorate_id === $user->personnel->directorate_id) {
                return true;
            }
        }

        // Head of department for the project department
        if ($user->hasRole('head_of_department') && $user->personnel?->department_id) {
            if ($project->department_id === $user->personnel->department_id) {
                return true;
            }
        }

        // Internal personnel assigned directly to the project
        if ($user->personnel) {
            return $project->project_personnel()
                ->where('personnel_id', $user->personnel->id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can participate in project chat / comments.
     */
    public function chat(User $user, Project $project): bool
    {
        return $this->view($user, $project);
    }
}
