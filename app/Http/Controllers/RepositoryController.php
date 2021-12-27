<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repository;
use \Illuminate\Database\Eloquent\Collection;

class RepositoryController extends Controller
{

    /**
     * getRepositories
     * 
     * Exposed via API route. Get repositories from GitHub, createOrUpdate (save/update in DB),
     * and return in order of stars descending.
     *
     * @param Request $request
     * @return void
     */
    public function getRepositories(Request $request): Collection
    {
        
        $APIRepositories = Repository::getGithubRepositories();

        Repository::createOrUpdate($APIRepositories->items);

        // Get all records in repositories table, and sort by stargazers_count after returned. 
        $repositories = Repository::all()->sortByDesc('stargazers_count');

        return $repositories; 
    }
}
