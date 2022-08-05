<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Shetabit\Visitor\Traits\Visitable;
use Shetabit\Visitor\Traits\Visitor;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;

class Post extends Model
{
  use HasFactory, HasSlug, Visitor, Visitable;

  protected $fillable = [
    'user_id',
    'type',
    'title',
    'slug',
    'synopsis',
    'body',
    'image',
    'published',
    'publish_at'
  ];

  protected $hidden = [
    'user_id'
  ];

  public function post_categories(){
    return $this->belongsToMany(PostCategory::class);
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($item) {
      $item->user_id = Auth::user()->id;
      $item->synopsis = Str::limit(strip_tags($item->body), 50);
    });

    static::updating(function ($item) {
      $item->user_id = Auth::user()->id;
      $item->synopsis = Str::limit(strip_tags($item->body), 50);
    });
  }

  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('title')
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

  public function datastorage()
  {
    return $this->morphMany(DataStorage::class, 'storagable', 'storage_data_type', 'storage_data_id')->orderBy('sort', 'asc');
  }

  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMMM YYYY HH:mm');
  }
}
