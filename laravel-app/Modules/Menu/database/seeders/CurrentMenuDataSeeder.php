<?php

namespace Modules\Menu\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;

class CurrentMenuDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find all menu_data.php files in Modules
        $files = glob(base_path('Modules/*/database/seeders/data/menu_data.php'));

        if (empty($files)) {
            $message = 'No menu_data.php files found in Modules.';
            if (property_exists($this, 'command') && $this->command) {
                $this->command->error($message);
            } else {
                echo $message.PHP_EOL;
            }
            return;
        }

        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('Seeding menus and menu items from PHP data...');
        } else {
            echo 'Seeding menus and menu items from PHP data...'.PHP_EOL;
        }

        // Disable foreign key constraints (database-specific)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('SET session_replication_role = replica;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $allMenus = [];
        $allMenuItems = [];

        foreach ($files as $file) {
            $data = require $file;

            if (isset($data['menus']) && is_array($data['menus'])) {
                $allMenus = array_merge($allMenus, $data['menus']);
            }
            if (isset($data['menu_items']) && is_array($data['menu_items'])) {
                $allMenuItems = array_merge($allMenuItems, $data['menu_items']);
            }
        }

        // Seed menus safely
        foreach ($allMenus as $menuData) {
            Menu::updateOrCreate(
                ['slug' => $menuData['slug']], // unique key
                $menuData
            );
        }

        // Seed menu items safely
        foreach ($allMenuItems as $itemData) {
            MenuItem::updateOrCreate(
                ['slug' => $itemData['slug'], 'menu_id' => $itemData['menu_id']], // composite unique key
                $itemData
            );
        }

        // Re-enable foreign key constraints (database-specific)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
