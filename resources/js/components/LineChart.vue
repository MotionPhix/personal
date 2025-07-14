<template>
  <div ref="container" class="chart-container"></div>
</template>

<script>
import { onMounted, ref } from 'vue';
import { Line } from '@antv/g2plot';

export default {
  props: {
    data: Array,
  },
  setup(props) {
    const container = ref(null);

    const formatDate = (dateString) => {
      const options = { day: 'numeric', month: 'short', year: 'numeric' };
      return new Date(dateString).toLocaleDateString('en-GB', options);
    };

    onMounted(() => {
      const linePlot = new Line(container.value, {
        data: props.data,
        xField: 'date',
        yField: 'count',
        smooth: true,
        point: {
          size: 3,
          shape: 'circle',
        },
        tooltip: {

          formatter: (datum) => {
            return { name: 'Subscribers', value: datum.count };
          },

          // formatter: (datum) => {
          //   return {
          //     name: 'Subscribers',
          //     value: `${datum.count} on ${formatDate(datum.date)}`,
          //   };
          // },

        },
        xAxis: {
          title: {
            text: 'Date',
          },

          // label: {
          //   formatter: (value) => formatDate(value),
          // },
        },
        yAxis: {
          title: {
            text: 'Number of Subscribers',
          },
        },
        color: '#FE740C',
      });

      linePlot.render();
    });

    return { container };
  },
};
</script>
