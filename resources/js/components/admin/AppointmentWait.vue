<template>
  <div class="container">
    <h1>Onay Bekleyen</h1>
    <AppointmentItem :data="items.data"/>
    <pagination :limit="1" :data="this.items" @pagination-change-page="getData"/>
  </div>
</template>

<script>
import AppointmentItem from './AppointmentItem'
export default {
  components:{AppointmentItem},
  data() {
    return {
      items: {
        data: []
      },
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData(page) {

      if (!page) page = 1
      axios.get(`http://localhost:3000/api/admin/waiting-list?page=${page}`)
          .then(res => this.items = res.data)
    },
  }
}
</script>

<style>
.nav-link.active {
  color: #11d262;
}
</style>