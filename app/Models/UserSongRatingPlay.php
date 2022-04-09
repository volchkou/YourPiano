<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserSongRatingPlay extends Model
{
    use HasFactory;

    public function song(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Song::class, 'song_id');
    }

    public static function userRatingPoints(User $user)
    {
        $totalPoints = self::select(DB::raw('MAX(rating_points) as max_points'))
            ->where('user_id', $user->id)
            ->groupBy('song_id')
            ->get();
        $totalPointsResult = 0;
        foreach ($totalPoints as $totalPoint) {
            $totalPointsResult += $totalPoint['max_points'];
        }

        return $totalPointsResult;
    }

    public static function userPlaces(User $user)
    {
        $songsRating = DB::table('users')
            ->leftJoin('user_song_rating_plays', 'users.id', '=', 'user_song_rating_plays.user_id')
            ->select('users.id', 'song_id', DB::raw('MAX(rating_points) as max_points'))
            ->groupBy(['users.id', 'song_id']);

        return DB::table('users')
            ->joinSub($songsRating, 'Rating', 'users.id', '=', 'Rating.id')
            ->select('users.id', 'users.name', 'users.email', 'users.profile_photo', DB::raw('SUM(Rating.max_points) as points'))
            ->groupBy('users.id')
            ->orderBy('points', 'DESC');
    }
}
