<x-profile-layout :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }}'s Profile">
    <div class="list-group">
        @foreach ($sharedData['posts'] as $post)
            <a wire:navigate href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $post->getUser->avatar }}" />
                <strong>{{ $post->title }}</strong> on <small>{{ $post->created_at->format('j/n/Y G:i:s') }}</small>
            </a>
        @endforeach

        <div class="mt-4">
            {{ $sharedData['posts']->links() }}
        </div>
    </div>
</x-profile-layout>
