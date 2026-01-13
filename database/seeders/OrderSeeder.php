<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::pluck('id');

        Order::factory(10)->create()->each(function (Order $order) use ($services) {
            $attachedServices = $services->random(rand(1, 3))->toArray();
            $order->services()->attach($attachedServices);
            $order->calculateOrderEnd();
        });
    }
}
