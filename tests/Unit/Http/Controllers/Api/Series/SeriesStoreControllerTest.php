<?php

namespace Unit\Http\Controllers\Api\Series;


use App\Models\Series;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class SeriesStoreControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Series
     */
    protected $series;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->series = Series::factory()->makeOne();
    }

    public function testSeriesStoreValid(): void
    {
        $response = $this->postJson(route('series.store'), [
            'title' => $this->series->title,
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(201);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($content['data']['title'], $this->series->title);
    }

    public function testSeriesStoreWithEmptyTitle(): void
    {
        $response = $this->postJson(route('series.store'), [
            'title' => '',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(422);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }

    public function testSeriesStoreWithShortTitle(): void
    {
        $response = $this->postJson(route('series.store'), [
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
