<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use \App\Models\Repository;

class GetRepositoriesTest extends TestCase
{
    /**
     * testCorrectAttributes
     * 
     * Test of the GitHub API wrapper function in \App\Models\Repository.
     *
     * @return void
     */
    public function testCorrectAttributes(): void
    {
        $repositories = Repository::getGithubRepositories();

        // Ensure that the method's response is an object.
        $this->assertIsObject($repositories);

        // Ensure that the response has what we need to store for each item, and check type too.
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
}
