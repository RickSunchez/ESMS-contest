<?php

class Model {
    function __construct() {
        $this -> handler = new SQLite3("data/videogames.db");

        $this -> init();
        $this -> mesh();
    }

    private function init() {
        $sql = "
            CREATE TABLE IF NOT EXISTS videogames (
                Id INTEGER PRIMARY KEY,
                Title TEXT,
                Developer TEXT, 
                Tags TEXT 
            )
        ";

        $this -> handler -> query($sql);
    }

    public function get_data() {}

    private function mesh() {
        $flag_path = "data/onload.flag";
        if (file_exists($flag_path)) return false;

        $sql = "INSERT INTO videogames VALUES ";
        $values = array();

        $apps = $this -> get_app_details(25);
        echo var_dump($apps);
        foreach ($apps as $key => $app) {
            $values[] = "(
                NULL,
                \"".$app["Title"]."\",
                \"".$app["Developer"]."\",
                json('".$app["Tags"]."')
            )";
        }

        $sql .= implode(",", $values);
        
        $this -> handler -> query($sql);

        file_put_contents("data/onload.flag", 1);
    }

    private function get_app_list($sync=false) {
        $path = "data/steam_apps.json";

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
        $cache_path = "data/steam_cache.json";
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
                "Title" => addslashes($data -> data -> name),
                "Developer" => addslashes($data -> data -> developers[0]),
                "Tags" => json_encode($tags)
            );

            $result[] = $cache -> $id;
            $counter++;
        }

        file_put_contents($cache_path, json_encode($cache));

        return $result;
    }
}