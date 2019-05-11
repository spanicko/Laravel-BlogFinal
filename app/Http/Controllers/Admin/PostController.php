<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['user', 'comments'])->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('create');
        $log->addNotice('PostController::create');
        
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('store');
        $log->addNotice('PostController::store');
        
        $post = Post::create([
            'title'       => $request->title,
            'body'        => $request->body
        ]);

        flash()->overlay('Post created successfully.');

        return redirect('/admin/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('show');
        $log->addNotice('PostController::show');
        
        $post = $post->load(['user', 'comments']);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('edit');
        $log->addNotice('PostController::edit');
        
        if($post->user_id != auth()->user()->id && auth()->user()->is_admin == false) {
            flash()->overlay("You can't edit other peoples post.");
            return redirect('/admin/posts');
        }

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('update');
        $log->addNotice('PostController::update');
        
        $post->update([
            'title'       => $request->title,
            'body'        => $request->body
        ]);

        flash()->overlay('Post updated successfully.');

        return redirect('/admin/posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('destory');
        $log->addNotice('PostController::destroy');
        
        if($post->user_id != auth()->user()->id && auth()->user()->is_admin == false) {
            flash()->overlay("You can't delete other peoples post.");
            return redirect('/admin/posts');
        }

        $post->delete();
        flash()->overlay('Post deleted successfully.');

        return redirect('/admin/posts');
    }

    /**
     * Publish the specified resource from storage.
     *
     * @param  App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function publish(Post $post)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('publish');
        $log->addNotice('PostController::publish');
        
        $post->is_published = !$post->is_published;
        $post->save();
        flash()->overlay('Post changed successfully.');

        return redirect('/admin/posts');
    }
}
