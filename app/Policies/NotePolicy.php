<?php
use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    // Super-admin can do anything
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Note $note): bool { return $user->id === $note->user_id; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Note $note): bool { return $user->id === $note->user_id; }
    public function delete(User $user, Note $note): bool { return $user->id === $note->user_id; }
}