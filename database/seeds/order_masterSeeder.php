<?php

use Illuminate\Database\Seeder;

class order_masterSeeder extends Seeder
{

    public function run(){
		DB::table('order_master')->insert(array(
					array(
						'order_number' => 'SH00001',
						'user_id' => '1',
						'total_amount' => '120.00',
						'swadesh_hut_id' => '3',
						'order_date' => '2020-11-02',
						'order_status' => 'Ordered',
						'payment_status' => 'Unpaid',
						'payment_mode' => 'COD',
					),
					array(
						'order_number' => "SH00002",
						'user_id' => '1',
						'total_amount' => '100.00',
						'swadesh_hut_id' => '4',
						'order_date' => '2020-11-01',
						'order_status' => 'Ordered',
						'payment_status' => 'Paid',
						'payment_mode' => 'COD',
					),
		));
    }
}
