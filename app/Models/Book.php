<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'genre',
        'description',
        'status',
        'credit_price',
        'image_path',
        'user_id',
        'file',
        'author_id'
    ];

    /**
     * Get the book's image URL
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return \App\Helpers\ImageHelper::getImage($this->image_path, 'book');
            }
        );
    }

    //A many-to-one relationship with User model
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //A many-to-one relationship wiht Author model
    public function writtenBy()
    {
        return $this->BelongsTo(Author::class, 'author_id');
    }

    //A many-to-many relationship with User model
    public function borrowedBy()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
