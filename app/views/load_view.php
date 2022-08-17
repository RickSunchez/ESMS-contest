<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" integrity="sha384-xeJqLiuOvjUBq3iGOjvSQSIlwrpqjSHXpduPd6rQpuiM3f5/ijby8pCsnbu5S81n" crossorigin="anonymous">
    
    <title>Videogames</title>
    <script>
        window.onload = () => {
            fetch("/api/init")
                .then(resp=>resp.text())
                .then(answ=>{
                    document.location.replace("/");
                })
        }
    </script>
</head>
<body style="height: 100vh;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="spinner-border" role="status" style="width: 80px; height: 80px;">
            <span class="sr-only"></span>
        </div>
    </div>
</body>
<html>