<template>
  <div class="min-h-screen bg-background">
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="flex items-center gap-4 mb-8">
        <router-link
          to="/products"
          class="flex items-center gap-2 transition-colors text-muted-foreground hover:text-primary"
        >
          <ArrowLeft :size="20" />
          <span class="hidden sm:inline">Back to Products</span>
        </router-link>
      </div>

      <div class="grid gap-8 mb-12 lg:grid-cols-2 lg:gap-12">
        <!-- Image Gallery -->
        <div class="space-y-4">
          <div
            class="overflow-hidden bg-white border shadow-sm rounded-2xl border-border"
          >
            <img
              :src="currentImage"
              :alt="product.name"
              class="w-full h-80 sm:h-96 lg:h-[500px] object-cover"
            />
          </div>

          <!-- Thumbnails -->
          <div v-if="galleryImages.length > 1" class="grid grid-cols-4 gap-3">
            <button
              v-for="(img, index) in galleryImages"
              :key="index"
              @click="currentImage = img"
              class="overflow-hidden transition-all border-2 rounded-xl"
              :class="currentImage === img ? 'border-primary' : 'border-border'"
            >
              <img
                :src="img"
                :alt="`${product.name} ${index + 1}`"
                class="object-cover w-full h-24"
              />
            </button>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3">
            <Button
              variant="outline"
              class="flex-1 py-3 border-2 border-primary text-primary hover:bg-primary/5 rounded-xl"
            >
              <Heart :size="20" class="mr-2" />
              Add to Wishlist
            </Button>
            <Button
              variant="outline"
              class="flex-1 py-3 border-2 border-primary text-primary hover:bg-primary/5 rounded-xl"
            >
              <Share2 :size="20" class="mr-2" />
              Share
            </Button>
          </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
          <div>
            <div
              class="inline-block px-4 py-2 mb-4 text-sm font-semibold bg-secondary/10 text-secondary rounded-xl"
            >
              {{ product.category?.name || "Uncategorized" }}
            </div>
            <h1
              class="mb-4 text-3xl font-bold leading-tight md:text-4xl lg:text-5xl text-foreground"
            >
              {{ product.name }}
            </h1>
            <p class="text-lg leading-relaxed text-muted-foreground">
              {{ product.description }}
            </p>
          </div>

          <!-- Stock & Rating -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="flex items-center gap-1">
                <Star
                  v-for="i in 5"
                  :key="i"
                  :size="20"
                  :class="
                    i <= Math.floor(product.rating)
                      ? 'fill-yellow-400 text-yellow-400'
                      : 'text-gray-300'
                  "
                />
              </div>
              <span class="text-muted-foreground">
                {{ product.rating }} ({{ product.reviews_count }} reviews)
              </span>
            </div>
            <span
              v-if="product.stock_quantity > 0"
              class="text-sm font-medium text-green-600"
            >
              In Stock ({{ product.stock_quantity }} left)
            </span>
            <span v-else class="text-sm font-medium text-red-600"
              >Out of Stock</span
            >
          </div>

          <!-- Price -->
          <div class="flex items-baseline gap-3">
            <div>
              <span
                v-if="hasSale"
                class="text-3xl line-through text-muted-foreground"
              >
                ₦{{ parseFloat(product.price).toFixed(2) }}
              </span>
              <span class="text-4xl font-bold md:text-5xl text-primary">
                ₦{{ displayPrice }}
              </span>
            </div>
            <span class="text-lg text-muted-foreground"
              >/ {{ product.weight || "pack" }}</span
            >
          </div>

          <!-- Quantity Selector -->
          <div class="space-y-4">
            <span class="text-lg font-semibold text-foreground">Quantity:</span>
            <div class="flex items-center gap-4">
              <div
                class="flex items-center border bg-background rounded-xl border-border"
              >
                <button
                  @click="quantity = Math.max(1, quantity - 1)"
                  class="p-3 transition-colors hover:bg-muted rounded-l-xl"
                  :disabled="quantity <= 1"
                >
                  <Minus :size="20" />
                </button>
                <span
                  class="px-6 py-3 font-bold text-lg min-w-[3rem] text-center"
                >
                  {{ quantity }}
                </span>
                <button
                  @click="quantity++"
                  class="p-3 transition-colors hover:bg-muted rounded-r-xl"
                >
                  <Plus :size="20" />
                </button>
              </div>
              <div class="text-right">
                <p class="text-sm text-muted-foreground">Total</p>
                <p class="text-2xl font-bold text-primary">
                  ₦{{ (parseFloat(displayPrice) * quantity).toFixed(2) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Add to Cart Button -->
          <Button
            @click="handleAddToCart"
            :disabled="product.stock_quantity === 0"
            class="w-full py-4 text-lg font-semibold text-white transition-all duration-300 shadow-lg bg-primary hover:bg-primary/90 rounded-xl hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <ShoppingCart :size="24" class="mr-3" />
            {{ product.stock_quantity > 0 ? "Add to Cart" : "Out of Stock" }}
          </Button>

          <!-- Trust Badges -->
          <div class="grid grid-cols-3 gap-4 pt-6 border-t border-border">
            <div class="text-center">
              <div
                class="flex items-center justify-center w-12 h-12 mx-auto mb-2 bg-secondary/10 rounded-xl"
              >
                <Truck :size="24" class="text-secondary" />
              </div>
              <p class="text-sm font-medium text-foreground">Free Shipping</p>
              <p class="text-xs text-muted-foreground">Over ₦1000</p>
            </div>
            <div class="text-center">
              <div
                class="flex items-center justify-center w-12 h-12 mx-auto mb-2 bg-secondary/10 rounded-xl"
              >
                <Shield :size="24" class="text-secondary" />
              </div>
              <p class="text-sm font-medium text-foreground">100% Natural</p>
              <p class="text-xs text-muted-foreground">No Preservatives</p>
            </div>
            <div class="text-center">
              <div
                class="flex items-center justify-center w-12 h-12 mx-auto mb-2 bg-secondary/10 rounded-xl"
              >
                <Leaf :size="24" class="text-secondary" />
              </div>
              <p class="text-sm font-medium text-foreground">Organic</p>
              <p class="text-xs text-muted-foreground">Certified</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs Section -->
      <div class="overflow-hidden bg-white border rounded-2xl border-border">
        <div class="flex border-b border-border">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'flex-1 px-6 py-4 font-semibold transition-colors',
              activeTab === tab.id
                ? 'bg-primary text-white'
                : 'text-muted-foreground hover:bg-muted/50',
            ]"
          >
            {{ tab.label }}
          </button>
        </div>

        <div class="p-6 md:p-8">
          <!-- Nutrition Tab -->
          <div v-if="activeTab === 'nutrition'" class="space-y-6">
            <div class="mb-8">
              <h3 class="mb-4 text-2xl font-bold text-foreground">
                Product Description
              </h3>
              <p class="text-lg leading-relaxed text-muted-foreground">
                {{ product.description }}
              </p>
            </div>

            <div>
              <h3 class="mb-4 text-2xl font-bold text-foreground">
                Nutritional Information
              </h3>
              <p class="mb-6 text-muted-foreground">Per serving</p>

              <div class="overflow-x-auto">
                <table class="w-full border border-border">
                  <thead>
                    <tr class="bg-muted/30">
                      <th
                        class="px-4 py-3 font-semibold text-left border-b text-foreground border-border"
                      >
                        Nutrient
                      </th>
                      <th
                        class="px-4 py-3 font-semibold text-right border-b text-foreground border-border"
                      >
                        Amount
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(value, key) in nutritionDisplay"
                      :key="key"
                      class="border-b border-border hover:bg-muted/10"
                    >
                      <td class="px-4 py-3 text-foreground">
                        {{ formatNutrientName(key) }}
                      </td>
                      <td
                        class="px-4 py-3 font-medium text-right text-foreground"
                      >
                        {{ value }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Benefits Tab (unchanged generic benefits) -->
          <div v-if="activeTab === 'benefits'" class="space-y-6">
            <!-- ... benefits content unchanged ... -->
          </div>

          <!-- Details Tab -->
          <div v-if="activeTab === 'details'" class="space-y-6">
            <h3 class="mb-6 text-2xl font-bold text-foreground">
              Product Details
            </h3>

            <div class="overflow-x-auto">
              <table class="w-full border border-border">
                <tbody>
                  <tr class="border-b border-border hover:bg-muted/10">
                    <td
                      class="w-1/3 px-4 py-4 font-semibold text-foreground bg-muted/20"
                    >
                      Ingredients
                    </td>
                    <td class="px-4 py-4 text-foreground">
                      {{ productIngredients }}
                    </td>
                  </tr>
                  <tr class="border-b border-border hover:bg-muted/10">
                    <td
                      class="px-4 py-4 font-semibold text-foreground bg-muted/20"
                    >
                      Allergens
                    </td>
                    <td class="px-4 py-4 text-foreground">
                      {{ productAllergens }}
                    </td>
                  </tr>
                  <tr class="border-b border-border hover:bg-muted/10">
                    <td
                      class="px-4 py-4 font-semibold text-foreground bg-muted/20"
                    >
                      Weight
                    </td>
                    <td class="px-4 py-4 text-foreground">
                      {{ product.weight || "Not specified" }}
                    </td>
                  </tr>
                  <tr class="border-b border-border hover:bg-muted/10">
                    <td
                      class="px-4 py-4 font-semibold text-foreground bg-muted/20"
                    >
                      Storage Instructions
                    </td>
                    <td class="px-4 py-4 text-foreground">
                      Store in a cool, dry place away from direct sunlight
                    </td>
                  </tr>
                  <tr class="border-b border-border hover:bg-muted/10">
                    <td
                      class="px-4 py-4 font-semibold text-foreground bg-muted/20"
                    >
                      Shelf Life
                    </td>
                    <td class="px-4 py-4 text-foreground">
                      12-18 months from date of manufacture
                    </td>
                  </tr>
                  <tr class="hover:bg-muted/10">
                    <td
                      class="px-4 py-4 font-semibold text-foreground bg-muted/20"
                    >
                      Certifications
                    </td>
                    <td class="px-4 py-4 text-foreground">
                      100% Organic, Non-GMO, Gluten-Free
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  Star,
  ShoppingCart,
  Minus,
  Plus,
  Heart,
  Share2,
  Truck,
  Shield,
  Leaf,
  ArrowLeft,
} from "lucide-vue-next";
import Button from "@/components/ui/Button.vue";
import { useCartStore } from "@/stores/cart";
import { Products } from "../services/products";

export default {
  name: "ProductDetail",
  components: {
    Star,
    ShoppingCart,
    Minus,
    Plus,
    Heart,
    Share2,
    Truck,
    Shield,
    Leaf,
    ArrowLeft,
    Button,
  },
  data() {
    return {
      quantity: 1,
      activeTab: "nutrition",
      current_product: null,
      loading: true,
      currentImage: "",
      tabs: [
        { id: "nutrition", label: "Nutrition" },
        { id: "benefits", label: "Benefits" },
        { id: "details", label: "Details" },
      ],
    };
  },
  computed: {
    product() {
      return (
        this.current_product || {
          name: "Loading...",
          description: "",
          short_description: "",
          price: "0.00",
          sale_price: null,
          image: "/placeholder.svg",
          images: "[]",
          rating: 0,
          reviews_count: 0,
          stock_quantity: 0,
          weight: "",
          calories: "",
          protein: "",
          carbs: "",
          fat: "",
          fiber: "",
          ingredients: "[]",
          allergens: "[]",
          category: { name: "" },
        }
      );
    },
    galleryImages() {
      const main = this.product.image ? [this.product.image] : [];
      try {
        const extra = this.product.images
          ? JSON.parse(this.product.images)
          : [];
        return [...main, ...extra.filter((img) => !main.includes(img))];
      } catch {
        return main;
      }
    },
    hasSale() {
      return (
        this.product.sale_price &&
        parseFloat(this.product.sale_price) < parseFloat(this.product.price)
      );
    },
    displayPrice() {
      return this.hasSale ? this.product.sale_price : this.product.price;
    },
    nutritionDisplay() {
      return {
        Calories: this.product.calories ? `${this.product.calories}` : "-",
        Protein: this.product.protein ? `${this.product.protein}` : "-",
        Carbohydrates: this.product.carbs ? `${this.product.carbs}` : "-",
        Fat: this.product.fat ? `${this.product.fat}` : "-",
        Fiber: this.product.fiber ? `${this.product.fiber}` : "-",
      };
    },
    productIngredients() {
      if (!this.product.ingredients) return "Not specified";
      try {
        const arr = JSON.parse(this.product.ingredients);
        return Array.isArray(arr) ? arr.join(", ") : this.product.ingredients;
      } catch {
        return this.product.ingredients;
      }
    },
    productAllergens() {
      if (!this.product.allergens) return "None";
      try {
        const arr = JSON.parse(this.product.allergens);
        return Array.isArray(arr) && arr.length > 0 ? arr.join(", ") : "None";
      } catch {
        return "None";
      }
    },
  },
  methods: {
    async fetchProduct() {
      this.loading = true;
      try {
        const product_id = this.$route.params.id;
        console.log("Fetching product with ID:", product_id);

        const res = await Products.getProductById(product_id);
        this.current_product = res.data?.data;

        this.currentImage = this.product.image || "/placeholder.svg";
      } catch (err) {
        console.error("Failed to fetch product:", err);
      } finally {
        this.loading = false;
      }
    },
    handleAddToCart() {
      const cartStore = useCartStore();
      cartStore.addItem({
        id: this.product.id,
        name: this.product.name,
        price: this.displayPrice,
        image: this.product.image,
        quantity: this.quantity,
      });
      this.quantity = 1;
    },
    formatNutrientName(key) {
      const map = {
        calories: "Calories",
        protein: "Protein",
        carbs: "Carbohydrates",
        fat: "Fat",
        fiber: "Dietary Fiber",
      };
      return map[key] || key.charAt(0).toUpperCase() + key.slice(1);
    },
  },
  mounted() {
    this.fetchProduct();
  },
};
</script>
