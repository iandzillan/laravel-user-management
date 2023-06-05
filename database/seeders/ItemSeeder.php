<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Uom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $acckoms = [
            [
                'name'         => 'Mouse Logitech G20',
                'code'         => 'M001',
                'location'     => 'Cabinet A',
                'stock'        => 20,
                'safety_stock' => 5,
                'desc'         => 'Mouse wireless',
                'status'       => null,
                'qrcode'       => null
            ],
            [
                'name'         => 'Keyboard',
                'code'         => 'K001',
                'location'     => 'Cabinet B',
                'stock'        => 20,
                'safety_stock' => 10,
                'desc'         => 'Keyboard wireless',
                'status'       => null,
                'qrcode'       => null
            ],
        ];

        $ipcs = [
            [
                'name'         => 'Monitor LG',
                'code'         => 'MO01',
                'location'     => 'Cabinet C',
                'stock'        => 25,
                'safety_stock' => 7,
                'desc'         => 'Monitor PC',
                'status'       => null,
                'qrcode'       => null
            ],
            [
                'name'         => 'Casing CPU',
                'code'         => 'CP01',
                'location'     => 'Cabinet D',
                'stock'        => 25,
                'safety_stock' => 2,
                'desc'         => 'Casing',
                'status'       => null,
                'qrcode'       => null
            ],
        ];


        foreach ($acckoms as $acckom) {
            $category           = Category::where('id', 1)->first();
            $uom                = Uom::where('id', 1)->first();
            $item               = new Item();
            $item->code         = $category->code . $acckom['code'];
            $item->name         = $acckom['name'];
            $item->location     = $acckom['location'];
            $item->stock        = $acckom['stock'];
            $item->safety_stock = $acckom['safety_stock'];
            $item->desc         = $acckom['desc'];
            $item->status       = $acckom['status'];
            $item->qrcode       = $acckom['qrcode'];
            $item->category()->associate($category);
            $item->uom()->associate($uom);
            $item->save();
        }

        foreach ($ipcs as $ipc) {
            $category           = Category::where('id', 2)->first();
            $uom                = Uom::where('id', 1)->first();
            $item               = new Item();
            $item->code         = $category->code . $ipc['code'];
            $item->name         = $ipc['name'];
            $item->location     = $ipc['location'];
            $item->stock        = $ipc['stock'];
            $item->safety_stock = $ipc['safety_stock'];
            $item->desc         = $ipc['desc'];
            $item->status       = $ipc['status'];
            $item->qrcode       = $ipc['qrcode'];
            $item->category()->associate($category);
            $item->uom()->associate($uom);
            $item->save();
        }
    }
}
