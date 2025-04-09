<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
        <div class="d-flex justify-content-between">
            <h2>{{ $post->title }}</h2>
            <div class="utility-buttons pt-2">
                @can('update', $post)
                    <a href="/post/{{ $post->id }}/edit" class="text-primary" data-toggle="tooltip"
                        data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                    <form class="delete-post-form d-inline" action="/delete-post/{{ $post->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top"
                            title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                @endcan
                <form action="{{ route('addBookmark', ['postId' => $post->id]) }}" method="post">
                    @csrf
                    <button class="delete-post-button {{ App\inBookmark(post: $post, user: auth()->user()) }}" data-toggle="tooltip" data-placement="top" title="Add Bookmark">
                        <i class="fa-solid fa-bookmark"></i>
                    </button>
                </form>
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
    </div>

</x-layout>