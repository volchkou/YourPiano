<template>
  <UpdatePhotoModal :open="modalOpen" :toggle-func="toggleModal"></UpdatePhotoModal>
  <div>
    <main class="profile-page opacity-0 animate-fade-in-down">
      <section class="relative block h-64">
        <div class="relative top-0 w-full h-full bg-center bg-indigo-50">
        </div>
        <div
          class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden"
          style="height: 70px;"
        >
        </div>
      </section>
      <section class="relative py-16">
        <div class="container mx-auto px-4">
          <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">

            <div class="lg:w-8/12 lg:mx-auto mb-8">
              <header class="flex flex-wrap justify-center items-start p-4 md:py-8">
                <div class="inline-flex flex-col relative">
                  <img class="relative w-20 h-20 md:w-40 md:h-40 object-cover rounded-full
                     border-2 border-indigo-600 p-1"
                       :src="store.state.user.data?.imagePath"
                       alt="profile">
                </div>

                <!-- profile meta -->
                <div class="w-auto px-12 mt-6 text-center md:text-left">
                  <h2 class="text-3xl font-light sm:mb-0  ">
                    {{ store.state.user.data.name }}
                  </h2>
                  <!-- user meta form medium screens -->
                  <div class="block mb-4 mt-1">
                    <h1 class="font-semibold">{{ store.state.user.data.email }}</h1>
                  </div>

                  <div class="block mb-4 mt-1">
                    <StatusLabel :is-user="true" :status="store.state.user.data.status"></StatusLabel>
                  </div>

                </div>
              </header>

              <!-- posts -->
              <div class="px-px md:px-3">
                <ul class="flex items-center justify-center space-x-12
                    uppercase tracking-widest font-semibold text-xs text-gray-600
                    border-t">
                  <li :class="$route.name === 'ProfileOverview' ? 'md:border-t md:border-gray-700 md:-mt-px md:text-gray-700' : ''">
                    <router-link :to="{name: 'ProfileOverview'}" class="inline-flex items-center p-3" href="#">
                      <i class="fas fa-th-large text-xl md:text-xs"></i>
                      <StarIcon class="inline w-5 h-5 mx-2"></StarIcon>
                      <span class="hidden md:inline">мой рейтинг</span>
                    </router-link>
                  </li>
                  <li :class="$route.name === 'ProfileLikes' ? 'md:border-t md:border-gray-700 md:-mt-px md:text-gray-700' : ''">
                    <router-link :to="{name: 'ProfileLikes'}"  class="inline-flex items-center p-3" href="#">
                      <i class="far fa-square text-xl md:text-xs"></i>
                      <HeartIcon class="inline w-5 h-5 mx-2"></HeartIcon>
                      <span class="hidden md:inline">понравившиеся</span>
                    </router-link>
                  </li>
                  <li :class="$route.name === 'ProfileSettings' ? 'md:border-t md:border-gray-700 md:-mt-px md:text-gray-700' : ''">
                    <router-link :to="{name: 'ProfileSettings'}"  class="inline-flex items-center p-3" href="#">
                      <i class="far fa-square text-xl md:text-xs"></i>
                      <CogIcon class="inline w-5 h-5 mx-2"></CogIcon>
                      <span class="hidden md:inline">настройки</span>
                    </router-link>
                  </li>
                </ul>

                <!-- flexbox grid -->
                <div class="flex flex-wrap justify-center w-full gap-2">
                  <router-view></router-view>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { PencilIcon, HeartIcon, StarIcon, CogIcon } from '@heroicons/vue/outline'
import StatusLabel from "./profile/StatusLabel.vue";
import {ref} from 'vue';
import store from "../store";
import UpdatePhotoModal from "./profile/UpdatePhotoModal.vue";
const modalOpen = ref(false);

store.dispatch('getStats');

function toggleModal() {
  modalOpen.value = !modalOpen.value;
}

</script>

<style scoped>

</style>
