<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">ZOOM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/device">Device</a>
                </li>
            </ul>
        </div>

    </nav>

    <div class="container">

        <form action="/api/saveDevice" method="POST">
            <div class="row">
                <div class="col-4">
                    <label for="deviceId" class="form-label">Device Id</label>
                    <input type="number" class="form-control" id="deviceId" name="device_id" required>
                </div>

                <div class="col-4">
                    <label for="name" class="form-label">name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-4">
                    <label for="location" class="form-label">location</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="col-4">
                    <label for="zoom_email" class="form-label">email</label>
                    <input type="text" class="form-control" id="zoom_email" name="zoom_email" required>
                </div>
                <div class="col-4">
                    <label for="zoom_api" class="form-label">api key</label>
                    <input type="text" class="form-control" id="zoom_api" name="zoom_api_key" required>
                </div>
                <div class="col-4">
                    <label for="zoom_secret" class="form-label">secret key</label>
                    <input type="text" class="form-control" id="zoom_secret" name="zoom_api_secret" required>
                </div>


                <div class="center">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
            </div>
        </form>
        <h3 style="text-align: center;">List Host</h3>
        <table class="table">
            <thead>
                <tr>

                    <th scope="col">Device ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Location</th>
                    <th scope="col">E-mail</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $data as $datalist)
                <tr>
                    <td>{{$datalist->device_id}}</td>
                    <td>{{$datalist->name}}</td>
                    <td>{{$datalist->location}}</td>
                    <td>{{$datalist->zoom_email}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
