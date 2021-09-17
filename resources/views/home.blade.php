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
    <title>Zoom List</title>
</head>

<body>
    <div class="container">
        <div class="row row-cols-4">
            <form action="/api/saveUrl" method="POST">
                <div class="col">
                    <label for="deviceId" class="form-label">Device Id</label>
                    <input type="number" class="form-control" id="deviceId" name="device_id" required>
                </div>
                <div class="col">
                    <label for="url" class="form-label">Zoom Url</label>
                    <input type="text" class="form-control" id="url" name="url" required>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start time</label>
                        <input type="text" class="form-control timepicker" id="start_time" placeholder="XX:XX" name="start_time" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End time</label>
                        <input type="text" class="form-control timepicker" id="end_time" placeholder="XX:XX" name="end_time" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary ">Submit</button>
            </form>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Device ID</th>
                <th scope="col">Zoom Url</th>
                <th scope="col">Start time</th>
                <th scope="col">End time</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $data as $datalist)
            <tr>
                <td>{{$datalist->id}}</td>
                <td>{{$datalist->device_id}}</td>
                <td>{{$datalist->url}}</td>
                <td>{{$datalist->start_time}}</td>
                <td>{{$datalist->end_time}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

<script>

$(document).ready(function(){
    $('input.timepicker').timepicker({});
});
$('.timepicker').timepicker({
    timeFormat: 'HH:mm ',
    interval: 15,
    minTime: '00',
    maxTime: '11:00pm',
    defaultTime: '08',
    startTime: '08:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
</script>