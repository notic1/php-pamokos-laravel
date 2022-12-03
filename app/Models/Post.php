<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    const A_USER_ID = 'user_id';
    const A_TITLE = 'title';
    const A_BODY = 'body';

    protected $fillable = [
        self::A_TITLE,
        self::A_BODY
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setUser(int $userId)
    {
        $this->{self::A_USER_ID} = $userId;
    }
}
