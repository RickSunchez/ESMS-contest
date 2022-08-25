<script>
    function filterItems(filter) {
        document.location.replace("genres?ts=" + filter);
    }
</script>
<div class="form-group">
    <label for="genres">Select genre</label>
    <select 
        id="genres"
        class="form-select"
        onchange="filterItems(this.value)">
        <?php foreach($view_data as $value):?>
        <option><?=$value["value"];?></option>
        <?php endforeach; ?>
    </select>
</div>
