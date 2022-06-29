<?php

namespace App\Console\Commands;

use DB;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $checkAdmin = User::where('is_admin', 1)->exists();

            if ($checkAdmin) {
                $this->error("Admin already created!");
                exit;
            }
            
            $admin = new User;
            $admin->name = 'Admin';
            $admin->email =  'admin@quiz.id';
            $admin->email_verified_at = now();
            $admin->password = Hash::make('123123');
            $admin->remember_token = Str::random(10);
            $admin->is_admin = 1;
            $admin->save();

            DB::commit();

            $this->info('Data created');
            $this->info('credential: admin@quiz.id, password: 123123');
        } catch (\Exception $err) {
            DB::rollback();

            $this->error($err->getMessage());
            $this->error('Failed to create admin, please re-run command');
        }
    }


}
