<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/index.css">
  <link rel="stylesheet" href="/css/books.css">
</head>
<body>

@section('header')
 @if (Route::has('login'))
    <div class="nav align-items-center justify-content-end">
        @auth
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline mr-4">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline mr-4">Register</a>
            @endif
        @endauth
    </div>
  @endif
@show

@section('menu')
<ul class="nav justify-content-center align-items-center">
  <li class="nav-item">
    <a class="nav-link" href="{{url('/categories')}}">Категории</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{url('/books')}}">Книги</a>
  </li>
</ul>
@show

<div class="container">
    <div class="row">
        <div class="col-12">
            @yield('content')
        </div>
    </div>
</div>	

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>