<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeleteExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:delete-expired {days_count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired orders by order_start date';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->argument('days_count') ?? 3;
        $date = Carbon::now()->subDays($days);

        $validator = Validator::make(
            ['days_count' => $days],
            ['days_count' => ['required', 'integer', 'min:1']]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first('days_count'));

            return self::FAILURE;
        }

        $expiredOrders = Order::where('order_start', '<', $date)->pluck('id');

        DB::table('order_service')
            ->whereIn('order_id', $expiredOrders)
            ->delete();

        $deleted = Order::whereIn('id', $expiredOrders)->delete();

        $this->info("Deleted {$deleted} expired orders older than {$days} day(s).");

        return self::SUCCESS;
    }
}
