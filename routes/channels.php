<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// $user is the currently authenticated user
// $userId is the {id} from the channel name 'notifications.{id}'
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    // User can only listen to their own notification channel
    return (int) $user->id === (int) $userId;
});