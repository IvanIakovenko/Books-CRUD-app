<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'books';
    protected $fillable = ['name', 'author', 'year', 'description', 'user_id', 'image', 'created_at', 'updated_at', 'categories_id'];

    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function categories() {
    	return $this->belongsTo(Categories::class);
    }
}
