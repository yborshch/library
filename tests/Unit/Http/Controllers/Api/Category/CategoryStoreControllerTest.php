<?php

namespace Unit\Http\Controllers\Api\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class CategoryStoreControllerTest extends TestCase
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
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->category = Category::factory()->makeOne();
    }

    public function testCategoryStoreValid(): void
    {
        $response = $this->postJson(route('category.store'), [
            'title' => $this->category->title,
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(201);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($content['data']['title'], $this->category->title);
    }

    public function testCategoryStoreWithEmptyTitle(): void
    {
        $response = $this->postJson(route('category.store'), [
            'title' => '',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(422);

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $content);
        $this->assertCount(1, $content['errors']);
        $this->assertNotEmpty($content, 'hash');
    }

    public function testCategoryStoreWithShortTitle(): void
    {
        $response = $this->postJson(route('category.store'), [
            'title' => '1',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(422);

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $content);
        $this->assertCount(1, $content['errors']);
        $this->assertNotEmpty($content, 'hash');
    }
}
