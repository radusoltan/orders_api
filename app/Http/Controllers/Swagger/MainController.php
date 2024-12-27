<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API order management and tracking.",
 *     version="1.0.0",
 *     description="This API allows you to manage and track orders"
 * ),
 * @OA\PathItem(
 *     path="/api"
 * )
 */
class MainController extends Controller
{
    //
}
