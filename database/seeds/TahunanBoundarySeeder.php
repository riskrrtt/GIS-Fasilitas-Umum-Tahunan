<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunanBoundarySeeder extends Seeder
{
    public function run()
    {
        // Read GeoJSON file
        $geojsonPath = 'D:/Tahunan.geojson';
        $geojsonContent = file_get_contents($geojsonPath);
        $geojson = json_decode($geojsonContent, true);

        // Extract coordinates and convert from [lng, lat] to [lat, lng]
        $coordinates = $geojson['features'][0]['geometry']['coordinates'][0][0];
        $formattedCoords = [];

        foreach ($coordinates as $coord) {
            $formattedCoords[] = [$coord[1], $coord[0]]; // Swap to [lat, lng]
        }

        // Insert into database
        DB::table('tb_desa')->insert([
            'nama' => 'Tahunan',
            'colour' => null,
            'area' => json_encode($formattedCoords),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        echo "SUCCESS: Tahunan boundary inserted with " . count($formattedCoords) . " points!\n";
    }
}
