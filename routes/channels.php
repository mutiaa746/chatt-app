<?php

use Illuminate\Support\Facades\Broadcast;

// Private chat channel — hanya 2 user yang terlibat bisa listen
Broadcast::channel('chat.{id1}.{id2}', function ($user, $id1, $id2) {
    return $user->id == $id1 || $user->id == $id2;
});

// Group channel — hanya member yang bisa listen
Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    return $user->groups()->where('groups.id', $groupId)->exists();
});

// User status channel — semua authenticated user bisa listen
Broadcast::channel('user-status', function ($user) {
    return (bool) $user;
});
