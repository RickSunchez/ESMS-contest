<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideogamesResource;
use App\Models\Videogames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideogamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (count($request->all()) == 0) {
            return VideogamesResource::collection(Videogames::all());
            // return Videogames::all();
        }
        if (isset($request->all()['tags'])) {
            $handler = new \SQLite3(env('DB_DATABASE'));
            $sql = "SELECT DISTINCT videogames.* FROM videogames, json_each(Tags)";

            $where = array();
            foreach ($request->all() as $key => $value) {
                $where[] = $this->compare_expression($key, $value);
            }

            $sql .= " WHERE " . implode(" OR ", $where);

            $raw = $handler -> query($sql);
            $result = array();

            while ($row = $raw -> fetchArray(SQLITE3_ASSOC)) {
                $result[] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'developer' => $row['developer'],
                    'tags' => json_decode($row['tags'])
                );;
            }
            return array(
                'data' => $result
            );
        }
    }

    private function compare_expression($field, $value) {
        if ($field == "id") {
            return "videogames.Id=" . $value;
        }
        if ($field == "title" || $field == "developer") {
            return "videogames." . $field . " LIKE \"%" . $value . "%\"";
        }
        if ($field == "tags") {
            $query = array();
            foreach (explode(",", $value) as $v) {
                $query[] = "value LIKE \"%" . $v . "%\"";
            }
            return implode(" OR ", $query);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tags = explode(",", $request->all()["tags"]);
        $created = Videogames::create(array(
            "title" => $request->all()["title"],
            "developer" => $request->all()["developer"],
            "tags" => json_encode($tags)
        ));

        return new VideogamesResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Videogames  $videogames
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new VideogamesResource(Videogames::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Videogames  $videogames
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $upd = array();
        if (isset($request->all()['title'])) {
            $upd['title'] = $request->all()['title'];
        }
        if (isset($request->all()['developer'])) {
            $upd['developer'] = $request->all()['developer'];
        }
        if (isset($request->all()['tags'])) {
            $tags = json_encode(explode(",", $request->all()['tags']));
            $upd['tags'] = $tags;
        }
        
        Videogames::where("Id", $id)
            ->update($upd);

        $game = Videogames::findOrFail($id);

        return new VideogamesResource($game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Videogames  $videogames
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Videogames::findOrFail($id);
        Videogames::where("Id", $id)
            ->delete();
        
        return new VideogamesResource($game);
    }
}
