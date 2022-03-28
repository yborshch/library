<?php

namespace Unit\Http\Controllers\Api\Category;


use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryRemoveControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Category
     */
    protected $category;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('list')->accessToken;
        $this->category = Category::factory()->create();
    }

    public function testCategoryRemoveValid(): void
    {
        $response = $this->postJson(route('category.remove'), [
            'id' => $this->category->id,
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('id', $content['data']);
        $this->assertArrayHasKey('title', $content['data']);
    }

    public function testCategoryRemoveWithoutId(): void
    {
        $response = $this->postJson(route('category.remove'), [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(422);
        $message = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $message);
    }

    public function testCategoryRemoveWithEmptyId(): void
    {
        $response = $this->postJson(route('category.remove'), [
            'id' => '',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(422);
        $message = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $message);
    }
}
