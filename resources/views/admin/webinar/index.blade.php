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



<div class="container mt-5">
    <h1 class="mb-4">Webinars</h1>
    <div class="row">
        @forelse($webinars as $webinar)
            <div class="col-md-4 mb-4">
                <div class="card {{ $webinar->canceled ? 'border-danger ' : '' }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $webinar->subject }}</h5>
                        <p class="card-text">{{ $webinar->description }}</p>
                        <p><strong>Start Time:</strong> {{ $webinar->startTime }}</p>
                        <p><strong>End Time:</strong> {{ $webinar->endTime }}</p>


                        @if($webinar->canceled)
                            <span class="badge bg-danger text-white">Canceled</span>
                        @else
                        <!-- Action Buttons -->
                        <a href="{{ route('webinar.edit', $webinar->id) }}" class="btn btn-primary btn-sm {{ $webinar->canceled ? 'disabled' : '' }} " {{ $webinar->canceled ? 'disabled' : '' }}>
                            Edit & View
                        </a>
                        <form action="{{ route('webinar.cancel', $webinar->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm {{ $webinar->canceled ? 'disabled' : '' }}" {{ $webinar->canceled ? 'disabled' : '' }}>
                                Cancel Webinar
                            </button>
                        </form>
                        @endif
                        
                    </div>
                </div>
            </div>
        @empty
            <p>No webinars available.</p>
        @endforelse

        
        
    </div>
    <div class="text-center mt-4">
            <a href="{{route('webinar.create')}}" class="btn btn-primary" disabled>Create Webinar</a>
        </div>
</div>




<!-- Bootstrap JS and dependencies (optional for JS functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
