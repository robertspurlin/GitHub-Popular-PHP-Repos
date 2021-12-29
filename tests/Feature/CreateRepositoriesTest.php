<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Repository;
use Illuminate\Support\Facades\DB;

class CreateRepositoriesTest extends TestCase
{
    /**
     * testCreate
     * 
     * Test Create portion of CreateOrUpdate.
     *
     * @return void
     */
    public function testCreate(): void
    {

        // Mock up an obj that doesn't exist in db.
        $create = new \StdClass;
        $create->id = 99999998;
        $create->name = 'test insert';
        $create->html_url = 'https://test.test';
        $create->created_at = '2022-01-01T00:00:01Z';
        $create->pushed_at = '2022-01-01T00:00:01Z';
        $create->description = "A test insert.";
        $create->stargazers_count = 3;

        $createArr = [ $create ];

        Repository::create($createArr);

        $this->assertDatabaseHas('repositories', [
            'id' => 99999998,
            'description' => "A test insert.",
            'stargazers_count' => 3
        ]);

        DB::table('repositories')->where('id', 99999998)->delete();
    }

    /**
     * testErrorException
     * 
     * Test exception handling when passed bad data to createOrUpdate.
     *
     * @return void
     */
    public function testErrorException(): void
    {

        // Warn PHPUnit ahead of time that this exception is expected.
        $this->expectException(\ErrorException::class);

        $badObj = new \StdClass;
        $badArr = [ $badObj ];

        Repository::create($badArr);

    }
}
