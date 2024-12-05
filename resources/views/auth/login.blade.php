<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Form</title>
    <link rel="icon" href="{{ asset('logos/lo.png') }}" type="image/png">
    <script src="{{ asset('tailwind.jsx') }}"></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <style>
        #hideMe {
            animation: hideAnimation 10s forwards;
        }

        @keyframes hideAnimation {
            to {
                opacity: 0;
                display: none;
            }
        }
    </style>
</head>
<body class="bg-blue-200">
    <div class="container mx-auto mt-4 col-md-7" id="hideMe">
        @if ($errors->any())
        <div class="col-12">
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        </div>
        @endif
        @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
    <div class="container mx-auto mt-12">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h6 class="text-center text-lg font-semibold mb-6">Sign into your account</h6>
            <form action="{{ route('login') }}" method="post">
                @csrf
                @method('post')
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="text" id="email" name="email" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Sign In</button>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a></p>
        </div>
    </div>
</body>
</html>
