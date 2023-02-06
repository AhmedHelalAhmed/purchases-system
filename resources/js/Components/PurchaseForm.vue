<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import FormControl from "@/Components/FormControl.vue";
import FormField from "@/Components/FormField.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import { mdiOpenInNew, mdiPlus, mdiRename, mdiTrashCan } from "@mdi/js";
import { computed, onMounted, ref, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import SearchDropdown from "search-dropdown-vue";
import _ from "lodash";
import axios from "axios";
import CardBoxModal from "@/Components/CardBoxModal.vue";

const ERROR_MESSAGE = "Something went wrong! please try again later";

const ADMIN_ROLE = "Admin";
const form = useForm({
  user_id: "",
  total: "",
  purchases_items: [],
});
const props = defineProps({
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
  isShow: {
    type: Boolean,
    default: false,
  },
});
const currentUserRole = computed(() => usePage().props.auth.user.role);

onMounted(() => {
  if (props.purchase?.id) {
    form.user_id = props.purchase.user_id;
    form.total = props.purchase.total;
    form.purchases_items = props.purchase.purchases_items;
    customers.value = props.customers;
    products.value = props.products;
    watch(form.purchases_items, (currentValue) => {
      let total = 0;
      for (const purchaseRecord of currentValue) {
        purchaseRecord.unit_price = round(purchaseRecord.unit_price);
        purchaseRecord.quantity = round(purchaseRecord.quantity);
        purchaseRecord.total = round(
          purchaseRecord.unit_price * purchaseRecord.quantity
        );
        total += purchaseRecord.total;
      }
      form.total = total;
    });
  }
});
const submit = () => {
  if (!props.purchase) {
    form.post(route("purchases.store"));
  } else {
    form.put(route("purchases.update", props.purchase.id));
  }
};

const addPurchaseItem = () => {
  form.purchases_items.push({
    product_id: "",
    quantity: 0,
    unit_price: 0,
    total: 0,
  });
};
const removePurchaseItem = (index) => {
  form.purchases_items.splice(index, 1);
};
const customers = ref([]);
const products = ref([]);
const status = ref(null);
const message = ref("");

const SEARCH_DEBOUNCE_TIME_OUT = 1000;

const mergeArraysOfObjects = (init, toMerge) => {
  const ids = new Set(init.map((item) => item.id));
  return [...init, ...toMerge.filter((item) => !ids.has(item.id))];
};

const searchForCustomers = _.debounce((customerSearchTerm) => {
  if (customerSearchTerm.trim()) {
    axios
      .get(route("customers.index", { name: customerSearchTerm }))
      .then((response) => {
        if (response.data.customers.length) {
          customers.value = mergeArraysOfObjects(
            customers.value,
            response.data.customers
          );
        }
      });
  }
}, SEARCH_DEBOUNCE_TIME_OUT);

const onSelectedCustomer = (customer) => {
  if (customer.id) {
    form.user_id = customer.id;
  }
};

const searchForProducts = _.debounce((productSearchTerm) => {
  if (productSearchTerm.trim()) {
    axios
      .get(route("products.index", { name: productSearchTerm }))
      .then((response) => {
        if (response.data.products.length) {
          products.value = mergeArraysOfObjects(
            products.value,
            response.data.products
          );
        }
      });
  }
}, SEARCH_DEBOUNCE_TIME_OUT);
const isModalCreateProductActive = ref(false);
const productName = ref("");
const onSelectedProduct = (product, index) => {
  if (product.id) {
    form.purchases_items[index].product_id = product.id;
  }
};
const createProduct = () => {
  if (productName.value.trim()) {
    axios
      .post(route("products.store"), {
        name: productName.value.trim(),
      })
      .then((response) => {
        if (response.status === 200) {
          productName.value = "";
          message.value = response.data.message;
          status.value = true;
        } else {
          message.value = ERROR_MESSAGE;
          status.value = false;
        }
      });
  }
};
const round = (num, decimalPlaces = 2) => {
  const power = Math.pow(10, decimalPlaces);
  return Math.round(num * power) / power;
};

watch(form.purchases_items, (currentValue) => {
  let total = 0;
  for (const purchaseRecord of currentValue) {
    purchaseRecord.unit_price = round(purchaseRecord.unit_price);
    purchaseRecord.quantity = round(purchaseRecord.quantity);
    purchaseRecord.total = round(
      purchaseRecord.unit_price * purchaseRecord.quantity
    );
    total += purchaseRecord.total;
  }
  form.total = total;
});
</script>

<template class="overflow-auto">
  <SectionMain>
    <CardBoxModal v-model="status" title="Info" has-cancel :has-button="false">
      <p>{{ message }}</p>
    </CardBoxModal>
    <CardBoxModal
      v-model="isModalCreateProductActive"
      title="Create Product"
      has-cancel
      button-label="Save"
      @confirm="createProduct"
    >
      <FormField
        label="Product name"
        label-for="product-name"
        help="Enter product name"
      >
        <FormControl
          id="product-name"
          v-model="productName"
          :icon="mdiRename"
          type="text"
          required
        />
      </FormField>
    </CardBoxModal>

    <CardBox form @submit.prevent="submit">
      <p v-if="isShow && customers[0]" class="pb-3 pt-3 mt-3 mb-3">
        Customer: {{ customers[0]["name"] }}
      </p>
      <div v-if="ADMIN_ROLE === currentUserRole">
        <FormField
          v-if="!isShow"
          label="Customer"
          label-for="customer-name"
          help="Please select the customer"
        >
          <SearchDropdown
            id="customer-name"
            :placeholder="
              customers[0]
                ? customers[0]['name']
                : 'Search for a customer and select it'
            "
            :options="customers"
            :value="form.user_id"
            @filter="searchForCustomers"
            @selected="onSelectedCustomer"
          >
          </SearchDropdown>
        </FormField>
        <InputError class="mt-1 mb-2" :message="form.errors.user_id" />
        <BaseDivider />
      </div>

      <BaseButtons v-if="!isShow" type="justify-start lg:justify-end" no-wrap>
        <BaseButton
          label="Add new product"
          color="warning"
          :icon="mdiOpenInNew"
          small
          @click="isModalCreateProductActive = true"
        />
        <BaseButton
          label="Add purchase record"
          color="success"
          :icon="mdiPlus"
          small
          @click="addPurchaseItem"
        />
      </BaseButtons>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th v-if="!isShow" />
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in form.purchases_items" :key="index">
            <td data-label="Product">
              <p v-if="isShow">
                {{
                  products.filter(
                    (product) => product.id === item.product_id
                  )[0]["name"]
                }}
              </p>
              <SearchDropdown
                v-if="!isShow"
                :placeholder="
                  item.product_id
                    ? products.filter(
                        (product) => product.id === item.product_id
                      )[0]['name']
                    : 'Search for the product then select'
                "
                :options="products"
                @filter="searchForProducts"
                @selected="onSelectedProduct($event, index)"
              >
              </SearchDropdown>
            </td>
            <td data-label="Quantity">
              <p v-if="isShow">{{ item.quantity }}</p>
              <FormControl
                v-if="!isShow"
                v-model="item.quantity"
                type="number"
                required
              />
            </td>
            <td data-label="Unit Price">
              <p v-if="isShow">{{ item.unit_price }}</p>
              <FormControl
                v-if="!isShow"
                v-model="item.unit_price"
                type="number"
                required
              />
            </td>
            <td data-label="Total">
              <p>{{ item.total }}</p>
            </td>
            <td v-if="!isShow" class="before:hidden lg:w-1 whitespace-nowrap">
              <BaseButtons type="justify-start lg:justify-end" no-wrap>
                <BaseButton
                  color="danger"
                  :icon="mdiTrashCan"
                  small
                  @click="removePurchaseItem(index)"
                />
              </BaseButtons>
            </td>
          </tr>
        </tbody>
      </table>
      <div
        v-if="form.purchases_items.length"
        class="mt-6 border shadow-lg py-10 px-4 font-bold text-center"
      >
        Total: {{ form.total }}
      </div>
      <div
        v-if="!form.purchases_items.length"
        class="mt-6 border shadow-lg py-10 px-4 font-bold text-center"
      >
        No products added to the purchase
      </div>
      <template #footer>
        <BaseButtons>
          <BaseButton
            v-if="!isShow"
            color="info"
            label="Save"
            @click="submit"
          />
          <BaseButton
            type="button"
            :href="route('purchases.index')"
            color="info"
            outline
            label="Back"
          />
        </BaseButtons>
      </template>
    </CardBox>
  </SectionMain>
</template>
