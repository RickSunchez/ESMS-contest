<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" integrity="sha384-xeJqLiuOvjUBq3iGOjvSQSIlwrpqjSHXpduPd6rQpuiM3f5/ijby8pCsnbu5S81n" crossorigin="anonymous">

    <script>
        function deleteItem(id) {
            fetch("/api/delete?id=" + id)
                .then(()=>{
                    document.location.reload();
                });
        }
        function updateItem(id) {
            document.location.replace("/update?id="+id);
        }
    </script>
    
    <title>Videogames</title>
</head>
<body style="height: 100vh;">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Main</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/genres">Genres</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <div class="d-flex flex-column">
            <div class="container my-4">
                <?php include "app/views/" . $content_view;?>
            </div>
            <div class="container my-4">
            <?php if (isset($model)):?>
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Developer</th>
                        <th scope="col">Tags</th>
                        <th scope="col">Delete</th>
                        <th scope="col">Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($model->data as $data):?>
                    <tr>
                        <th scope="row"><?=$data["id"];?></td>
                        <td><?=$data["title"];?></td>
                        <td><?=$data["developer"];?></td>
                        <td><?=implode(", ",$data["tags"]);?></td>
                        <td>
                            <button 
                                type="button"   
                                onclick="deleteItem(this.getAttribute('item-id'))"
                                class="btn btn-danger"
                                item-id="<?=$data["id"];?>">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </td>
                        <td>
                            <a
                                type="button"
                                class="btn btn-primary"
                                href="update?id=<?=$data["id"];?>"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif;?>
            </div>
        </div>
    </div>
            

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>