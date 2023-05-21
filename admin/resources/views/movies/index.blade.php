<x-app-layout>

    <section class="flex items-center justify-between pt-3 pb-6">
        <div class="md:text-2xl text-lx font-semibold">
            Movies
        </div>

        <div>
            <a href="{{ route('movies.create') }}"
               class="bg-warning text-slate-50 font-semibold md:text-base text-sm py-2 px-3 rounded-md focus:outline-none hover:bg-amber-600 border-0">New Movie</a>
        </div>

    </section>

    <section class="flex-column w-full bg-white pt-6 border border-gray-300 shadow-sm rounded-xl">

        <table class="w-full md:table-fixed table-auto border rounded-md mt-4">
            <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Poster</th>
                <th class="border px-4 py-2">Title</th>
                <th class="border px-4 py-2 lg:table-cell hidden">Rated</th>
                <th class="border px-4 py-2 lg:table-cell hidden">Rating</th>
                <th class="border px-4 py-2 lg:table-cell hidden">Runtime</th>
                <th class="border px-4 py-2 md:table-cell hidden">Released</th>
                <th class="border px-4 py-2">Complete</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($movies as $movie)
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2">
                        <a class="block" href="{{ route('movies.edit', $movie['id']) }}">
                            <img src="{{ $movie['image'] }}" alt="{{ $movie['title'] }} Poster" class="max-h-16">
                        </a>
                    </td>
                    <td class="border px-4 py-2">
                        <a class="block" href="{{ route('movies.show', $movie['id']) }}">
                            {{ $movie['title'] }}
                        </a>
                    </td>
                    <td class="border px-4 py-2 lg:table-cell hidden">
                        <a class="block" href="{{ route('movies.show', $movie['id']) }}">
                            {{ $movie['rated'] }}
                        </a>
                    </td>
                    <td class="border px-4 py-2 lg:table-cell hidden">
                        <a class="block" href="{{ route('movies.show', $movie['id']) }}">
                            {{ $movie['rating'] }}
                        </a>
                    </td>
                    <td class="border px-4 py-2 lg:table-cell hidden">
                        <a class="block" href="{{ route('movies.show', $movie['id']) }}">
                            {{ $movie['runtime'] }}
                        </a>
                    </td>
                    <td class="border px-4 py-2 md:table-cell hidden">
                        <a class="block" href="{{ route('movies.show', $movie['id']) }}">
                            {{ $movie['release_date'] }}
                        </a>
                    </td>
                    <td class="border px-4 py-2">
                        <a class="block" href="{{ route('movies.show', $movie['id']) }}">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="{{ $movie['is_complete'] }}" class="sr-only peer" readonly>
                                <div class="
                                    w-11
                                    h-6
                                    bg-gray-200
                                    peer-focus:outline-none
                                    peer-focus:ring-4
                                    peer-focus:ring-blue-300
                                    rounded-full
                                    peer
                                    peer-checked:after:translate-x-full
                                    peer-checked:after:border-white
                                    after:content-['']
                                    after:absolute
                                    after:top-[2px]
                                    after:left-[2px]
                                    after:bg-white
                                    after:border-gray-300
                                    after:border
                                    after:rounded-full
                                    after:h-5
                                    after:w-5
                                    after:transition-all
                                    peer-checked:bg-blue-600"></div>
                            </label>
                        </a>
                    </td>
                    <td class="border sm:px-4 px-1 py-2">

                        <div class="flex flex-col sm:flex-row mx-1 sm:space-x-1 space-x-0 text-center">

                            <div class="flex flex-row gap-1">
                                <a href="{{ route('movies.show', $movie['id']) }}" class="text-info" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                        <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>

                                <a href="{{ route('movies.edit', $movie['id']) }}" class="text-success" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                            </div>

                            <div class="flex flex-row gap-1 sm:mt-0 mt-2">
                                <a href="{{ config('imdb.movie_url') . $movie['imdb_id'] }}" class="text-amber-500" title="IMDB" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" class="w-5 h-5">
                                        <path d="M89.5 323.6H53.93V186.2H89.5V323.6zM156.1 250.5L165.2 186.2H211.5V323.6H180.5V230.9L167.1 323.6H145.8L132.8 232.9L132.7 323.6H101.5V186.2H147.6C148.1 194.5 150.4 204.3 151.9 215.6L156.1 250.5zM223.7 323.6V186.2H250.3C267.3 186.2 277.3 187.1 283.3 188.6C289.4 190.3 294 192.8 297.2 196.5C300.3 199.8 302.3 203.1 303 208.5C303.9 212.9 304.4 221.6 304.4 234.7V282.9C304.4 295.2 303.7 303.4 302.5 307.6C301.4 311.7 299.4 315 296.5 317.3C293.7 319.7 290.1 321.4 285.8 322.3C281.6 323.1 275.2 323.6 266.7 323.6H223.7zM259.2 209.7V299.1C264.3 299.1 267.5 298.1 268.6 296.8C269.7 294.8 270.4 289.2 270.4 280.1V226.8C270.4 220.6 270.3 216.6 269.7 214.8C269.4 213 268.5 211.8 267.1 210.1C265.7 210.1 263 209.7 259.2 209.7V209.7zM316.5 323.6V186.2H350.6V230.1C353.5 227.7 356.7 225.2 360.1 223.5C363.7 222 368.9 221.1 372.9 221.1C377.7 221.1 381.8 221.9 385.2 223.3C388.6 224.8 391.2 226.8 393.2 229.5C394.9 232.1 395.9 234.8 396.3 237.3C396.7 239.9 396.1 245.3 396.1 253.5V292.1C396.1 300.3 396.3 306.4 395.3 310.5C394.2 314.5 391.5 318.1 387.5 320.1C383.4 324 378.6 325.4 372.9 325.4C368.9 325.4 363.7 324.5 360.2 322.9C356.7 321.1 353.5 318.4 350.6 314.9L348.5 323.6L316.5 323.6zM361.6 302.9C362.3 301.1 362.6 296.9 362.6 290.4V255C362.6 249.4 362.3 245.5 361.5 243.8C360.8 241.9 357.8 241.1 355.7 241.1C353.7 241.1 352.3 241.9 351.6 243.4C351 244.9 350.6 248.8 350.6 255V291.4C350.6 297.5 351 301.4 351.8 303C352.4 304.7 353.9 305.5 355.9 305.5C358.1 305.5 360.1 304.7 361.6 302.9L361.6 302.9zM418.4 32.04C434.1 33.27 447.1 47.28 447.1 63.92V448.1C447.1 464.5 435.2 478.5 418.9 479.1C418.6 479.1 418.4 480 418.1 480H29.88C29.6 480 29.32 479.1 29.04 479.9C13.31 478.5 1.093 466.1 0 449.7L.0186 61.78C1.081 45.88 13.82 33.09 30.26 31.1H417.7C417.9 31.1 418.2 32.01 418.4 32.04L418.4 32.04zM30.27 41.26C19 42.01 10.02 51.01 9.257 62.4V449.7C9.63 455.1 11.91 460.2 15.7 464C19.48 467.9 24.51 470.3 29.89 470.7H418.1C429.6 469.7 438.7 459.1 438.7 448.1V63.91C438.7 58.17 436.6 52.65 432.7 48.45C428.8 44.24 423.4 41.67 417.7 41.26L30.27 41.26z"/>
                                    </svg>
                                </a>

                                <a href="{{ config('tmdb.movie_url') . $movie['tmdb_id'] }}" class="text-blue-900" title="TMDB" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor" class="w-5 h-5">
                                        <path d="M17.37 160.41L7 352h43.91l5.59-79.83L84.43 352h44.71l25.54-77.43 4.79 77.43H205l-12.79-191.59H146.7L106 277.74 63.67 160.41zm281 0h-47.9V352h47.9s95 .8 94.2-95.79c-.78-94.21-94.18-95.78-94.18-95.78zm-1.2 146.46V204.78s46 4.27 46.8 50.57-46.78 51.54-46.78 51.54zm238.29-74.24a56.16 56.16 0 0 0 8-38.31c-5.34-35.76-55.08-34.32-55.08-34.32h-51.9v191.58H482s87 4.79 87-63.85c0-43.14-33.52-55.08-33.52-55.08zm-51.9-31.94s13.57-1.59 16 9.59c1.43 6.66-4 12-4 12h-12v-21.57zm-.1 109.46l.1-24.92V267h.08s41.58-4.73 41.19 22.43c-.33 25.65-41.35 20.74-41.35 20.74z"/>
                                    </svg>
                                </a>

                                <a href="{{ config('emby.movie_url') . $movie['emby_id'] }}" class="text-green-700" title="Emby" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M1 4.75C1 3.784 1.784 3 2.75 3h14.5c.966 0 1.75.784 1.75 1.75v10.515a1.75 1.75 0 01-1.75 1.75h-1.5c-.078 0-.155-.005-.23-.015H4.48c-.075.01-.152.015-.23.015h-1.5A1.75 1.75 0 011 15.265V4.75zm16.5 7.385V11.01a.25.25 0 00-.25-.25h-1.5a.25.25 0 00-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 00.25-.25zm0 2.005a.25.25 0 00-.25-.25h-1.5a.25.25 0 00-.25.25v1.125c0 .108.069.2.165.235h1.585a.25.25 0 00.25-.25v-1.11zm-15 1.11v-1.11a.25.25 0 01.25-.25h1.5a.25.25 0 01.25.25v1.125a.25.25 0 01-.164.235H2.75a.25.25 0 01-.25-.25zm2-4.24v1.125a.25.25 0 01-.25.25h-1.5a.25.25 0 01-.25-.25V11.01a.25.25 0 01.25-.25h1.5a.25.25 0 01.25.25zm13-2.005V7.88a.25.25 0 00-.25-.25h-1.5a.25.25 0 00-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 00.25-.25zM4.25 7.63a.25.25 0 01.25.25v1.125a.25.25 0 01-.25.25h-1.5a.25.25 0 01-.25-.25V7.88a.25.25 0 01.25-.25h1.5zm0-3.13a.25.25 0 01.25.25v1.125a.25.25 0 01-.25.25h-1.5a.25.25 0 01-.25-.25V4.75a.25.25 0 01.25-.25h1.5zm11.5 1.625a.25.25 0 01-.25-.25V4.75a.25.25 0 01.25-.25h1.5a.25.25 0 01.25.25v1.125a.25.25 0 01-.25.25h-1.5zm-9 3.125a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-xl font-semibold">No movies found</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="m-3">
            {{ $movieList->links() }}
        </div>
    </section>

</x-app-layout>
