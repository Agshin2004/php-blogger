<x-profile-layout :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }}'s Followers">
    <div class="list-group">
        @foreach ($followers as $follower)
            <a href="/profile/{{ $follower->userDoingFollowing->username }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $follower->userDoingFollowing->avatar }}" />
                {{ $follower->userDoingFollowing->username }}
            </a>
        @endforeach
    </div>
</x-profile-layout>
