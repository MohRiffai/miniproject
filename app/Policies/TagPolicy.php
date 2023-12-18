<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Tag $tag): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tag $tag): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Tag $tag): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, User $model): bool
    // {
    //     return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, User $model): bool
    // {
    //     return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Tag');
    // }
}
