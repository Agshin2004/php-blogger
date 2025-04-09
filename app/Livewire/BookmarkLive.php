<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bookmark;

class BookmarkLive extends Component
{
    public $count;
    public $post; // Will be passed as argument from single-post
    public $added;

    public function mount($post)
    {
        $this->post = $post;
        $this->added = auth()->user()->myBookmarks->contains($post->id);
    }

    public function addOrRemove()
    {
        $userId = auth()->user()->id;
        $postId = $this->post->id;

        if (auth()->user()->myBookmarks->contains($postId)) {
            // operator can be omitted, every consecutive where clause will be 'and'
            Bookmark::where('user_id', $userId)->where('post_id', $postId)->delete();
            $this->added = false;
            return;
        }
        Bookmark::create([
            'user_id' => $userId,
            'post_id' => $postId
        ]);

        $this->added = true;
    }

    public function render()
    {
        return view('livewire.bookmarkLive', [
            'added' => $this->added
        ]);
    }
}
