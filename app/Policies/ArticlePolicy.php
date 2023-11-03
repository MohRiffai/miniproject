<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    public function create(User $user, Article $article)
    {
        if ($user->hasRole('Admin') || $user->hasRole('Editor') || $user->hasRole('Author')) {
            // Periksa apakah pengguna memiliki izin 'Manage Article'
            if ($user->can('Manage Article')) {
                return true; // Pengguna memiliki izin dan peran yang sesuai
            }
        }

        return false;
    }

    public function update(User $user, Article $article)
    {
        // Jika pengguna adalah admin, izinkan pengeditan untuk semua artikel.
        if ($user->hasRole('Admin') && $user->can('Manage Article')) {
            return true;
        }

        // Jika pengguna adalah editor, izinkan pengeditan artikel yang bukan milik admin.
        if ($user->hasRole('Editor') && $user->can("Manage Article")) {
            return $article->author_id !== null && !$article->author->hasRole('Admin');
        }


        // // Jika pengguna adalah author, izinkan pengeditan artikel yang dia miliki.
        // if ($user->hasRole('Author') && $user->can('Manage Article')) {
        //     return $article->author_id !== null && !$article->author->hasAnyRole(['Admin','Editor']);
        // }

        if ($user->hasRole('Author') && $user->can('Manage Article') && $user->id === $article->author->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Article $article)
    {
        // Jika pengguna adalah admin, izinkan pengeditan untuk semua artikel.
        if ($user->hasRole('Admin') && $user->can("Manage Article")) {
            return true;
        }

        // Jika pengguna adalah editor, izinkan pengeditan artikel yang bukan milik admin.
        if ($user->hasRole('Editor') && $user->can("Manage Article") && $article->author_id) {
            // return $article->author_id !== null && !$article->author->hasRole('Admin');
            return true;
        }

        // // Jika pengguna adalah author, izinkan pengeditan artikel yang dia miliki.
        // if ($user->hasRole('Author') && $user->can("Manage Article")) {
        //     return $article->author_id !== null && !$article->author->hasAnyRole(['Admin','Editor']);
        // }

        if ($user->hasRole('Author') && $user->can('Manage Article') && $user->id === $article->author->id) {
            return true;
        }

        return false;

        // return $user->hasAnyRole(['Admin', 'Editor', 'Author']) && $user->id === $article->author->id  && $user->can('Manage Article'); 
    }
}
