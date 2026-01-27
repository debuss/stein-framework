<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$this->e($title)?> | Stein Framework</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0a0a0a; color: #ededed; }
        .electric-glow { box-shadow: 0 0 20px rgba(52, 211, 153, 0.2); }
    </style>
</head>
<body class="font-sans antialiased">
<nav class="border-b border-gray-800 p-6">
    <div class="container mx-auto flex justify-between items-center">
        <span class="text-2xl font-bold tracking-tighter text-emerald-400">⚡ STEIN</span>
        <div class="space-x-6 text-gray-400">
            <a href="/api/v1/users" class="hover:text-white transition" target="_blank">API</a>
            <a href="https://github.com" class="hover:text-white transition">GitHub</a>
        </div>
    </div>
</nav>

<main>
    <?=$this->section('content')?>
</main>

<footer class="mt-20 border-t border-gray-800 p-10 text-center text-gray-600 text-sm">
    Built with bolts and lightning. Running on FrankenPHP.
</footer>
</body>
</html>
