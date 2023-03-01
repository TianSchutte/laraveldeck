<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<head>
    <title>Laravel 8 Form Example Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div>
            User Register
        </div>
        <div >
            <form name="user-register-form" id="user-register-form" method="post" action="{{url('register')}}">
                @csrf
                <div>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div>
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
