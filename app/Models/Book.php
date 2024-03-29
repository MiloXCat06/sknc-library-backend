<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'synopsis',
        'isbn',
        'writer',
        'page_amount',
        'stock_amount',
        'published',
        'category',
        'image',
        'status',
    ];

    /**
     * user
     * 
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * views
     * 
     * @return void
     */
    public function views()
    {
        return $this->hasMany(BookView::class);
    }

    /**
     * image
     * ini fungsi Accesor -> getter/mengambil data dari database
     * untuk menyeragamkan format image
     * @return Attributs
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/books/' . $image),
        );
    }
}
