<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 1
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 1
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 1
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 2
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 2
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 2
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 2
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 3
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 3
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 3
        ]);
        Post::create([
            'title' => fake()->title(15),
            'content' => fake()->text(30),
            'image' => fake()->text(10),
            'status' => 1,
            'user_id' => 3
        ]);
    }
}
