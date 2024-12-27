<?php

namespace Tests\Feature;


use App\Jobs\UpdateOrderStatus;
use App\Models\Order;
use Bezhanov\Faker\Provider\Commerce;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_order()
    {
        $faker = $this->faker();
        $faker->addProvider(new Commerce($faker));
        $orderData = [
            'total_amount' => $faker->randomFloat(2,5),
            'items' => [
                [
                    'product_name' => $faker->productName,
                    'quantity' => $faker->numberBetween(1,10),
                    'price' => $faker->randomFloat(2,5),
                ],
                [
                    'product_name' => $faker->productName,
                    'quantity' => $faker->numberBetween(1,10),
                    'price' => $faker->randomFloat(2,5),
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'order_number',
                'status',
                'total_amount',
                'items'=>[
                    '*' => [
                        'product_name',
                        'quantity',
                        'price'
                    ]
                ]
            ]);
    }

    public function test_can_get_order_details()
    {
        $order = Order::factory()->create();
        $response = $this->getJson("/api/orders/{$order->order_number}");
        $response->assertStatus(200)
            ->assertJsonFragment([
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'total_amount' => $order->total_amount,
                'items' => $order->items
            ]);
    }

    public function test_order_status_update_through_external_api(){

        $order = Order::factory()->create([
            'order_number' => 'eb8de7f2-9098-3ca0-acb4-b4463b706d83',
            'status' => 'pending', // status inițial
            'total_amount' => 100.00,
        ]);


        Http::fake([
            'http://localhost:8000/api/orders/*' => Http::response([
                'data' => [
                    'status' => 'cancelled',
                    'total_amount' => 2103.65,
                    'order_items' => [
                        [
                            'product_name' => 'Fantastic Silk Coat',
                            'quantity' => 3,
                            'price' => 62.91
                        ]
                    ]
                ]
            ], 200),
        ]);


        dispatch(new UpdateOrderStatus($order->order_number));


        $order->refresh();


        $this->assertEquals('cancelled', $order->status->value);
        $this->assertEquals(2103.65, $order->total_amount);
    }

    /** @test */
    public function it_handles_timeout_error_when_external_api_does_not_respond()
    {

        $order = Order::factory()->create([
            'order_number' => 'eb8de7f2-9098-3ca0-acb4-b4463b706d83',
            'status' => 'pending',
            'total_amount' => 100.00,
        ]);


        Http::fake([
            'http://localhost:8000/api/orders/*' => Http::timeout(3),
        ]);


        $order->refresh();


        $this->assertEquals('pending', $order->status->value);
        $this->assertEquals(100.00, $order->total_amount);


        $this->assertCount(0, $order->items);
    }

    /** @test */
    public function it_handles_invalid_json_response_from_external_api()
    {

        $order = Order::factory()->create([
            'order_number' => 'eb8de7f2-9098-3ca0-acb4-b4463b706d83',
            'status' => 'pending', // status inițial
            'total_amount' => 100.00,
        ]);


        Http::fake([
            'http://localhost:8000/api/orders/*' => Http::response('invalid json', 200),  // Răspuns invalid
        ]);

        $order->refresh();


        $this->assertEquals('pending', $order->status->value);
        $this->assertEquals(100.00, $order->total_amount);


        $this->assertCount(0, $order->items);
    }
}
