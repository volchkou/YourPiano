<template>
  <div class="bg-white">
    <div>
      <!-- Mobile filter dialog -->
      <TransitionRoot as="template" :show="mobileFiltersOpen">
        <Dialog as="div" class="fixed inset-0 flex z-40 lg:hidden" @close="mobileFiltersOpen = false">
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
                <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                <button type="button"
                        class="-mr-2 w-10 h-10 bg-white p-2 rounded-md flex items-center justify-center text-gray-400"
                        @click="mobileFiltersOpen = false">
                  <span class="sr-only">Close menu</span>
                  <XIcon class="h-6 w-6" aria-hidden="true"/>
                </button>
              </div>

              <!-- Filters -->
              <form class="mt-4 border-t border-gray-200">
                <h3 class="sr-only">Categories</h3>
                <ul role="list" class="font-medium text-gray-900 px-2 py-3">
                  <li v-for="category in subCategories" :key="category.name">
                    <a :href="category.href" class="block px-2 py-3">
                      {{ category.name }}
                    </a>
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

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative z-10 flex items-baseline justify-between pt-16 pb-6 border-b border-gray-200">
          <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Песни</h1>

          <div class="flex items-center">
            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                  Упорядочить
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
                      <a :href="option.href"
                         :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">
                        {{ option.name }}
                      </a>
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
              <h3 class="sr-only">Categories</h3>
              <div class="border-b border-gray-200 py-6">
                <label for="song-name" class="block text-sm font-medium text-gray-700">Название композиции</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <input type="text" name="song-name" id="song-name"
                         class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                         placeholder="Название композиции"/>
                </div>
              </div>

              <div class="border-b border-gray-200 py-4">
                <label for="song-author" class="block text-sm font-medium text-gray-700">Исполнитель</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <input type="text" name="song-author" id="song-author"
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
                             type="checkbox" :checked="option.checked"
                             class="h-4 w-4 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500"/>
                      <label :for="`filter-${section.id}-${optionIdx}`" class="ml-3 text-sm text-gray-600">
                        {{ option.label }}
                      </label>
                    </div>
                  </div>
                </DisclosurePanel>
              </Disclosure>
            </form>

            <!-- Product grid -->
            <div class="lg:col-span-3">
              <!-- Replace with your content -->
              <div class="bg-white">
                <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                  <h2 class="sr-only">Products</h2>

                  <div
                    class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    <a v-for="product in products" :key="product.id" :href="product.href" class="group">
                      <div
                        class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                        <img :src="product.imageSrc" :alt="product.imageAlt"
                             class="w-full h-full object-center object-cover group-hover:opacity-75"/>
                      </div>
                      <h3 class="mt-4 text-sm text-gray-700">
                        {{ product.name }}
                      </h3>
                      <p class="mt-1 text-lg font-medium text-gray-900">
                        {{ product.price }}
                      </p>
                    </a>
                  </div>
                </div>
              </div>
              <!-- /End replace -->
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script>
import {ref} from 'vue'
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

const products = [
  {
    id: 1,
    name: 'Earthen Bottle',
    href: '#',
    price: '$48',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-04-image-card-01.jpg',
    imageAlt: 'Tall slender porcelain bottle with natural clay textured body and cork stopper.',
  },
  {
    id: 2,
    name: 'Nomad Tumbler',
    href: '#',
    price: '$35',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-04-image-card-02.jpg',
    imageAlt: 'Olive drab green insulated bottle with flared screw lid and flat top.',
  },
  {
    id: 3,
    name: 'Focus Paper Refill',
    href: '#',
    price: '$89',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-04-image-card-03.jpg',
    imageAlt: 'Person using a pen to cross a task off a productivity paper card.',
  },
  {
    id: 4,
    name: 'Machined Mechanical Pencil',
    href: '#',
    price: '$35',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/category-page-04-image-card-04.jpg',
    imageAlt: 'Hand holding black machined steel mechanical pencil with brass tip and top.',
  },
  // More products...
]

const sortOptions = [
  {name: 'Сначала популярные', href: '#', current: true},
  {name: 'Сначала новые', href: '#', current: false},
  {name: 'Сначала старые', href: '#', current: false},
]
const subCategories = [
  {name: 'Totes', href: '#'},
  {name: 'Backpacks', href: '#'},
  {name: 'Travel Bags', href: '#'},
  {name: 'Hip Bags', href: '#'},
  {name: 'Laptop Sleeves', href: '#'},
]
const filters = [
  {
    id: 'genre',
    name: 'Жанр',
    options: [
      {value: 'white', label: 'White', checked: false},
      {value: 'beige', label: 'Beige', checked: false},
      {value: 'blue', label: 'Blue', checked: true},
      {value: 'brown', label: 'Brown', checked: false},
      {value: 'green', label: 'Green', checked: false},
      {value: 'purple', label: 'Purple', checked: false},
    ],
  },
  {
    id: 'difficulty',
    name: 'Сложность',
    options: [
      {value: 'easy', label: 'Легко', checked: false},
      {value: 'medium', label: 'Средне', checked: false},
      {value: 'high', label: 'Сложно', checked: true},
    ],
  },
]

export default {
  components: {
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
  setup() {
    const mobileFiltersOpen = ref(false)

    return {
      sortOptions,
      subCategories,
      filters,
      mobileFiltersOpen,
      products
    }
  },
}
</script>