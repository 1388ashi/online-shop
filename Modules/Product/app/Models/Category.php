<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Modules\Specification\Models\Specification;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Category extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;
    protected $fillable = [
        'name',
        'parent_id',
        'featured',
        'status',
    ];/**\
     */
    public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'دسته بندی ' . __('logs.' . $eventName));
	}

    public function parent(): BelongsTo 
    {
        return $this->belongsTo(Category::class,'parent_id');
        
    }
    public function children(): HasMany 
    {
        return $this->hasMany(Category::class,'parent_id');
    }
    public function recursiveChildren(): HasMany 
    {
        return $this->children()->with('children');
    }
    //mediaLibrary
    protected $with = ['media'];
    protected $hidden = ['media'];
    protected $appends = ['image'];
    
    public function registerMediaCollections(): void {
        $this->addMediaCollection('category_images')->singleFile();
    }
    
    protected function image() : Attribute 
    {
        $media = $this->getFirstMedia('category_images');

        return Attribute::make(
            get: fn () => [
                'id' => $media?->id,
                'url' => $media?->getFullUrl(),
                'name' => $media?->file_name
            ],
        );
    }
    public function addImage(UploadedFile $file): bool|\Spatie\MediaLibrary\MediaCollections\Models\Media
    {
        return $this->addMedia($file)->toMediaCollection('category_images');
    }
    public function uploadFiles(Request $request): void
    {
        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->addImage($request->file('image'));
            }
        } catch (FileDoesNotExist $e) {
            Log::error('Category upload file (FileDoesNotExist): ' . $e->getMessage());
        }catch (FileIsTooBig $e) {
            Log::error('Category upload file (FileIsTooBig): ' . $e->getMessage());
        }
    }

    public static function booted()
    {
        static::deleting(function(Category $category){
            if ($category->specifications->isNotEmpty()) {
                abort(403,'این دسته بندی قابل حذف نمیباشد');
            }
        });
    }
    public function specifications() : BelongsToMany{
		return $this->belongsToMany(Specification::class);
    }
    public function products() : hasMany{
		return $this->hasMany(Product::class);
    }
}
