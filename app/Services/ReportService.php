<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getReport(string $type, string $from, string $to): array
    {
        return match ($type) {
            'deliveries' => $this->deliveriesReport($from, $to),
            'suppliers'  => $this->suppliersReport($from, $to),
            'stock'      => $this->stockReport($from, $to),
            default      => $this->deliveriesReport($from, $to),
        };
    }

    private function deliveriesReport(string $from, string $to): array
    {
        // example: delivery totals per day
        $rows = DB::select("
            SELECT DATE(created_at) as day,
                   COUNT(*) as deliveries,
                   SUM(quantity) as total_quantity
            FROM deliveries
            WHERE created_at::date BETWEEN ? AND ?
            GROUP BY day
            ORDER BY day ASC
        ", [$from, $to]);

        return [
            'title' => 'Delivery Summary',
            'labels' => array_map(fn($r) => $r->day, $rows),
            'datasets' => [
                [
                    'label' => 'Deliveries',
                    'data' => array_map(fn($r) => (int)$r->deliveries, $rows),
                ],
                [
                    'label' => 'Quantity',
                    'data' => array_map(fn($r) => (int)$r->total_quantity, $rows),
                ],
            ],
            'table' => $rows,
        ];
    }

    private function suppliersReport(string $from, string $to): array
    {
        $rows = DB::select("
            SELECT suppliers.name as supplier,
                   COUNT(deliveries.id) as delivery_count,
                   SUM(deliveries.quantity) as total_quantity
            FROM suppliers
            LEFT JOIN deliveries ON deliveries.supplier_id = suppliers.id
            WHERE deliveries.created_at::date BETWEEN ? AND ?
            GROUP BY suppliers.name
            ORDER BY total_quantity DESC NULLS LAST
        ", [$from, $to]);

        return [
            'title' => 'Supplier Performance',
            'table' => $rows,
        ];
    }

    private function stockReport(string $from, string $to): array
    {
        // Using your stock table design (item_name, quantity)
        $rows = DB::select("
            SELECT item_name, quantity, last_updated
            FROM stock
            ORDER BY quantity ASC
            LIMIT 50
        ");

        return [
            'title' => 'Stock Snapshot',
            'table' => $rows,
        ];
    }
}

