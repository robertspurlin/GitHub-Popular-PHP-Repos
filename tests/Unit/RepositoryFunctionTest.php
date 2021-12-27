<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use \App\Models\Repository;

class RepositoryFunctionTest extends TestCase
{
    /**
     * testGetHithubRepositories
     * 
     * Test of the GitHub API wrapper function in \App\Models\Repository.
     *
     * @return void
     */
    public function testGetGithubRepositories(): void
    {
        $repositories = Repository::getGithubRepositories();

        // 1st test - ensure that the method's response is a string.
        $this->assertIsObject($repositories);

        // 2nd test(s) - ensure that the response has what we need to store for each item, and check type too.
        foreach ($repositories->items as $repository) {
            $this->assertIsObject($repository);

            $this->assertObjectHasAttribute('id', $repository);
            $this->assertIsNumeric($repository->id);

            $this->assertObjectHasAttribute('name', $repository);
            $this->assertIsString($repository->name);

            $this->assertObjectHasAttribute('html_url', $repository);
            $this->assertIsString($repository->html_url);

            // For datetime checks, we just want to see if it's a valid date. strtotime will always return an int
            // or false if invalid date.
            $this->assertObjectHasAttribute('created_at', $repository);
            $this->assertIsInt(strtotime($repository->created_at));

            $this->assertObjectHasAttribute('pushed_at', $repository);
            $this->assertIsInt(strtotime($repository->pushed_at));

            // Description can be null. So if it isn't null, ensure it's a string.
            $this->assertObjectHasAttribute('description', $repository);
            if ($repository->description != null) {
                $this->assertIsString($repository->description);
            }

            $this->assertObjectHasAttribute('stargazers_count', $repository);
            $this->assertIsNumeric($repository->stargazers_count);
        }
    }

    /**
     * testUpdateOrCreateRepositories
     * 
     * Test of the function that updates or creates rows in the DB.
     * Normally, unit tests would not include a function that uses the DB write functions
     * as a dependency, but testing this function specifically would be testing the smallest
     * independent unit of behavior possible (i.e this function alone) so I believe it belongs here.
     *
     * @return void
     */
    public function testUpdateOrCreateRepositories(): void
    {
        /**
         * pass function a mocked github repository collection,
         * test and see if function updates/creates records in DB.
         * also check response.
         */

        $this->assertTrue(true);
    }
}
