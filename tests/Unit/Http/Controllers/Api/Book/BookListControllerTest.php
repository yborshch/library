<?php

namespace Unit\Http\Controllers\Api\Book;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;


class BookListControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Book
     */
    protected $book;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
    }

    public function testBookListValid()
    {
        $response = $this->getJson(route('book.list', [
            'perPage' => 3,
            'orderBy' => 'desc'
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
    public function testBookListWithPerPageInvalid(int $perPage): void
    {
        $response = $this->getJson(route('book.list', [
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

    public function testBookListWithoutPerPage(): void
    {
        $response = $this->getJson(route('book.list', [
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

    public function testBookListWithoutOrderBy(): void
    {
        $response = $this->getJson(route('book.list', [
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

    public function testBookListWithCurrentPageInvalid(): void
    {
        $response = $this->getJson(route('book.list', [
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

    /**
     * @param array $authors
     */
    protected function assertFields(array $authors): void
    {
        $this->assertArrayHasKey('currentPage', $authors);
        $this->assertArrayHasKey('perPage', $authors);
        $this->assertArrayHasKey('total', $authors);
        $this->assertArrayHasKey('lastPage', $authors);
        $this->assertArrayHasKey('orderBy', $authors);
        $this->assertEquals('desc', $authors['orderBy']);
        $this->assertArrayHasKey('list', $authors);
        $this->assertIsArray($authors['list']);
    }
}
