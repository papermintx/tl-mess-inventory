@props(['user'])

<div class="flex items-center gap-3 bg-base-100/90 backdrop-blur-sm py-1.5 px-3.5 rounded-2xl border border-base-300 shadow-sm hover-lift">
    <div class="text-right hidden sm:block">
        <p class="font-bold text-xs leading-tight text-base-content">{{ $user->name }}</p>
        <p class="text-[10px] text-base-content/60">{{ $user->email }}</p>
    </div>
    @if($user->isAdmin())
        <div class="flex items-center gap-1.5 badge badge-primary badge-sm font-bold tracking-wider py-2.5 px-2.5 shadow-xs" title="Hak Akses: Administrator Pengelola (CRUD Penuh)">
            <x-icon name="o-shield-check" class="w-3.5 h-3.5 stroke-2" />
            <span>ADMIN PENGELOLA</span>
        </div>
    @else
        <div class="flex items-center gap-1.5 badge badge-neutral badge-sm font-semibold tracking-wider py-2.5 px-2.5 shadow-xs" title="Hak Akses: Auditor / Staff Peninjau (Read-Only)">
            <x-icon name="o-eye" class="w-3.5 h-3.5" />
            <span>AUDITOR (VIEWER)</span>
        </div>
    @endif
</div>