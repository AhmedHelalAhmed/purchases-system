<script setup>
import { ref } from "vue";
import { mdiBookOpen, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
  checkable: Boolean,
  users: {
    type: Object,
    default: {
      data: [],
    },
  },
});

const selectUser = ref(null);
const isModalDangerActive = ref(false);

const formDelete = useForm({});

const deleteConfirm = (userId) => {
  formDelete.delete(route("users.destroy", userId));
};
</script>

<template>
  <CardBoxModal
    v-model="isModalDangerActive"
    button="danger"
    button-label="Delete"
    has-cancel
    title="Please confirm"
    @confirm="deleteConfirm(selectUser?.id)"
  >
    <p>
      Are you sure you want to delete <b>{{ selectUser?.name }}</b> ?
    </p>
  </CardBoxModal>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Mobile</th>
        <th>Role</th>
        <th>Nationality</th>
        <th>Created</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users.data" :key="user.id">
        <td data-label="Name">
          {{ user.name }}
        </td>
        <td data-label="Username">
          {{ user.username }}
        </td>
        <td data-label="Mobile">
          {{ user.profile?.mobile }}
        </td>
        <td data-label="Mobile">
          {{ user.role }}
        </td>
        <td data-label="Nationality">
          {{ user.profile?.nationality?.name }}
        </td>
        <td class="lg:w-1 whitespace-nowrap" data-label="Created">
          <small
            :title="user.created_at"
            class="text-gray-500 dark:text-slate-400"
            >{{ user.created_at }}</small
          >
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons no-wrap type="justify-start lg:justify-end">
            <BaseButton
              :href="route('users.edit', user.id)"
              :icon="mdiBookOpen"
              color="warning"
              small
            />

            <BaseButton
              :icon="mdiTrashCan"
              color="danger"
              small
              @click="
                isModalDangerActive = true;
                selectUser = user;
              "
            />
          </BaseButtons>
        </td>
      </tr>
    </tbody>
  </table>

  <div
    v-if="!users.data.length"
    class="mt-6 border shadow-lg py-10 px-4 font-bold text-center"
  >
    No users
  </div>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <Pagination :data="users" />
  </div>
</template>
