<?php

namespace App\Traits;

use App\DTO\ResponseDTO;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use stdClass;

trait ValidationJsonResponseTrait
{
    use MakeHashTrait;
    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function makeJsonResponse(Validator $validator)
    {
        $result = [];
        foreach ($validator->errors()->getMessages() as $field) {
            foreach ($field as $error) {
                array_push($result, $error);
            }
        }

        $hash = $this->makeErrorHash();
        Log::error($hash, ['error' => $validator->errors()->getMessages(), 'context' => $validator->failed()]);

        throw new ValidationException(
            $validator,
            response()->json(new ResponseDTO(new stdClass(), $result,  $hash), 422)
        );
    }
}
