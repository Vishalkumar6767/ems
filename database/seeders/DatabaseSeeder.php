<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Factory;
use App\Models\Floor;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ──────────────────────────────────
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@system.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'factory_id' => null,
        ]);

        // ── Factory 1: Tata Steel ────────────────────────
        $tata = Factory::create(['name' => 'Tata Steel Plant', 'address' => 'Bistupur, Jamshedpur, Jharkhand 831001']);

        $tataAdmin = User::create([
            'name' => 'Rajiv Mehta',
            'email' => 'admin@tatasteel.com',
            'password' => Hash::make('password'),
            'role' => 'company_admin',
            'factory_id' => $tata->id,
        ]);

        $t1 = Floor::create(['factory_id' => $tata->id, 'name' => 'Smelting Unit', 'floor_number' => 1]);
        $t2 = Floor::create(['factory_id' => $tata->id, 'name' => 'Rolling Mill', 'floor_number' => 2]);
        $t3 = Floor::create(['factory_id' => $tata->id, 'name' => 'Quality Control Lab', 'floor_number' => 3]);

        $tataWorkers = [
            ['floor' => $t1, 'name' => 'Rajesh Kumar Singh', 'id' => 'TATA-001', 'phone' => '9876543210', 'designation' => 'Furnace Operator'],
            ['floor' => $t1, 'name' => 'Amit Sharma', 'id' => 'TATA-002', 'phone' => '9876543211', 'designation' => 'Furnace Helper'],
            ['floor' => $t1, 'name' => 'Sunil Prasad', 'id' => 'TATA-003', 'phone' => '9876543212', 'designation' => 'Crane Operator'],
            ['floor' => $t1, 'name' => 'Manoj Kumar Yadav', 'id' => 'TATA-004', 'phone' => '9876543213', 'designation' => 'Ladle Man'],
            ['floor' => $t1, 'name' => 'Deepak Mahto', 'id' => 'TATA-005', 'phone' => '9876543214', 'designation' => 'Technician'],
            ['floor' => $t2, 'name' => 'Vikram Oraon', 'id' => 'TATA-006', 'phone' => '9876543215', 'designation' => 'Mill Operator'],
            ['floor' => $t2, 'name' => 'Santosh Munda', 'id' => 'TATA-007', 'phone' => '9876543216', 'designation' => 'Roller'],
            ['floor' => $t2, 'name' => 'Ramesh Bauri', 'id' => 'TATA-008', 'phone' => '9876543217', 'designation' => 'Helper'],
            ['floor' => $t2, 'name' => 'Prakash Tirkey', 'id' => 'TATA-009', 'phone' => '9876543218', 'designation' => 'Fitter'],
            ['floor' => $t3, 'name' => 'Anita Kumari', 'id' => 'TATA-010', 'phone' => '9876543219', 'designation' => 'QC Inspector'],
            ['floor' => $t3, 'name' => 'Priya Sinha', 'id' => 'TATA-011', 'phone' => '9876543220', 'designation' => 'Lab Technician'],
            ['floor' => $t3, 'name' => 'Ravi Shankar Dubey', 'id' => 'TATA-012', 'phone' => '9876543221', 'designation' => 'QC Head'],
        ];

        // ── Factory 2: Bajaj Auto ────────────────────────
        $bajaj = Factory::create(['name' => 'Bajaj Auto Works', 'address' => 'Akurdi, Pune, Maharashtra 411035']);

        User::create([
            'name' => 'Sanjay Kulkarni',
            'email' => 'admin@bajajauto.com',
            'password' => Hash::make('password'),
            'role' => 'company_admin',
            'factory_id' => $bajaj->id,
        ]);

        $b1 = Floor::create(['factory_id' => $bajaj->id, 'name' => 'Assembly Line A', 'floor_number' => 1]);
        $b2 = Floor::create(['factory_id' => $bajaj->id, 'name' => 'Paint Shop', 'floor_number' => 2]);

        $bajajWorkers = [
            ['floor' => $b1, 'name' => 'Sachin Patil', 'id' => 'BAJ-001', 'phone' => '9823456701', 'designation' => 'Assembly Fitter'],
            ['floor' => $b1, 'name' => 'Ganesh Jadhav', 'id' => 'BAJ-002', 'phone' => '9823456702', 'designation' => 'Welder'],
            ['floor' => $b1, 'name' => 'Nilesh Deshmukh', 'id' => 'BAJ-003', 'phone' => '9823456703', 'designation' => 'Line Supervisor'],
            ['floor' => $b1, 'name' => 'Pramod Shinde', 'id' => 'BAJ-004', 'phone' => '9823456704', 'designation' => 'Electrician'],
            ['floor' => $b1, 'name' => 'Arun More', 'id' => 'BAJ-005', 'phone' => '9823456705', 'designation' => 'Helper'],
            ['floor' => $b1, 'name' => 'Suresh Kulkarni', 'id' => 'BAJ-006', 'phone' => '9823456706', 'designation' => 'Quality Check'],
            ['floor' => $b2, 'name' => 'Mahesh Pawar', 'id' => 'BAJ-007', 'phone' => '9823456707', 'designation' => 'Paint Operator'],
            ['floor' => $b2, 'name' => 'Vishal Gaikwad', 'id' => 'BAJ-008', 'phone' => '9823456708', 'designation' => 'Spray Technician'],
            ['floor' => $b2, 'name' => 'Rahul Bhosale', 'id' => 'BAJ-009', 'phone' => '9823456709', 'designation' => 'Drying Operator'],
            ['floor' => $b2, 'name' => 'Kiran Chavan', 'id' => 'BAJ-010', 'phone' => '9823456710', 'designation' => 'Helper'],
        ];

        // ── Factory 3: Gujarat Textile ───────────────────
        $gtm = Factory::create(['name' => 'Gujarat Textile Mills', 'address' => 'Ring Road, Surat, Gujarat 395002']);

        User::create([
            'name' => 'Bhavesh Patel',
            'email' => 'admin@gtmills.com',
            'password' => Hash::make('password'),
            'role' => 'company_admin',
            'factory_id' => $gtm->id,
        ]);

        $g1 = Floor::create(['factory_id' => $gtm->id, 'name' => 'Weaving Section', 'floor_number' => 1]);
        $g2 = Floor::create(['factory_id' => $gtm->id, 'name' => 'Dyeing Unit', 'floor_number' => 2]);

        $gtmWorkers = [
            ['floor' => $g1, 'name' => 'Haresh Patel', 'id' => 'GTM-001', 'phone' => '9712345601', 'designation' => 'Loom Operator'],
            ['floor' => $g1, 'name' => 'Jignesh Shah', 'id' => 'GTM-002', 'phone' => '9712345602', 'designation' => 'Weaver'],
            ['floor' => $g1, 'name' => 'Bhavesh Desai', 'id' => 'GTM-003', 'phone' => '9712345603', 'designation' => 'Mechanic'],
            ['floor' => $g1, 'name' => 'Mukesh Solanki', 'id' => 'GTM-004', 'phone' => '9712345604', 'designation' => 'Helper'],
            ['floor' => $g1, 'name' => 'Dinesh Parmar', 'id' => 'GTM-005', 'phone' => '9712345605', 'designation' => 'Supervisor'],
            ['floor' => $g2, 'name' => 'Ramaben Vaghela', 'id' => 'GTM-006', 'phone' => '9712345606', 'designation' => 'Dye Mixer'],
            ['floor' => $g2, 'name' => 'Komal Rathod', 'id' => 'GTM-007', 'phone' => '9712345607', 'designation' => 'Machine Operator'],
            ['floor' => $g2, 'name' => 'Jayesh Makwana', 'id' => 'GTM-008', 'phone' => '9712345608', 'designation' => 'Helper'],
            ['floor' => $g2, 'name' => 'Nilam Chauhan', 'id' => 'GTM-009', 'phone' => '9712345609', 'designation' => 'Quality Inspector'],
        ];

        // Create all workers with employee user accounts
        $allWorkerData = array_merge($tataWorkers, $bajajWorkers, $gtmWorkers);

        foreach ($allWorkerData as $w) {
            // Create employee user account
            $empUser = User::create([
                'name' => $w['name'],
                'email' => strtolower(str_replace(' ', '.', $w['name'])) . '@factory.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'factory_id' => $w['floor']->factory_id,
            ]);

            Worker::create([
                'floor_id' => $w['floor']->id,
                'user_id' => $empUser->id,
                'name' => $w['name'],
                'employee_id' => $w['id'],
                'phone' => $w['phone'],
                'designation' => $w['designation'],
            ]);
        }

        // ── Seed today's attendance ──────────────────────
        $today = Carbon::today();
        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'absent', 'late', 'half_day'];

        foreach (Worker::all() as $worker) {
            $status = $statuses[array_rand($statuses)];
            $checkIn = null;
            $checkOut = null;

            if ($status === 'present') {
                $checkIn = sprintf('%02d:%02d', rand(8, 9), rand(0, 59));
                $checkOut = sprintf('%02d:%02d', rand(17, 18), rand(0, 59));
            } elseif ($status === 'late') {
                $checkIn = sprintf('%02d:%02d', rand(10, 11), rand(0, 59));
                $checkOut = sprintf('%02d:%02d', rand(17, 19), rand(0, 59));
            } elseif ($status === 'half_day') {
                $checkIn = sprintf('%02d:%02d', rand(8, 9), rand(0, 59));
                $checkOut = sprintf('%02d:%02d', rand(13, 14), rand(0, 59));
            }

            Attendance::create([
                'worker_id' => $worker->id,
                'floor_id' => $worker->floor_id,
                'date' => $today,
                'status' => $status,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
            ]);
        }
    }
}
