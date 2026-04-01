<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const TYPE_META = {
    banner: { color: "#f59e0b", bg: "rgba(245,158,11,0.12)", label: "Banner" },
    card: { color: "#6366f1", bg: "rgba(99,102,241,0.12)", label: "Card" },
    list: { color: "#10b981", bg: "rgba(16,185,129,0.12)", label: "List" },
    stats: { color: "#ec4899", bg: "rgba(236,72,153,0.12)", label: "Stats" },
};

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
    blocks: { type: Array, default: () => [] },
});

const activeBlocks = computed(() => {
    return [...props.blocks]
        .filter((block) => block.status)
        .sort((a, b) => a.order - b.order);
});
</script>

<template>
    <Head title="Welcome" />

    <div
        class="bg-gray-50 text-black/75 dark:bg-black dark:text-white/65 min-h-screen"
    >
        <div class="mx-auto w-full max-w-7xl px-6 py-10">
            <header
                class="mb-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1
                        class="text-3xl font-bold text-slate-900 dark:text-white"
                    >
                        Dynamic UI Blocks
                    </h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">
                        Admin-configured blocks control this client-side page.
                    </p>
                </div>

                <nav class="flex gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-md border px-3 py-2 text-sm font-medium text-slate-700 dark:text-white"
                        >Dashboard</Link
                    >
                    <Link
                        v-else
                        :href="route('login')"
                        class="rounded-md border px-3 py-2 text-sm font-medium text-slate-700 dark:text-white"
                        >Log in</Link
                    >
                    <Link
                        v-if="canRegister && !$page.props.auth.user"
                        :href="route('register')"
                        class="rounded-md border px-3 py-2 text-sm font-medium text-slate-700 dark:text-white"
                        >Register</Link
                    >
                    <Link
                        :href="route('admin.blocks.index')"
                        class="rounded-md border px-3 py-2 text-sm font-medium text-slate-700 dark:text-white"
                        >Admin edit</Link
                    >
                </nav>
            </header>

            <main class="space-y-6">
                <div
                    v-if="activeBlocks.length === 0"
                    class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center dark:border-white/20 dark:bg-white/5"
                >
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        No active UI blocks are configured. Use admin panel to
                        add blocks.
                    </p>
                </div>

                <section
                    v-for="block in activeBlocks"
                    :key="block.id"
                    class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-zinc-900"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <span
                            class="text-xs font-semibold uppercase tracking-wide"
                            :style="{
                                color: TYPE_META[block.type]?.color || '#999',
                            }"
                            >{{
                                TYPE_META[block.type]?.label || block.type
                            }}</span
                        >
                        <span class="text-xs text-gray-500 dark:text-gray-400"
                            >Order #{{ block.order }}</span
                        >
                    </div>

                    <div v-if="block.type === 'banner'" class="space-y-2">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ block.config?.headline || "Banner Headline" }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{
                                block.config?.subtitle ||
                                "Banner subtitle listed here."
                            }}
                        </p>
                        <button
                            class="rounded-md bg-blue-600 px-4 py-2 text-white"
                        >
                            {{ block.config?.cta || "Learn More" }}
                        </button>
                    </div>

                    <div
                        v-else-if="block.type === 'stats'"
                        class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <div
                            v-for="(item, idx) in block.config?.items || []"
                            :key="idx"
                            class="rounded-lg border border-gray-200 p-4 dark:border-white/10"
                        >
                            <div
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ item.split(" ")[0] }}
                            </div>
                            <div
                                class="text-xs text-gray-600 dark:text-gray-400"
                            >
                                {{ item.split(" ").slice(1).join(" ") }}
                            </div>
                        </div>
                    </div>

                    <div
                        v-else-if="block.type === 'card'"
                        class="grid gap-3 sm:grid-cols-2"
                    >
                        <div
                            v-for="(item, idx) in block.config?.cards || []"
                            :key="idx"
                            class="rounded-lg border border-gray-200 p-4 dark:border-white/10 dark:bg-zinc-800"
                        >
                            <div
                                class="font-semibold text-gray-900 dark:text-white"
                            >
                                {{ item }}
                            </div>
                        </div>
                    </div>

                    <div v-else-if="block.type === 'list'" class="space-y-2">
                        <ul
                            class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-300"
                        >
                            <li
                                v-for="(item, idx) in block.config?.items || []"
                                :key="idx"
                            >
                                {{ item }}
                            </li>
                        </ul>
                    </div>

                    <div
                        v-else
                        class="text-sm text-gray-500 dark:text-gray-400"
                    >
                        Unknown block type.
                    </div>
                </section>
            </main>

            <footer
                class="mt-12 text-center text-sm text-gray-500 dark:text-gray-400"
            >
                Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </footer>
        </div>
    </div>
</template>
