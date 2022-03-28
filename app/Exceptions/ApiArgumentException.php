<?php

namespace App\Exceptions;

use App\DTO\ResponseDTO;
use App\Traits\MakeHashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;

class ApiArgumentException extends Exception
{
    use MakeHashTrait;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected string $context;

    /**
     * @var string
     */
    protected string $hash;

    /**
     * @param string $message
     * @param string $context
     */
    public function __construct(string $message, string $context = '')
    {
        parent::__construct();
        $this->message = $message;
        $this->context = $context;
        $this->hash = $this->makeErrorHash();
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report(): ?bool
    {
        Log::error($this->hash, ['error' => $this->message, 'context' => $this->context]);
        return true;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO( new stdClass(), [$this->message], $this->hash), 404
        );
    }
}
