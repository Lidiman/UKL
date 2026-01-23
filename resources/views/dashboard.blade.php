<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .profile-card { max-width: 480px; margin: 1.5rem auto; }
        .avatar {
            width: 96px; height: 96px; object-fit:cover;
            border-radius:50%; border:4px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Team Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Team</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Reports</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email ?? ''))).' ?s=40&d=identicon' }}" alt="avatar" class="rounded-circle me-2" width="32" height="32">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') ?? '#' }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1 class="h4">Please make a good Dashboard here that have Profile and a navbar</h1>
            <p class="text-muted">This is a starter layout. Customize components and routes as needed.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Profile card -->
        <div class="col-12 col-md-6">
            <div class="card profile-card shadow-sm">
                <div class="card-body text-center">
                    @auth
                        <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=200&d=identicon' }}" alt="avatar" class="avatar mb-3">
                        <h5 class="card-title mb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small mb-2">{{ Auth::user()->email }}</p>
                        <p class="mb-3"><span class="badge bg-primary">{{ Auth::user()->role ?? 'Team Member' }}</span></p>

                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-outline-secondary btn-sm">View Activity</a>
                        </div>
                    @else
                        <img src="https://www.gravatar.com/avatar/?s=200&d=mp" alt="avatar" class="avatar mb-3">
                        <h5 class="card-title mb-0">Gues</h5>
                        <p class="text-muted small mb-2">Please log in to see profile details.</p>
                        <a href="{{ route('login') ?? '#' }}" class="btn btn-primary btn-sm">Login</a>
                    @endauth
                </div>
            </div>

            <!-- Quick stats / placeholders -->
            <div class="row mt-3 gx-2">
                <div class="col-6">
                    <div class="card text-center small">
                        <div class="card-body">
                            <div class="fw-bold">12</div>
                            <div class="text-muted">Projects</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card text-center small">
                        <div class="card-body">
                            <div class="fw-bold">4</div>
                            <div class="text-muted">Open Tasks</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right column: quick links / activity -->
        <div class="col-12 col-md-4 mt-3 mt-md-0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled small mb-0">
                        <li><a href="#" class="d-block py-1">Create Project</a></li>
                        <li><a href="#" class="d-block py-1">Team Members</a></li>
                        <li><a href="#" class="d-block py-1">Reports</a></li>
                        <li><a href="#" class="d-block py-1">Support</a></li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Recent Activity</h6>
                    <p class="small text-muted mb-0">No recent activity — start collaborating with your team.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="text-center py-3 text-muted small">
    © {{ date('Y') }} Your Team
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>