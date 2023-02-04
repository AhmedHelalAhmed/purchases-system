<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import FormControl from "@/Components/FormControl.vue";
import FormField from "@/Components/FormField.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import {mdiAccount, mdiEmail, mdiFormTextboxPassword} from "@mdi/js";
import {computed, onMounted} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const CUSTOMER_ROLE = 2;
let action = 'store';
const form = useForm({
  name: '',
  email: '',
  username: '',
  password: '',
  password_confirmation: '',
  role: '',
  mobile: '',
  nationality_id: '',
});


const props = defineProps({
  nationalities: Array,
  roles: Array,
  user: {
    type: Object,
    default: null
  }
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
    action = 'update';
    updateEndpoint = computed(() =>
      form.role === CUSTOMER_ROLE ? route(`customers.update`, props.user.id) : route(`admins.update`, props.user.id)
    );
  } else {
    action = 'store';
    storeEndpoint = computed(() =>
      form.role === CUSTOMER_ROLE ? route(`customers.store`) : route(`admins.store`)
    );
  }
});
const submit = () => {
  if (action === 'store') {
    form.post(storeEndpoint.value, {
      onFinish: () => form.reset('password', 'password_confirmation'),
    });
  } else {
    form.put(updateEndpoint.value, {
      onFinish: () => form.reset('password', 'password_confirmation'),
    });
  }
}


</script>

<template class="overflow-auto">
  <SectionMain>
    <CardBox form @submit.prevent="submit">
      <FormField
        label="Name"
        label-for="name"
        help="Please enter your name"
      >
        <FormControl
          v-model="form.name"
          id="name"
          :icon="mdiAccount"
          autocomplete="name"
          type="text"
          required
        />

      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.name"/>


      <FormField
        label="Email"
        label-for="email"
        help="Please enter your email"
      >
        <FormControl
          v-model="form.email"
          id="email"
          :icon="mdiEmail"
          autocomplete="email"
          type="email"
          required
        />

      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.email"/>

      <BaseDivider/>

      <FormField
        label="Username"
        label-for="username"
        help="Please enter your username"
      >
        <FormControl
          v-model="form.username"
          id="username"
          :icon="mdiAccount"
          autocomplete="username"
          type="text"
          required
        />

      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.username"/>

      <FormField
        label="Password"
        label-for="password"
        help="Please enter new password"
      >
        <FormControl
          v-model="form.password"
          id="password"
          :icon="mdiFormTextboxPassword"
          type="password"
          autocomplete="new-password"
          required
        />
      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.password"/>

      <FormField
        label="Confirm Password"
        label-for="password_confirmation"
        help="Please confirm your password"
      >
        <FormControl
          v-model="form.password_confirmation"
          id="password_confirmation"
          :icon="mdiFormTextboxPassword"
          type="password"
          autocomplete="new-password"
          required
        />
      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.password_confirmation"/>

      <BaseDivider/>

      <FormField label="Role">
        <FormControl v-model="form.role" :options="roles"/>

      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.role"/>

      <FormField v-if="form.role===CUSTOMER_ROLE" label="Contact number" help="Do not enter the leading zero">
        <FormControl
          v-model="form.mobile"
          type="tel"
          placeholder="Your phone number or mobile"
        />

      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.mobile"/>

      <FormField v-if="form.role===CUSTOMER_ROLE" label="Nationality">
        <FormControl v-model="form.nationality_id	" :options="nationalities"/>
      </FormField>
      <InputError class="mt-1 mb-2" :message="form.errors.nationality_id"/>

      <template #footer>
        <BaseButtons>
          <BaseButton @click="submit" color="info" label="Submit"/>
          <BaseButton type="button" :href="route('users.index')" color="danger" outline  label="Back"/>
        </BaseButtons>
      </template>
    </CardBox>
  </SectionMain>
</template>
