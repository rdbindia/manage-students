<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method void prepareToAttachMedia(Media $media, FileAdder $fileAdder)
 */
class Student extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'students';
    protected $fillable = [
        'name',
        'email',
        'bio',
        'date_of_birth',
        'advisor_id'
    ];

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Advisor::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'student_course');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photos')->singleFile();
    }

    protected static function booted(): void
    {
        static::saved(function ($student) {
            // Check if Livewire has a file reference
            if (is_array($student->profile_photo)) {
                // Extract the file reference
                $livewireFile = collect($student->profile_photo)
                    ->keys()
                    ->first();

                if ($livewireFile) {
                    $tempFilePath = storage_path('framework/livewire-tmp/' . $livewireFile);

                    if (file_exists($tempFilePath)) {
                        $student->clearMediaCollection('profile_photos');
                        $student->addMedia($tempFilePath)
                            ->toMediaCollection('profile_photos');

                        logger()->info('Profile photo processed and added to the media collection.', [
                            'temp_file' => $tempFilePath,
                        ]);
                    } else {
                        logger()->error('Temporary file not found.', ['temp_file' => $tempFilePath]);
                    }
                } else {
                    logger()->info('No valid Livewire file reference found.');
                }
            } else {
                logger()->info('No profile photo provided in the form submission.');
            }
        });
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('profile_photos');
    }
}
