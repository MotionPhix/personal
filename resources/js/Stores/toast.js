import {reactive} from "vue";

export default reactive({
  items: [],

  duration: 6000,

  add(toast) {
    this.items.unshift({
      key: Symbol(),
      ...toast
    });
  },

  setDuration(period) {
    this.duration = period
  },

  remove(index) {

    setTimeout(
      () => {
        this.items.splice(index, 1)
        this.setDuration(6000) // reset timer for the next toast
      }, this.duration
    )

  },

  reset() {
    this.items = []
  }
});
