<script setup lang="js">
import axios from "axios";
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
const amount = ref("");
const payment = ref("boleto");
const processing = ref(false);

watch(cpf, (newValue = '') => {
  cpf.value = newValue.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
});

watch(zip_code, (newValue = '') => {
  zip_code.value = newValue.replace(/(\d{5})(\d{3})/, "$1-$2");
});

function createPayment() {
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
      amount: amount.value
    }
  };

  axios.post("/api/payments/boleto", payload)
    .then((response) => {
      const data = response.data;
      window.location.href = data.content.url;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      processing.value = false;
    });
}

function searchAddress() {
  if (zip_code.value.length > 8) {
    axios.get(`${import.meta.env.VITE_BRASIL_API_ENDPOINT}${zip_code.value}`)
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
  <main class="md:flex md:flex-row md:w-2/3 mx-auto h-screen relative">
    <section class="md:w-1/2 min-h-52 flex flex-col justify-center items-center">
      <img src="/image/cielo.png" width="200" alt="">
    </section>
    <section class="md:w-1/2">
      <form @submit.prevent="createPayment" class="flex flex-col justify-center h-full px-5">
        <div class="rounded-3xl bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold">Dados pessoais</h2>
          <input class="rounded-lg my-2 border-gray-300 w-full" v-model="name" type="text" placeholder="nome" required>
          <input class="rounded-lg my-2 border-gray-300 w-full" v-model="cpf" maxlength="14" type="text"
            placeholder="CPF" required>
        </div>
        <br>
        <div class="rounded-3xl bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold">Endereço</h2>
          <div class="flex flex-row">
            <input @keyup="searchAddress" class="rounded-lg my-2 border-gray-300 w-1/3" v-model="zip_code" maxlength="8"
              type="tel" placeholder="CEP" required>
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-2/3 disabled:bg-gray-200" v-model="street"
              :disabled="street" type="text" placeholder="endereço">
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-1/3" v-model="number" type="tel" maxlength="5"
              placeholder="número" required>
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-2/3" v-model="complement" type="text" placeholder="complemento"
              required>
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-2/3 disabled:bg-gray-200" v-model="district"
              :disabled="district" type="text" placeholder="complemento" required>
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-1/3 disabled:bg-gray-200" v-model="state" :disabled="state"
              type="text" placeholder="estado" required>
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-2/3 disabled:bg-gray-200" v-model="city" :disabled="city"
              type="text" placeholder="cidade" required>
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-1/3 disabled:bg-gray-200" v-model="country"
              :disabled="country" type="text" placeholder="país" required>
          </div>
        </div>
        <br>
        <div class="rounded-3xl flex flex-col items-end bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold w-full">Pagamento</h2>
          <div class="flex flex-row w-full">
            <select class="rounded-lg my-2 border-gray-300 w-full" v-model="payment">
              <option value="boleto">boleto</option>
            </select>
            <hr class="w-4">
            <input class="rounded-lg my-2 border-gray-300 w-full" v-model="amount" type="text" placeholder="R$ 0,00">
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
    </section>
    <transition>
      <div class="absolute bottom-0 right-0">
        Processando...
      </div>
    </transition>
  </main>
</template>
