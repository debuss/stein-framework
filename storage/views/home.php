<?php $this->layout('layout', ['title' => 'It\'s Alive!']) ?>

<div class="container mx-auto px-6 py-20 text-center">
    <div class="inline-block px-3 py-1 mb-6 text-xs font-semibold tracking-wider text-emerald-400 uppercase bg-emerald-400/10 rounded-full">
        v1.0.0 "The Prometheus"
    </div>

    <h1 class="text-6xl md:text-8xl font-black mb-8 tracking-tight">
        It's <span class="text-emerald-400 italic">Alive!</span>
    </h1>

    <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-12">
        The PSR-assembled, high-performance micro-framework.
        Minimalist core, monstrous power, stitched for <span class="text-white border-b-2 border-emerald-500">FrankenPHP</span>.
    </p>

    <div class="flex flex-col md:flex-row justify-center gap-4 mb-20">
        <code class="bg-gray-900 text-emerald-300 px-6 py-4 rounded-lg border border-gray-700 font-mono electric-glow">
            composer create-project stein/stein monstrous-app
        </code>
    </div>

    <div class="grid md:grid-cols-3 gap-8 text-left">
        <div class="p-8 bg-gray-900/50 border border-gray-800 rounded-2xl">
            <h3 class="text-emerald-400 font-bold mb-2">⚡ Worker Ready</h3>
            <p class="text-gray-500 text-sm">Designed specifically for long-running processes and lightning fast responses.</p>
        </div>
        <div class="p-8 bg-gray-900/50 border border-gray-800 rounded-2xl">
            <h3 class="text-emerald-400 font-bold mb-2">🧩 PSR Obsessed</h3>
            <p class="text-gray-500 text-sm">No lock-in. Use any PSR-compliant library. Swap Twig for Plates in seconds.</p>
        </div>
        <div class="p-8 bg-gray-900/50 border border-gray-800 rounded-2xl">
            <h3 class="text-emerald-400 font-bold mb-2">💉 Auto-Wired</h3>
            <p class="text-gray-500 text-sm">Smart dependency injection with Inflectors keeps your controllers clean and lean.</p>
        </div>
    </div>
</div>
