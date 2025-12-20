<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunanBoundarySeeder extends Seeder
{
    public function run()
    {
        // Read GeoJSON file from database/data folder
        $geojsonPath = database_path('data/Tahunan.geojson');

        if (!file_exists($geojsonPath)) {
            $this->command->warn("File '$geojsonPath' not found. Skipping boundary seed.");
            return;
        }

        $geojsonContent = file_get_contents($geojsonPath);
        $geojson = json_decode($geojsonContent, true);

        // Check if file is valid JSON
        if (!$geojson) {
            $this->command->error("Invalid JSON in '$geojsonPath'.");
            return;
        }

        // Extract coordinates and convert from [lng, lat] to [lat, lng]
        if (!isset($geojson['features'][0]['geometry']['coordinates'][0][0])) {
            $this->command->error("Unexpected GeoJSON structure.");
            return;
        }

        $coordinates = $geojson['features'][0]['geometry']['coordinates'][0][0];
        $formattedCoords = [];

        foreach ($coordinates as $coord) {
            $formattedCoords[] = [$coord[1], $coord[0]]; // Swap to [lat, lng]
        }

        // Insert into database
        DB::table('tb_desa')->insert([
            'nama' => 'Tahunan',
            'colour' => '#3388ff', // Default color
            'area' => json_encode($formattedCoords),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info("SUCCESS: Tahunan boundary inserted with " . count($formattedCoords) . " points!");
    }
}
