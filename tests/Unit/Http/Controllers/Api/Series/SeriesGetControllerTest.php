<?php

namespace Unit\Http\Controllers\Api\Series;


use App\Models\Series;
use App\Models\User;
use Tests\TestCase;

class SeriesGetControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Series
     */
    protected $series;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('list')->accessToken;
        $this->series = Series::factory()->create();
    }

    public function testSeriesGetValid(): void
    {
        $response = $this->getJson(route("series.get", [
            'id' => $this->series->id,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content['data'][0]);
        $this->assertArrayHasKey('title', $content['data'][0]);
        $this->assertCount(3, $content);
        $this->assertCount(1, $content['data']);
    }

    public function testSeriesGetWithIdInvalid(): void
    {
        $response = $this->getJson(route("series.get", [
            'id' => 100500,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);
    }
}
