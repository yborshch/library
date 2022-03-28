<?php

namespace App\DTO;

class ResponseDTO
{
    /**
     * @var object
     */
    public object $data;

    /**
     * @var array
     */
    public array  $errors;

    /**
     * @var string
     */
    public string $hash;

    /**
     * @param object $data
     * @param array $errors
     * @param string $hash
     */
    public function __construct(object $data, array $errors = [], string $hash = '')
    {
        $this->data = $data;
        $this->errors = $errors;
        $this->hash = $hash;
    }
}
