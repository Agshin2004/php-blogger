<x-layout>
    <h1 class="text-center mt-5 text-info">Bookmarks</h1>
    <div class="list-group container py-md-5 container--narrow">
        @forelse ($posts as $post)
            <a wire:navigate href="/post/{{ $post->slug }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $post->getUser->avatar }}" />
                <strong>{{ $post->title }}</strong> on <small>{{ $post->created_at->format('j/n/Y G:i:s') }}</small>
            </a>
        @empty
            <h1 class="text-center">
                No Bookmarks. <a href="/">Add.</a>
            </h1>
        @endforelse

        
    </div>
</x-layout>