<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BlogController extends Controller
{
    
    /**
     * Displays the main blog page.
     *
     * @param  \Illuminate\Http\Request $request
     * @return App\Models\Post
     */
    public function index(Request $request)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('TESTING');
        $log->addNotice('showing $request varible: '.$request);
        
        $posts = Post::when($request->search, function($query) use($request) {
                        $search = $request->search;

                        return $query->where('title', 'like', "%$search%")
                            ->orWhere('body', 'like', "%$search%");
                    })->with('user')
                    ->withCount('comments')
                    ->simplePaginate(5);
        return view('frontend.index', compact('posts'));
    }

    /**
     * Displaying a specific blog.
     *
     * @param  App\Models\Post $post
     * @return App\Models\Post
     */
    public function post(Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('TESTING');
        $log->addNotice('showing $post varible: '.$post);
        
        $post = $post->load(['comments.user', 'user']);

        return view('frontend.post', compact('post'));
    }

    /**
     * Creates the comment.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\Models\Post $post
     * @return App\Models\Post
     */
    public function comment(Request $request, Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('comment');
        $log->addNotice('showing $post varible: '.$post);
        
        $this->validate($request, ['body' => 'required']);

        $post->comments()->create([
            'body' => $request->body
        ]);
        flash()->overlay('Comment successfully created');

        return redirect("/posts/{$post->id}");
    }
}
