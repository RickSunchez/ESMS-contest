<script>
    function updateItem(ev, obj) {
        ev.preventDefault();
        const id = obj.getAttribute("item-id");
        const data = new FormData(obj);
  
        data.append("id", id);
        var ts = data.get("ts").replaceAll(/ *, */g, ",");
        
        data.set("ts", escape(ts));

        var params = [];
        for (const row of data) params.push(row.join("="));
        
        var url = "api/update?" + params.join("&");
        console.log(url);
        fetch(url)
            .then(()=>{
                document.location.reload();
            })
    }
</script>

<form onsubmit="updateItem(event, this)" item-id="<?=$view_data[0]["id"]?>">
    <div class="form-group">
        <label for="exampleInputEmail1">Title</label>
        <input 
            type="text" 
            class="form-control" 
            id="title" 
            aria-describedby="title" 
            placeholder="Enter title"
            name="t"
            value="<?=$view_data[0]["title"]?>"
        >
    </div>
    <div class="form-group">
        <label for="developer">Developer</label>
        <input 
            type="text" 
            class="form-control" 
            id="developer" 
            aria-describedby="developer" 
            placeholder="Enter developer"
            name="d"
            value="<?=$view_data[0]["developer"]?>"
        >
    </div>
    <div class="form-group">
        <label for="tags">Tags</label>
        <textarea 
            class="form-control" 
            id="tags" 
            rows="2"
            name="ts"
        ><?=implode(", ", $view_data[0]["tags"])?></textarea>
        <small id="tags" class="form-text text-muted">Enter tags separated by comma</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>