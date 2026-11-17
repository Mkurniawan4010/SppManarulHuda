<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Pembayaran SPP Manarul Huda</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-custom-image {
            background-image: url('{{ asset('images/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .login-card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-custom-image">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-4">
            <!-- Login Card -->
            <div class="login-card rounded-lg shadow-xl p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <!-- Area untuk Logo -->
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Manarul Huda" class="h-24 w-auto">
                    </div>
                    <h1 class="text-4xl font-bold text-green-600">Aplikasi Pembayaran SPP Manarul Huda</h1>
                </div>
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="mt-1">
                                <input id="username" type="text" 
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                                    focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm
                                    @error('username') border-red-500 @enderror"
                                    name="username" value="{{ old('username') }}" required autofocus>
                                
                                @error('username')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1">
                                <input id="password" type="password" 
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                                    focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm
                                    @error('password') border-red-500 @enderror"
                                    name="password" required>
                                
                                @error('password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Sign in
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Footer -->
                <div class="mt-4 text-center text-sm text-gray-600">
                    &copy; {{ date('Y') }} Aplikasi Pembayaran SPP Manarul Huda. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html> 


