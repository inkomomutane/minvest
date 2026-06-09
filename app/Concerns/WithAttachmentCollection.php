<?php

namespace App\Concerns;

use App\Data\FileData;
use File;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait WithAttachmentCollection
{
    public function getUploadedAttachmentsAttribute(): null|array|Collection
    {
        return $this->getMedia(getMorphAlias(self::class))->map(fn ($file) => $file ? FileData::fromModel($file) : null);
    }

    public function getPrintableLinkAttribute(): string
    {
        $media = $this->getFirstMedia(getMorphAlias(self::class).'_printable_path');

        return $media ? $media->getFullUrl() : '';
    }

    /**
     * @param array $attachments
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $this->addMedia($attachment)->toMediaCollection(getMorphAlias(self::class), 's3');
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachment(UploadedFile|File $attachment): void
    {
        $this->addMedia($attachment)->toMediaCollection(getMorphAlias(self::class), 's3');
    }

    public function removeAttachments(): void
    {
        $this->clearMediaCollection(getMorphAlias(self::class));
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addPrintableAttachment(string $path): void
    {
        // ensure the file exists
        if (! File::exists($path)) {
            throw new FileDoesNotExist("File at path {$path} does not exist.");
        }
        $this->addMedia($path)->toMediaCollection(getMorphAlias(self::class).'_printable_path', 's3');
        // ensure the file is deleted after adding to media library
        File::delete($path);
    }
}
