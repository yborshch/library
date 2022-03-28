<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CORS
{
    /**
     * @var bool
     */
    private bool $allowCredentials;
    /**
     * @var int
     */
    private int $maxAge;
    /**
     * @var string[]
     */
    private array $exposeHeaders;
    /**
     * @var string[]
     */
    private array $headers  = [
        'origin' => 'Access-Control-Allow-Origin',
        'Access-Control-Request-Headers' => 'Access-Control-Allow-Headers',
        'Access-Control-Request-Method' => 'Access-Control-Allow-Methods'
    ];
    /**
     * @var string[]
     */
    private array $allowOrigins;

    public function __construct()
    {
        $this->allowCredentials = true;

        // Время для кеширования пред-запросов
        $this->maxAge = 600;

        // Разрешенные заголовки
        $this->exposeHeaders = [];

        // Разрешенные хосты для обращения
        $this->allowOrigins = [];
    }

    public function handle(Request $request, Closure $next)
    {
        // Проверка разрешения на обращение данного клиента
        if (
            !empty($this->allowOrigins)
            && $request->hasHeader('origin')
            && !in_array($request->header('origin'), $this->allowOrigins)
        ) {
            return new JsonResponse("origin: {$request->header('origin')} not allowed");
        }

        // Пред-запрос
        if ($request->hasHeader('origin') && $request->isMethod('OPTIONS')) {
            $response = new JsonResponse('cors pre response');
        } else {
            $response = $next($request);
        }

        // Добавление заголовков в ответ
        foreach ($this->headers as $key => $value) {
            if ($request->hasHeader($key)) {
                $response->header($value, $request->header($key));
            }
        }

        // Обязательные заголовки
        $response->header('Access-Control-Max-Age', $this->maxAge);
        $response->header('Access-Control-Allow-Credentials', $this->allowCredentials);
        $response->header('Access-Control-Expose-Headers', implode(', ', $this->exposeHeaders));

        return $response;
    }
}
