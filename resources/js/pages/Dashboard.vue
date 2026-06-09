<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard, iconSearch,ListUsers } from '@/routes';
import AsyncSelect from '@/components/VSelect/AsyncSelect.vue';
import { Item, ItemContent, ItemMedia, ItemTitle } from '@/components/ui/item';
import { Avatar } from '@/components/ui/avatar';
import { ref } from 'vue';
import LinkTab from '@/components/LinkTab.vue';



defineProps({
    icons: Array,
});

const modeV= ref();
const page = usePage();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >

         <LinkTab :tabs="[
             {
                    title: 'Dashboard',
                    url: dashboard(),
                    isActive: page.url === dashboard().url
             },
              {
                    title: 'Users',
                    url: ListUsers(),
                    isActive: page.url === ListUsers().url
             },
         ]"/>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <AsyncSelect
                v-model="modeV"
                :mapper="
                    (e) => ({ title: e.name, slug: e.set, trailer: e.svg })
                "
                selected-key="name"
                :routeMethod="iconSearch"
                :get-label="(e) => `${e.name}`"
                :reduce="(e) => e.name"
                class="rounded"
            >

                <template #label="{ label }">
                    <div class="flex items-center gap-2">
                        <div v-html="label?.svg" class="!h-6 !w-6 [&>svg]:!h-full [&>svg]:!w-full text-black dark:text-white" />
                        <span>{{ label?.name }}</span>
                    </div>
                </template>


                <template #trailer="{ option }">
                    <div v-html="option.trailer" class="!h-6 !w-6 [&>svg]:!h-full [&>svg]:!w-full text-black dark:text-white"  />
                </template>
            </AsyncSelect>

            <template v-for="(icon, index) in icons" :key="index">
                <div
                    class="flex items-center gap-4 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <div v-html="icon.svg" class="h-6 w-6" />
                    </div>
                    <div>
                        <p
                            class="text-sm font-medium text-gray-900 dark:text-white"
                        >
                            {{ icon.name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ icon.path }}
                        </p>
                    </div>
                </div>
            </template>
        </div>
        <div
            class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
        >
            <PlaceholderPattern />
        </div>
    </div>
</template>
