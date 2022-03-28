<?php

namespace Unit\Http\Controllers\Api\Category;


use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryUpdateControllerTest extends TestCase
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

    public function testCategoryUpdateWithValidData(): void
    {
        $response = $this->postJson(route("category.update"), [
            'id' => $this->category->id,
            'title' => $this->category->title . 'Test',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        $category = Category::find($this->category->id);
        $this->assertNotNull($category);
        $this->assertEquals($this->category->title . 'Test', $category->title);
    }

    public function testCategoryUpdateWithInvalidId()
    {
        $response = $this->postJson(route("category.update"), [
            'id' => 100500,
            'title' => $this->category->title . 'Test'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }
}
