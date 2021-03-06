<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoom List</title>
</head>
<style>
    .center {
        margin: 0;
        top: 50%;
        left: 50%;
        width: 50%;
    }
</style>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">ZOOM</a>
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
                <li class="nav-item">
                    <a class="nav-link" href="/mqtt">Mqtt</a>
                </li>
            </ul>

        </div>
        <div style="margin-right: 40px;">

            <p> Download: <a download="main-ksta.zip" href="{{ Storage::url('main-ksta.zip')}}" title="main-ksta.zip"> .exe</a>
            </p>
        </div>
    </nav>
    @include('flash-message')
    <div class="container">
        <form action="/api/saveUrl" method="POST">
            <div class="row">
                <div class="col-3">
                    <label for="deviceId" class="form-label">Device Id</label>
                    <input type="number" class="form-control" id="deviceId" name="device_id" required>
                </div>

                <div class="col-3">
                    <label for="topic" class="form-label">Topic</label>
                    <input type="text" class="form-control" id="topic" name="topic" required>
                </div>
                <div class="col-3">
                    <label for="agenda" class="form-label">Agenda</label>
                    <input type="text" class="form-control" id="agenda" name="agenda" required>
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
                <div class="center">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
            </div>
        </form>

        <div class="row row-cols-4">

            <div class="col">

            </div>
            <!-- <div class="col">
                    <label for="url" class="form-label">Zoom Url</label>
                    <input type="text" class="form-control" id="url" name="url" required>
                </div> -->
            <div class="col-sm">

            </div>



        </div>

        <h3 style="text-align: center;">{{$date}}</h3>
        <table class="table">
            <thead>
                <tr>

                    <th scope="col">Device ID</th>
                    <th scope="col">Topic</th>
                    <th scope="col">Agenda</th>
                    <th scope="col">Zoom Url</th>
                    <th scope="col">Meeting ID</th>
                    <th scope="col">Passcode</th>
                    <th scope="col">Start time</th>
                    <th scope="col">End time</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $data as $datalist)
                <tr>

                    <td>{{$datalist->device_id}}</td>
                    <td>{{$datalist->topic}}</td>
                    <td>{{$datalist->agenda}}</td>

                    <td>{{$datalist->url}}</td>
                    <td>{{$datalist->meeting_id}}</td>
                    <td>{{$datalist->password}}</td>
                    <td>{{$datalist->start_time}}</td>
                    <td>{{$datalist->end_time}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
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
