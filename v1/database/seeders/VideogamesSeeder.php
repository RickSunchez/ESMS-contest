<?php

namespace Database\Seeders;

use App\Models\Videogames;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideogamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', -1);
        $this->mesh(15);
        ini_set('memory_limit', '128M');
    }

    private function mesh($count=5) {
        $apps = $this -> get_app_details($count);

        foreach ($apps as $key => $app) {
            Videogames::create($app);
        }
    }

    private function get_app_list($sync=false) {
        $path = env("STEAM_APPS");

        if (!file_exists($path) || $sync) {
            $source = file_get_contents("http://api.steampowered.com/ISteamApps/GetAppList/v2");
            $apps = json_decode($source) -> applist -> apps;
            
            $apps = array_values(
                array_filter($apps, function($app) {
                    return ($app -> name != "") && (!preg_match("/<.*>/mi", $app -> name));
                })
            );

            file_put_contents($path, json_encode($apps));
        }

        $data = file_get_contents($path);

        return json_decode($data);
    }

    private function get_app_details($amount=5) {
        $cache_path = env("STEAM_CACHE");
        $apps = $this -> get_app_list();

        if (!file_exists($cache_path)) {
            file_put_contents($cache_path, "{}");
        }

        $cache = json_decode(file_get_contents($cache_path));
        $result = array();

        $counter = 0;

        while ($counter < $amount) {
            $key = array_rand($apps, 1);
            $id = $apps[$key] -> appid;

            if (property_exists($cache, $id)) {
                $result[] = $cache -> $id;
                $counter++;
                continue;
            }

            $source = file_get_contents("https://store.steampowered.com/api/appdetails?appids=" . $id);
            $decode = json_decode($source);

            if (!isset($decode -> $id)) continue;

            $data = $decode -> $id;

            if (!$data -> success) continue;
            if (!isset($data -> data -> genres)) continue;
            if (count($data -> data -> genres) <= 1) continue;
            if (!isset($data -> data -> name)) continue;
            if (!isset($data -> data -> developers)) continue;

            $tags = array();
            foreach ($data -> data -> genres as $key => $value) {
                $tags[] = $value -> description;
            }

            $cache -> $id = array(
                "title" => addslashes($data -> data -> name),
                "developer" => addslashes($data -> data -> developers[0]),
                "tags" => json_encode($tags)
            );

            $result[] = $cache -> $id;
            $counter++;
        }

        file_put_contents($cache_path, json_encode($cache));

        return $result;
    }
}
