<template>
  <div class="bg-white">
    <div>
      <!-- Mobile filter dialog -->
      <TransitionRoot as="template" :show="mobileFiltersOpen">
        <Dialog as="div" class="fixed inset-0 flex z-50 lg:hidden" @close="mobileFiltersOpen = false">
          <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0"
                           enter-to="opacity-100" leave="transition-opacity ease-linear duration-300"
                           leave-from="opacity-100" leave-to="opacity-0">
            <DialogOverlay class="fixed inset-0 bg-black bg-opacity-25"/>
          </TransitionChild>

          <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                           enter-from="translate-x-full" enter-to="translate-x-0"
                           leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                           leave-to="translate-x-full">
            <div
              class="ml-auto relative max-w-xs w-full h-full bg-white shadow-xl py-4 pb-12 flex flex-col overflow-y-auto">
              <div class="px-4 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Поиск песни</h2>
                <button type="button"
                        class="-mr-2 w-10 h-10 bg-white p-2 rounded-md flex items-center justify-center text-gray-400"
                        @click="mobileFiltersOpen = false">
                  <span class="sr-only">Close menu</span>
                  <XIcon class="h-6 w-6" aria-hidden="true"/>
                </button>
              </div>

              <!-- Filters -->
              <form class="mt-4 border-t border-gray-200">
                <h3 class="sr-only">Поиск песни</h3>
                <ul role="list" class="font-medium text-gray-900 px-2 py-3">
                  <li>
                    <div class="border-b border-gray-200 py-6">
                      <label for="song-name-mobile" class="block font-medium text-gray-700">Название композиции</label>
                      <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="song-name" id="song-name-mobile" v-model="searchTitleInput"
                               class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                               placeholder="Название композиции"/>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="border-b border-gray-200 py-4">
                      <label for="song-author-mobile" class="block font-medium text-gray-900">Исполнитель</label>
                      <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="song-author" id="song-author-mobile" v-model="searchAuthorInput"
                               class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                               placeholder="Исполнитель"/>
                      </div>
                    </div>
                  </li>
                </ul>

                <Disclosure as="div" v-for="section in filters" :key="section.id"
                            class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                  <h3 class="-mx-2 -my-3 flow-root">
                    <DisclosureButton
                      class="px-2 py-3 bg-white w-full flex items-center justify-between text-gray-400 hover:text-gray-500">
                      <span class="font-medium text-gray-900">
                        {{ section.name }}
                      </span>
                      <span class="ml-6 flex items-center">
                        <PlusSmIcon v-if="!open" class="h-5 w-5" aria-hidden="true"/>
                        <MinusSmIcon v-else class="h-5 w-5" aria-hidden="true"/>
                      </span>
                    </DisclosureButton>
                  </h3>
                  <DisclosurePanel class="pt-6">
                    <div class="space-y-6">
                      <div v-for="(option, optionIdx) in section.options" :key="option.value" class="flex items-center">
                        <input :id="`filter-mobile-${section.id}-${optionIdx}`" :name="`${section.id}[]`"
                               :value="option.value" type="checkbox" :checked="option.checked"
                               class="h-4 w-4 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500"/>
                        <label :for="`filter-mobile-${section.id}-${optionIdx}`"
                               class="ml-3 min-w-0 flex-1 text-gray-500">
                          {{ option.label }}
                        </label>
                      </div>
                    </div>
                  </DisclosurePanel>
                </Disclosure>
              </form>
            </div>
          </TransitionChild>
        </Dialog>
      </TransitionRoot>

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="relative z-10 flex items-baseline justify-between pt-16 pb-6 border-b border-gray-200">
          <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Песни</h1>

          <div class="flex items-center">
            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                  {{sortOptions.find(option => option.current === true).name}}
                  <ChevronDownIcon class="flex-shrink-0 -mr-1 ml-1 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                   aria-hidden="true"/>
                </MenuButton>
              </div>

              <transition enter-active-class="transition ease-out duration-100"
                          enter-from-class="transform opacity-0 scale-95"
                          enter-to-class="transform opacity-100 scale-100"
                          leave-active-class="transition ease-in duration-75"
                          leave-from-class="transform opacity-100 scale-100"
                          leave-to-class="transform opacity-0 scale-95">
                <MenuItems
                  class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-2xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <div class="py-1">
                    <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                      <div
                        @click="updateSortingOrder(option)"
                        :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500',
                        active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">
                        {{ option.name }}
                      </div>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>

            <button type="button" class="p-2 -m-2 ml-4 sm:ml-6 text-gray-400 hover:text-gray-500 lg:hidden"
                    @click="mobileFiltersOpen = true">
              <span class="sr-only">Filters</span>
              <FilterIcon class="w-5 h-5" aria-hidden="true"/>
            </button>
          </div>
        </div>

        <section aria-labelledby="products-heading" class="pt-6 pb-24">
          <h2 id="products-heading" class="sr-only">Products</h2>

          <div class="grid grid-cols-1 lg:grid-cols-4 gap-x-8 gap-y-10">
            <!-- Filters -->
            <form class="hidden lg:block">
              <h3 class="sr-only">Поиск песни</h3>
              <div class="border-b border-gray-200 py-6">
                <label for="song-name" class="block text-sm font-medium text-gray-700">Название композиции</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <input type="text" name="song-name" id="song-name" v-model="searchTitleInput"
                         class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                         placeholder="Название композиции"/>
                </div>
              </div>

              <div class="border-b border-gray-200 py-4">
                <label for="song-author" class="block text-sm font-medium text-gray-700">Исполнитель</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <input type="text" name="song-author" id="song-author" v-model="searchAuthorInput"
                         class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                         placeholder="Исполнитель"/>
                </div>
              </div>

              <Disclosure as="div" v-for="section in filters" :key="section.id" class="border-b border-gray-200 py-6"
                          v-slot="{ open }">
                <h3 class="-my-3 flow-root">
                  <DisclosureButton
                    class="py-3 bg-white w-full flex items-center justify-between text-sm text-gray-400 hover:text-gray-500">
                    <span class="font-medium text-gray-900">
                      {{ section.name }}
                    </span>
                    <span class="ml-6 flex items-center">
                      <PlusSmIcon v-if="!open" class="h-5 w-5" aria-hidden="true"/>
                      <MinusSmIcon v-else class="h-5 w-5" aria-hidden="true"/>
                    </span>
                  </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                  <div class="space-y-4">
                    <div v-for="(option, optionIdx) in section.options" :key="option.value" class="flex items-center">
                      <input :id="`filter-${section.id}-${optionIdx}`" :name="`${section.id}[]`" :value="option.value"
                             type="checkbox" v-model="option.checked"
                             class="h-4 w-4 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500"/>
                      <label :for="`filter-${section.id}-${optionIdx}`" class="ml-3 text-sm text-gray-600">
                        {{ option.label }}
                      </label>
                    </div>
                  </div>
                </DisclosurePanel>
              </Disclosure>
              <button
                @click.prevent="getSongs()"
                class="flex justify-center text-white mt-6 bg-indigo-500 border-0
                py-2 w-full px-6 focus:outline-none hover:bg-indigo-600 rounded">
                Поиск
              </button>
            </form>

            <SongList :songs="songs" v-if="songs.loading || songs.data.length > 0">
              <Pagination :links="songs.links" :get-for-page="getForPage"></Pagination>
            </SongList>
            <NothingFound v-else message="К сожалению, по вашему запросу ничего не найдено."></NothingFound>

          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script>
