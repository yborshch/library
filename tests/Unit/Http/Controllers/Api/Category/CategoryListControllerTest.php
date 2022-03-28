<?php

namespace Unit\Http\Controllers\Api\Category;

use App\Models\User;
use Tests\TestCase;

class CategoryListControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('list')->accessToken;
    }

    public function testCategoryListValid(): void
    {
        $response = $this->getJson(route("category.list", [
            'perPage' => 3,
            'orderBy' => "desc"
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);

        $this->assertFields($content['data']);
    }

    /**
     * @return \int[][]
     */
    public function perPageProviderCase(): array
    {
        return [
            [0],
            [-1]
        ];
    }

    /**
     * @dataProvider perPageProviderCase
     */
    public function testCategoryListWithPerPageInvalid(int $perPage): void
    {
        $response = $this->getJson(route('category.list', [
            'perPage' => $perPage,
            'orderBy' => "desc"
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $content);
    }

    public function testCategoryListWithoutPerPage(): void
    {
        $response = $this->getJson(route('category.list', [
            'orderBy' => "desc"
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        $this->assertFields($content['data']);
        $this->assertCount(10, $content['data']['list']);
    }

    public function testCategoryListWithoutOrderBy(): void
    {
        $response = $this->getJson(route('category.list', [
            'perPage' => 1,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        $this->assertFields($content['data']);
        $this->assertCount(1, $content['data']['list']);
    }

    public function testCategoryListWithCurrentPageInvalid(): void
    {
        $response = $this->getJson(route('category.list', [
            'perPage' => 1,
            'currentPage' => 100500
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        $this->assertFields($content['data']);
        $this->assertCount(0, $content['data']['list']);
    }

    protected function assertFields(array $categories): void
    {
        $this->assertArrayHasKey('currentPage', $categories);
        $this->assertArrayHasKey('perPage', $categories);
        $this->assertArrayHasKey('total', $categories);
        $this->assertArrayHasKey('lastPage', $categories);
        $this->assertArrayHasKey('orderBy', $categories);
        $this->assertEquals('desc', $categories['orderBy']);
        $this->assertArrayHasKey('list', $categories);
        $this->assertIsArray($categories['list']);
    }
}
