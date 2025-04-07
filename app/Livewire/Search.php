<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class Search extends Component
{
    // $searchTerm will be populated by livewire - wire:model.live="searchTerm"
    public $searchTerm = '';
    public $results;
        
    public function render()
    {
        if ($this->searchTerm === '') {
            $this->results = [];
        } else {
            $posts = Post::search($this->searchTerm)->get();
            $this->results = $posts;
        }

        return view('livewire.search');
    }
}
