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
    
    <div class="container my-5">
    <!-- Webinar Title -->
    <div class="text-center mb-4">
        <h2>{{ $webinar->subject }}</h2>
    </div>

    <!-- Webinar Details -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Webinar Details</h5>
            <p><strong>Description:</strong> {{ $webinar->description }}</p>
            <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($webinar->startTime)->format('M d, Y h:i A') }}</p>
            <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($webinar->endTime)->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Register for Webinar</h5>
            <form action="{{ route('webinar.register', $webinar->id) }}" method="POST">
                @csrf

                <!-- First Name -->
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>





<!-- Bootstrap JS and dependencies (optional for JS functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
