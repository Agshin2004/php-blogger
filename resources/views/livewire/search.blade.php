<div x-data="{ isOpen: false }">
    <button x-on:click="isOpen = !isOpen;" href="#"
        class="text-white mr-2 header-search-icon b-none bg-transparent border-0" title="Search" data-toggle="tooltip"
        data-placement="bottom">
        <i class="fas fa-search"></i>
    </button>

    <div class="search-overlay" x-bind:class="isOpen ? 'search-overlay--visible' : '';">
        <div class="search-overlay-top shadow-sm">
            <div class="container container--narrow">
                <label for="live-search-field" class="search-overlay-icon"><i class="fas fa-search"></i></label>
                <input x-on:keyup="document.querySelector('.circle-loader').classList.add('circle-loader--visible'); if (document.getElementById('no-results')) {document.getElementById('no-results').style.display = 'none'}"
                    wire:model.live.debounce.500ms="searchTerm" autocomplete="off" type="text" id="live-search-field"
                    class="live-search-field" placeholder="What are you interested in?">
                <span x-on:click="isOpen = !isOpen;" class="close-live-search"><i
                        class="fas fa-times-circle"></i></span>
            </div>
        </div>

        <div class="search-overlay-bottom">
            <div class="container container--narrow py-3">
                <div class="circle-loader"></div>
                <div class="live-search-results live-search-results--visible">
                    @if (count($results) == 0 && $searchTerm !== '')
                        <p id="no-results" class="alert alert-danger text-center shadow-sm">
                            Sorry. Nothing found for {{ $searchTerm }}
                        </p>
                    @endif
                    <div class="list-group shadow-sm">
                        <div class="list-group-item active"><strong>Search Results</strong>
                            ({{ count($results) }}) results found
                        </div>
                        @foreach ($results as $post)
                            <a x-on:click.prevent="isOpen = false; Livewire.navigate('/post/{{ $post->id }}')" href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
                                <img class="avatar-tiny" src="{{ $post->getUser->avatar }}">
                                <strong>{{ $post->title }}</strong>
                                <span class="text-muted small">
                                    by {{ $post->getUser->username }} on
                                    {{ $post->created_at->format('j/n/Y') }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
