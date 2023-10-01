<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function viewInboxMessage(Request $request)
    {
        if ($request->has('add')) {
            $recipients = $request->input('recipients');
            $subject = $request->input('subject');
            $content = $request->input('content');
            foreach ($recipients as $recipientId) {
                $token = $this->generateRandomToken($subject . $recipientId);
                try {
                    $message = new Message([
                        'sender_id' => auth()->id(),
                        'recipient_id' => $recipientId,
                        'subject' => $subject,
                        'content' => $content,
                        'token' => $token
                    ]);
                    $message->save();
                } catch (\Throwable $th) {
                    return back()->with(['error' => "Failed to send message!"]);
                }
            }
            return back()->with(['success' => 'Message sent!']);
        }
        if ($request->has('delete')) {
            $messages = $request->input('checked-message');
            foreach ($messages as $message_token) {
                try {
                    Message::where('token', $message_token)->delete();
                } catch (\Throwable $th) {
                    return back()->with(['error' => "Failed to delete messages!"]);
                }
            }
            return back()->with(['success' => 'Success Delete Messages!']);
        }
        if ($request->has('star')) {
            $messages = $request->input('checked-message');
            foreach ($messages as $message_token) {
                try {
                    Message::where('token', $message_token)->update([
                        'is_starred' => 1
                    ]);
                } catch (\Throwable $th) {
                    return back()->with(['error' => "Failed to star messages!"]);
                }
            }
            return back()->with(['success' => 'Messages Starred!']);
        }

        $user = auth()->user();
        $sentMessagesCount = $user->sentMessages()->count();
        $receivedMessages =  $user->receivedMessages()->latest()->with('sender')->paginate(10);
        $receivedMessagesCount = $user->receivedMessages()->count();
        $starredMessagesCount = Message::where('is_starred', 1)->where('recipient_id', $user->id)->count();

        $pageHeader = 'Your Inbox Messages';
        $users = User::all();

        foreach ($receivedMessages as $message) {
            $spoiler = $message->content;
            if (strlen($spoiler) > 50) {
                $spoiler = substr($spoiler, 0, 50);
                $spoiler .= "...";
            }
            $message->spoiler = $spoiler;
        }
        return view('umum.messages.message_inbox', compact('receivedMessages', 'sentMessagesCount', 'receivedMessagesCount', 'starredMessagesCount', 'pageHeader', 'users'));
    }

    public function viewSentMessage(Request $request)
    {
        $user = auth()->user();
        $sentMessages = $user->sentMessages()->latest()->with('recipient')->paginate(10);
        $sentMessagesCount = $user->sentMessages()->count();
        $receivedMessagesCount = $user->receivedMessages()->count();
        $starredMessagesCount = Message::where('is_starred', 1)->where('recipient_id', $user->id)->count();

        $pageHeader = 'Your Sent Messages';

        foreach ($sentMessages as $message) {
            $spoiler = $message->content;
            if (strlen($spoiler) > 50) {
                $spoiler = substr($spoiler, 0, 50);
                $spoiler .= "...";
            }
            $message->spoiler = $spoiler;
        }
        return view('umum.messages.message_sent', compact('sentMessages', 'sentMessagesCount', 'receivedMessagesCount', 'starredMessagesCount', 'pageHeader'));
    }

    public function viewDetailMessage(Message $message, Request $request, $token)
    {
        $message = Message::with('sender', 'recipient')->where('token', $token)->first();
        if (!$message) {
            return redirect('/error/404');
        }

        if ($request->has('reply')) {
            $replyId = $message->sender_id;
            $subject = $request->input('subject');
            $messageContent = $request->input('content');
            $token = $this->generateRandomToken($subject . $token . $replyId);
            try {
                Message::create([
                    'sender_id' => auth()->user()->id,
                    'recipient_id' => $replyId,
                    'subject' => $subject,
                    'content' => $messageContent,
                    'token' => $token,
                ]);
                return back()->with(['success' => "Success Reply Message!"]);
            } catch (\Throwable $th) {
                return back()->with(['error' => "Failed to send message!"]);
            }
        }
        if ($request->has('forward')) {
            $forwardTo = $request->input('forward_to');
            $subject = $request->input('subject');
            $messageContent = $message->content;

            foreach ($forwardTo as $forward) {
                $token = $this->generateRandomToken($subject . $token . $forward);
                try {
                    Message::create([
                        'sender_id' => auth()->user()->id,
                        'recipient_id' => $forward,
                        'subject' => $subject,
                        'content' => $messageContent,
                        'token' => $token,
                    ]);
                    return back()->with(['success' => "Success Forward Message!"]);
                } catch (\Throwable $th) {
                    return back()->with(['error' => "Failed to forward message!"]);
                }
            }
        }
        if ($request->has('delete')) {
            try {
                Message::where('token', $token)->delete();
                return redirect('/message')->with(['success' => "Success Delete Message!"]);
            } catch (\Throwable $th) {
                return back()->with(['error' => "Failed Delete Message!"]);
            }
        }
        $user = auth()->user();
        $sentMessagesCount = $user->sentMessages()->count();
        $receivedMessagesCount = $user->receivedMessages()->count();
        $starredMessagesCount = Message::where('is_starred', 1)->where('recipient_id', $user->id)->count();

        $pageHeader = 'Detail Messages';
        $users = User::all();
        $sender_profile = $this->encodeImage($this->profile_path . '/' . $message->sender->profile_img);
        $message->sender_profile = $sender_profile;

        if ($message->recipient_id == auth()->user()->id) {
            try {
                Message::whereId($message->id)->update([
                    'is_read' => 1
                ]);
                auth()->user()->unreadNotifications->where('notifiable_id', auth()->user()->id)->markAsRead();
            } catch (\Throwable $th) {
                return redirect('/error/500');
            }
        }

        return view('umum.messages.message_detail', compact('message', 'sentMessagesCount', 'receivedMessagesCount', 'starredMessagesCount', 'users', 'pageHeader'));
    }

    public function viewStarredMessage(Request $request)
    {
        if ($request->has('delete')) {
            $messages = $request->input('checked-message');
            foreach ($messages as $message_token) {
                try {
                    Message::where('token', $message_token)->delete();
                } catch (\Throwable $th) {
                    return back()->with(['error' => "Failed to delete messages!"]);
                }
            }
            return back()->with(['success' => 'Success Delete Messages!']);
        }
        if ($request->has('star')) {
            $messages = $request->input('checked-message');
            foreach ($messages as $message_token) {
                try {
                    Message::where('token', $message_token)->update([
                        'is_starred' => 0
                    ]);
                } catch (\Throwable $th) {
                    return back()->with(['error' => "Failed to remove star!"]);
                }
            }
            return back()->with(['success' => 'Messages Unstarred!']);
        }

        $user = auth()->user();
        $sentMessagesCount = $user->sentMessages()->count();
        $receivedMessagesCount = $user->receivedMessages()->count();
        $starredMessages =  $user->receivedMessages()->where('is_starred', 1)->latest()->with('sender')->paginate(10);
        $starredMessagesCount = Message::where('is_starred', 1)->where('recipient_id', $user->id)->count();

        $pageHeader = 'Your Starred Messages';

        foreach ($starredMessages as $message) {
            $spoiler = $message->content;
            if (strlen($spoiler) > 50) {
                $spoiler = substr($spoiler, 0, 50);
                $spoiler .= "...";
            }
            $message->spoiler = $spoiler;
        }
        return view('umum.messages.message_starred', compact('starredMessages', 'sentMessagesCount', 'receivedMessagesCount', 'starredMessagesCount', 'pageHeader'));
    }
}
