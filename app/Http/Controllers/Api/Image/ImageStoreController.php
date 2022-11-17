<?php

namespace App\Http\Controllers\Api\Image;

use App\Http\Controllers\Controller;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Request;

class ImageStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        dd($request->get('file'));
    }
}
