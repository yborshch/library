<?php

namespace Unit\Http\Controllers\Api\Series;

use App\Models\User;
use Tests\TestCase;

class SeriesListControllerTest extends TestCase
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

    public function testSeriesListValid(): void
    {
        $response = $this->getJson(route("series.list", [
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
    public function testSeriesListWithPerPageInvalid(int $perPage): void
    {
        $response = $this->getJson(route('series.list', [
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

    public function testSeriesListWithoutPerPage(): void
    {
        $response = $this->getJson(route('series.list', [
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

    public function testSeriesListWithoutOrderBy(): void
    {
        $response = $this->getJson(route('series.list', [
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

    public function testSeriesListWithCurrentPageInvalid(): void
    {
        $response = $this->getJson(route('series.list', [
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

    protected function assertFields(array $series): void
    {
        $this->assertArrayHasKey('currentPage', $series);
        $this->assertArrayHasKey('perPage', $series);
        $this->assertArrayHasKey('total', $series);
        $this->assertArrayHasKey('lastPage', $series);
        $this->assertArrayHasKey('orderBy', $series);
        $this->assertEquals('desc', $series['orderBy']);
        $this->assertArrayHasKey('list', $series);
        $this->assertIsArray($series['list']);
    }
}
