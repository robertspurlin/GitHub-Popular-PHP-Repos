<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Repository;
use Illuminate\Support\Facades\DB;

class CreateOrUpdateRepositoriesTest extends TestCase
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

        Repository::createOrUpdate($createArr);

        $this->assertDatabaseHas('repositories', [
            'id' => 99999998,
            'description' => "A test insert.",
            'stargazers_count' => 3
        ]);

        DB::table('repositories')->where('id', 99999998)->delete();
    }

    /**
     * testUpdate
     * 
     * Test Update portion of CreateOrUpdate.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        
        // mock up something that should be updated.
        $repository = Repository::insert([
            'id' => 99999999,
            'name' => 'test insert',
            'url' => 'https://test.test',
            'created_at' => '2022-01-01 00:00:01',
            'pushed_at' => '2022-01-01 00:00:01',
            'description' => 'A test insert.',
            'stargazers_count' => 1
        ]);

        // Ensure that it's there before update.
        $this->assertDatabaseHas('repositories', [
            'id' => 99999999
        ]);

        // Then, mock up what an update would look like (an array of objects).
        $update = new \StdClass;
        $update->id = 99999999;
        $update->name = 'test insert';
        $update->html_url = 'https://test.test';
        $update->created_at = '2022-01-01T00:00:01Z';
        $update->pushed_at = '2022-01-01T00:00:01Z';
        $update->description = "A test insert that has been updated later.";
        $update->stargazers_count = 2;

        $updateArr = [ $update ];

        Repository::createOrUpdate($updateArr);

        $this->assertDatabaseHas('repositories', [
            'id' => 99999999,
            'description' => "A test insert that has been updated later.",
            'stargazers_count' => 2
        ]);

        DB::table('repositories')->where('id', 99999999)->delete();

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

        Repository::createOrUpdate($badArr);

    }
}
