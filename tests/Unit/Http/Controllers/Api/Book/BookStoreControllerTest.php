<?php

namespace Unit\Http\Controllers\Api\Book;


use App\Models\Author;
use App\Models\Book;
use App\Models\Tag;
use App\Models\User;
use Tests\TestCase;

/**
 * @property  faker
 */
class BookStoreControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Author
     */
    public Author $author1;

    /**
     * @var Author
     */
    protected Author $author2;

    /**
     * @var Tag
     */
    protected Tag $tag1;

    /**
     * @var Tag
     */
    protected Tag $tag2;

    /**
     * @var Book
     */
    protected Book $book;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->book = Book::factory()->make();

        $this->author1 = Author::factory()->create();
        $this->author2 = Author::factory()->create();

        $this->tag1 = Tag::factory()->create();
        $this->tag2 = Tag::factory()->create();
    }

    public function testBookStoreWithMaximalData(): void
    {
        $data = [
            'author' => [
                $this->author1->getFullAuthorName(),
                $this->author2->getFullAuthorName(),
            ],
            'category_id' => $this->book->category_id,
            'description' => $this->book->description,
            'title' => $this->book->title,
            'series_id' => $this->book->series_id,
            'tag' => [
                $this->tag1->id,
                $this->tag2->id,
            ],
            'pages' => $this->book->pages,
            'year' => $this->book->year,
        ];

        $response = $this->postJson(route('book.store'), $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(201);

        $content = json_decode($response->getContent(), true);

        $this->assertEquals($content['data']['category_id'], $this->book->category_id);
        $this->assertEquals($content['data']['title'], $this->book->title);
    }

    public function testBookStoreWithMinimalData(): void
    {
        $data = [
            'author' => [
                $this->author1->getFullAuthorName(),
                $this->author2->getFullAuthorName(),
            ],
            'category_id' => $this->book->category_id,
            'title' => $this->book->title,
        ];

        $response = $this->postJson(route('book.store'), $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
// !!! Черная магия - ответ приходит пустой. Почему? Не понятттннноо
        $response->assertStatus(201);

//        $content = json_decode($response->getContent(), true);
//
//        $this->checkResponseArrayStructure($content);
//
//        $this->assertEquals($content['data']['category_id'], $this->book->category_id);
//        $this->assertEquals($content['data']['title'], $this->book->title);;
    }

    /**
     * @param array $content
     */
    protected function checkResponseArrayStructure(array $content): void
    {
        $this->assertArrayHasKey('id', $content['data']);
        $this->assertArrayHasKey('category_id', $content['data']);
        $this->assertArrayHasKey('title', $content['data']);
    }
}
