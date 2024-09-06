<script setup lang="js">
import CurrencyInput from "@/Components/CurrencyInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { vMaska } from "maska/vue"
import { defineModel, ref, watch } from "vue";

const name = ref("");
const cpf = ref("");
const zip_code = ref("");
const street = ref("");
const number = ref("");
const district = ref("");
const city = ref("");
const state = ref("");
const complement = ref("");
const country = ref("");
const amount = ref(0);
const payment = ref("boleto");
const processing = ref(false);
const error_messages = ref([]);

function createPayment() {
  processing.value = true;

  const payload = {
    customer: {
      name: name.value,
      identity: cpf.value.replace(/[.-]/g, "")
    },
    address: {
      street: street.value,
      number: number.value,
      complement: complement.value,
      zip_code: zip_code.value.replace(/[-]/g, ""),
      city: city.value,
      state: state.value,
      country: 'BR',
      district: district.value
    },
    payment: {
      amount: amount.value * 100
    }
  };

  axios.post("/api/payments/boleto", payload)
    .then((response) => {
      const data = response.data;
      window.location.href = data.content.url;
    })
    .catch((error) => {
      const data = error.response.data;
      error_messages.value = [];
      if (data.status == 'validation_fail') {
        const messages = Object.values(data.details);
        messages.forEach((message) => {
          error_messages.value = error_messages.value.concat(message);
        });
      } else {
        error_messages.value.push(data.details);
      }
      window.scrollTo({ top: 0, behavior: 'smooth' });
    })
    .finally(() => {
      processing.value = false;
    });
}

function searchAddress() {
  if (zip_code.value.length > 8) {
    const search_code = zip_code.value.replace("-", "");
    axios.get(`${import.meta.env.VITE_BRASIL_API_ENDPOINT}${search_code}`)
      .then((response) => {
        const data = response.data;
        street.value = data.street;
        city.value = data.city;
        state.value = data.state;
        district.value = data.neighborhood;
        country.value = "BR";
      })
      .catch(() => { })
  }
}

</script>

<template>
  <AuthenticatedLayout>
    <Head title="Pagamentos"></Head>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pagamentos</h2>
    </template>
    <form @submit.prevent="createPayment" class="flex flex-col justify-center m-5 md:w-3/4 md:mx-auto md:mt-10 h-full">
      <div v-show="error_messages.length">
        <div v-for="(message, index) in error_messages" :key="index"
          class="px-5 py-5 rounded-xl w-full bg-red-200 text-red-600 my-5">
          <p>{{ message }}</p>
        </div>
      </div>
      <div class="rounded-3xl bg-white shadow-sm px-5 py-5">
        <h2 class="text-2xl font-bold">Dados pessoais</h2>
        <input class="rounded-lg my-2 border-gray-300 w-full" v-model="name" type="text" placeholder="nome" required>
        <input class="rounded-lg my-2 border-gray-300 w-full" v-model="cpf" v-maska="'###.###.###-##'" type="text"
          placeholder="CPF" required>
      </div>
      <br>
      <div class="rounded-3xl bg-white shadow-sm px-5 py-5">
        <h2 class="text-2xl font-bold">Endereço</h2>
        <div class="flex flex-row">
          <input @keyup="searchAddress" class="rounded-lg my-2 border-gray-300 w-1/3" v-model="zip_code"
            v-maska="'#####-###'" type="tel" placeholder="CEP" required>
          <hr class="w-2">
          <input class="rounded-lg my-2 border-gray-300 w-2/3 disabled:bg-gray-200" v-model="street"
            :disabled="street != ''" type="text" placeholder="endereço">
        </div>
        <div class="flex flex-row">
          <input class="rounded-lg my-2 border-gray-300 w-1/3" v-model="number" type="tel" maxlength="5"
            placeholder="número" required>
          <hr class="w-2">
          <input class="rounded-lg my-2 border-gray-300 w-2/3" v-model="complement" type="text"
            placeholder="complemento" required>
        </div>
        <div class="flex flex-row">
          <input class="rounded-lg my-2 border-gray-300 w-2/3 disabled:bg-gray-200" v-model="district"
            :disabled="district != ''" type="text" placeholder="complemento" required>
          <hr class="w-2">
          <input class="rounded-lg my-2 border-gray-300 w-1/3 disabled:bg-gray-200" v-model="state"
            :disabled="state != ''" type="text" placeholder="estado" required>
        </div>
        <div class="flex flex-row">
          <input class="rounded-lg my-2 border-gray-300 w-2/3 disabled:bg-gray-200" v-model="city"
            :disabled="city != ''" type="text" placeholder="cidade" required>
          <hr class="w-2">
          <input class="rounded-lg my-2 border-gray-300 w-1/3 disabled:bg-gray-200" v-model="country"
            :disabled="country != ''" type="text" placeholder="país" required>
        </div>
      </div>
      <br>
      <div class="rounded-3xl flex flex-col items-end bg-white shadow-sm px-5 py-5">
        <h2 class="text-2xl font-bold w-full">Pagamento</h2>
        <div class="flex flex-row w-full">
          <select class="rounded-lg my-2 border-gray-300 w-full" v-model="payment">
            <option value="boleto">boleto</option>
          </select>
          <hr class="w-4">
          <CurrencyInput v-model="amount" :options="{ currency: 'BRL' }" class="rounded-lg my-2 border-gray-300 w-full"
            placeholder="R$ 0,00"></CurrencyInput>
        </div>
        <button type="submit"
          class="h-12 w-44 flex flex-row justify-center items-center rounded-lg bg-anoitecer text-white mt-4">
          <p v-show="!processing">Solicitar pagamento</p>
          <svg v-show="processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>
