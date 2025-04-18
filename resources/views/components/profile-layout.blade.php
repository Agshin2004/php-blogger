<x-layout :doctitle="$doctitle">
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{ $sharedData['avatar'] }}" /> {{ $sharedData['username'] }}
            @auth
                @if (!$sharedData['isFollowing'])
                    <form class="ml-2 d-inline" action="/follow/{{ $sharedData['username'] }}" method="POST">
                        @csrf
                        @if (auth()->user()->username === $sharedData['username'])
                            <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>
                        @else
                            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                        @endif
                    </form>
                @else
                    <form class="ml-2 d-inline" action="/unfollow/{{ $sharedData['username'] }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>

                        @if (auth()->user()->username === $sharedData['username'])
                            <a wire:navigate href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>
                        @endif
                    </form>
                @endif
            @endauth
        </h2>
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a wire:navigate href="/profile/{{ $sharedData['username'] }}" class="profile-nav-link nav-item nav-link {{ Request::segment(3) == '' ? 'active' : '' }}">Posts: {{ $sharedData['numberOfPosts'] }}</a>
            <a wire:navigate href="/profile/{{ $sharedData['username'] }}/followers" class="profile-nav-link nav-item nav-link {{ Request::segment(3) == 'followers' ? 'active' : '' }}">Followers: {{ $sharedData['followerCount'] }}</a>
            <a wire:navigate href="/profile/{{ $sharedData['username'] }}/following" class="profile-nav-link nav-item nav-link {{ Request::segment(3) == 'following' ? 'active' : '' }}">Following: {{ $sharedData['followingCount'] }}</a>
        </div>

        <div class="post-list">
            {{ $slot }}
        </div>
    </div>
</x-layout>
