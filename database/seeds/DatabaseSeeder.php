<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(\App\User::class)->times($this->totalUsers)->create();

        $tags = factory(\App\Tag::class)->times($this->totalTags)->create();

        $users->random((int) $this->totalUsers * $this->userWithArticleRatio)
            ->each(function ($user) use ($faker, $tags) {
                $user->articles()
                    ->saveMany(
                        factory(\App\Article::class)
                        ->times($faker->numberBetween(1, $this->maxArticlesByUser))
                        ->make()
                    )
                    ->each(function ($article) use ($faker, $tags) {
                        $article->tags()->attach(
                            $tags->random($faker->numberBetween(1, min($this->maxArticleTags, $this->totalTags)))
                        );
                    })
                    ->each(function ($article) use ($faker) {
                        $article->comments()
                            ->saveMany(
                                factory(\App\Comment::class)
                                ->times($faker->numberBetween(1, $this->maxCommentsInArticle))
                                ->make()
                            );
                    });
            });

        $articles = \App\Article::all();

        $users->random((int) $users->count() * $this->usersWithFavoritesRatio)
            ->each(function ($user) use($faker, $articles) {
                $articles->random($faker->numberBetween(1, (int) $articles->count() * 0.5))
                    ->each(function ($article) use ($user) {
                        $user->favorite($article);
                    });
            });

        $users->random((int) $users->count() * $this->usersWithFollowingRatio)
            ->each(function ($user) use($faker, $users) {
                $users->except($user->id)
                    ->random($faker->numberBetween(1, (int) ($users->count() - 1) * 0.2))
                    ->each(function ($userToFollow) use ($user) {
                        $user->follow($userToFollow);
                    });
            });
    }
}
