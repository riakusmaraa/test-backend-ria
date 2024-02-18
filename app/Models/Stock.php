<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $guarded = ['id'];

    public function category()
{
    return $this->belongsTo(Category::class);
}
public function book()
{
    return $this->belongsTo(Book::class);
}
}
