<?php

namespace App\Data;

use BladeUI\Icons\Exceptions\SvgNotFound;
use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\Data;

class IconData extends Data
{
    public function __construct(
        public string $name,
        public ?string $set = null,
        public ?string $path = null,
        public ?string $disk = null,
        public ?string $svg = null,
    ) {}
    public static function fromModel($icon): self
    {
        $cacheKey = "icon_svg:{$icon->set}:{$icon->name}";
        $svgHtml = Cache::rememberForever($cacheKey, static function () use ($icon) {
            try {
                return app(IconFactory::class)->svg($icon->name)->toHtml();
            } catch (SvgNotFound $e) {
                return '';
            }
        });

        return new self(
            name: $icon->name,
            set: $icon->set,
            svg: $svgHtml,
        );
    }
}
