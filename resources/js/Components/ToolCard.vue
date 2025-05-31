<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { Link } from '@inertiajs/vue3'

import {
  faBook,
  faBullhorn,
  faLink,
  faCalculator,
  faComments,
  faCalendar,
  faCar,
  faMap,
  faLocationDot   // << أضف هذا
} from '@fortawesome/free-solid-svg-icons'

const icons = {
  book: faBook,
  bullhorn: faBullhorn,
  link: faLink,
  calculator: faCalculator,
  comments: faComments,
  calendar: faCalendar,
  car: faCar,
  map: faMap,
  'location-dot': faLocationDot   // << أضف هذا
}

defineProps(['icon', 'label', 'to'])
</script>

<template>
  <Link :href="to" class="flex flex-col items-center group">
    <div class="w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center text-purple-700 text-3xl group-hover:bg-purple-100 transition">
      <font-awesome-icon :icon="icons[icon]" />
    </div>
    <p class="mt-3 text-gray-800 font-medium group-hover:text-purple-700">{{ label }}</p>
  </Link>
</template>