<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, User $model): bool
    // {
    //     return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, User $model): bool
    // {
    //     return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->can('Manage Category');
    // }
}
