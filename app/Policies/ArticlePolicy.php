<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    
    public function update (User $user, Article $article)
    {
        // Jika pengguna adalah admin, izinkan pengeditan untuk semua artikel.
        if ($user->hasRole('Admin') && $user->can('Manage Article')) {
            return true;
        }

        // Jika pengguna adalah editor, izinkan pengeditan artikel yang bukan milik admin.
        if ($user->hasRole('Editor') && $user->can('Manage Article')) {
            return $article->author_id !== null && !$article->author->hasRole('Admin');
        }

        // Jika pengguna adalah author, izinkan pengeditan artikel yang dia miliki.
        if ($user->hasRole('Author') && $user->can('Manage Article')) {
            return $article->author_id !== null && !$article->author->hasAnyRole(['Admin','Editor']);
        }

        return false;
    }

    public function delete (User $user, Article $article)
    {
        // Jika pengguna adalah admin, izinkan pengeditan untuk semua artikel.
        if ($user->hasRole('Admin') && $user->can("Manage Article")) {
            return true;
        }

        // Jika pengguna adalah editor, izinkan pengeditan artikel yang bukan milik admin.
        if ($user->hasRole('Editor') && $user->can("Manage Article")) {
            return $article->author_id !== null && !$article->author->hasRole('Admin');
        }

        // Jika pengguna adalah author, izinkan pengeditan artikel yang dia miliki.
        if ($user->hasRole('Author') && $user->can("Manage Article")) {
            return $article->author_id !== null && !$article->author->hasAnyRole(['Admin','Editor']);
        }

        return false;
    }
}
