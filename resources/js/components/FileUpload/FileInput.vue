<script setup lang="ts">
import FileToUpload from './FileToUpload.vue';
import { Button } from '@/components/ui/button';
import { useFileDialog,useDropZone } from '@vueuse/core';
import { Paperclip, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { cn } from '@/lib/utils';
import { ButtonGroup } from '@/components/ui/button-group';
import { useTemplateRef } from 'vue' ;

const props = defineProps({
    multiple: {
        type: Boolean,
        default: false,
    },
    accept: {
        type: String,
        default: 'image/*',
    },
    liteUpload: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Upload a file',
    },
    multipleLines: {
        type: Boolean,
        default: false,
    },
    maxFileSize: {
        type: Number,
        default: 10 * 1024 * 1024,
    },

    class: {
        default: '',
        type: String
    }
});

const pickedFiles = ref<FileList | null | File[]>([]);
const invalidFiles = ref<
    {
        file: File;
        error: string;
    }[]
>([]);
const dropZoneRef = useTemplateRef('dropZoneRef')


const model = defineModel<FileList | null | File[]>();

const { open, reset, onChange } = useFileDialog({
    accept: props.accept,
    multiple: props.multiple,
});

const handleChangeFiles = (files) => {
    invalidFiles.value = [...invalidFiles.value];
    model.value = [...(model.value ?? [])];
    if (((files ?? [])?.length > 0) && props.multiple) {
        pickedFiles.value = [...model.value, ...Array.from(files ?? [])];
    }else if (((files ?? [])?.length > 0) && !props.multiple) {
        pickedFiles.value = Array.from(files ?? []);
    } else {
        pickedFiles.value = [];
        model.value = [];
    }
}

onChange((files) => {
    handleChangeFiles(files);
});
function onDrop(files) {
    handleChangeFiles(files);
}

const { isOverDropZone } = useDropZone(dropZoneRef, {
    onDrop,
    // control multi-file drop
    multiple:  props.multiple,
    // whether to prevent default behavior for unhandled events
    preventDefaultForUnhandled: false
});

const resetFiles = () => {
    pickedFiles.value = [];
    invalidFiles.value = [];
    model.value = [];
    reset();
}


watch(
    () => pickedFiles.value,
    (newFiles: FileList | File[] | null) => {
        // Clear previous model state
        model.value = [];

        // Early return if no files
        if (!newFiles || newFiles.length === 0) {
            return;
        }

        // Convert FileList to array for easier manipulation
        const filesArray = Array.from(newFiles);
        const maxSizeMB = props.maxFileSize / (1024 * 1024);

        // Process files in a single pass
        const { validFiles, invalidFileEntries } = filesArray.reduce(
            (acc, file: File) => {
                if (file.size > props.maxFileSize) {
                    acc.invalidFileEntries.push({
                        file,
                        error: `File size exceeds the maximum limit of ${maxSizeMB.toFixed(1)} MB`,
                    });
                } else {
                    acc.validFiles.push(file);
                }
                return acc;
            },
            { validFiles: [] as File[], invalidFileEntries: [] as Array<{ file: File; error: string }> },
        );

        // Update reactive values
        model.value = validFiles;
        invalidFiles.value = invalidFileEntries;
    },
    {
        immediate: false,
        deep: false, // Shallow watch is sufficient for FileList/Array changes
    },
);

const isUploadNotLite = computed(() => !props.liteUpload && model.value);

const removeFile = (file: File) => {
    const files = Array.from(model.value ?? []).filter((f) => f !== file);
    const dto = new DataTransfer();
    files.forEach((f: File) => dto.items.add(f));
    model.value = dto.files;
};

const removeInvalidFile = (file: File) => {
    invalidFiles.value = invalidFiles.value.filter((f) => f.file !== file);
};
</script>

<template>
    <div ref="dropZoneRef">
        <div >
            <ButtonGroup :class="cn('flex w-fit items-center justify-start',props.class)">
                <Button variant="outline" :class="cn('cursor-pointer justify-start gap-2 rounded-none rounded-l border-r-0',props.class)" @click="open">
                    <slot  name="icon">
                        <Paperclip class="h-5 w-5" />
                    </slot>
                    <template v-if="model?.length > 0 "> {{ model.length }} file{{ model.length > 1 ? 's' : '' }} selected </template>
                    <span v-else>{{ title }}</span>
                </Button>
                <Button @click="resetFiles" variant="outline" class="cursor-pointer rounded-none rounded-r hover:bg-red-50/50 hover:text-red-500">
                    <X />
                </Button>
            </ButtonGroup>
        </div>
        <div>
            <template v-if="isUploadNotLite">
                <div v-for="invalid in invalidFiles" :key="invalid.file.name" class="flex items-center justify-between py-2">
                    <FileToUpload status="invalid" :error="invalid.error" :file="invalid.file" @delete-file="removeInvalidFile(invalid.file)" />
                </div>
                <div v-if="model?.length < 4" v-for="file in model" :key="file.name" class="flex items-center justify-between py-2">
                    <FileToUpload :file="file" @delete-file="removeFile(file)" />
                </div>
            </template>
        </div>
    </div>
</template>
