<template>
  <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-waiting" role="tabpanel" aria-labelledby="waiting-tab">
      <div class="row">
        <div v-for="item in items" :key="item.id" class="col-md-4 mb-3 justify-content-center">
          <div class="card">
            <div class="card-header">
              <span class="h5 font-weight-bold">Randevu #{{ item.id }}</span>
              <i @click="itemId(item)" role="button" data-toggle="modal" data-target="#exampleModal"
                 class="text-primary fas fa-edit fa-lg float-right pointer mt-1 ml-1"></i>
              <span v-if="item.isActive == 0" class="badge badge-warning float-right mt-1">Yeni</span>
              <span v-if="item.isActive == 1" class="badge badge-success float-right mt-1">Onaylı</span>
              <span v-if="item.isActive == 2" class="badge badge-danger float-right mt-1">İptal</span>

            </div>
            <div class="card-body pb-2">
              <span>İsim : {{ item.fullName }}</span><br>
              <span>Tarih : {{ item.date }}</span><br>
              <span>Saat : {{ item.workingHour }}</span>
              <div v-if="item.isActive == 0" class="row d-flex justify-content-end p-0">
                <button @click="$emit('confirm',item.id,1)" class="btn btn-sm btn-outline-success">Onayla</button>
                <button @click="$emit('confirm',item.id,2)" type="button" class="btn btn-sm btn-outline-danger ml-2 mr-1">İptal</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ['items'],
  methods:{
    itemId(item){
      this.$emit('openModal',item)
    }
  }
}
</script>

<style>
.nav-link.active {
  color: #11d262;
}
</style>