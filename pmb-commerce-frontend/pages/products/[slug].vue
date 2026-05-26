<template>
  <div v-if="product" class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
    <!-- Gallery -->
    <div>
      <div class="aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-4">
        <img :src="selectedImage || product.thumbnail" :alt="product.name" class="w-full h-full object-cover">
      </div>
      <div v-if="product.images?.length" class="grid grid-cols-4 gap-3">
        <button v-for="(img, i) in product.images" :key="i"
                @click="selectedImage = img"
                class="aspect-square rounded-xl overflow-hidden border-2 transition-all"
                :class="selectedImage === img ? 'border-indigo-500 shadow-md shadow-indigo-100' : 'border-gray-200 hover:border-gray-300'">
          <img :src="img" class="w-full h-full object-cover">
        </button>
      </div>
    </div>

    <!-- Info -->
    <div>
      <p class="text-sm font-medium text-indigo-600 mb-1">{{ product.brand?.name }} · {{ ['Wanita','Pria','Unisex'][product.gender - 1] }}</p>
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

      <div v-if="product.description" class="prose prose-sm text-gray-600 mb-6">
        <p>{{ product.description }}</p>
      </div>

      <div v-if="product.features" class="bg-gray-50 rounded-xl p-4 mb-6">
        <p class="text-sm text-gray-700 whitespace-pre-line">{{ product.features }}</p>
      </div>

      <!-- Variants -->
      <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Pilih Varian</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <button v-for="variant in product.variants" :key="variant.id"
                  @click="selectedVariant = variant"
                  :disabled="!variant.stock"
                  class="relative flex flex-col items-center p-4 rounded-xl border-2 text-center transition-all"
                  :class="selectedVariant?.id === variant.id
                    ? 'border-indigo-500 bg-indigo-50 shadow-sm shadow-indigo-100'
                    : variant.stock ? 'border-gray-200 hover:border-indigo-300 bg-white' : 'border-gray-100 bg-gray-50 opacity-60 cursor-not-allowed'">
            <span class="text-sm font-medium text-gray-900">{{ variant.label }}</span>
            <div class="mt-1">
              <span v-if="variant.override_price" class="text-sm font-bold text-rose-600">Rp {{ formatPrice(variant.override_price) }}</span>
              <span v-else class="text-sm font-bold text-gray-900">Rp {{ formatPrice(variant.price) }}</span>
            </div>
            <span v-if="variant.override_price" class="text-xs text-gray-400 line-through">Rp {{ formatPrice(variant.price) }}</span>
            <span class="text-xs text-gray-500 mt-1">{{ variant.stock > 0 ? 'Stok: '+variant.stock : 'Habis' }}</span>
            <div v-if="variant.override_price" class="absolute -top-1.5 -right-1.5">
              <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white shadow-sm">SALE</span>
            </div>
          </button>
        </div>
      </div>

      <!-- Add to Cart -->
      <div class="flex gap-3 mb-8">
        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
          <button @click="decrementQty" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors" :disabled="quantity <= 1">−</button>
          <input type="number" v-model.number="quantity" min="1" :max="selectedVariant?.stock || 1"
                 class="w-14 h-10 text-center text-sm font-medium border-x border-gray-200 outline-none">
          <button @click="quantity++" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors" :disabled="quantity >= (selectedVariant?.stock || 1)">+</button>
        </div>
        <button @click="addToCart"
                class="flex-1 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:shadow-lg hover:shadow-indigo-200 transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!selectedVariant || !selectedVariant.stock">
          {{ selectedVariant?.stock ? 'Tambah ke Keranjang' : 'Stok Habis' }}
        </button>
      </div>

      <!-- Reviews -->
      <div v-if="product.reviews?.length" class="border-t border-gray-200 pt-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">
          Ulasan <span class="text-gray-400 font-normal">({{ product.avg_rating ? product.avg_rating.toFixed(1) : '-' }} ★)</span>
        </h3>
        <div class="space-y-4">
          <div v-for="review in product.reviews" :key="review.id"
               class="bg-gray-50 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-1">
              <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center">
                <span class="text-xs font-medium text-indigo-600">{{ review.user?.name?.[0] || '?' }}</span>
              </div>
              <span class="text-sm font-medium text-gray-800">{{ review.user?.name }}</span>
              <span class="text-yellow-500 text-sm ml-auto">{{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}</span>
            </div>
            <p v-if="review.reason" class="text-sm text-gray-600 mt-1 ml-9">{{ review.reason }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Loading -->
  <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-12 animate-pulse">
    <div class="aspect-[4/3] bg-gray-100 rounded-2xl"></div>
    <div class="space-y-4">
      <div class="h-4 bg-gray-100 rounded w-1/4"></div>
      <div class="h-8 bg-gray-100 rounded w-3/4"></div>
      <div class="h-20 bg-gray-100 rounded"></div>
      <div class="h-12 bg-gray-100 rounded-xl"></div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const api = useApi()
const authStore = useAuthStore()

const product = ref(null)
const selectedVariant = ref(null)
const quantity = ref(1)
const selectedImage = ref(null)

const { data } = await useAsyncData(`product-${route.params.slug}`,
  () => api.get(`/products/${route.params.slug}`))
product.value = data.value

function formatPrice(p) { return new Intl.NumberFormat('id-ID').format(p) }

function decrementQty() { if (quantity.value > 1) quantity.value-- }

async function addToCart() {
  if (!authStore.token) return navigateTo('/login')
  try {
    await api.post('/cart', {
      product_variant_id: selectedVariant.value.id,
      quantity: quantity.value,
    })
    alert('Ditambahkan ke keranjang!')
  } catch (e) {
    alert(e.message)
  }
}
</script>