import {computed, ref} from 'vue'
import {
  Dialog,
  DialogOverlay,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {XIcon} from '@heroicons/vue/outline'
import {ChevronDownIcon, FilterIcon, MinusSmIcon, PlusSmIcon, ViewGridIcon} from '@heroicons/vue/solid'
import SongList from "./sections/SongList.vue";
import store from "../store";
import Pagination from "./ui/Pagination.vue";
import NothingFound from "./ui/NothingFound.vue";

export default {
  components: {
    NothingFound,
    Pagination,
    SongList,
    Dialog,
    DialogOverlay,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
    ChevronDownIcon,
    FilterIcon,
    MinusSmIcon,
    PlusSmIcon,
    ViewGridIcon,
    XIcon,
  },
  beforeUnmount() {
    store.commit('updateSearchTitleInput', '');
    store.commit('updateSearchAuthorInput', '');
    store.commit('setDefaultSearch');
  },
  computed: {
    searchTitleInput: {
      get() {
        return store.state.searchOptions.searchTitleInput
      },
      set(value) {
        store.commit('updateSearchTitleInput', value);
      }
    },
    searchAuthorInput: {
      get() {
        return store.state.searchOptions.searchAuthorInput
      },
      set(value) {
        store.commit('updateSearchAuthorInput', value);
      }
    }
  },
  setup() {
    const mobileFiltersOpen = ref(false);
    const searchOptions = computed(() => store.state.searchOptions);
    const songs = computed(() => store.state.songs);

    store.dispatch('getSongs');

    function getSongs() {
      store.dispatch('getSongs');
    }

    function updateSortingOrder(option) {
      store.commit('updateSortingOrder', option);
      this.getSongs();
    }

    function getForPage(link) {
      if (!link.url || link.active) {
        return;
      }
      store.dispatch('getSongs', {url: link.url});
    }

    return {
      store,
      getSongs,
      updateSortingOrder,
      getForPage,
      sortOptions: searchOptions.value.sorting,
      filters: searchOptions.value.filters,
      mobileFiltersOpen,
      songs,
    }
  },
}
</script>
