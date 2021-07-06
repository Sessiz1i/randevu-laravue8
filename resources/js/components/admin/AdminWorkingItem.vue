<template>
  <div class="card m-3">
    <div class="card-header d-flex justify-content-between">
      <h5 class="card-title mb-0">{{ day.tr }}</h5>
      <i class="text-primary fas fa-edit fa-lg pointer mt-1" @click="showForm" role="button"></i>
    </div>
    <div class="card-body">
      <h5 class="card-title">Randevu Saatlerini belirle.</h5>
      <div v-if="isShow">
        <div class="form-row align-items-center justify-content-center">
          <div class="col-auto">
            <div class="input-group m-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="far fa-clock"></i></div>
              </div>
              <input type="text" v-model="time" v-mask="'##:## - ##:##'" class="form-control" id="inlineFormInputGroup ready" placeholder="Randevu Saati Ekle">
              <button v-if="this.time.length === 13" @click="addTime" class=" btn btn-primary ml-2">Ekle</button>
            </div>
          </div>
        </div>
        <i class="  fa-2x mt-1" @toggle="isShow" role="button"></i>
      </div>
      <div class="row align-items-center justify-content-center">
        <div class="card col-auto px-2 p-1 m-1 grow-2" v-for="(item,i) in data" :key="i">
          <span class="text-center">{{ item }}&nbsp;<i @click="removeTime(item)" class="fas fa-times text-danger" role="button"></i></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AdminWorkingItem",
  props: ['data', 'day'],
  data() {
    return {
      isShow: false,
      time: []
    }
  },
  methods: {
    showForm() {
      this.isShow = !this.isShow
    },
    addTime() {
      this.$emit('add', {time: this.time, day: this.day.en})
      this.time = ''
      this.showForm()
    },
    removeTime(time) {

      this.$emit('remove', {time: time, day: this.day.en})
    }
  }
}

</script>

<style scoped>

</style>