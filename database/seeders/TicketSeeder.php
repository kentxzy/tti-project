<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\OrderItem;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Order items seeded by OrderSeeder (in insertion order):
        //   order_item id=1 → Laptop x1       (order 1, pending)
        //   order_item id=2 → Printer x2      (order 2, verified)
        //   order_item id=3 → Laptop x1       (order 3, dispatched)
        //   order_item id=4 → Office Chair x1 (order 4, delivered) ← ticket candidate
        //   order_item id=5 → Printer x1      (order 5, delivered) ← ticket candidate

        // Ticket 1 — received (just submitted)
        Ticket::create([
            'user_id'           => 1,
            'order_item_id'     => 4,   // Office Chair from delivered order
            'issue_description' => 'The chair armrest is broken on the right side.',
            'contact_number'    => '09171234567',
            'status'            => 'received',
        ]);

        // Ticket 2 — diagnosing (technician is looking at it)
        Ticket::create([
            'user_id'           => 1,
            'order_item_id'     => 5,   // Printer from delivered order
            'issue_description' => 'Printer is not feeding paper correctly and jams frequently.',
            'contact_number'    => '09171234567',
            'status'            => 'diagnosing',
        ]);

        // Ticket 3 — repaired (fix done, pending release)
        // Needs a third delivered order item — we create an extra order here
        // so there is a distinct order_item to attach it to.
        $extraOrder = \App\Models\Order::create([
            'user_id'   => 1,
            'branch_id' => 1,
            'price'     => 45000.00,
            'status'    => 'delivered',
        ]);

        $extraItem = \App\Models\OrderItem::create([
            'order_id'   => $extraOrder->id,
            'product_id' => 1,   // Laptop
            'quantity'   => 1,
            'unit_price' => 45000.00,
        ]);

        Ticket::create([
            'user_id'           => 1,
            'order_item_id'     => $extraItem->id,
            'issue_description' => 'Laptop screen has flickering issues when on battery power.',
            'contact_number'    => '09171234567',
            'status'            => 'repaired',
        ]);

        // Ticket 4 — released (fully resolved, closed)
        $extraOrder2 = \App\Models\Order::create([
            'user_id'   => 1,
            'branch_id' => 2,
            'price'     => 12000.00,
            'status'    => 'delivered',
        ]);

        $extraItem2 = \App\Models\OrderItem::create([
            'order_id'   => $extraOrder2->id,
            'product_id' => 2,   // Printer
            'quantity'   => 1,
            'unit_price' => 12000.00,
        ]);

        Ticket::create([
            'user_id'           => 1,
            'order_item_id'     => $extraItem2->id,
            'issue_description' => 'Printer was printing with horizontal lines across all pages.',
            'contact_number'    => '09171234567',
            'status'            => 'released',
        ]);
    }
}