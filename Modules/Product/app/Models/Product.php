<?php

namespace Modules\Product\Models;

use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\OrderItem;
use Modules\Specification\Models\Specification;
use Modules\Store\Models\Store;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;


class Product extends Model implements Viewable, HasMedia
{
    use HasFactory, HasSlug, LogsActivity, InteractsWithMedia, InteractsWithViews;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'description',
        'quantity',
        'price',
        'discount',
        'discount_type',
        'created_at',
        'updated_at',
        'status',
    ];
    
    	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'محصول ' . __('logs.' . $eventName));
	}
    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }
    public function specifications() : BelongsToMany{
        return $this->belongsToMany(Specification::class)->withPivot('value');
    }
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function totalPriceWithDiscount(): int
	{
		$price = $this->attributes['price'];
		$discount = $this->attributes['discount'];
		$discountType = $this->attributes['discount_type'];

		if ($discountType === 'percent') {
			return $price - ($price * $discount / 100);
		}
		if ($discountType === 'flat') {
			return $price - $discount;
		}

		return $price;
	}
    public static function getTopDiscountedProducts()
    {
        $topDiscountedProducts = DB::select("select `id`, `title`,`status`,`price`, IF( `discount_type` = 'percent', (`discount`/100) * `price`, `discount`) AS `total_discount` FROM `products`  WHERE `status` = 'available' ORDER BY `total_discount` DESC LIMIT 10;");

        return $topDiscountedProducts;
    }
        /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->usingLanguage('')
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(191)
            ->doNotGenerateSlugsOnUpdate();
    }

    //start media-library
    protected $with = ['media'];

    protected $hidden = ['media'];

    protected $appends = ['image', 'galleries'];

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('product_images')->singleFile();
        $this->addMediaCollection('product_galleries');
    }

    protected function image(): Attribute
    {
        $media = $this->getFirstMedia('product_images');

        return Attribute::make(
            get: fn () => [
                'id' => $media?->id,
                'url' => $media?->getFullUrl(),
                'name' => $media?->file_name
            ],
        );
    }

    protected function galleries(): Attribute
    {
        $media = $this->getMedia('product_galleries');

        $galleries = [];
        if ($media->count()) {
            foreach ($media as $mediaItem) {
                $galleries[] = [
                    'id' => $mediaItem?->id,
                    'url' => $mediaItem?->getFullUrl(),
                    'name' => $mediaItem?->file_name
                ];
            }
        }

        return Attribute::make(
            get: fn () => $galleries,
        );
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addImage(UploadedFile $file): bool|\Spatie\MediaLibrary\MediaCollections\Models\Media
    {
        return $this->addMedia($file)->toMediaCollection('product_images');
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addGallery(UploadedFile $file): bool|\Spatie\MediaLibrary\MediaCollections\Models\Media
    {
        return $this->addMedia($file)->toMediaCollection('product_galleries');
    }
    public function uploadFiles(Request $request): void{
        
        $this->uploadImage($request);
        $this->uploadGalleries($request);
    }
    public function uploadImage(Request $request): void
    {
        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->addImage($request->file('image'));
            }
        } catch (FileDoesNotExist $e) {
            Log::error('Product upload file (FileDoesNotExist): ' . $e->getMessage());
        }catch (FileIsTooBig $e) {
            Log::error('Product upload file (FileIsTooBig): ' . $e->getMessage());
        }
    }
    protected function uploadGalleries(Request $request): void
	{
		try {
			if ($request->hasFile('galleries')) {
				foreach ($request->file('galleries') as $image) {
					if ($image->isValid()) {
						$this->addGallery($image);
					}
				}
			}

			if ($request->method() == 'PATCH' && $request->filled('deleted_image_ids')) {
				$this->deleteImages($request->input('deleted_image_ids'));
			}

		} catch (FileDoesNotExist $e) {
			Log::error('آپلود فایل برای دسته بندی (فایل وجود ندارد) : ' . $e->getMessage());
		} catch (FileIsTooBig $e) {
			Log::error('آپلود فایل برای دسته بندی (حجم فایل زیاد است) : ' . $e->getMessage());
		}
	}
    //End media-library
}
