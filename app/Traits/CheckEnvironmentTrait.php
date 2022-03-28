<?php


namespace App\Traits;


use Illuminate\Support\Facades\App;

trait CheckEnvironmentTrait
{
    /**
     * Filter error message for prod environment
     * @param string $message
     * @return string
     */
    public static function filterErrorMessage(string $message): string
    {
        if (App::environment() !== 'prod') {
            return $message;
        }

        return 'An internal error. Please look log file';
    }
}
