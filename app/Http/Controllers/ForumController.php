<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use App\Models\Post;
use App\Models\Thread;
use App\Models\ThreadCategory;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function viewForumCategory(Request $request)
    {
        if ($request->has('add')) {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'category_icon' => 'required',
                'category_description' => 'nullable|string',
            ]);

            $existingCategory = ThreadCategory::where('category_name', $request->category_name)->first();
            if ($existingCategory) {
                return back()->with(['error' => 'Category Already Existed!']);
            }
            try {
                ThreadCategory::create($request->all());
                return back()->with(['success' => 'Success Add Category!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Add Category!']);
            }
        }
        if ($request->has('update')) {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'category_icon' => 'required',
                'category_description' => 'nullable|string',
            ]);
            $categoryId = $request->input('catid');
            try {
                ThreadCategory::whereId($categoryId)->update([
                    'category_name' => $request->category_name,
                    'category_icon' => $request->category_icon,
                    'category_description' => $request->category_description,
                ]);
                return back()->with(['success' => 'Success Update Category!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Category!']);
            }
        }
        if ($request->has('delete')) {
            $categoryId = $request->input('catid');
            try {
                ThreadCategory::whereId($categoryId)->delete();
                return back()->with(['success' => 'Success Delete Category!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Category!']);
            }
        }

        $threadCategories =  ThreadCategory::with(['latestThread', 'latestThread.user', 'latestPost', 'latestPost.user'])
            ->withCount(['threads', 'posts'])
            ->get();
        $pageHeader = 'Forum Categories';
        $icons = Icon::all();
        return view('umum.forum.category_list', compact('threadCategories', 'pageHeader', 'icons'));
    }

    public function viewThreadList(Request $request, $categoryName)
    {
        $category = ThreadCategory::where('category_name', $categoryName)->firstOrFail();
        $categoryId = $category->id;

        if ($request->has('add')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
            $existingThread = Thread::where('title', $request->input('title'))->first();
            if ($existingThread) {
                return back()->with(['error' => 'Title Already Exists!']);
            }
            try {
                Thread::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'author_id' => auth()->user()->id,
                    'thread_category_id' => $categoryId,
                ]);
                return back()->with(['success' => 'Success Create Thread!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Create Thread!']);
            }
        }
        if ($request->has('update')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
            $threadId = $request->input('thread_id');
            try {
                Thread::whereId($threadId)->update([
                    'title' => $request->title,
                    'content' => $request->content,
                ]);
                return back()->with(['success' => 'Success Update Thread!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Thread!']);
            }
        }
        if ($request->has('delete')) {
            $threadId = $request->input('thread_id');
            try {
                Thread::whereId($threadId)->delete();
                return back()->with(['success' => 'Success Delete Thread!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Thread!']);
            }
        }

        $threads = Thread::where('thread_category_id', $categoryId)
            ->with('user', 'posts.user')
            ->withCount('posts')
            ->latest()
            ->paginate(10);

        $pageHeader = 'Thread List';
        return view('umum.forum.thread_list', compact('threads', 'category', 'pageHeader'));
    }

    public function viewThreadDetail(Request $request, $categoryName, $threadTitle)
    {
        $category = ThreadCategory::where('category_name', $categoryName)->firstOrFail();
        $categoryId = $category->id;

        $thread = Thread::where('title', $threadTitle)->where('thread_category_id', $categoryId)->firstOrFail();
        $thread->user->preview_profile = $this->encodeImage($this->profile_path . '/' . $thread->user->profile_img);
        $thread->load('posts.user');

        if ($request->has('reply_post')) {
            try {
                Post::create([
                    'content' => $request->input('reply_content'),
                    'user_id' => auth()->user()->id,
                    'thread_id' => $thread->id,
                ]);
                return back()->with(['success' => 'Success Reply Thread!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Reply Thread!']);
            }
        }

        if ($request->has('update_thread')) {
            try {
                $thread->update([
                    'content' => $request->input('content'),
                ]);
                return back()->with(['success' => 'Success Update Thread!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Thread!']);
            }
        }
        if ($request->has('update_post')) {
            $post_id = $request->input('post_id');
            try {
                Post::whereId($post_id)->update([
                    'content' => $request->input('content'),
                ]);
                return back()->with(['success' => 'Success Update Post!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Post!']);
            }
        }
        if ($request->has('delete_post')) {
            $post_id = $request->input('post_id');
            try {
                Post::whereId($post_id)->delete();
                return back()->with(['success' => 'Success Delete Post!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Post!']);
            }
        }

        if (count($thread->posts) > 0) {
            foreach ($thread->posts as $post) {
                $encodedProfileImage = $this->encodeImage($this->profile_path . '/' . $post->user->profile_img);
                $post->user->preview_profile = $encodedProfileImage;
            }
        }

        $pageHeader = $thread->title;
        return view('umum.forum.thread_detail', compact('thread', 'pageHeader'));
    }
}
