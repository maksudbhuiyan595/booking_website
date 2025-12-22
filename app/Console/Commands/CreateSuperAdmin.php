<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user and assign role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'admin@gmail.com';
        $password = 'password';
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            $this->info("Super Admin user created successfully.");
        } else {
            $this->info("User already exists. Assigning role...");
        }

        if (!$user->hasRole('super_admin')) {
            $user->assignRole($role);
            $this->info("Role 'super_admin' assigned to the user.");
        } else {
            $this->info("User already has the 'super_admin' role.");
        }

        $this->info("---------------------------------------");
        $this->info("Login Email: $email");
        $this->info("Login Password: $password");
        $this->info("---------------------------------------");
    }
}
