<script setup>
import {ref} from "vue";
import {mdiBookOpen, mdiTrashCan, mdiTypewriter} from "@mdi/js";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import Pagination from "@/Components/Pagination.vue";
import {useForm} from "@inertiajs/vue3";

defineProps({
  checkable: Boolean,
  users: Object,
});

const isModalActive = ref(false);
const selectUser = ref(null);
const isModalDangerActive = ref(false);
const checkedRows = ref([]);
const userFormTitle = ref("");
const remove = (arr, cb) => {
  const newArr = [];

  arr.forEach((item) => {
    if (!cb(item)) {
      newArr.push(item);
    }
  });

  return newArr;
};

const checked = (isChecked, client) => {
  if (isChecked) {
    checkedRows.value.push(client);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === client.id
    );
  }
};
const formDelete = useForm({})

const deleteConfirm = (userId) => {
  formDelete.delete(route('users.destroy', userId));
};
const isFormModalActive = ref(false);





</script>

<template>

  <CardBoxModal v-model="isModalActive" title="Sample modal">
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <CardBoxModal
    @confirm="deleteConfirm(selectUser?.id)"
    v-model="isModalDangerActive"
    title="Please confirm"
    button="danger"
    has-cancel
    buttonLabel="Delete"
  >
    <p>Are you sure you want to delete <b>{{ selectUser?.name }}</b></p>
  </CardBoxModal>

  <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
    <span
      v-for="checkedRow in checkedRows"
      :key="checkedRow.id"
      class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
    >
      {{ checkedRow.name }}
    </span>
  </div>

  <table>
    <thead>
    <tr>
      <th>Name</th>
      <th>Username</th>
      <th>Mobile</th>
      <th>Role</th>
      <th>Nationality</th>
      <th>Created</th>
      <th/>
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
      <td data-label="Created" class="lg:w-1 whitespace-nowrap">
        <small
          class="text-gray-500 dark:text-slate-400"
          :title="user.created_at"
        >{{ user.created_at }}</small
        >
      </td>
      <td class="before:hidden lg:w-1 whitespace-nowrap">
        <BaseButtons type="justify-start lg:justify-end" no-wrap>

          <BaseButton
            :href="route('users.edit',user.id)"
            color="info"
            :icon="mdiBookOpen"
            small
          />

          <BaseButton
            color="danger"
            :icon="mdiTrashCan"
            small
            @click="isModalDangerActive = true;selectUser=user "
          />
        </BaseButtons>
      </td>
    </tr>
    </tbody>
  </table>

  <div v-if="!users.data.length" class="mt-6 border shadow-lg py-10 px-4 font-bold text-center">
    No users
  </div>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <Pagination :data="users"/>
  </div>
</template>
