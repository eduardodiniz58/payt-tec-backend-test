<?php

// database/seeders/DatabaseSeeder.php

use App\Models\Redirect;
use Database\Factories\RedirectFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Redirect::factory(10)->create();
    }
}
