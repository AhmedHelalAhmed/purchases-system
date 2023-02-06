<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import FormControl from "@/Components/FormControl.vue";
import FormField from "@/Components/FormField.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import { mdiAccount, mdiEmail, mdiFormTextboxPassword } from "@mdi/js";
import { computed, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const CUSTOMER_ROLE = 2;
let action = "store";
const form = useForm({
  name: "",
  email: "",
  username: "",
  password: "",
  password_confirmation: "",
  role: "",
  mobile: "",
  nationality_id: "",
});

const props = defineProps({
  nationalities: {
    type: Array,
    default: [],
  },
  roles: {
    type: Array,
    default: [],
  },
  user: {
    type: Object,
    default: null,
  },
});

let updateEndpoint, storeEndpoint;

onMounted(() => {
  if (props.user) {
    form.name = props.user.name;
    form.email = props.user.email;
    form.username = props.user.username;
    form.role = props.user.originalRole;
    form.nationality_id = props.user.profile?.nationality_id;
    form.mobile = props.user.profile?.mobile;
    action = "update";
    updateEndpoint = computed(() =>
      form.role === CUSTOMER_ROLE
        ? route(`customers.update`, props.user.id)
        : route(`admins.update`, props.user.id)
    );
  } else {
    action = "store";
    storeEndpoint = computed(() =>
      form.role === CUSTOMER_ROLE
        ? route(`customers.store`)
        : route(`admins.store`)
    );
  }
});
const submit = () => {
  if (action === "store") {
    form.post(storeEndpoint.value, {
      onFinish: () => form.reset("password", "password_confirmation"),
    });
  } else {
    form.put(updateEndpoint.value, {
      onFinish: () => form.reset("password", "password_confirmation"),
    });
  }
};
</script>

<template class="overflow-auto">
  <SectionMain>
    <CardBox form @submit.prevent="submit">
      <FormField help="Please enter your name" label="Name" label-for="name">
        <FormControl
          id="name"
          v-model="form.name"
          :icon="mdiAccount"
          autocomplete="name"
          required
          type="text"
        />
      </FormField>
      <InputError :message="form.errors.name" class="mt-1 mb-2" />

      <FormField help="Please enter your email" label="Email" label-for="email">
        <FormControl
          id="email"
          v-model="form.email"
          :icon="mdiEmail"
          autocomplete="email"
          required
          type="email"
        />
      </FormField>
      <InputError :message="form.errors.email" class="mt-1 mb-2" />

      <BaseDivider />

      <FormField
        help="Please enter your username"
        label="Username"
        label-for="username"
      >
        <FormControl
          id="username"
          v-model="form.username"
          :icon="mdiAccount"
          autocomplete="username"
          required
          type="text"
        />
      </FormField>
      <InputError :message="form.errors.username" class="mt-1 mb-2" />

      <FormField
        help="Please enter new password"
        label="Password"
        label-for="password"
      >
        <FormControl
          id="password"
          v-model="form.password"
          :icon="mdiFormTextboxPassword"
          autocomplete="new-password"
          required
          type="password"
        />
      </FormField>
      <InputError :message="form.errors.password" class="mt-1 mb-2" />

      <FormField
        help="Please confirm your password"
        label="Confirm Password"
        label-for="password_confirmation"
      >
        <FormControl
          id="password_confirmation"
          v-model="form.password_confirmation"
          :icon="mdiFormTextboxPassword"
          autocomplete="new-password"
          required
          type="password"
        />
      </FormField>
      <InputError
        :message="form.errors.password_confirmation"
        class="mt-1 mb-2"
      />

      <BaseDivider />

      <FormField label="Role">
        <FormControl v-model="form.role" :options="roles" />
      </FormField>
      <InputError :message="form.errors.role" class="mt-1 mb-2" />

      <FormField
        v-if="form.role === CUSTOMER_ROLE"
        help="Do not enter the leading zero"
        label="Contact number"
      >
        <FormControl
          v-model="form.mobile"
          placeholder="Your phone number or mobile"
          type="tel"
        />
      </FormField>
      <InputError :message="form.errors.mobile" class="mt-1 mb-2" />

      <FormField v-if="form.role === CUSTOMER_ROLE" label="Nationality">
        <FormControl v-model="form.nationality_id" :options="nationalities" />
      </FormField>
      <InputError :message="form.errors.nationality_id" class="mt-1 mb-2" />

      <template #footer>
        <BaseButtons>
          <BaseButton color="info" label="Save" @click="submit" />
          <BaseButton
            :href="route('users.index')"
            color="danger"
            label="Back"
            outline
            type="button"
          />
        </BaseButtons>
      </template>
    </CardBox>
  </SectionMain>
</template>
