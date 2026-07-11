<?php

namespace App\Policies;

use App\Models\Content;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Content $content): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return $user->program_id === $content->program_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'program-admin', 'editor', 'contributor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Content $content): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Verify program matches
        if ($user->program_id !== $content->program_id) {
            return false;
        }

        if ($user->hasAnyRole(['program-admin', 'editor'])) {
            return true;
        }

        if ($user->hasRole('contributor')) {
            // Contributor can only update their own drafts
            return $content->author_id === $user->id && $content->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Content $content): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return $user->hasRole('program-admin') && $user->program_id === $content->program_id;
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'program-admin']);
    }
}
