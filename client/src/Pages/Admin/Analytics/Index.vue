<template>
  <div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">Analytics</h2>

    <!-- Time Period Selector -->
    <div class="flex gap-2">
      <button
        v-for="period in periods"
        :key="period.value"
        @click="selectedPeriod = period.value"
        :class="[
          'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
          selectedPeriod === period.value
            ? 'bg-primary text-white'
            : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
        ]"
      >
        {{ period.label }}
      </button>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
      <div
        v-for="metric in metrics"
        :key="metric.label"
        class="p-6 bg-white border border-gray-200 rounded-xl"
      >
        <p class="mb-1 text-sm text-gray-600">{{ metric.label }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ metric.value }}</p>
        <p class="mt-2 text-xs text-green-600">↑ {{ metric.growth }}%</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 gap-6">
      <!-- Monthly Sales Bar Chart -->
      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-lg font-bold text-gray-900">
          Monthly Sales Comparison
        </h3>
        <div class="h-80">
          <canvas ref="monthlySalesChart"></canvas>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
      <!-- Revenue & Orders Trend Line Chart -->
      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-lg font-bold text-gray-900">
          Revenue & Orders Trend
        </h3>
        <div class="h-80">
          <canvas ref="revenueOrdersChart"></canvas>
        </div>
      </div>

      <!-- Sales by Category Pie Chart -->
      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-lg font-bold text-gray-900">Sales by Category</h3>
        <div class="flex items-center justify-center h-80">
          <canvas ref="categoryPieChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Top Selling Products -->
    <div class="p-6 bg-white border border-gray-200 rounded-xl">
      <h3 class="mb-4 text-lg font-bold text-gray-900">Top Selling Products</h3>
      <div class="space-y-3">
        <div
          v-for="(product, index) in topProducts"
          :key="product.id"
          class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50"
        >
          <div class="flex items-center gap-3">
            <span
              class="flex items-center justify-center w-8 h-8 text-sm font-bold rounded-lg bg-primary/10 text-primary"
            >
              {{ index + 1 }}
            </span>
            <img
              :src="product.image"
              :alt="product.name"
              class="object-cover w-12 h-12 rounded-lg"
            />
            <div>
              <p class="text-sm font-medium text-gray-900">
                {{ product.name }}
              </p>
              <p class="text-xs text-gray-600">{{ product.category }}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm font-semibold text-gray-900">
              {{ product.sales }} units sold
            </p>
            <p class="text-xs text-gray-600">
              ₦{{ (product.price * product.sales).toLocaleString() }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  BarController,
  ArcElement,
  DoughnutController,
  LineController,
  Title,
  Tooltip,
  Legend,
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  BarController,
  ArcElement,
  DoughnutController,
  LineController,
  Title,
  Tooltip,
  Legend
);

