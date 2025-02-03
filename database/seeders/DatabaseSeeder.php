<?php

namespace Database\Seeders;

use Closure;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $this->command->warn(PHP_EOL . 'Creating superadmin...');
        $this->withProgressBar(1, fn() => User::factory(1)->create([
            'name' => 'Superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@polytrack.com',
            'password' => bcrypt('12345678'),
        ]));
        $this->command->info('Superadmin user has been created successfully.');

        Artisan::call('shield:install', [
            'panel' => 'admin',
            '--no-interaction' => true,
        ]);

        Artisan::call('shield:generate', [
            '--panel' => 'admin',
            '--all' => true,
            '--no-interaction' => true,
        ]);

        Artisan::call('shield:super-admin', [
            '--user' => 1,
            '--panel' => 'admin',
            '--no-interaction' => true,
        ]);

        $this->call([
            UnitSeeder::class,
            TahunAkademikSeeder::class,
            StatusCasisSeeder::class,
        ]);

        // if (!Role::where('name', 'operator_sd')->exists()) {
        //     $operatorSdRole = Role::create(['name' => 'operator_sd']);
        //     $operatorSdRolePermissions = Permission::query()
        //         ->whereIn('name', [
        //             'view_croscek::sd',
        //             'view_any_croscek::sd',
        //             'create_croscek::sd',
        //             'update_croscek::sd',
        //             'delete_croscek::sd',
        //             'delete_any_croscek::sd',
        //             'force_delete_croscek::sd',
        //             'force_delete_any_croscek::sd',
        //         ])->get();
        //     $operatorSdRole->syncPermissions($operatorSdRolePermissions);

        //     $user = User::create([
        //         'name' => 'Operator SD',
        //         'username' => 'operatorsd',
        //         'email' => 'sdit@alfityan.sch.id',
        //         'email_verified_at' => now(),
        //         'unit_id' => 2,
        //         'password' => bcrypt('asdasd'),
        //     ]);
        //     $user->assignRole('operator_sd');
        // }


        // if (!Role::where('name', 'operator_tk')->exists()) {
        //     $operatorTkRole = Role::create(['name' => 'operator_tk']);
        //     $operatorTkRolePermissions = Permission::query()
        //         ->whereIn('name', [
        //             'view_croscek::tk',
        //             'view_any_croscek::tk',
        //             'create_croscek::tk',
        //             'update_croscek::tk',
        //             'delete_croscek::tk',
        //             'delete_any_croscek::tk',
        //             'force_delete_croscek::tk',
        //             'force_delete_any_croscek::tk',
        //         ])->get();
        //     $operatorTkRole->syncPermissions($operatorTkRolePermissions);

        //     $user = User::create([
        //         'name' => 'Operator TK',
        //         'username' => 'operatorsk',
        //         'email' => 'tkit@alfityan.sch.id',
        //         'email_verified_at' => now(),
        //         'unit_id' => 1,
        //         'password' => bcrypt('asdasd'),
        //     ]);
        //     $user->assignRole('operator_tk');
        // }

        // if (!Role::where('name', 'operator_a')->exists()) {
        //     $operatorSmpRole = Role::create(['name' => 'operator_smp']);
        //     $operatorSmpRolePermissions = Permission::query()
        //         ->whereIn('name', [
        //             'view_croscek::smp',
        //             'view_any_croscek::smp',
        //             'create_croscek::smp',
        //             'update_croscek::smp',
        //             'delete_croscek::smp',
        //             'delete_any_croscek::smp',
        //             'force_delete_croscek::smp',
        //             'force_delete_any_croscek::smp',
        //         ])->get();
        //     $operatorSmpRole->syncPermissions($operatorSmpRolePermissions);

        //     $user = User::create([
        //         'name' => 'Operator Smp',
        //         'username' => 'operatorsmp',
        //         'email' => 'smpit@alfityan.sch.id',
        //         'email_verified_at' => now(),
        //         'unit_id' => 3,
        //         'password' => bcrypt('asdasd'),
        //     ]);
        //     $user->assignRole('operator_smp');
        // }

        // if (!Role::where('name', 'operator_sma')->exists()) {
        //     $operatorSmaRole = Role::create(['name' => 'operator_sma']);
        //     $operatorSmaRolePermissions = Permission::query()
        //         ->whereIn('name', [
        //             'view_croscek::sma',
        //             'view_any_croscek::sma',
        //             'create_croscek::sma',
        //             'update_croscek::sma',
        //             'delete_croscek::sma',
        //             'delete_any_croscek::sma',
        //             'force_delete_croscek::sma',
        //             'force_delete_any_croscek::sma',
        //         ])->get();
        //     $operatorSmaRole->syncPermissions($operatorSmaRolePermissions);

        //     $user = User::create([
        //         'name' => 'Operator Sma',
        //         'username' => 'operatorsma',
        //         'email' => 'smait@alfityan.sch.id',
        //         'email_verified_at' => now(),
        //         'unit_id' => 4,
        //         'password' => bcrypt('asdasd'),
        //     ]);
        //     $user->assignRole('operator_sma');
        // }
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);
        $progressBar->start();
        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');

        return $items;
    }

    
}
