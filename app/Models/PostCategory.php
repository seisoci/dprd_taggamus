<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PostCategory extends Model
{
  use HasFactory, HasSlug;

  protected $fillable = [
    'type',
    'name',
    'slug'
  ];

  public function posts(){
    $this->belongsToMany(Post::class);
  }

  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }

  protected function name(): Attribute
  {
    return Attribute::make(
      set: fn($value) => ucfirst($value),
    );
  }

  protected function type(): Attribute
  {
    return Attribute::make(
      get: fn($value) => ucwords($value),
    );
  }

  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMMM YYYY');
  }
}
