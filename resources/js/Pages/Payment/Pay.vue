<script setup lang="js">
import { defineModel, ref, watch } from 'vue';

const name = ref();
const cpf = ref();
const zip_code = ref();
const address = ref();
const number = ref();
const district = ref();
const city = ref();
const state = ref();
const amount = ref();
const payment = ref('boleto');
const processing = ref(false);

watch(cpf, (newValue = '') => {
  cpf.value = newValue.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
});

watch(zip_code, (newValue = '') => {
  zip_code.value = newValue.replace(/(\d{5})(\d{3})/, "$1-$2");
});

function createPayment() {
  processing.value = true;
  setTimeout(() => {
    processing.value = false;
  }, 40000);
}

</script>

<template>
  <main class="md:flex md:flex-row md:w-2/3 mx-auto h-screen relative">
    <section class="md:w-1/2 min-h-52 flex flex-col justify-center items-center">
      <img src="/image/cielo.png" width="200" alt="">
    </section>
    <section class="md:w-1/2">
      <form @submit.prevent class="flex flex-col justify-center h-full px-5">
        <div class="rounded-3xl bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold">Dados pessoais</h2>
          <input class="rounded-lg my-2 border-gray-300 w-full" v-model="name" type="text" placeholder="nome">
          <input class="rounded-lg my-2 border-gray-300 w-full" v-model="cpf" maxlength="14" type="text"
            placeholder="CPF">
        </div>
        <br>
        <div class="rounded-3xl bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold">Endereço</h2>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-1/3" v-model="zip_code" maxlength="8" type="tel"
              placeholder="CEP">
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-2/3" v-model="address" type="text" placeholder="endereço">
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-1/3" v-model="number" type="tel" maxlength="5"
              placeholder="número">
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-2/3" v-model="district" type="text" placeholder="bairro">
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-full" v-model="city" type="text" placeholder="cidade">
            <hr class="w-4">
            <input class="rounded-lg my-2 border-gray-300 w-full" v-model="state" type="text" placeholder="estado">
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
          <button @click="createPayment"
            class="h-12 w-44 flex flex-row justify-center items-center rounded-lg bg-anoitecer text-white mt-4">
            <p v-show="!processing">Solicitar pagamento</p>
            <svg v-show="processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24">
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
