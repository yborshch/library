<?php

namespace Unit\Http\Controllers\Api\Tag;


use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class TagStoreControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Tag
     */
    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->tag = Tag::factory()->makeOne();
    }

    public function testTagStoreValid(): void
    {
        $response = $this->postJson(route('tag.store'), [
            'title' => $this->tag->title,
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(201);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($content['data']['title'], $this->tag->title);
    }

    public function testTagStoreWithEmptyTitle(): void
    {
        $response = $this->postJson(route('tag.store'), [
            'title' => '',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(422);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }

    public function testTagStoreWithShortTitle(): void
    {
        $response = $this->postJson(route('tag.store'), [
            'title' => '1',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(422);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }
}
