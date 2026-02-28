<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Mockery;
use Modules\Order\Models\Order;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Services\OrderService;
use Modules\Product\Services\ProductService;
use Modules\User\Models\User;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::shouldReceive('transaction')->andReturnUsing(fn ($callback) => $callback());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_successful_order_creation_with_valid_items(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);

        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([
            (object)['id' => 1],
            (object)['id' => 2],
        ])->keyBy('id'));

        $order = Mockery::mock(Order::class)->makePartial();
        $order->id = 1;
        $order->supplier_id = 10;
        $order->shouldReceive('isEditable')->andReturn(true);
        $order->shouldReceive('load')->with(['items', 'supplier'])->andReturn($order);
        $itemsRelation = Mockery::mock();
        $itemsRelation->shouldReceive('create')->times(2)->andReturn(null);
        $order->shouldReceive('getRelation')->with('items')->andReturn(null);
        $order->shouldReceive('items')->andReturn($itemsRelation);

        $repo->shouldReceive('create')->once()->with(Mockery::on(function ($arg) {
            return $arg['supplier_id'] === 10
                && $arg['user_id'] === 1
                && $arg['date'] === '2025-01-15'
                && $arg['status'] === 'Pendente'
                && ($arg['observation'] ?? null) === 'Obs';
        }))->andReturn($order);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'observation' => 'Obs',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 2],
                ['id' => 2, 'unitPrice' => 5.00, 'quantity' => 3],
            ],
        ];

        $result = $service->createFromFrontPayload($payload, $user);

        $this->assertSame($order, $result);
    }

    public function test_correct_total_calculation_from_created_items(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([
            (object)['id' => 1],
            (object)['id' => 2],
        ])->keyBy('id'));

        $order = Mockery::mock(Order::class)->makePartial();
        $order->id = 1;
        $order->supplier_id = 10;
        $order->shouldReceive('isEditable')->andReturn(true);
        $order->shouldReceive('load')->with(['items', 'supplier'])->andReturn($order);
        $createdItems = [];
        $itemsRelation = Mockery::mock();
        $itemsRelation->shouldReceive('create')->andReturnUsing(function ($arg) use (&$createdItems) {
            $createdItems[] = $arg;
            return null;
        });
        $order->shouldReceive('items')->andReturn($itemsRelation);

        $repo->shouldReceive('create')->once()->andReturn($order);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 2],
                ['id' => 2, 'unitPrice' => 5.50, 'quantity' => 3],
            ],
        ];

        $service->createFromFrontPayload($payload, $user);

        $total = array_sum(array_map(fn ($item) => $item['unit_price'] * $item['quantity'], $createdItems));
        $this->assertEqualsWithDelta(36.5, $total, 0.01);
    }

    public function test_throws_when_products_do_not_belong_to_supplier(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([(object)['id' => 1]])->keyBy('id'));

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'products' => [
                ['id' => 999, 'unitPrice' => 10.00, 'quantity' => 1],
            ],
        ];

        try {
            $service->createFromFrontPayload($payload, $user);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('products', $e->errors());
            $this->assertStringContainsString('fornecedor', $e->errors()['products'][0]);
        }
    }

    public function test_throws_when_quantity_zero_or_negative(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([(object)['id' => 1]])->keyBy('id'));

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 0],
            ],
        ];

        try {
            $service->createFromFrontPayload($payload, $user);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $messages = is_array($e->errors()) ? $e->errors() : $e->errors()->toArray();
            $flat = collect($messages)->flatten()->implode(' ');
            $this->assertStringContainsString('maior que zero', $flat);
        }
    }

    public function test_throws_when_unit_price_zero_or_negative(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([(object)['id' => 1]])->keyBy('id'));

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 0, 'quantity' => 1],
            ],
        ];

        try {
            $service->createFromFrontPayload($payload, $user);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $messages = is_array($e->errors()) ? $e->errors() : $e->errors()->toArray();
            $flat = collect($messages)->flatten()->implode(' ');
            $this->assertStringContainsString('preço unitário', $flat);
        }
    }

    public function test_throws_when_order_not_editable_on_update(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);

        $order = Mockery::mock(Order::class)->makePartial();
        $order->supplier_id = 10;
        $order->shouldReceive('isEditable')->andReturn(false);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 1],
            ],
        ];

        try {
            $service->updateFromFrontPayload($order, $payload, $user);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('order', $e->errors());
            $this->assertStringContainsString('não pode ser alterado', $e->errors()['order'][0]);
        }
    }

    public function test_throws_when_invalid_status_on_update(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([(object)['id' => 1]])->keyBy('id'));

        $order = Mockery::mock(Order::class)->makePartial();
        $order->supplier_id = 10;
        $order->status = 'Pendente';
        $order->shouldReceive('isEditable')->andReturn(true);
        $order->shouldReceive('update')->never();
        $itemsRelation = Mockery::mock();
        $itemsRelation->shouldReceive('delete')->never();
        $order->shouldReceive('items')->andReturn($itemsRelation);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 1],
            ],
            'status' => 'Invalid',
        ];

        try {
            $service->updateFromFrontPayload($order, $payload, $user);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $messages = is_array($e->errors()) ? $e->errors() : $e->errors()->toArray();
            $flat = collect($messages)->flatten()->implode(' ');
            $this->assertStringContainsString('Status inválido', $flat);
        }
    }

    public function test_throws_when_seller_without_supplier_access(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->never();

        $suppliersRelation = Mockery::mock();
        $suppliersRelation->shouldReceive('where')->with('suppliers.id', 10)->andReturnSelf();
        $suppliersRelation->shouldReceive('exists')->andReturn(false);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'seller';
        $user->shouldReceive('suppliers')->andReturn($suppliersRelation);

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 1],
            ],
        ];

        try {
            $service->createFromFrontPayload($payload, $user);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('supplier', $e->errors());
            $this->assertStringContainsString('permissão', $e->errors()['supplier'][0]);
        }
    }

    public function test_successful_update_with_valid_status_transition(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([(object)['id' => 1]])->keyBy('id'));

        $order = Mockery::mock(Order::class)->makePartial();
        $order->id = 1;
        $order->supplier_id = 10;
        $order->status = 'Pendente';
        $order->shouldReceive('isEditable')->andReturn(true);
        $order->shouldReceive('update')->once()->with(Mockery::on(function ($arg) {
            return $arg['date'] === '2025-01-15'
                && $arg['status'] === 'Concluído'
                && array_key_exists('observation', $arg);
        }));
        $order->shouldReceive('load')->with(['items', 'supplier'])->andReturn($order);
        $itemsRelation = Mockery::mock();
        $itemsRelation->shouldReceive('delete')->once();
        $itemsRelation->shouldReceive('create')->once()->with([
            'product_id' => 1,
            'unit_price' => 10.00,
            'quantity' => 2,
        ]);
        $order->shouldReceive('items')->andReturn($itemsRelation);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'date' => '2025-01-15',
            'observation' => null,
            'status' => 'Concluído',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 2],
            ],
        ];

        $result = $service->updateFromFrontPayload($order, $payload, $user);

        $this->assertSame($order, $result);
    }

    public function test_normalize_products_merges_duplicate_product_ids(): void
    {
        $repo = Mockery::mock(OrderRepository::class);
        $productService = Mockery::mock(ProductService::class);
        $productService->shouldReceive('getBySupplier')->with(10)->andReturn(collect([(object)['id' => 1]])->keyBy('id'));

        $order = Mockery::mock(Order::class)->makePartial();
        $order->id = 1;
        $order->supplier_id = 10;
        $order->shouldReceive('isEditable')->andReturn(true);
        $order->shouldReceive('load')->with(['items', 'supplier'])->andReturn($order);
        $itemsRelation = Mockery::mock();
        $itemsRelation->shouldReceive('create')->once()->with([
            'product_id' => 1,
            'unit_price' => 10.00,
            'quantity' => 5,
        ]);
        $order->shouldReceive('items')->andReturn($itemsRelation);

        $repo->shouldReceive('create')->once()->andReturn($order);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->type = 'admin';

        $service = new OrderService($repo, $productService);
        $payload = [
            'supplier' => ['id' => 10],
            'date' => '2025-01-15',
            'products' => [
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 2],
                ['id' => 1, 'unitPrice' => 10.00, 'quantity' => 3],
            ],
        ];

        $result = $service->createFromFrontPayload($payload, $user);

        $this->assertSame($order, $result);
    }
}
