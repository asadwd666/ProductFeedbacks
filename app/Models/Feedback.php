<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    protected $fillable = ['title', 'description', 'category', 'user_id','parent_id','vote_count'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    // for specific user
    public function vote()
    {
        return $this->hasOne(Voting::class);
    }
    // for all users
    public function votes()
    {
        return $this->hasMany(Voting::class)->where('vote_count',1);
    }


}
