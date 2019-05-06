<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class PostController extends Controller
{
    public function index(Request $request)
    {
         $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('return post search');
        $log->addNotice('return post search: '.$request);
        
        return Post::when($request->title, function($query) use ($request) {
            return $query->where('title', 'like', "%{$request->title}%");
        })
        ->when($request->search, function($query) use ($request) {
            return $query->where('title', 'like', "%{$request->search}%")
                         ->orWhere('body', 'like', "%{$request->search}%");
        })
        ->when($request->order, function($query) use ($request) {
            if($request->order == 'oldest') {
                return $query->oldest();
            }
            return $query->latest();
        }, function($query) {
            return $query->latest();
        })
        ->when($request->status, function($query) use ($request) {
            if($query->status == 'published') {
                return $query->published();
            }
            return $query->drafted();
        })
        ->paginate($request->get('limit', 10));
    }

    public function show(Post $post)
    {
         $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('show post');
        $log->addNotice('show post: '.$request);
        
        $post = $post->load(['comments.user', 'user']);

        return $post;
    }
}
