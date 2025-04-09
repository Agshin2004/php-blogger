<div>
    <button wire:click="addOrRemove" wire:model="added" class="delete-post-button {{ App\inBookmark($added) }}" data-toggle="tooltip"
        data-placement="top" title="Add Bookmark">
        <i class="fa-solid fa-bookmark"></i>
    </button>
</div>