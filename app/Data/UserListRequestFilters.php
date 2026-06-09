<?php

namespace App\Data;

use App\Enums\UserRole;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserListRequestFilters extends Data
{
    public function __construct(
        #[Nullable]
        public ?string $search = null,
        #[Nullable]
        public ?string $per_page = '12',
//        #[WithCast(DateTimeInterfaceCast::class)]
//        #[WithTransformer(DateTimeInterfaceTransformer::class)]
//        public Carbon|string|null $from_date = null,
//        #[WithCast(DateTimeInterfaceCast::class)]
//        #[WithTransformer(DateTimeInterfaceTransformer::class)]
//        public Carbon|string|null $to_date = null,
        public ?string $sort = null,
        public ?UserRole $role = null,
    ) {}
}
