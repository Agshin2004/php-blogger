<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\ChatMessage;

class Chat extends Component
{
    public $message = '';
    public $chatLog = [];

    /**
     * Must be called exactly getListeners.
     * 'echo-private:chatchannel,ChatMessage' tells Livewire to listen for a ChatMessage 
     * event on a private channel named chatchannel.
     * When the event is received, the method notifyNewMessage will be triggered.
     */
    // 
    public function getListeners()
    {
        return [
            'echo-private:chatchannel,ChatMessage' => 'notifyNewMessage'
        ];
    }

    public function notifyNewMessage($x)
    {
        array_push($this->chatLog, $x['chat']);
    }

    public function send()
    {
        if (!auth()->check())
            abort(403, 'Unauthorized');

        if (trim(strip_tags($this->message)) == '') {
            return;
        }

        array_push($this->chatLog, [
            'selfmessage' => true,
            'username' => auth()->user()->username,
            'message' => strip_tags($this->message),
            'avatar' => auth()->user()->avatar
        ]);

        broadcast(new ChatMessage([
            'selfmessage' => false,
            'username' => auth()->user()->username,
            'message' => strip_tags($this->message),
            'avatar' => auth()->user()->avatar
        ]))->toOthers();

        $this->message = '';
    }


    public function render()
    {
        return view('livewire.chat');
    }
}
