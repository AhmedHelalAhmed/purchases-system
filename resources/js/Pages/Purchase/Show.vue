<script setup>
import { Head } from "@inertiajs/vue3";
import { mdiTableBorder } from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import PurchaseForm from "@/Components/PurchaseForm.vue";
defineProps({
  purchase: {
    type: Object,
    default: null,
  },
  customers: {
    type: Array,
    default: [],
  },
  products: {
    type: Array,
    default: [],
  },
});
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Purchase details" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiTableBorder"
        title="Purchase details"
        main
      >
      </SectionTitleLineWithButton>
      <div
        v-if="$page.props.flash.message"
        class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
        role="alert"
      >
        <span class="font-medium">
          {{ $page.props.flash.message }}
        </span>
      </div>

      <CardBox class="mb-6" has-table>
        <PurchaseForm
          :is-show="true"
          :purchase="purchase"
          :customers="customers"
          :products="products"
        />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
