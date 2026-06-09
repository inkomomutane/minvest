<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { cn } from '@/lib/utils';

defineProps<{
    tabs: {
        title: string;
        url: string;
        isActive: boolean;
    }[];
}>();
</script>

<template>
    <nav
        class="flex flex-wrap items-center gap-x-1 gap-y-2 border-b border-zinc-200 pb-px dark:border-zinc-800 w-fit"
        aria-label="Tabs"
    >
        <Link
            v-for="(tab, index) in tabs"
            :key="index"
            :href="tab.url"
            :aria-current="tab.isActive ? 'page' : undefined"
            :class="cn(
        'inline-flex items-center justify-center whitespace-nowrap rounded-t-md px-3 py-2 text-sm font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 disabled:pointer-events-none disabled:opacity-50 w-fit',
        tab.isActive
          ? 'relative text-zinc-950 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-zinc-950 dark:text-zinc-50 dark:after:bg-zinc-50'
          : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200'
      )"
        >
            {{ tab.title }}
        </Link>
    </nav>
</template>
