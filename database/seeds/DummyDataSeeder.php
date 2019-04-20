<?php

use App\User;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 9)->create();
        factory(Post::class, 25)->create();
        factory(Comment::class, 40)->create();
    }
}
