<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['uuid' => '1e2f3g4h-5i6j-7k8l-9m0n-1o2p3q4r5s6t', 'description' => 'PENDING'],
            ['uuid' => '2b3c4d5e-6f7g-8h9i-0j1k-2l3m4n5o6p7q', 'description' => 'APPROVED'],
            ['uuid' => '3c4d5e6f-7g8h-9i0j-1k2l-3m4n5o6p7q8r', 'description' => 'REJECTED'],
            ['uuid' => '4d5e6f7g-8h9i-0j1k-2l3m-4n5o6p7q8r9s', 'description' => 'BLOCKED']
        ];

        foreach ($statuses as $status) {
            \App\Infra\Persistence\Models\Status::create($status);
        }
    }
}
