<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bookmark;

class BookmarkLive extends Component
{
    public $count;
    public $post; // Will be passed as argument from single-post
    public $bookmarkAdded;

    public function mount($post)
    {
        $this->post = $post;
    }

    public function addOrRemove()
    {
        $userId = auth()->user()->id;
        $postId = $this->post->id;

        if (auth()->user()->myBookmarks->contains($postId)) {
            // operator can be omitted, every consecutive where clause will be 'and'
            Bookmark::where('user_id', $userId)->where('post_id', $postId)->delete();
            $this->bookmarkAdded = false;
            return;
        }
        Bookmark::create([
            'user_id' => $userId,
            'post_id' => $postId
        ]);
        $this->bookmarkAdded = true;
    }

    public function render()
    {
        return view('livewire.bookmarkLive', [
            'bookmarkdAdded' => $this->bookmarkAdded
        ]);
    }
}
