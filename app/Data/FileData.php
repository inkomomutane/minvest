<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileData extends Data
{
    public function __construct(
        public string $name,
        public string $size,
        public string $type,
        public string $url,
        public ?string $id = null,
        public ?string $uuid = null,
        public ?string $collection_name = null,
        public ?string $disk = null,
    ) {}

    public static function fromModel(Media $media): self
    {
        return new self(
            name: $media->name,
            size: $media->size,
            type: $media->mime_type,
            url: $media->getTemporaryUrl(Carbon::now()->addMinutes(5)),
            id: $media->id,
            uuid: $media->uuid,
            collection_name: $media->collection_name,
            disk: $media->disk,
        );
    }
}
