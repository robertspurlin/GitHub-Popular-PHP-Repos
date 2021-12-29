<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Repository;
use ErrorException;
use \Illuminate\Database\Eloquent\Collection;

class RepositoryController extends Controller
{

    /**
     * getRepositories
     * 
     * Exposed via API route. Return all stored repositories in order of stars descending.
     *
     * @param Request $request
     * @return Collection
     */
    public function getRepositories(Request $request): Collection
    {
        // Get all records in repositories table, and sort by stargazers_count after returned. 
        $repositories = Repository::all()->sortByDesc('stargazers_count')->values();

        return $repositories; 
    }

    /**
     * updateRepositories
     * 
     * Remove all stored repositories, and store fresh response from GitHub API.
     *
     * @param Request $request
     * @return Response
     */
    public function updateRepositories(Request $request): Response 
    {      
        try {
            $APIRepositories = Repository::getGithubRepositories();

        // I chose 422 to denote that this API heard the request just fine, it just failed b/c 
        // (most likely) the GitHub API reached it's rate limit. And it's response would be the 
        // error code of the GitHub API response. I could've had this API respond with the same
        // error code, but I feel like that would not be representative. 
        } catch (ErrorException $e) {
            return response($e->getMessage(), 422);
        }

        Repository::truncate();

        Repository::create($APIRepositories->items);
        return response('Repositories pulled and stored', 201);
    }
}
