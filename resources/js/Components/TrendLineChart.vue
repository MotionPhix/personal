<template>
  <div ref="container" class="chart-container"></div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { Line } from '@antv/g2plot';

const props = defineProps({
  data: Array
})

const container = ref(null);

onMounted(() => {

  const linePlot = new Line(container.value, {
    data: props.data,
    xField: 'date',
    yField: 'value',
    seriesField: 'category',
    smooth: true,
    xAxis: {
      title: {
        text: 'Date',
      },
    },

    yAxis: {
      title: {
        text: 'Number of Users',
      },

      grid: {
        line: {
          style: {
            lineWidth: 0.5,
          },
        },
      },
    },

    point: {
      shape: 'circle',
      size: 2,
      style: () => {
        return {
          fillOpacity: 0,
          stroke: 'transparent',
        };
      },
    },

    // legend: false,

    lineStyle: {
      // lineWidth: 1.5,
    },

    color: ['#2358aa', '#D62A0D'],

    tooltip: {
      showMarkers: false,
      follow: false,
      position: 'top',
      customContent: () => null,
    },

    theme: {
      geometries: {
        point: {
          circle: {
            active: {
              style: {
                r: 4,
                fillOpacity: 1,
                stroke: '#000',
                lineWidth: 1,
              },
            },
          },
        },
      },
    },

    interactions: [{ type: 'marker-active' }, { type: 'brush' }],
  });

  linePlot.render();
});
</script>
