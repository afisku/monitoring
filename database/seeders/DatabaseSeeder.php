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
        ]);

        if (!Role::where('name', 'operator_sd')->exists()) {
            $operatorJurusanRole = Role::create(['name' => 'operator_sd']);
            $operatorJurusanRolePermissions = Permission::query()
                ->whereIn('name', [
                    'view_honor::non::kjm',
                    'view_any_honor::non::kjm',
                    'create_honor::non::kjm',
                    'update_honor::non::kjm',
                    'delete_honor::non::kjm',
                    'delete_any_honor::non::kjm',
                    'view_honor::kjm',
                    'view_any_honor::kjm',
                    'create_honor::kjm',
                    'update_honor::kjm',
                    'delete_honor::kjm',
                    'delete_any_honor::kjm'
                ])->get();
            $operatorJurusanRole->syncPermissions($operatorJurusanRolePermissions);

            $user = User::create([
                'name' => 'User 1',
                'username' => 'user1',
                'email' => 'asd@asd.asd',
                'email_verified_at' => now(),
                'unit_id' => 2,
                'password' => bcrypt('asdasd'),
            ]);
            $user->assignRole('operator_sd');
        }
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
