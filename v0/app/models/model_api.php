<?php

class Model_Api extends Model {
    public function create_data($body=null) {
        $body = is_null($body)
            ? $this -> responce_body()
            : $body;
        
        if (!isset($body["Title"])) return false;
        if (!isset($body["Developer"])) return false;
        if (!isset($body["Tags"])) return false;

        $sql = "
            INSERT INTO videogames 
            VALUES (
                NULL, 
                '" . $body["Title"] . "', 
                '" . $body["Developer"] . "', 
                json('" . json_encode(explode(",", $body["Tags"])) . "'))
        ";
        
        return $this -> handler -> query($sql);
    }
    
    public function read_data($and=true) {
        $sql = "SELECT DISTINCT videogames.* FROM videogames, json_each(Tags)";

        $filters = $this -> responce_body();
        if (count($filters) != 0) {
            $where = array();
            foreach ($filters as $key => $value) {
                $where[] = $this -> compare_expression($key, $value);
            }

            $sql .= " WHERE " . implode($and ? " AND " : " OR ", $where);
        }
        
        return $this -> handler -> query($sql);
    }

    public function update_data() {
        $filters = $this -> responce_body();
        if (count($filters) <= 1 || !isset($filters["Id"])) {
            return false;
        }

        $sql = "UPDATE videogames SET ";
        $sql .= $this -> set_fields($filters);
        $sql .= " WHERE Id=" . $filters["Id"];
        
        return $this -> handler -> query($sql);
    }

    public function delete_data() {
        $filters = $this -> responce_body();
        if (!isset($filters["Id"])) {
            return false;
        }

        $sql = "DELETE FROM videogames WHERE Id=" . $filters["Id"];

        return $this -> handler -> query($sql);
    }

    public function get_genres() {
        $sql = "SELECT DISTINCT value FROM videogames, json_each(Tags)";
        return $this -> handler -> query($sql);
    }

    private function responce_body() {
        $filters = array();

        if (isset($_GET["id"])) $filters["Id"] = $_GET["id"];
        if (isset($_GET["t"])) $filters["Title"] = $_GET["t"];
        if (isset($_GET["d"])) $filters["Developer"] = $_GET["d"];
        if (isset($_GET["ts"])) $filters["Tags"] = $_GET["ts"];

        return $filters;
    }

    private function compare_expression($field, $value) {
        if ($field == "Id") {
            return "videogames.Id=" . $value;
        }
        if ($field == "Title" || $field == "Developer") {
            return "videogames." . $field . " LIKE \"%" . $value . "%\"";
        }
        if ($field == "Tags") {
            $query = array();
            foreach (explode(",", $value) as $v) {
                $query[] = "value LIKE \"%" . $v . "%\"";
            }
            return implode(" OR ", $query);
        }
    }

    private function set_fields($fields) {
        $query = array();
        foreach ($fields as $key => $value) {
            if ($key == "Id") continue;
            if ($key == "Title" || $key == "Developer") {
                $value = "\"" . $value . "\"";
            }
            if ($key == "Tags") {
                $value="json('" . json_encode(explode(",", $value)) . "')";
            }

            $query[] = $key . "=" . $value;
        }

        return implode(",", $query);
    }
}