<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    <div v-if="loading" class="text-center py-12 text-gray-500">Memuat data...</div>
    <div v-else>
      <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Gudang Pengiriman</label>
            <select v-model="form.warehouse_id" class="w-full border rounded px-3 py-2" required>
              <option value="">Pilih Gudang</option>
              <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }} - {{ wh.city }}, {{ wh.province }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Alamat Pengiriman</label>
            <textarea v-model="form.shipping_address" rows="3" class="w-full border rounded px-3 py-2" required placeholder="Nama jalan, nomor, kelurahan, kecamatan, kota, provinsi"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Catatan Pengiriman (opsional)</label>
            <textarea v-model="form.shipping_note" rows="2" class="w-full border rounded px-3 py-2" placeholder="Contoh: Titipkan ke satpam"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Biaya Kirim (Rp)</label>
            <input v-model.number="form.shipping_cost" type="number" min="0" class="w-full border rounded px-3 py-2" required>
          </div>
        </div>
        <div class="space-y-4">
          <div class="border rounded-lg p-6">
            <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>
            <div v-if="cartItems.length">
              <div v-for="item in cartItems" :key="item.id" class="flex justify-between text-sm mb-2">
                <span>
                  <NuxtLink :to="`/products/${item.product?.slug}`" class="text-blue-600 hover:underline">
                    {{ item.product?.name }}
                  </NuxtLink>
                  <span class="text-gray-500"> - {{ item.variant?.label }} x{{ item.quantity }}</span>
                </span>
                <span>Rp {{ formatPrice((item.variant?.override_price || item.variant?.price || 0) * item.quantity) }}</span>
              </div>
              <hr class="my-3">
              <div class="flex justify-between mb-1"><span class="text-gray-600">Subtotal</span><span>Rp {{ formatPrice(subtotal) }}</span></div>
              <div class="flex justify-between mb-1"><span class="text-gray-600">Ongkir</span><span>Rp {{ formatPrice(form.shipping_cost) }}</span></div>
              <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t">
                <span>Total</span>
                <span>Rp {{ formatPrice(subtotal + form.shipping_cost) }}</span>
              </div>
            </div>
            <div v-else class="text-gray-500 text-sm py-4 text-center">
              Keranjang kosong.
              <NuxtLink to="/products" class="text-blue-600 hover:underline block mt-2">Belanja Sekarang</NuxtLink>
            </div>
          </div>

          <div v-if="paymentAccounts.length" class="border rounded-lg p-6">
            <h3 class="font-semibold mb-4">Rekening Tujuan Transfer</h3>
            <p class="text-xs text-gray-500 mb-3">Transfer ke salah satu rekening di bawah setelah pesanan dibuat</p>
            <div v-for="acc in paymentAccounts" :key="acc.id" class="flex items-center gap-2 mb-2">
              <input type="radio" :value="acc.id" v-model="form.payment_account_id">
              <label class="text-sm">{{ acc.bank_name }} - {{ acc.account_number }}<br><span class="text-xs text-gray-500">a.n. {{ acc.account_name }}</span></label>
            </div>
          </div>

          <p v-if="error" class="text-red-600 text-sm">{{ error }}</p>

          <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded hover:bg-gray-800 mt-4"
                  :disabled="submitting || !cartItems.length">
            {{ submitting ? 'Memproses...' : 'Buat Pesanan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
const api = useApi()
definePageMeta({ middleware: 'auth' })

const cartItems = ref([])
const warehouses = ref([])
const paymentAccounts = ref([])
const subtotal = ref(0)
const loading = ref(true)
const submitting = ref(false)
const error = ref('')

const form = reactive({
  warehouse_id: '',
  shipping_address: '',
  shipping_note: '',
  shipping_cost: 15000,
  payment_account_id: null,
})

onMounted(loadData)

async function loadData() {
  loading.value = true
  try {
    const [cartRes, whRes, paRes] = await Promise.all([
      api.get('/cart'),
      api.get('/warehouses'),
      api.get('/payment-accounts'),
    ])
    cartItems.value = cartRes.data || []
    subtotal.value = cartRes.total || 0
    warehouses.value = whRes.data || []
    paymentAccounts.value = paRes.data || []
    if (paymentAccounts.value.length) {
      form.payment_account_id = paymentAccounts.value[0].id
    }
  } catch (e: any) {
    error.value = 'Gagal memuat data: ' + e.message
  } finally { loading.value = false }
}

function formatPrice(p) { return p ? new Intl.NumberFormat('id-ID').format(p) : '0' }

async function submit() {
  error.value = ''
  if (!form.payment_account_id) {
    error.value = 'Pilih rekening tujuan transfer.'
    return
  }
  submitting.value = true
  try {
    await api.post('/checkout', form)
    navigateTo('/orders')
  } catch (e: any) {
    error.value = e.message
  } finally { submitting.value = false }
}
</script>
