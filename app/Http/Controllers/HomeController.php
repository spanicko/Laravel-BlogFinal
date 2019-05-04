<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('Home Page');
        $log->addNotice('Going to home page');
        
        $posts      = Post::count();
        $comments   = Comment::count();

        return view('home', get_defined_vars());
    }
}
