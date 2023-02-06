<script setup>
import { Head, Link } from "@inertiajs/vue3";
import {
  mdiMonitorCellphone,
  mdiTableBorder,
  mdiTableOff,
  mdiTypewriter,
} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import NotificationBar from "@/Components/NotificationBar.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import BaseButton from "@/Components/BaseButton.vue";
import TablePurchases from "@/Components/TablePurchases.vue";

defineProps({
  purchases: {
    type: Object,
    default: {
      data: [],
    },
  },
});
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Purchases" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Purchases" main>
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
      <NotificationBar v-if="false" color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>
      <NotificationBar v-if="false" color="danger" :icon="mdiTableOff">
        <b>Empty table.</b> When there's nothing to show
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <div class="md:text-right p-2">
          <Link :href="route('purchases.create')">
            <BaseButton
              :icon="mdiTypewriter"
              color="success"
              label="Add new purchase"
              :small="true"
              :outline="true"
            />
          </Link>
        </div>

        <TablePurchases checkable :purchases="purchases" />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
