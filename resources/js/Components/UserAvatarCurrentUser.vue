<script setup>
import UserAvatar from "@/Components/UserAvatar.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
const userName = computed(() => usePage().props.auth.user.name);
</script>

<template>
  <template>
    <UserAvatar :username="userName" api="initials" />
  </template>
</template>
