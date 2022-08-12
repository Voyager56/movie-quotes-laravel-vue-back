<?php

namespace Tests\Feature;

use App\Models\Comments;
use App\Models\Likes;
use App\Models\Movie;
use App\Models\MovieGenre;
use App\Models\Notification;
use App\Models\Quote;
use Tests\TestCase;

class RelationTest extends TestCase
{
	public function test_genre_movie_relationship_test()
	{
		$genre = Movie::find(1)->genres->first();
		$movie = $genre->movies->random();
		$this->assertTrue($genre->movies->contains($movie));
	}

	public function test_likes_users_relationship_test()
	{
		$like = Likes::inRandomOrder()->first();
		$user = $like->user;
		$this->assertTrue($user->likes->contains($like));
	}

	public function test_moviegenre_movie_relationship_test()
	{
		$movieGenre = MovieGenre::inRandomOrder()->first();
		$movie = $movieGenre->movie;
		$genre = $movieGenre->genre;
		$this->assertTrue($genre->movies->contains($movie));
	}

	public function test_moviegenre_genre_relationship_test()
	{
		$movieGenre = MovieGenre::inRandomOrder()->first();
		$genre = $movieGenre->genre;
		$movie = $movieGenre->movie;
		$this->assertTrue($movie->genres->contains($genre));
	}

	public function test_notification_users_relation_test()
	{
		$notification = Notification::inRandomOrder()->first();
		$user_from = $notification->from_user;
		$to_user = $notification->to_user;

		$this->assertTrue($user_from->notificationsTo->contains($notification) && $to_user->notifications->contains($notification));
	}

	public function test_user_comment_relationship_test()
	{
		$user = Comments::inRandomOrder()->first()->user;
		$comment = $user->comments->random();
		$this->assertTrue($user->comments->contains($comment));
	}

	public function test_user_quotes_relationship_test()
	{
		$user = Quote::inRandomOrder()->first()->user;
		$quote = $user->quotes->random();
		$this->assertTrue($user->quotes->contains($quote));
	}
}
