<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article; // Pastikan Anda mengganti dengan model yang sesuai
use Illuminate\Support\Facades\DB;

class SearchpostController extends Controller
{
    public function search(Request $request)
    {
        $keywords = $request->input('keywords', '');
        $tittle = $keywords;

        $results = Article::query()
            ->when(trim($keywords) !== '', function ($query) use ($keywords) {
                $query->where(function ($query) use ($keywords) {
                    $query->where('tittle', 'like', "%$keywords%")
                        ->orWhere('category_name', 'like', "%$keywords%")
                        ->orWhere('tag_name', 'like', "%$keywords%")
                        ->orWhereHas('author', function ($query) use ($keywords) {
                            $query->where('name', 'like', "%$keywords%");
                        });
                });
            })
            ->paginate(6);

        return view('searchpost', [
            'results' => $results,
            'keywords' => $keywords,
            'tittle' => $tittle
        ]);
    }
}