export default {
  name: "Analytics",

  props: {
    products: {
      type: Array,
      default: () => [],
    },
  },

  data() {
    return {
      selectedPeriod: "30d",
      periods: [
        { value: "7d", label: "Last 7 Days" },
        { value: "30d", label: "Last 30 Days" },
        { value: "90d", label: "Last 90 Days" },
        { value: "1y", label: "Last Year" },
      ],
      analyticsData: {
        revenue: 48568,
        revenueGrowth: 12.5,
        orders: 127,
        ordersGrowth: 8.2,
        conversionRate: 3.4,
        conversionGrowth: 2.1,
        avgOrderValue: 1247,
        avgOrderGrowth: 5.3,
      },
      monthlySalesChartInstance: null,
      revenueOrdersChartInstance: null,
      categoryPieChartInstance: null,
    };
  },

  computed: {
    metrics() {
      return [
        {
          label: "Revenue",
          value: `₦${this.analyticsData.revenue.toLocaleString()}`,
          growth: this.analyticsData.revenueGrowth,
        },
        {
          label: "Orders",
          value: this.analyticsData.orders,
          growth: this.analyticsData.ordersGrowth,
        },
        {
          label: "Conversion Rate",
          value: `${this.analyticsData.conversionRate}%`,
          growth: this.analyticsData.conversionGrowth,
        },
        {
          label: "Avg Order Value",
          value: `₦${this.analyticsData.avgOrderValue}`,
          growth: this.analyticsData.avgOrderGrowth,
        },
      ];
    },

    topProducts() {
      return this.products
        .map((p) => ({ ...p, sales: Math.floor(Math.random() * 100) + 20 }))
        .sort((a, b) => b.sales - a.sales)
        .slice(0, 5);
    },
  },

  methods: {
    initCharts() {
      this.destroyCharts();

      setTimeout(() => {
        try {
          this.createMonthlySalesChart();
          this.createRevenueOrdersChart();
          this.createCategoryPieChart();
        } catch (error) {
          console.error("Error initializing charts:", error);
        }
      }, 100);
    },

    destroyCharts() {
      if (this.monthlySalesChartInstance) {
        this.monthlySalesChartInstance.destroy();
        this.monthlySalesChartInstance = null;
      }
      if (this.revenueOrdersChartInstance) {
        this.revenueOrdersChartInstance.destroy();
        this.revenueOrdersChartInstance = null;
      }
      if (this.categoryPieChartInstance) {
        this.categoryPieChartInstance.destroy();
        this.categoryPieChartInstance = null;
      }
    },

    createMonthlySalesChart() {
      const canvas = this.$refs.monthlySalesChart;
      if (!canvas) return;

      const ctx = canvas.getContext("2d");

      this.monthlySalesChartInstance = new ChartJS(ctx, {
        type: "bar",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
          ],
          datasets: [
            {
              label: "Revenue",
              data: [
                4200, 3800, 5100, 4500, 6200, 5800, 6800, 6500, 7300, 7900,
                9200,
              ],
              backgroundColor: "#8B7355",
              borderRadius: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: "bottom",
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return "₦" + value.toLocaleString();
                },
              },
            },
          },
        },
      });
    },

    createRevenueOrdersChart() {
      const canvas = this.$refs.revenueOrdersChart;
      if (!canvas) return;

      const ctx = canvas.getContext("2d");

      this.revenueOrdersChartInstance = new ChartJS(ctx, {
        type: "line",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
          ],
          datasets: [
            {
              label: "Revenue",
              data: [
                4200, 3800, 5100, 4500, 6200, 5800, 6800, 6500, 7300, 7900,
                9200,
              ],
              borderColor: "#8B7355",
              backgroundColor: "rgba(139, 115, 85, 0.1)",
              tension: 0.4,
              fill: true,
            },
            {
              label: "Orders",
              data: [142, 128, 173, 152, 198, 185, 215, 208, 234, 252, 294],
              borderColor: "#A89080",
              backgroundColor: "rgba(168, 144, 128, 0.1)",
              tension: 0.4,
              fill: true,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: "bottom",
            },
          },
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    },

    createCategoryPieChart() {
      const canvas = this.$refs.categoryPieChart;
      if (!canvas) return;

      const ctx = canvas.getContext("2d");

      this.categoryPieChartInstance = new ChartJS(ctx, {
        type: "doughnut",
        data: {
          labels: ["Dried Fruits", "Nuts", "Seeds", "Superfoods"],
          datasets: [
            {
              data: [39, 30, 16, 15],
              backgroundColor: ["#8B7355", "#A89080", "#C5ADA0", "#D9C7BA"],
              borderWidth: 2,
              borderColor: "#ffffff",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: "right",
              labels: {
                generateLabels: function (chart) {
                  const data = chart.data;
                  if (data.labels.length && data.datasets.length) {
                    return data.labels.map((label, i) => {
                      const value = data.datasets[0].data[i];
                      return {
                        text: `${label} ${value}%`,
                        fillStyle: data.datasets[0].backgroundColor[i],
                        hidden: false,
                        index: i,
                      };
                    });
                  }
                  return [];
                },
              },
            },
          },
        },
      });
    },
  },

  mounted() {
    this.$nextTick(() => {
      this.initCharts();
    });
  },

  beforeUnmount() {
    this.destroyCharts();
  },
};
</script>

<style scoped>
.bg-primary {
  background-color: #8b7355;
}

.text-primary {
  color: #8b7355;
}

.border-primary {
  border-color: #8b7355;
}

.hover\:bg-primary\/90:hover {
  background-color: rgba(139, 115, 85, 0.9);
}

.bg-primary\/10 {
  background-color: rgba(139, 115, 85, 0.1);
}
</style>
