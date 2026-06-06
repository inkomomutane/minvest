<script setup lang="ts">
import { Button } from '@/components/ui/button'
import type { Row } from '@tanstack/vue-table'
import { MoreHorizontal,EllipsisVertical  } from 'lucide-vue-next'
import  type { LucideIcon } from 'lucide-vue-next'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem, DropdownMenuSeparator,
    DropdownMenuShortcut, DropdownMenuSub, DropdownMenuSubContent, DropdownMenuSubTrigger,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
interface DataTableRowActionsProps {
    row: Row,
    defaultActionIcon?: LucideIcon,
    actions: {
        label: string,
        onClick: (row: Row) => void
        icon?: LucideIcon,
        hasSeparator?: boolean,
        children?: Array<{
            label: string,
            onClick: (row: Row) => void
            icon?: LucideIcon,
            hasSeparator?: boolean,
        }>
    }[]
}
const props = withDefaults(defineProps<DataTableRowActionsProps>(), {
    actions: [],
})
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                variant="ghost"
                class="flex h-6 w-6 p-0 data-[state=open]:bg-muted"
            >
                <template v-if="props.defaultActionIcon">
                    <component :is="props.defaultActionIcon" class="h-4 w-4" />
                </template>
                <template v-else>
                    <EllipsisVertical class="h-4 w-4" />
                </template>

                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-[160px]">
            <template v-for="(action, index) in props.actions" :key="index">
                <DropdownMenuItem @click="action.onClick(props.row)" v-if="!action.children || action.children.length === 0">
                    <component v-if="action.icon" :is="action.icon" class="mr-2 h-4 w-4" />
                    {{ action.label }}
                </DropdownMenuItem>
                <DropdownMenuSeparator v-if="action.hasSeparator" />
                <DropdownMenuSub v-if="action.children && action.children.length > 0">
                    <DropdownMenuSubTrigger>
                        <component v-if="action.icon" :is="action.icon" class="mr-2 h-4 w-4" />
                        {{ action.label }}
                    </DropdownMenuSubTrigger>
                    <DropdownMenuSubContent align="start" class="w-[160px]">
                        <template v-for="(childAction, childIndex) in action.children" :key="childIndex">
                            <DropdownMenuItem @click="childAction.onClick(props.row)">
                                <component v-if="childAction.icon" :is="childAction.icon" class="mr-2 h-4 w-4" />
                                {{ childAction.label }}
                            </DropdownMenuItem>
                            <DropdownMenuSeparator v-if="childAction.hasSeparator" />
                        </template>
                    </DropdownMenuSubContent>
                </DropdownMenuSub>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
