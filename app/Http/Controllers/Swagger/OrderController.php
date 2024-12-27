<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Operations related to orders"
 * ),
 * @OA\Post(
 *     path="/api/orders",
 *     summary="Add a new Order",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"total_amount","items"},
 *                 @OA\Property(
 *                     property="total_amount",
 *                     type="number",
 *                     format="float",
 *                     description="Total amount of the order",
 *                 ),
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     description="List of order items",
 *                     @OA\Items(ref="#/components/schemas/OrderItem"),
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order was added successfuly",
 *         @OA\JsonContent(
 *             @OA\Property(property="Order", ref="#/components/schemas/Order")
 *         )
 *     )
 * ),
 * @OA\Get(
 *     path="/api/orders",
 *     summary="Get paginated orders",
 *     description="Returns a paginated list of orders",
 *     operationId="getOrders",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="page",
 *         in="path",
 *         description="Page number",
 *         @OA\Schema(type="integer")
 *     ),
 *      @OA\Response(
 *         response=200,
 *         description="A paginated list of orders",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Order")),
 *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8001/api/orders?page=1"),
 *             @OA\Property(property="from", type="integer", nullable=true, example=null),
 *             @OA\Property(property="last_page", type="integer", example=1),
 *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8001/api/orders?page=1"),
 *             @OA\Property(property="links", type="array", @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="url", type="string", nullable=true),
 *                 @OA\Property(property="label", type="string"),
 *                 @OA\Property(property="active", type="boolean")
 *             )),
 *              @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
 *              @OA\Property(property="path", type="string", example="http://localhost:8001/api/orders"),
 *              @OA\Property(property="per_page", type="integer", example=10),
 *              @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
 *              @OA\Property(property="to", type="integer", nullable=true, example=null),
 *              @OA\Property(property="total", type="integer", example=0)
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *      )
 * ),
 * @OA\Get(
 *     path="/api/orders/{orderNumber}",
 *     summary="Get order by order number",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="orderNumber",
 *         in="path",
 *         required=true,
 *         description="Order Number",
 *         @OA\Schema(type="srting")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfull operation",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found"
 *     )
 * )
 * @OA\Schema(
 *       schema="Order",
 *       type="object",
 *       required={"id", "total_amount", "status", "items"},
 *       @OA\Property(property="id", type="integer"),
 *       @OA\Property(property="order_number", type="string"),
 *       @OA\Property(property="total_amount", type="number", format="float"),
 *       @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"}),
 *       @OA\Property(property="created_at", type="string", format="datetime"),
 *       @OA\Property(property="updated_at", type="string", format="datetime"),
 *       @OA\Property(
 *           property="order_items",
 *           type="array",
 *           @OA\Items(ref="#/components/schemas/OrderItem")
 *       )
 *   ),
 *  @OA\Schema(
 *       schema="OrderItem",
 *       type="object",
 *       @OA\Property(property="order_id", type="integer"),
 *       @OA\Property(property="product_name", type="string"),
 *       @OA\Property(property="quantity", type="integer"),
 *       @OA\Property(property="price", type="number", format="float")
 *   )
 */
class OrderController extends Controller
{
    //
}
