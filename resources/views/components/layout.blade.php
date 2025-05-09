<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        @isset($doctitle)
            {{ $doctitle }} | blogger
        @else
            blogger
        @endisset
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>

<body>
    <header class="header-bar mb-3">
        <div class="container d-flex flex-column flex-md-row align-items-center p-3">
            <h4 class="my-0 mr-md-auto font-weight-normal">
                {{-- At least one wire component must be on page for wire:navigate to work --}}
                <a wire:navigate href="/" class="text-white">blogger</a>
            </h4>

            @auth
                <div class="flex-row my-3 my-md-0">
                    @persist('persist_header')
                        <livewire:chat />
                    <livewire:search />
                    @endpersist

                    <a wire:navigate href="/profile/{{ auth()->user()->username }}" class="mr-2">
                        <img title="My Profile" data-toggle="tooltip" data-placement="bottom"
                            style="width: 34px; height: 34px; border-radius: 16px; border: 2px solid purple;"
                            src="{{ auth()->user()->avatar }}" />
                    </a>
                </div>


                <ul class="shadow-button-set">
                    <li>
                        <button>
                            <a wire:navigate class="inner-btn" href="/create-post">Create Post</a>
                        </button>
                    </li>
                    <li>
                        <button>
                            <a wire:navigate class="inner-btn" href="/bookmarks/">
                                My Bookmarks
                                <i class="ml-2 fa-solid fa-bookmark"></i>
                            </a>
                        </button>
                    </li>
                    <form action="/logout" method="POST" class="d-inline">
                        @csrf
                        <button class="button">
                            <div class="text">Sign Out</div>
                        </button>
                    </form>


                </ul>
            @else
                <form action="/login" method="POST" class="mb-0 pt-2 pt-md-0">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md mr-0 pr-md-0 mb-3 mb-md-0">
                            <input name="loginusername" class="form-control form-control-sm input-dark" type="text"
                                placeholder="Username" autocomplete="off" />
                        </div>
                        <div class="col-md mr-0 pr-md-0 mb-3 mb-md-0">
                            <input name="loginpassword" class="form-control form-control-sm input-dark" type="password"
                                placeholder="Password" />
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm">Sign In</button>
                        </div>
                    </div>
                </form>
            @endauth
        </div>


    </header>
    <!-- header ends here -->


    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="container conatiner--narrow">
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        </div>
    @elseif(session()->has('fail'))
        <div class="container conatiner--narrow">
            <div class="alert alert-danger text-center">
                {{ session('fail') }}
            </div>
        </div>
    @endif

    {{ $slot }}


    <!-- footer begins -->
    <footer class="border-top text-center small text-muted py-3">
        <p class="m-0">Copyright &copy; {{ date('Y') }} <a href="/" class="text-muted">blogger</a>. All
            rights reserved.
        </p>
    </footer>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
        </script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
    </script>
</body>

</html>