<script lang="js">
import { vMaska } from "maska/vue"

</script>

<template>
  <main class="md:flex md:flex-row md:w-2/3 mx-auto h-screen">
    <section class="w-1/2 flex flex-col justify-center">
      <img src="/image/cielo.png" width="200" alt="">
    </section>
    <section class="w-1/2">
      <form action="" class="flex flex-col justify-center h-full">
        <div class="rounded-3xl bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold">Dados pessoais</h2>
          <input class="rounded-lg my-2 border-gray-300 w-full" type="text" placeholder="nome">
          <input class="rounded-lg my-2 border-gray-300 w-full" type="text" placeholder="CPF">
        </div>
        <br>
        <div class="rounded-3xl bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold">Endereço</h2>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-1/3" type="text" placeholder="CEP">
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-2/3" type="text" placeholder="endereço">
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-1/3" type="text" placeholder="número">
            <hr class="w-2">
            <input class="rounded-lg my-2 border-gray-300 w-2/3" type="text" placeholder="bairro">
          </div>
          <div class="flex flex-row">
            <input class="rounded-lg my-2 border-gray-300 w-full" type="text" placeholder="cidade">
            <hr class="w-4">
            <input class="rounded-lg my-2 border-gray-300 w-full" type="text" placeholder="estado">
          </div>
        </div>
        <br>
        <div class="rounded-3xl flex flex-col items-end bg-gray-100 px-5 py-5">
          <h2 class="text-2xl font-bold w-full">Pagamento</h2>
          <div class="flex flex-row w-full">
            <select name="" id="" class="rounded-lg my-2 border-gray-300 w-full">
              <option value="boleto">boleto</option>
            </select>
            <hr class="w-4">
            <input class="rounded-lg my-2 border-gray-300 w-full" type="text">
          </div>
          <button class="h-12 rounded-lg bg-anoitecer text-white px-7 mt-4">Solicitar pagamento</button>
        </div>
      </form>
    </section>
  </main>
</template>
