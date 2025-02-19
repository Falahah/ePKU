<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $primaryKey = 'announcement_id'; // Specify the primary key column

    protected $fillable = ['user_id', 'title', 'content', 'visible'];

    /**
     * Get the user associated with the announcement.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'userID');
    }
}
