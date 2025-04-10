<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
        <div class="d-flex justify-content-between">
            <h2>{{ $post->title }}</h2>
            <div class="utility-buttons pt-2">
                @can('update', $post)
                    <a href="/post/{{ $post->slug }}/edit" class="text-primary" data-toggle="tooltip"
                        data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                    <form class="delete-post-form d-inline" action="/delete-post/{{ $post->slug }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top"
                            title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                @endcan
                <livewire:bookmark-live :post="$post" />
            </div>
        </div>

        <p class="text-muted small mb-4">
            <a href="#">
                <img class="avatar-tiny" src="{{ $post->getUser->avatar }}" />
            </a>
            Posted by <a href="/profile/{{ $post->getUser->username }}">{{ $post->getUser->username }}</a> on
            {{ $post->created_at->format('j/n/Y') }}
        </p>

        <div class="body-content">
            {{-- {!! NOT ESCAPED DATA !!} --}}
            {!! $post->body !!}
        </div>

        <br><br>
        <h5 class="text-right">
            <b>Views:</b>
            <i>{{ $viewCount }}</i>
        </h5>
    </div>

</x-layout>