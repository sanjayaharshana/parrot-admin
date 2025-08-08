<?php

namespace Modules\Documentation\Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DocumentationCategorySeeder::class,
            DocumentationPageSeeder::class,
        ]);
    }
}
