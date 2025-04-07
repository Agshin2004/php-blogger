<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


/**
 * Return true if current use has permissions to this private channel, return false otherwise.
 */
Broadcast::channel('chatchannel', function() {
    return auth()->check(); // Only allow authenticated users
});
