<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class Repository extends Model
{
    use HasFactory;

    /**
     * Laravel by default writes created_at and updated_at when using
     * it's Query Builder functions (like we are, upsert.)
     * Let's disable that.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * getGithubRepositories
     * 
     * A wrapper function that calls the GitHub API, and returns it's response.
     * 
     * I considered using the PHP library recommended on the GitHub docs (and it's Laravel port over),
     * but it's implementation does not condider the per_page param which I wanted to pass.
     * Plus, the library had many other things that I didn't need (like authenciation)
     * So, make the call manually. 
     *
     * @return object
     */
    public static function getGithubRepositories(): object
    {
        // Required header to hit the API
        $headers = [
            'Accept' => 'application/vnd.github.v3+json'
        ];

        // Search for repositories with "php", that has a language of php, and is public. 
        $queryParams = 'q=' . urlencode("php language:php is:public");
        // Tell GitHub to sort by max stars.
        $sortParam = 'sort=stars';
        // It returns paginated, but I'm not interested in going through the pages. So I'll return the max for one page (100).
        $perPageParam = 'per_page=100';

        $fullQueryString = 'https://api.github.com/search/repositories?' . $queryParams . '&' . $sortParam . '&' . $perPageParam;

        $client = new Client;

        $request = $client->request('GET', $fullQueryString, [
            'headers' => $headers
        ]);

        return json_decode($request->getBody()->getContents());
    }


    /**
     * createOrUpdate
     * 
     * A wrapper of Model::upsert, created to abstract the call into this model
     * and to add validation of the array passed.
     *
     * @param array $repositories
     * @return void
     */
    public static function createOrUpdate(array $repositories): void
    {

        foreach ($repositories as $repository) {
            $upsertArr[] = [
                'id' => $repository->id,
                'name' => $repository->name,
                'url' => $repository->html_url,
                'created_at' => Carbon::parse($repository->created_at)->setTimezone('UTC'),
                'pushed_at' => Carbon::parse($repository->pushed_at)->setTimezone('UTC'),
                'description' => $repository->description,
                'stargazers_count' => $repository->stargazers_count
            ];
        }

        /**
         * Upsert translates to:
         * 
         * INSERT INTO repositories (id, name, url, created_at, pushed_at, description, stargazers_count)
         * (1, 'x', 'x.com', '2022-01-01 00:00:00', '2022-01-01 00:00:00', 'x', 10),
         * (2, 'x', 'x.com', '2022-01-01 00:00:00', '2022-01-01 00:00:00', 'x', 10)
         * ON DUPLICATE KEY UPDATE
         * id = VALUES(id),
         * name = VALUES(name),
         * url = VALUES(url),
         * created_at = VALUES(created_at),
         * pushed_at = VALUES(pushed_at),
         * description = VALUES(description),
         * stargazers_count = VALUES(description);
         */

        self::upsert(
            $upsertArr, 
            'id', 
            ['name', 'url', 'created_at', 'pushed_at', 'description', 'stargazers_count']
        );

    }
}
