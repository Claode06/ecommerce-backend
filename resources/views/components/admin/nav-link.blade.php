@props(['href' => '#', 'active' => false, 'icon' => null])

<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
          {{ $active
              ? 'bg-sky-700/40 text-white shadow-sm border-l-2 border-sky-400'
              : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
    @if($icon)
        <svg class="w-5 h-5 flex-shrink-0 {{ $active ? 'text-sky-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
        </svg>
    @endif
    <span>{{ $slot }}</span>
</a>
