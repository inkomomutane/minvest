<script lang="ts" setup>
import { h, PropType, ref, watch, computed } from 'vue';
import { Link, useForm, router, Head } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { createColumnHelper } from '@tanstack/vue-table';

// Components
import Pagination from '@/components/Pagination.vue';
import VTable from '@/components/VTable/VTable.vue';
import VTableHeader from '@/components/VTable/VTableHeader.vue';
import VTableRowAction from '@/components/VTable/VTableRowAction.vue';
import TableSkeleton from '@/components/TableSkeleton.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

// Icons & Helpers
import { Download, Plus, Search, Eye, Pencil, LucideTrash } from '@lucide/vue';
import { t } from '@/lib/trans';
import { UserData } from '@/generated/types/App/Data';
import VSyncSelect from '@/components/VSyncSelect.vue';
import ContainerBox from '@/components/Box/ContainerBox.vue';
import { crudManager } from '@/lib/helpers';
import { ListUsers } from '@/routes';

interface PaginatedUsers {
    data: UserData[];
    from: number;
    to: number;
    total: number;
    per_page: number;
    links: any[];
    first_page_url: string;
    last_page_url: string;
    next_page_url: string | null;
    prev_page_url: string | null;
}

// Added Record<string, any> type to PropType generic
const props = defineProps({
    users: { type: Object as PropType<PaginatedUsers>, required: true },
    request: { type: Object as PropType<Record<string, any>>, required: true },
});

const loading = ref(false);

// Modal state configurations
const createUser = ref(crudManager<UserData>());
const editUser = ref(crudManager<UserData>());
const deleteUser = ref(crudManager<UserData>());

// Added from_date and to_date to form tracking state
const form = useForm({
    search: props.request?.search ?? '',
    per_page: props.request?.per_page ?? '12',
    sort: props.request?.sort ?? '',
    role: props.request?.role ?? '',
    from_date: props.request?.from_date ?? '',
    to_date: props.request?.to_date ?? '',
});

// Auto-submit search and filters using debounced tracking
const performSearchAndFilter = useDebounceFn((filterData) => {
    router.visit(ListUsers(), {
        data: filterData,
        only: ['users'],
        replace: false,
        preserveState: true,
        onStart: () => {
            loading.value = true;
        },
        onFinish: () => {
            loading.value = false;
        },
    });
}, 500);

watch(
    () => form.data(),
    (data) => {
        performSearchAndFilter(data);
    },
    { deep: true },
);

const tableData = computed(() => props.users?.data ?? []);
const columnHelper = createColumnHelper<UserData>();

// Action Items Pattern
const buildActions = (row: UserData) => [
    {
        label: t('View'),
        icon: Eye,
        onClick: () => {}
    },
    {
        label: t('Edit'),
        icon: Pencil,
        onClick: () => editUser.value.open(row),
    },
    {
        label: t('Delete'),
        icon: LucideTrash,
        onClick: () => deleteUser.value.open(row),
    },
];

