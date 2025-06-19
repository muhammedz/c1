<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Şifre Koruması</title>
    <script>
    // Tailwind CDN uyarısını bastır
    const originalWarn = console.warn;
    console.warn = function(...args) {
        const message = args.join(' ');
        if (message.includes('cdn.tailwindcss.com should not be used in production')) {
            return; // Bu uyarıyı gösterme
        }
        originalWarn.apply(console, args);
    };
</script>
<script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
        }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-8 bg-white rounded-lg shadow-lg">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Site Şifre Koruması</h2>
            <p class="text-gray-600">Bu site şu anda şifre korumalıdır. Lütfen devam etmek için şifreyi giriniz.</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Hata!</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('check.site.password') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Şifre</label>
                <input type="password" name="password" id="password" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    required autocomplete="off">
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Giriş Yap
                </button>
            </div>
        </form>
    </div>
</body>
</html> 