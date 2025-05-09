<x-profile-layout :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }}'s Following">
    <div class="list-group">
        @foreach ($following as $following)
            <a href="/profile/{{ $following->userBeingFollowed->username }}"
                class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $following->userBeingFollowed->avatar }}" />
                {{ $following->userBeingFollowed->username }}
            </a>
        @endforeach
    </div>
</x-profile-layout>
