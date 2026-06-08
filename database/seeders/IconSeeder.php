<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;
use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class IconSeeder extends Seeder
{
    protected Filesystem $fileSystem;

    public function run(): void
    {
        $this->fileSystem = new Filesystem();
        $factory = app(IconFactory::class);
        $iconsToSeed = [];

        // Loop 1 & 2: Pass data out quickly to flat helpers to kill nesting complexity
        foreach ($factory->all() as $iconSet) {
            foreach ($iconSet['paths'] as $path) {
                $iconsToSeed = array_merge($iconsToSeed, $this->getIconsFromPath($path, $iconSet['prefix']));
            }
        }

        // Chunk and upsert safely
        foreach (array_chunk($iconsToSeed, 500) as $chunk) {
            Icon::upsert($chunk, ['name'], ['set', 'path', 'disk']);
        }

        $this->command->info('Icons seeded successfully!');
    }

    /**
     * Isolated helper to parse a single path.
     * This eliminates deep nesting and plummets the static complexity score.
     */
    protected function getIconsFromPath(string $path, ?string $prefix): array
    {
        if (! $this->fileSystem->isDirectory($path)) {
            return [];
        }

        $icons = [];

        foreach ($this->fileSystem->allFiles($path) as $file) {
            if ($file->getExtension() !== 'svg') {
                continue;
            }

            $relativePath = $file->getRelativePathname();
            $iconName = Str::beforeLast($relativePath, '.svg');
            $iconName = Str::replace(DIRECTORY_SEPARATOR, '-', $iconName);

            $icons[] = [
                'name' => $prefix ? "{$prefix}-{$iconName}" : $iconName,
                'set'  => $prefix,
                'path' => $relativePath,
                'disk' => null,
            ];
        }

        return $icons;
    }
}
