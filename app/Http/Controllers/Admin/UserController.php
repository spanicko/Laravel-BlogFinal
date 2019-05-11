<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withCount('posts')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $log = new Logger('authentication logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
        error_log('destroy');
        $log->addNotice('this is destory');
        
        if(auth()->user() == $user) {
            flash()->overlay("You can't delete yourself.");

            return redirect('/admin/users');
        }

        $user->delete();
        flash()->overlay('User deleted successfully.');

        return redirect('/admin/users');
    }
}
