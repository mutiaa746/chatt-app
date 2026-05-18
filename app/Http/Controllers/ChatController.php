<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────
    public function index()
    {
        $users  = User::where('id', '!=', Auth::id())->get();
        $groups = Auth::user()->groups()->with('members')->get();

        return view('chat.index', compact('users', 'groups'));
    }

    // ── Get private messages between two users ─────────────
    public function getMessages(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())
              ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', Auth::id());
        })
        ->with('sender')
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark as read
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json($messages->map(fn($m) => [
            'id'          => $m->id,
            'message'     => $m->message,
            'sender_id'   => $m->sender_id,
            'sender_name' => $m->sender->name,
            'is_mine'     => $m->sender_id === Auth::id(),
            'created_at'  => $m->created_at->format('H:i'),
            'date'        => $m->created_at->format('d M Y'),
        ]));
    }

    // ── Send private message ───────────────────────────────
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000', 'receiver_id' => 'required|exists:users,id']);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        $message->load('sender');
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'          => $message->id,
            'message'     => $message->message,
            'sender_id'   => $message->sender_id,
            'sender_name' => $message->sender->name,
            'is_mine'     => true,
            'created_at'  => $message->created_at->format('H:i'),
            'date'        => $message->created_at->format('d M Y'),
        ]);
    }

    // ── Get group messages ─────────────────────────────────
    public function getGroupMessages(Group $group)
    {
        // Pastikan user adalah member group
        if (!$group->members->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::where('group_id', $group->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages->map(fn($m) => [
            'id'          => $m->id,
            'message'     => $m->message,
            'sender_id'   => $m->sender_id,
            'sender_name' => $m->sender->name,
            'is_mine'     => $m->sender_id === Auth::id(),
            'created_at'  => $m->created_at->format('H:i'),
            'date'        => $m->created_at->format('d M Y'),
        ]));
    }

    // ── Send group message ─────────────────────────────────
    public function sendGroupMessage(Request $request, Group $group)
    {
        if (!$group->members->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate(['message' => 'required|string|max:1000']);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'group_id'  => $group->id,
            'message'   => $request->message,
        ]);

        $message->load('sender');
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'          => $message->id,
            'message'     => $message->message,
            'sender_id'   => $message->sender_id,
            'sender_name' => $message->sender->name,
            'is_mine'     => true,
            'created_at'  => $message->created_at->format('H:i'),
            'date'        => $message->created_at->format('d M Y'),
        ]);
    }

    // ── Create group ───────────────────────────────────────
    public function createGroup(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name'       => $request->name,
            'created_by' => Auth::id(),
        ]);

        // Tambahkan creator + members
        $memberIds = array_unique(array_merge($request->members, [Auth::id()]));
        $group->members()->attach($memberIds);

        return response()->json([
            'id'      => $group->id,
            'name'    => $group->name,
            'members' => $group->members->map(fn($m) => ['id' => $m->id, 'name' => $m->name]),
        ]);
    }

    // ── Update online status ───────────────────────────────
    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'is_online'    => $request->is_online,
            'last_seen_at' => $request->is_online ? null : now(),
        ]);

        broadcast(new \App\Events\UserStatusChanged(
            $user->id,
            $request->is_online,
            $user->last_seen_at?->diffForHumans() ?? ''
        ));

        return response()->json(['ok' => true]);
    }

    // ── Get all users with online status ───────────────────
    public function getUsers()
    {
        $users = User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'is_online', 'last_seen_at')
            ->get()
            ->map(fn($u) => [
                'id'        => $u->id,
                'name'      => $u->name,
                'is_online' => $u->is_online,
                'last_seen' => $u->last_seen_at?->diffForHumans() ?? 'Never',
                'unread'    => Message::where('sender_id', $u->id)
                                      ->where('receiver_id', Auth::id())
                                      ->where('is_read', false)
                                      ->count(),
            ]);

        return response()->json($users);
    }
}
