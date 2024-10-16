<template>
  <div ref="container" class="chart-container"></div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { Line } from '@antv/g2plot';

const props = defineProps({
  data: Array
})

const seriesKey = 'series';
const valueKey = 'value';

function processData(data, yFields, meta) {
  const result = [];
  data.forEach((d) => {
    yFields.forEach((yField) => {
      const name = meta?.[yField]?.alias || yField;
      result.push({ ...d, date: d.date, [seriesKey]: name, [valueKey]: d[yField] });
    });
  });
  return result;
}

const container = ref(null);

const formatDate = (dateString) => {
  const options = { day: 'numeric', month: 'short', year: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-GB', options);
};

onMounted(() => {
  const meta = {
    date: {
      alias: '销售日期',
    },

    subscribed: {
      alias: 'Subscribed',
    },

    unsubscribed: {
      alias: 'Unsubscribed',
    },
  };

  const linePlot = new Line(container.value, {
    data: processData(props.data, ['subscribed', 'unsubscribed'], meta),
    xField: 'date',
    yField: valueKey,
    seriesField: seriesKey,
    appendPadding: [0, 8, 0, 0],
    xAxis: {
      type: 'time',
      title: {
        text: 'Date',
      },
    },
    yAxis: {
      title: {
        text: 'Number of Users',
      },
    },
  });

  linePlot.render();
});
</script>
