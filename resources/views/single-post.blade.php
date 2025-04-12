<x-layout :doctitle="$post->title">
    <section class="container mt-5">
        <div class="page-container">
            <div class="page-content">
                <div class="card">
                    <div class="card-header pt-0">
                        <div class="d-flex justify-content-between">
                            <h2>{{ $post->uppercased_title }}</h2>
                            <div class="utility-buttons pt-2">
                                @can('update', $post)
                                    <a href="/post/{{ $post->slug }}/edit" class="text-primary" data-toggle="tooltip"
                                        data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form class="delete-post-form d-inline" action="/delete-post/{{ $post->slug }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="delete-post-button text-danger" data-toggle="tooltip"
                                            data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                                <livewire:bookmark-live :post="$post" />
                            </div>
                        </div>

                        <small class="small text-muted">
                            <span class="mr-2">By</span>
                            <a href="/profile/{{ $post->getUser->username }}" class="text-muted">
                                <img class="avatar-tiny" src="{{ $post->getUser->avatar }}" />
                                <b>
                                    {{ $post->getUser->username }}
                                </b>
                            </a>
                            <span class="px-2">·</span>
                            <span>{{ $post->created_at->format('j/n/Y') }}</span>
                        </small>
                    </div>
                    <div class="card-body border-top">
                        {{-- Escape malicious script tags, rendering only markdown --}}
                        {!! \Illuminate\Support\Str::markdown($post->body) !!}
                    </div>
                    <br><br>
                    <h5 class="text-right mr-3">
                        <b>Views:</b>
                        <i>{{ $viewCount }}</i>
                    </h5>
                </div>

                <h6 class="mt-5 text-center h3">Related Posts</h6>
                <hr>

                <div class="card-wrapper">
                    @forelse ($relatedPosts as $post)
                        <div class="card card-sleek">
                            <a href="{{ route('post', ['post' => $post->slug]) }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $post->uppercased_title }}</h5>
                                    <p class="card-text">
                                        {{-- Limit to 40 chars --}}
                                        {{ \Illuminate\Support\Str::limit($post->body, 40) }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @empty
                        No posts related posts.
                    @endforelse
                </div>

            </div>
        </div>
    </section>
</x-layout>