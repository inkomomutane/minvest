<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { ColumnOrderDto, FormUIMap } from '@/types/generated';
import { Check, GripVertical } from 'lucide-vue-next';
import { nextTick, onMounted, ref, useTemplateRef, watch } from 'vue';
import axios from 'axios';
import { useSortable } from '@vueuse/integrations/useSortable';
import { Checkbox } from '@/components/ui/checkbox';
import { t } from '@/lib/trans';

interface Props {
    table: any;
    document_type: FormUIMap;
}

const props = defineProps<Props>();

const visibleFields = ref<ColumnOrderDto[]>([]);
const loading = ref(false);
const updating = ref(false);

const fetchVisibleFields = async () => {
    try {
        loading.value = true;

        const response = await axios.get(route('ui-fields', { type: props.document_type }));
        let data = response.data;
        // Ensure data is a one-dimensional array of strings

        console.log('Data from backend is not valid, using original fields:', (getOriginalFields(props.table).length !== data.length));
        if (!Array.isArray(data) || !data || data.length === 0 || getOriginalFields(props.table).length !== data.length) {
            visibleFields.value = getOriginalFields(props.table);
            applyFieldVisibility(props.table,visibleFields.value);
            applyFieldsOrder(props.table,getFieldInOrderStringArrayByKeys(visibleFields.value));
            await updateBackendFields( visibleFields.value);
        } else {
            visibleFields.value = data;
            applyFieldVisibility(props.table,visibleFields.value);
            applyFieldsOrder(props.table,getFieldInOrderStringArrayByKeys(visibleFields.value));
        }
    } catch (error) {
        console.error('Error fetching visible fields:', error);
        loading.value = false;
    } finally {
        console.log('Visible fields fetched:', visibleFields.value);
        loading.value = false;
    }
};



const applyFieldVisibility = (table,fields: Array<ColumnOrderDto>) => {
    table.getAllColumns().forEach((column: any) => {
        if (column.getCanHide()) {
            const isVisible = fields.some((field) => field.field_name === column.id && field.showing);
            column.toggleVisibility(isVisible);
        }
    });
};
const applyFieldsOrder = (table,fields: string[]) => {
    table.setColumnOrder(fields);
}
const getFieldInOrderStringArrayByKeys = (fields: Array<ColumnOrderDto>): string[] => {
    return fields.filter((field) => field.showing)
        .sort((a, b) => a.order - b.order)
        .map((field) => field.field_name);
};
const getOriginalFields = (table): ColumnOrderDto[] => {
    return table
        .getAllColumns()
        .filter((column: any) => column.getCanHide())
        .map((column: ColumnOrderDto, index: number) => {
            return {
                order: index,
                field_name: column.id,
                showing: column.getIsVisible(),
            };
        });
};
const updateBackendFields = async (fields: ColumnOrderDto[]) => {
    try {
        updating.value = true;

        await axios.post(route('ui-fields-update', {
            type: props.document_type,
        }), {
            visible_fields: fields,
            type: props.document_type,
        });
    } catch (error) {
        console.error('Error updating backend fields:', error);
    } finally {
        updating.value = false;
    }
};

const el = useTemplateRef('el')
const popoverOpen = ref(false)

onMounted(() => {
    fetchVisibleFields();
});
watch(popoverOpen, async  (newVal) => {
    if (newVal) {
        await nextTick()
        useSortable(el, visibleFields,{
            handle: '.handle',
        })
    }
});


const getMetaKeyNameOfColumn = (columnId: string): string | null => {
    return  props.table.getAllColumns().find((col: any) => col.id === columnId);
};

const handleFieldToggle = (column: ColumnOrderDto, isVisible: boolean) => {
    const fieldIndex = visibleFields.value.findIndex((field) => field.field_name === column.field_name);
    if (fieldIndex !== -1) {
        visibleFields.value[fieldIndex].showing = isVisible;
    }
};



const updateChanges = async () => {
    // 1. update the order based on the current order after drag-and-drop
    visibleFields.value = visibleFields.value.map((field, index) => ({ ...field, order: index}));
    // 2. apply the visibility and order to the table
    applyFieldVisibility(props.table, visibleFields.value);
    applyFieldsOrder(props.table, getFieldInOrderStringArrayByKeys(visibleFields.value));
    // 3. send the updated configuration to the backend
    await updateBackendFields( visibleFields.value);
};

</script>

<template>
    <DropdownMenu v-model:open="popoverOpen">
        <DropdownMenuTrigger as-child>
            <Button variant="outline" class="ml-auto">
                {{ t("Columns") }}
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-auto  p-0 mb-6" align="end">
            <DropdownMenuLabel class="font-medium sticky top-0 bg-background z-10 p-2 px-4  border-b">
                <div class="flex flex-row justify-between items-center">
                    <div>
                        {{ t("Columns") }}
                    </div>
                    <Button size="sm"  class="cursor-pointer p-1 px-3"
                            :disabled="updating"
                            variant="outline"
                            @click="updateChanges"
                    >
                        {{ t("Ok") }}
                    </Button>
                </div>

            </DropdownMenuLabel>
            <DropdownMenuGroup ref="el">
                <DropdownMenuItem
                    v-for="column in visibleFields"
                    :key="column"
                    @select="(e) => { e.preventDefault() }"
                >
                    <div class="handle cursor-move p-1">
                        <GripVertical class="size-5" />
                    </div>
                    <div class=" flex-1 font-bold  ">
                        {{  getMetaKeyNameOfColumn(column.field_name)?.columnDef.meta?.label || column.field_name }}
                    </div>
                    <div class="mx-4 cursor-pointer text-white dark:text-black">
                        <Checkbox
                            :model-value="column.showing"
                            class="size-5 data-[state=checked]:text-white dark:data-[state=checked]:text-black  border-2 border-zinc-600 dark:border-zinc-100 "
                            @update:model-value="(val) => handleFieldToggle(column, val)"
                        >
                            <Check class="size-3.5 text-white dark:text-black" />
                        </Checkbox>
                    </div>
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
