<?php

namespace App;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface WithAttachmentCollectionInterface
{
    public function getUploadedAttachmentsAttribute(): null|array|Collection;

    public function getPrintableLinkAttribute(): string;

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachments(array $attachments): void;

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachment(UploadedFile|\File $attachment): void;

    public function removeAttachments(): void;

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addPrintableAttachment(string $path): void;
}