// Unified TanStack column definitions layout
const columns = computed(() => [
    columnHelper.accessor('name', {
        enableSorting: true,
        enablePinning: true,
        header: ({ column }) =>
            h(VTableHeader, {
                column,
                title: t('Name'),
                modelValue: form.sort,
                'onUpdate:modelValue': (v) => (form.sort = v),
            }),
        cell: ({ row }) =>
            h(
                Link,
                {
                    class: 'pe-4 ps-4 text-xs font-medium underline underline-offset-4 text-blue-600 cursor-pointer',
                    href: ListUsers()
                },
                row.original.name,
            ),
        meta: { label: t('Name') },
    }),
    columnHelper.accessor('email', {
        enableSorting: true,
        enablePinning: false,
        header: ({ column }) =>
            h(VTableHeader, {
                column,
                title: t('Email Address'),
                modelValue: form.sort,
                'onUpdate:modelValue': (v) => (form.sort = v),
            }),
        cell: ({ row }) =>
            h(
                'span',
                { class: 'text-xs pe-4 ps-4 font-mono text-muted-foreground' },
                row.original.email,
            ),
        meta: { label: t('Email Address') },
    }),
    columnHelper.accessor('role', {
        enableSorting: true,
        header: ({ column }) =>
            h(VTableHeader, {
                column,
                title: t('Roles'),
                modelValue: form.sort,
                'onUpdate:modelValue': (v) => (form.sort = v),
            }),
        cell: ({ row }) =>
            h(
                'div',
                { class: 'flex flex-wrap gap-1 ps-4 pe-4' },
                row.original.role?.map((roleName) =>
                    h(
                        Badge,
                        {
                            variant: 'outline',
                            class: 'text-xs font-mono uppercase font-medium',
                        },
                        // FIXED: Render string wrapped in explicit render text array node
                        () => [roleName],
                    ),
                ),
            ),
        meta: { label: t('Roles') },
    }),
    columnHelper.display({
        id: 'actions',
        enablePinning: true,
        header: ({ column }) =>
            h(VTableHeader, { column, class: 'max-w-12 w-fit', title: '' }),
        cell: ({ row }) =>
            h(VTableRowAction, { row, actions: buildActions(row.original) }),
        meta: { label: t('Actions') },
    }),
]);


</script>

<template>
    <Head title="Users" />
    <ContainerBox :header="t('System Users')">
        <template #header-left>
            <div class="flex flex-col items-end gap-4 lg:flex-row md:ml-6">
                <div>
                    <Label class="mb-1 block text-xs text-muted-foreground">{{
                            t('Search Users')
                        }}</Label>
                    <div class="relative w-full max-w-sm items-center">
                        <Input
                            id="user-search"
                            v-model="form.search"
                            :placeholder="t('Search name or email') + '...'"
                            class="pl-10"
                            type="text"
                        />
                        <span
                            class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
                        >
                            <Search class="size-4 text-muted-foreground" />
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <template #header-right>
            <div class="flex items-center gap-3 md:mr-6">
                <Button
                    @click="createUser.open"
                    class="flex items-center gap-2"
                >
                    <Plus class="w-5" />
                    {{ t('Create user') }}
                </Button>
            </div>
        </template>

        <template #content-table>
            <div class="p-4">
                <div v-if="loading" class="space-y-4 py-6">
                    <TableSkeleton />
                </div>

                <div v-else>
                    <VTable
                        :pinning="{ left: ['name'], right: [] }"
                        :columns-defs="columns"
                        v-model="tableData"
                    >
                        <template #fields_visibility>
                            <div
                                class="mb-2 flex items-center justify-between px-2"
                            >
                                <div class="flex flex-wrap items-center gap-4">
                                    <div>
                                        <Label class="mb-1 block text-xs">{{
                                                t('Role Filter')
                                            }}</Label>
                                        <VSyncSelect
                                            v-model="form.role"
                                            :options="[
                                                {
                                                    label: t('All Roles'),
                                                    value: '',
                                                },
                                                {
                                                    label: t('Admin'),
                                                    value: 'admin',
                                                },
                                                {
                                                    label: t('Guest'),
                                                    value: 'guest',
                                                },{
                                                    label: t('Manager'),
                                                    value: 'manager',
                                                }
                                            ]"
                                            :get-label="
                                                (option) => option.label
                                            "
                                            :reduce="(option) => option.value"
                                            placeholder="Select Role"
                                        />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </VTable>

                    <Pagination
                        :from="users?.from"
                        :to="users?.to"
                        :total="users?.total"
                        :per_page="users?.per_page"
                        :links="users?.links"
                        :first_page_url="users?.first_page_url"
                        :last_page_url="users?.last_page_url"
                        :next_page_url="users?.next_page_url"
                        :prev_page_url="users?.prev_page_url"
                        class="p-4"
                    />
                </div>
            </div>
        </template>
    </ContainerBox>
</template>
