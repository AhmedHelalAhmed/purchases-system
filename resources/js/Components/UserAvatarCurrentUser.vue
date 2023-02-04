<script setup>
import { useMainStore } from "@/Stores/main";
import UserAvatar from "@/Components/UserAvatar.vue";
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const mainStore = useMainStore();
const userName = computed(() => usePage().props.value.auth.user.name)

</script>

<template>
    <template>
        <UserAvatar
            :username="userName"
            api="initials"
        />
    </template>
</template>
