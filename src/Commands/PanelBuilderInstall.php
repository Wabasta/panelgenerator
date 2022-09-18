<?php

namespace Wabasta\PanelBuilder\Commands;

use Wabasta\PanelBuilder\Models\Role;
use Wabasta\PanelBuilder\Models\Menu;
use Illuminate\Console\Command;
use App\Models\User;

class PanelBuilderInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panelbuilder:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run installation of PanelBuilder.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
        $this->info('Publishing Spatie Permission configuration.');
        $this->callSilent('vendor:publish', [
            '--provider'   => "Spatie\Permission\PermissionServiceProvider",
        ]);

        $this->info('Please note: PanelBuilder requires fresh Laravel installation!');
        $this->info('Starting installation process of PanelBuilder...');
        $this->info('1. Copying initial files');
        $this->copyInitial();

        $this->info('2. Running migration');
        $this->call('migrate');

        $this->createRole();

        $this->info('3. Create first user');
        $this->createUser();

        $this->info('4. Copying master template to resource\views....');
        $this->copyMasterTemplate();

        $this->info('Installation was successful. Visit your_domain.com/admin to access admin panel');
    }

    /**
     *  Copy migration files to database_path('migrations') and User.php model to App
     */
    public function copyInitial()
    {
        // copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '2015_10_10_000000_create_roles_table', database_path('migrations' . DIRECTORY_SEPARATOR . '2015_10_10_000000_create_roles_table.php'));
        copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '2015_10_10_000000_update_users_table', database_path('migrations' . DIRECTORY_SEPARATOR . '2015_10_10_000000_update_users_table.php'));
        copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '2015_10_10_000000_create_menus_table', database_path('migrations' . DIRECTORY_SEPARATOR . '2015_10_10_000000_create_menus_table.php'));
        copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '2015_12_11_000000_create_users_logs_table', database_path('migrations' . DIRECTORY_SEPARATOR . '2015_12_11_000000_create_users_logs_table.php'));
        copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '2016_03_14_000000_update_menus_table', database_path('migrations' . DIRECTORY_SEPARATOR . '2016_03_14_000000_update_menus_table.php'));
        copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'publish' . DIRECTORY_SEPARATOR . 'User', app_path('Models\User.php'));
        $this->info('Migrations were transferred successfully');
    }

    /**
     *  Create first roles
     */
    public function createRole()
    {
        Role::create([
            'name' => 'Administrator'
        ]);
        Role::create([
            'name' => 'User'
        ]);
    }

    /**
     *  Create first user
     */
    public function createUser()
    {
        $data['name']     = $this->ask('Administrator name');
        $data['email']    = $this->ask('Administrator email');
        $data['password'] = bcrypt($this->secret('Administrator password'));
        $data['role_id']  = 1;
        User::create($data);
        $this->info('User has been created');
    }

    /**
     *  Copy master template to resource/view
     */
    public function copyMasterTemplate()
    {
        Menu::insert([
            [
                'name'      => 'User',
                'title'     => 'User',
                'menu_type' => 0
            ],
            [
                'name'      => 'Role',
                'title'     => 'Role',
                'menu_type' => 0
            ]
        ]);
        $this->callSilent('vendor:publish', [
            '--tag'   => ['panelbuilder'],
            '--force' => true
        ]);
        $this->info('Master template was transferred successfully');
    }
}

