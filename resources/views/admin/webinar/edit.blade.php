<!-- resources/views/webinars/create.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Webinar</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">My App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{route('webinar.index')}}">Webinar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('gotoauth.edit')}}">GoToWebinar Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container">
<div class="row">

    <div class="col-md-12 p-0">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Data From GOTOWEBINAR</h3>
                </div>
                <div class="card-body">

                    @if ($webinar->webinarKey == '')
                        <div class="alert alert-warning">
                        <strong>Warning!</strong>
                            The create endpoint for GoToWebinar is not working. Please create a webinar directly on GoToWebinar and update the "webinar key" down in the update form to synchronize data to continue testing.
                         </div>
                    @else
                    <p><strong>Organizer:</strong> {{ $webinarDataFromApi['organizerName'] }} ({{ $webinarDataFromApi['organizerEmail'] }})</p>
                    <p><strong>Start Time:</strong> {{ date('F j, Y, g:i A', strtotime($webinarDataFromApi['times'][0]['startTime'])) }}</p>
                    <p><strong>End Time:</strong> {{ date('F j, Y, g:i A', strtotime($webinarDataFromApi['times'][0]['endTime'])) }}</p>
                    <p><strong>Time Zone:</strong> {{ $webinarDataFromApi['timeZone'] }}</p>
                    <p><strong>Registration URL:</strong> <a href="{{ $webinarDataFromApi['registrationUrl'] }}" target="_blank">{{ $webinarDataFromApi['registrationUrl'] }}</a></p>
                    <p><strong>Type:</strong> {{ ucfirst($webinarDataFromApi['type']) }}</p>
                    <p><strong>Experience Type:</strong> {{ ucfirst(strtolower($webinarDataFromApi['experienceType'])) }}</p>
                    <p><strong>Registrants:</strong> {{ $webinarDataFromApi['numberOfRegistrants'] }} / {{ $webinarDataFromApi['registrationLimit'] }}</p>
                    <p><strong>Is Password Protected:</strong> {{ $webinarDataFromApi['isPasswordProtected'] ? 'Yes' : 'No' }}</p>
                    @endif
                </div>
                <div class="card-footer text-center">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 p-0">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Registrants From GOTOWEBINAR</h3>
                </div>
                <div class="card-body">

                @if(!empty($registrants))
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                                <th>Join URL</th>
                                <th>Time Zone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrants as $index => $registrant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $registrant['firstName'] }}</td>
                                    <td>{{ $registrant['lastName'] }}</td>
                                    <td>{{ $registrant['email'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($registrant['registrationDate'])->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $registrant['status'] }}</td>
                                    <td>
                                        <a href="{{ $registrant['joinUrl'] }}" target="_blank" class="btn btn-primary btn-sm">
                                            Join
                                        </a>
                                    </td>
                                    <td>{{ $registrant['timeZone'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No registrants found.
                </div>
            @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{route('webinar.register', $webinar->id)}}" class="btn btn-primary {{ ($webinar->webinarKey == '') ? 'disabled' : '' }} " {{ ($webinar->webinarKey == '')  ? 'disabled' : '' }}>
                        Register User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 p-0">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Data From Application</h3>
                </div>
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $webinar['subject'] }}</p>
                    <p><strong>Description:</strong>  {{ $webinar['description'] }}</p>
                    <p><strong>Start Time:</strong> {{ date('F j, Y, g:i A', strtotime($webinar['startTime'])) }}</p>
                    <p><strong>End Time:</strong> {{ date('F j, Y, g:i A', strtotime($webinar['endTime'])) }}</p>
                </div>
                <div class="card-footer text-center">
                    
                </div>
            </div>
        </div>
    </div>
   
    <div class="col-md-12 p-0">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Update Webinar "{{ $webinar['subject']}}"</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('webinar.update', $webinar->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Webinar Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$webinar['subject']}}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"  required>{{$webinar['description']}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="event_time">Event Time</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{$startDate}}" required>
                        </div>

                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="{{$startTimeOnly}}" required>
                        </div>

                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="{{$endTimeOnly}}" required>
                        </div>

                        <br>
                        <br>
                        <div class="alert alert-warning">
                            <strong>Warning!</strong>
                            The create endpoint for GoToWebinar is not working. Please create a webinar directly on GoToWebinar and update the "webinar key" here to synchronize data to continue testing.
                        </div>

                        <div class="form-group">
                            <label for="name">GTW Webinar Key</label>
                            <input type="text" class="form-control" id="name" name="webinarKey" value="{{$webinar['webinarKey']}}">
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
            </div>
            <div class="card-footer text-center">
            </div>
        </div>
    </div>

</div>
</div>

<!-- Bootstrap JS and dependencies (optional for JS functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
