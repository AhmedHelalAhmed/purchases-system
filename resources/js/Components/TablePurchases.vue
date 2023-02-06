<script setup>
import {ref} from "vue";
import {mdiBookOpen, mdiBullseye, mdiTrashCan} from "@mdi/js";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import Pagination from "@/Components/Pagination.vue";
import {useForm} from "@inertiajs/vue3";

defineProps({
  checkable: Boolean,
  purchases: Object,
});

const isModalActive = ref(false);
const selectPurchase = ref(null);
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

const deleteConfirm = (purchaseId) => {
  formDelete.delete(route('purchases.destroy', purchaseId));
};
const isFormModalActive = ref(false);

</script>

<template>

  <CardBoxModal v-model="isModalActive" title="Sample modal">
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <CardBoxModal
    @confirm="deleteConfirm(selectPurchase?.id)"
    v-model="isModalDangerActive"
    title="Please confirm"
    button="danger"
    has-cancel
    buttonLabel="Delete"
  >
    <p>Are you sure you want to delete purchase with code <b>{{ selectPurchase?.code }}</b> ?</p>
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
      <th>Code</th>
      <th>Customer Name</th>
      <th>Total</th>
      <th>Created</th>
      <th>Updated</th>
      <th/>
    </tr>
    </thead>
    <tbody>
    <tr v-for="purchase in purchases.data" :key="purchase.id">
      <td data-label="Purchase Code">
        {{ purchase.code }}
      </td>
      <td data-label="Customer Name">
        {{ purchase.user.name }}
      </td>
      <td data-label="Total">
        {{ purchase.total }}
      </td>
      <td data-label="Created" class="lg:w-1 whitespace-nowrap">
        <small
          class="text-gray-500 dark:text-slate-400"
          :title="purchase.created_at"
        >{{ purchase.created_at }}</small
        >
      </td>
      <td data-label="Created" class="lg:w-1 whitespace-nowrap">
        <small
          class="text-gray-500 dark:text-slate-400"
          :title="purchase.updated_at"
        >{{ purchase.updated_at }}</small
        >
      </td>
      <td class="before:hidden lg:w-1 whitespace-nowrap">
        <BaseButtons type="justify-start lg:justify-end" no-wrap>

          <BaseButton
            :href="route('purchases.show',purchase.id)"
            color="info"
            outline
            :icon="mdiBullseye"
            small
          />

          <BaseButton
            :href="route('purchases.edit',purchase.id)"
            color="warning"
            :icon="mdiBookOpen"
            small
          />

          <BaseButton
            color="danger"
            :icon="mdiTrashCan"
            small
            @click="isModalDangerActive = true;selectPurchase=purchase "
          />
        </BaseButtons>
      </td>
    </tr>
    </tbody>
  </table>

  <div v-if="!purchases.data.length" class="mt-6 border shadow-lg py-10 px-4 font-bold text-center">
    No purchases
  </div>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <Pagination :data="purchases"/>
  </div>
</template>
