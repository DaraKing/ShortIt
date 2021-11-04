<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'url'
    ];

    /**
     * Random generating code for short url
     *
     * @returns string
     */
    public function generateCode() {
        return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
    }

    public function scopeShortUrlByBaseUrl($query, $url) {
        return $query->where('url', '=', $url);
    }

    public function scopeShortUrlByCode($query, $code) {
        return $query->where('code', '=', $code);
    }
}
