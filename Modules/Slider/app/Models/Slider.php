<?php

namespace Modules\Slider\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Slider extends Model implements HasMedia
{
    use HasFactory, LogsActivity,InteractsWithMedia;
    public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'اسلایدر ' . __('logs.' . $eventName));
	}
    protected $fillable = [
        'link',
        'status',
    ];
    
    protected $with = ['media'];
    protected $hidden = ['media'];
    protected $appends = ['image'];
    
    public function registerMediaCollections(): void {
        $this->addMediaCollection('slider_images')->singleFile();
    }
    
    protected function image() : Attribute 
    {
        $media = $this->getFirstMedia('slider_images');

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
        return $this->addMedia($file)->toMediaCollection('slider_images');
    }
    public function uploadFiles(Request $request): void
    {
        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->addImage($request->file('image'));
            }
        } catch (FileDoesNotExist $e) {
            Log::error('Slider upload file (FileDoesNotExist): ' . $e->getMessage());
        }catch (FileIsTooBig $e) {
            Log::error('Slider upload file (FileIsTooBig): ' . $e->getMessage());
        }
    }

}
