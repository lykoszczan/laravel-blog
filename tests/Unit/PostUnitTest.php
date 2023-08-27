<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\PostRepository;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_post(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);
        $user = User::factory()->create();

        $data = [
            'title' => 'title example',
            'body' => 'body example',
            'thumbnail' => null,
            'author_id' => $user->id,
        ];

        $post_repository = new PostRepository();
        $post = $post_repository->create($data);

        $this->assertEquals($data['title'], $post->title);
        $this->assertEquals($user->id, $post->author_id);
    }
}
