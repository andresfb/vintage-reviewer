<nav class="flex items-center justify-between lg:w-[92%] w-[94%] mx-auto py-5">

    <!-- Logo -->
    <div class="flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-slate-50 font-semibold lg:text-2xl">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>

    <div id="list-menu" class="md:static absolute bg-primary md:min-h-fit min-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5 md:pb-0 pb-3 md:pt-0 pt-3">
        <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-6">
            <li>
                <a class="{{ request()->routeIs('movies') ? 'text-slate-50' : 'text-slate-400' }}
                    hover:text-slate-500
                    duration-300" href="{{ route('movies') }}">Movies</a>
            </li>
            <li>
                <a class="{{ request()->routeIs('dashboard') ? 'text-slate-50' : 'text-slate-400' }}
                    hover:text-slate-500
                    duration-300" href="#">Posts</a>
            </li>
        </ul>
    </div>

    <div class="text-slate-50 flex items-center gap-6">

{{-- TODO: add the search form  --}}

{{--        <form action="" method="post">--}}
{{--            @csrf--}}
{{--            <input type="text" name="search" id="search" placeholder="Search" class="text-slate-50 border border-slate-50 rounded-md px-3 py-1">--}}

{{--        </form>--}}

{{-- TODO: change the user name to a initials bardge --}}
{{-- TODO: add a pulldown menu to add the user name, the profile and logout links  --}}

        <a href="{{ route('profile.edit') }}">
            {{ Auth::user()->name }}
        </a>

        <div class="md:hidden">
            <a id="open-menu" href="#" onclick="menu(true)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </a>

            <a id="close-menu" href="#" onclick="menu(false)" class="hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

    </div>

</nav>

@push('scripts')
    <script>
        function menu(open) {
            document.getElementById('open-menu').classList.toggle('hidden');
            document.getElementById('close-menu').classList.toggle('hidden');
            let list = document.getElementById('list-menu');
            if (open) {
                list.classList.remove('top-[-100%]');
                list.classList.add('top-[6%]');
            } else {
                list.classList.remove('top-[6%]');
                list.classList.add('top-[-100%]');
            }
        }
    </script>
@endpush
