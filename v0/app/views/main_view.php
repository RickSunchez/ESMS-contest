<script>
    function createItem(ev, obj) {
        ev.preventDefault();
        const data = new FormData(obj);
  
        var ts = data.get("ts").replaceAll(/ *, */g, ",");
        
        data.set("ts", escape(ts));

        var params = [];
        for (const row of data) params.push(row.join("="));
        
        var url = "api/create?" + params.join("&");
        console.log(url);
        fetch(url)
            .then(()=>{
                document.location.reload();
            })
    }
</script>
<form onsubmit="createItem(event, this)">
    <div class="form-group">
        <label for="exampleInputEmail1">Title</label>
        <input 
            type="text" 
            class="form-control" 
            id="title" 
            aria-describedby="title" 
            placeholder="Enter title"
            name="t"
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
        >
    </div>
    <div class="form-group">
        <label for="tags">Tags</label>
        <textarea 
            class="form-control" 
            id="tags" 
            rows="2"
            name="ts"
        ></textarea>
        <small id="tags" class="form-text text-muted">Enter tags separated by comma</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>