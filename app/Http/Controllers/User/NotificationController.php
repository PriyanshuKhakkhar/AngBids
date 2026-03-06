<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // List all notifications
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(15);

        return view('website.user.notifications', compact('notifications'));
    }

    // Mark notification as read
    public function markAsRead(Request $request, $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => auth()->user()->unreadNotifications->count()
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    // Mark all notifications as read
    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    // Clear all notifications
    public function clearAll(Request $request)
    {
        auth()->user()->notifications()->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'All notifications cleared.']);
        }

        return redirect()->back()->with('success', 'All notifications cleared.');
    }

    // Delete notification
    public function destroy(Request $request, $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notification deleted.']);
        }

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
