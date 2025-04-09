<x-layout>
    <div class="container py-md-5 container--narrow">
        @unless ($posts->isEmpty())
            <h1 class="display-5 text-center">Latest From Those You Follow</h1>
            @foreach ($posts as $post)
                <a wire:navigate href="/post/{{ $post->slug }}" class="list-group-item list-group-item-action">
                    <img class="avatar-tiny" src="{{ $post->getUser->avatar }}" />
                    <strong>{{ $post->title }}</strong>
                    <small>
                        By
                        {{ $post->getUser->username }}
                        on {{ $post->created_at->format('j/n/Y H:i:s') }}
                    </small>
                </a>
            @endforeach

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center">
                <h2>Hello <strong>{{ auth()->user()->username }}</strong>, your feed is empty.</h2>
                <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t
                    have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top
                    menu bar to find content written by people with similar interests and then follow them.</p>
            </div>
        @endunless
    </div>
</x-layout>
