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
        <div class="row">
            <!-- Left Half: Form -->
            <div class="col-md-6">
                <h3 class="text-center mb-4">GoToWebinar Settings</h3>
                <form action="{{ route('gotoauth.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client ID</label>
                        <input type="text" class="form-control" id="client_id" name="client_id" placeholder="Enter Client ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="client_secret" class="form-label">Client Secret</label>
                        <input type="text" class="form-control" id="client_secret" name="client_secret" placeholder="Enter Client Secret" required>
                    </div>
                    <div class="mb-3">
                        <label for="organizer_key" class="form-label">Organizer Key</label>
                        <input type="text" class="form-control" id="organizer_key" name="organizer_key" placeholder="Enter Organizer Key" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>

            <!-- Right Half: Card -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="card w-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">Current Settings</h5>
                        <p><strong>Client ID:</strong> <span id="display-client-id">{{ $gotoauth ? '********' : 'N/A' }}</span></p>
                        <p><strong>Client Secret:</strong> <span id="display-client-secret">{{ $gotoauth ? '********' : 'N/A' }}</span></p>
                        <p><strong>Organizer Key:</strong> <span id="display-organizer-key">{{ $gotoauth ? $gotoauth->organizer_key : 'N/A' }}</span></p>
                        <div class="text-center mt-4">
                            <a href="{{route('gotoauth.getAccess')}}" id="get-access-btn" class="btn btn-success @if(!$gotoauth) disabled @endif" disabled>Get Access</a>
                        </div>
                        <div class="text-center mt-4">
                        <form action="{{ route('gotoauth.refreshToken') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary @if(!$gotoauth) disabled @endif" @if(!$gotoauth) disabled @endif>
                                Refresh Access Token
                            </button>
                        </form>
                    </div>
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
