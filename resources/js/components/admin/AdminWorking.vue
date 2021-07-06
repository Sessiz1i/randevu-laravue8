<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Pazartesi',en:'monday'}" :data="monday"/>
      </div>
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Salı',en:'tuesday'}" :data="tuesday"/>
      </div>
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Çarşamba',en:'wednesday'}" :data="wednesday"/>
      </div>
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Perşembe',en:'thursday'}" :data="thursday"/>
      </div>
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Cuma',en:'friday'}" :data="friday"/>
      </div>
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Cumartesi',en:'saturday'}" :data="saturday"/>
      </div>
      <div class="col-md-6">
        <working-item @add="add" @remove="remove" :day="{tr:'Pazar',en:'sunday'}" :data="sunday"/>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AdminWorking",
  data() {
    return {
      monday: [],
      tuesday: [],
      wednesday: [],
      thursday: [],
      friday: [],
      saturday: [],
      sunday: [],
    }
  },
  beforeMount() {
    this.getData()


  },
  created() {
  },
  methods: {
    getData() {
      axios.get('http://randevu-laravue8.test/api/admin/working-hour-list')
          .then(res => {
            (res.data.monday) ? this.monday = res.data.monday : [],
                (res.data.tuesday) ? this.tuesday = res.data.tuesday : [],
                (res.data.wednesday) ? this.wednesday = res.data.wednesday : [],
                (res.data.thursday) ? this.thursday = res.data.thursday : [],
                (res.data.friday) ? this.friday = res.data.friday : [],
                (res.data.saturday) ? this.saturday = res.data.saturday : [],
                (res.data.sunday) ? this.sunday = res.data.sunday : []
          }).catch(err => console.log(err))
    },
    add(data) {
      (!this[data.day].find(time => time === data.time)) ? this[data.day].push(data.time) : console.log('Veri var')

      axios.post('http://randevu-laravue8.test/api/admin/working-hour-store', {
        monday: this.monday,
        tuesday: this.tuesday,
        wednesday: this.wednesday,
        thursday: this.thursday,
        friday: this.friday,
        saturday: this.saturday,
        sunday: this.sunday
      }).then(async _ => {
        await this.getData()
      }).catch(err => console.log(err))

    },
    remove(data) {
      this[data.day] = this[data.day].filter(time => time !== data.time)
      axios.post('http://randevu-laravue8.test/api/admin/working-hour-store', {
        monday: this.monday,
        tuesday: this.tuesday,
        wednesday: this.wednesday,
        thursday: this.thursday,
        friday: this.friday,
        saturday: this.saturday,
        sunday: this.sunday
      }).then().catch(err => console.log(err))
    }
  },

}
</script>

<style scoped>

</style>