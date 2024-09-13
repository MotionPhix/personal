<script setup lang="ts">
import ListItem from '@tiptap/extension-list-item';

import Placeholder from "@tiptap/extension-placeholder"

import { useEditor, EditorContent } from "@tiptap/vue-3"

import StarterKit from "@tiptap/starter-kit"

import HorizontalRule from '@tiptap/extension-horizontal-rule'

import BulletList from "@tiptap/extension-bullet-list"

import OrderedList from "@tiptap/extension-ordered-list"

import Blockquote from "@tiptap/extension-blockquote"

import Paragraph from "@tiptap/extension-paragraph"

import Bold from "@tiptap/extension-bold"

import Underline from "@tiptap/extension-underline"

import { onBeforeUnmount, watch } from 'vue'

const model = defineModel()

// Define props for v-model and placeholder
const props = defineProps({
  placeholder: {
    type: String,
    default: 'Start typing...'
  }
})

const editor = useEditor({
  extensions: [
    Placeholder.configure({
      placeholder: props.placeholder as any,
      emptyNodeClass: 'text-gray-800 dark:text-neutral-200'
    }),

    StarterKit,

    Paragraph.configure({
      HTMLAttributes: {
        class: 'text-gray-800 dark:text-neutral-200'
      }
    }),

    Bold.configure({
      HTMLAttributes: {
        class: 'font-bold'
      }
    }),

    Underline,

    BulletList.configure({
      HTMLAttributes: {
        class: 'list-disc list-inside text-gray-800 dark:text-white'
      }
    }),

    OrderedList.configure({
      HTMLAttributes: {
        class: 'list-decimal list-inside text-gray-800 dark:text-white'
      }
    }),

    ListItem,

    Blockquote.configure({
      HTMLAttributes: {
        class: 'text-gray-800 dark:text-white border-l-2 dark:border-l-gray-300 border-l-gray-800 pl-[1rem]'
      }
    }),

    HorizontalRule,
  ],
})

// Toolbar actions using editor commands
const toggleBold = () => editor.value?.chain().focus().toggleBold().run()
const toggleItalic = () => editor.value?.chain().focus().toggleItalic().run()
const toggleUnderline = () => editor.value?.chain().focus().toggleUnderline().run()
const toggleStrike = () => editor.value?.chain().focus().toggleStrike().run()
const toggleOrderedList = () => editor.value?.chain().focus().toggleOrderedList().run()
const toggleBulletList = () => editor.value?.chain().focus().toggleBulletList().run()
const toggleBlockquote = () => editor.value?.chain().focus().toggleBlockquote().run()
const toggleCode = () => editor.value?.chain().focus().toggleCode().run()
const setHorizontalRule = () => editor.value?.chain().focus().setHorizontalRule().run()

onBeforeUnmount(() => {
  editor.value?.destroy()
})

// Watch the editor's content and sync it with v-model
watch(() => editor.value?.getHTML(), (newValue) => {
  model.value = newValue // Update the modelValue when editor content changes
})
</script>

<template>

 <!-- Tiptap Editor -->
  <div
    class="bg-white border border-gray-200 rounded-xl overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">

    <div id="hs-editor-tiptap">

      <div class="flex align-middle gap-x-0.5 border-b border-gray-200 p-2 dark:border-neutral-700">

        <!-- Toolbar buttons with Vue event binding -->
        <button
          type="button"
          @click="toggleBold"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-bold="">
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 12a4 4 0 0 0 0-8H6v8"></path>
            <path d="M15 20a4 4 0 0 0 0-8H6v8Z"></path>
          </svg>
        </button>

        <button
          type="button"
          @click="toggleItalic"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-italic="">
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" x2="10" y1="4" y2="4"></line>
            <line x1="14" x2="5" y1="20" y2="20"></line>
            <line x1="15" x2="9" y1="4" y2="20"></line>
          </svg>
        </button>

        <button
          type="button"
          @click="toggleUnderline"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-underline="">
          <svg
            fill="currentColor"
            class="shrink-0 size-3.5"
            id="Layer_1" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 360 360"
            xml:space="preserve">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <rect x="62.877" y="330" width="234.245" height="30"></rect> <path d="M180,290c61.825,0,112.123-50.298,112.123-112.122V0h-30v177.878C262.123,223.16,225.283,260,180,260 s-82.123-36.84-82.123-82.122V0h-30v177.878C67.877,239.702,118.175,290,180,290z"></path> </g> </g></svg>
        </button>

        <button
          type="button"
          @click="toggleStrike"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-strike="">
          <svg
            class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M16 4H9a3 3 0 0 0-2.83 4"></path>
            <path d="M14 12a4 4 0 0 1 0 8H6"></path>
            <line x1="4" x2="20" y1="12" y2="12"></line>
          </svg>
        </button>

        <button
          type="button"
          @click="toggleOrderedList"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-ol="">
          <svg
            class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <line x1="10" x2="21" y1="6" y2="6"></line>
            <line x1="10" x2="21" y1="12" y2="12"></line>
            <line x1="10" x2="21" y1="18" y2="18"></line>
            <path d="M4 6h1v4"></path>
            <path d="M4 10h2"></path>
            <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
          </svg>
        </button>

        <button
          type="button"
          @click="toggleBulletList"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-ul="">
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="8" x2="21" y1="6" y2="6"></line>
            <line x1="8" x2="21" y1="12" y2="12"></line>
            <line x1="8" x2="21" y1="18" y2="18"></line>
            <line x1="3" x2="3.01" y1="6" y2="6"></line>
            <line x1="3" x2="3.01" y1="12" y2="12"></line>
            <line x1="3" x2="3.01" y1="18" y2="18"></line>
          </svg>
        </button>

        <button
          type="button"
          @click="toggleBlockquote"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-blockquote="">
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 6H3"></path>
            <path d="M21 12H8"></path>
            <path d="M21 18H8"></path>
            <path d="M3 12v6"></path>
          </svg>
        </button>

        <button
          type="button"
          @click="toggleCode"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-code="">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 16 4-4-4-4"></path>
            <path d="m6 8-4 4 4 4"></path>
            <path d="m14.5 4-5 16"></path>
          </svg>
        </button>

        <button
          type="button"
          @click="setHorizontalRule"
          class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-editor-code="">

          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path fill-rule="evenodd" d="M4.25 12a.75.75 0 0 1 .75-.75h14a.75.75 0 0 1 0 1.5H5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
          </svg>

        </button>

      </div>

      <!-- Editor content -->
      <EditorContent
        id="hs-editor-tiptap"
        :editor="editor"
        class="h-[10rem] overflow-auto px-4 pt-1 dark:text-neutral-200"
        data-hs-editor-field="" />
    </div>

  </div>

</template>

<style lang="scss">
/* Basic editor styles */
.tiptap {
  :first-child {
    margin-top: 0;
  }

  hr {
    border: none;
    cursor: pointer;
    margin: 2rem 0;

    // Replace @apply with regular border styles
    border-top: 1px solid #b9bcc3; // Equivalent to border-t-gray-600 in Tailwind
    @media (prefers-color-scheme: dark) {
      border-top: 1px solid #d1d5db; // Equivalent to dark:border-t-gray-300
    }

    &.ProseMirror-selectednode {
      border-top: 1px solid red;
    }
  }
}
</style>
