<div>
    {{-- <form action="{{ route('addBookmark', ['postId' => $post->id]) }}" method="post">
        @csrf
        <button class="delete-post-button {{ App\inBookmark(post: $post, user: auth()->user()) }}" data-toggle="tooltip"
            data-placement="top" title="Add Bookmark">
            <i class="fa-solid fa-bookmark"></i>
        </button>
    </form> --}}

    <button wire:click="addOrRemove" wire:model="added" class="delete-post-button {{ App\inBookmark($added) }}" data-toggle="tooltip"
        data-placement="top" title="Add Bookmark">
        <i class="fa-solid fa-bookmark"></i>
    </button>
</div>