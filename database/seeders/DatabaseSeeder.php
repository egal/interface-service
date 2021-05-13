<?php

namespace Database\Seeders;

use App\Models\InterfaceMetadata;
use App\Models\MenuEntryMetadata;
use App\Models\MenuMetadata;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(SomeSeeder::class);

        $clientMenu = new MenuMetadata();
        $clientMenu->id = 'client';
        $clientMenu->name = 'Клиент';
        $clientMenu->save();

        $userCrudInterface = new InterfaceMetadata();
        $userCrudInterface->id = 'test_interface';
        $userCrudInterface->name = 'Тестовый интерфейс';
        $userCrudInterface->type = 'egal:table';
        $userCrudInterface->data = json_encode([]);
        $userCrudInterface->save();

        for ($i = 0; $i < 10; $i++) {
            $menuEntry = new MenuEntryMetadata();
            $menuEntry->name = 'Test'.$i;
            if (array_rand([true, false])) {
                /** @var MenuEntryMetadata $parent */
                $parent = MenuEntryMetadata::query()->inRandomOrder()->first();
                $menuEntry->parent_menu_entry_metadata_id = $parent->id ?? null;
            }
            $menuEntry->interface_metadata_id = $userCrudInterface->id;
            $menuEntry->menu_metadata_id = $clientMenu->id;
            $menuEntry->save();
        }

    }

}
