<script setup>
import { ref, computed, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const BLOCK_TYPES = [
  { key: 'banner', label: 'Banner', color: '#f59e0b' },
  { key: 'card', label: 'Card', color: '#6366f1' },
  { key: 'list', label: 'List', color: '#10b981' },
  { key: 'stats', label: 'Stats', color: '#ec4899' },
];

const TYPE_META = {
  banner: { placeholder: { headline: 'Headline', subtitle: 'Subtitle', cta: 'Call to action' } },
  card: { placeholder: { cards: ['First feature', 'Second feature'] } },
  list: { placeholder: { items: ['Item A', 'Item B'] } },
  stats: { placeholder: { items: ['100 Users', '99.9% Uptime'] } },
};

const props = defineProps({ blocks: { type: Array, default: () => [] } });

const blocks = ref([...props.blocks]);
const metadata = ref({});
const editing = ref(null);
const modal = ref(false);

const activeCount = computed(() => blocks.value.filter(item => item.status).length);

watch(
  () => props.blocks,
  () => { blocks.value = [...props.blocks]; },
);

function openModal(block = null) {
  if (block) {
    editing.value = { ...block, config: { ...(block.config || {}), } };
  } else {
    editing.value = {
      title: '',
      type: 'banner',
      status: true,
      config: TYPE_META.banner.placeholder,
    };
  }
  modal.value = true;
}

function closeModal() {
  modal.value = false;
  editing.value = null;
}

function storeBlock() {
  if (!editing.value?.title?.trim()) return;

  if (editing.value.type === 'list' || editing.value.type === 'stats') {
    const items = (editing.value.config?.itemsString || '').split('\n').map(i => i.trim()).filter(Boolean);
    editing.value.config = { items };
  }

  if (editing.value.type === 'card') {
    const cards = (editing.value.config?.cardsString || '').split('\n').map(i => i.trim()).filter(Boolean);
    editing.value.config = { cards };
  }

  if (!editing.value.config) {
    editing.value.config = TYPE_META[editing.value.type]?.placeholder || {};
  }

  const payload = {
    title: editing.value.title,
    type: editing.value.type,
    status: editing.value.status,
    config: editing.value.config,
  };

  if (editing.value.id) {
    Inertia.patch(route('admin.blocks.update', editing.value.id), { ...payload, order: editing.value.order || 0 }, {
      preserveState: true,
      onSuccess: page => {
        refreshBlocks(page.props.blocks || []);
        closeModal();
      },
    });
    return;
  }

  Inertia.post(route('admin.blocks.store'), payload, {
    preserveState: true,
    onSuccess: page => {
      refreshBlocks(page.props.blocks || []);
      closeModal();
    },
  });
}

function deleteBlock(block) {
  if (!confirm('Delete this block?')) return;
  Inertia.delete(route('admin.blocks.destroy', block.id), {
    preserveState: true,
    onSuccess: page => refreshBlocks(page.props.blocks || []),
  });
}

function toggleStatus(block) {
  Inertia.patch(route('admin.blocks.update', block.id), {
    ...block,
    status: !block.status,
  }, {
    preserveState: true,
    onSuccess: page => refreshBlocks(page.props.blocks || []),
  });
}

function reorderBlocks(from, to) {
  if (from === to) return;
  const cloned = [...blocks.value];
  const [removed] = cloned.splice(from, 1);
  cloned.splice(to, 0, removed);
  cloned.forEach((block, index) => { block.order = index + 1; });

  Inertia.post(route('admin.blocks.reorder'), {
    order: cloned.map(b => b.id),
  }, {
    preserveState: true,
    onSuccess: page => refreshBlocks(page.props.blocks || []),
  });
}

function refreshBlocks(newBlocks) {
  if (newBlocks.length) {
    blocks.value = [...newBlocks];
    return;
  }
  // If no new blocks in props, stay with local updates.
  blocks.value = [...blocks.value].sort((a, b) => a.order - b.order);
}

function blockColor(type) {
  return BLOCK_TYPES.find(t => t.key === type)?.color || '#999';
}

function setConfigValue(key, value) {
  editing.value.config = { ...(editing.value.config || {}), [key]: value };
}
</script>

<template>
  <div class="p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold">Admin UI Blocks</h1>
        <p class="text-sm text-slate-500">Changes here control the public Welcome page layout.</p>
      </div>
      <button @click="openModal" class="px-4 py-2 rounded bg-blue-600 text-white">Add block</button>
    </div>

    <div class="grid gap-3 bg-slate-800 rounded-xl p-3">
      <div class="text-sm text-slate-100">Active blocks: {{ activeCount }} / {{ blocks.length }}</div>
      <template v-if="blocks.length">
        <div v-for="(block, idx) in blocks" :key="block.id" class="flex items-center gap-3 p-3 rounded border border-slate-700 bg-slate-900">
          <div class="w-2 h-8 rounded" :style="{ background: blockColor(block.type) }"></div>
          <div class="grow">
            <div class="font-semibold text-sm">{{ block.title }} <span class="text-xs text-slate-400">({{ block.type }})</span></div>
            <div class="text-xs text-slate-400">Order {{ block.order }} · {{ block.status ? 'Active' : 'Inactive' }}</div>
          </div>
          <button @click="toggleStatus(block)" class="px-2 py-1 text-xs rounded border" :class="block.status ? 'text-emerald-400' : 'text-slate-400'">
            {{ block.status ? 'Disable' : 'Enable' }}
          </button>
          <button @click="openModal(block)" class="px-2 py-1 text-xs rounded border text-indigo-300">Edit</button>
          <button @click="deleteBlock(block)" class="px-2 py-1 text-xs rounded border text-rose-400">Delete</button>
          <button v-if="idx > 0" @click="reorderBlocks(idx, idx - 1)" class="px-2 py-1 text-xs rounded border">↑</button>
          <button v-if="idx < blocks.length - 1" @click="reorderBlocks(idx, idx + 1)" class="px-2 py-1 text-xs rounded border">↓</button>
        </div>
      </template>
      <div v-else class="py-6 text-center text-sm text-slate-400">No blocks configured yet. Create one to power the client layout.</div>
    </div>
  </div>

  <div v-if="modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 px-4">
    <div class="bg-slate-900 border border-slate-700 rounded-xl p-5 w-full max-w-xl">
      <h2 class="text-lg font-semibold mb-3">{{ editing.id ? 'Edit block' : 'Create block' }}</h2>

      <div class="mb-3">
        <label class="block text-xs text-slate-400">Title</label>
        <input v-model="editing.title" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700" />
      </div>

      <div class="mb-3">
        <label class="block text-xs text-slate-400">Type</label>
        <select v-model="editing.type" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700">
          <option v-for="type in BLOCK_TYPES" :key="type.key" :value="type.key">{{ type.label }}</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="inline-flex items-center gap-2 text-xs text-slate-400">
          <input type="checkbox" v-model="editing.status" class="form-checkbox" /> Active
        </label>
      </div>

      <div class="mb-3">
        <label class="block text-xs text-slate-400">Configuration</label>

        <template v-if="editing.type === 'banner'">
          <div class="space-y-2">
            <input v-model="editing.config.headline" placeholder="Headline" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700" />
            <input v-model="editing.config.subtitle" placeholder="Subtitle" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700" />
            <input v-model="editing.config.cta" placeholder="Button text" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700" />
          </div>
        </template>

        <template v-if="editing.type === 'list' || editing.type === 'stats'">
          <textarea v-model="editing.config.itemsString" rows="4" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700" placeholder="One item per line"></textarea>
        </template>

        <template v-if="editing.type === 'card'">
          <textarea v-model="editing.config.cardsString" rows="4" class="w-full rounded px-2 py-1 bg-slate-800 border border-slate-700" placeholder="One card title per line"></textarea>
        </template>
      </div>

      <div class="flex justify-end gap-2">
        <button @click="closeModal" class="px-3 py-1 rounded border bg-slate-700">Cancel</button>
        <button @click="storeBlock" class="px-3 py-1 rounded bg-blue-600 text-white">Save</button>
      </div>
    </div>
  </div>
</template>

