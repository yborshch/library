<?php

namespace Unit\Http\Controllers\Api\Tag;


use App\Models\Tag;
use App\Models\User;
use Tests\TestCase;

class TagUpdateControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Tag
     */
    protected $tag;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('list')->accessToken;
        $this->tag = Tag::factory()->create();
    }

    public function testTagUpdateWithValidData(): void
    {
        $response = $this->postJson(route("tag.update"), [
            'id' => $this->tag->id,
            'title' => $this->tag->title . 'Test',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        $tag = Tag::find($this->tag->id);
        $this->assertNotNull($tag);
        $this->assertEquals($this->tag->title . 'Test', $tag->title);
    }

    public function testTagUpdateWithInvalidId()
    {
        $response = $this->postJson(route("tag.update"), [
            'id' => 100500,
            'title' => $this->tag->title . 'Test'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);

        $message = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $message);
    }
}
