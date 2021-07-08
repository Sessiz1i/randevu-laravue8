<template>
  <div class="container">
    <!--    <div >
          <i class="far fa-check-circle text-success text-center mx-auto col-md-12" style="font-size: 250px; font-weight: bolder"></i>
        </div>-->
    <div class="col-md-9 mx-auto">
            <div v-if="this.feedbacks.length >0">
              <div :class="this.feedbacks[0].code ==='error' ? 'alert-danger': ''" class="alert" role="alert">
                <li v-for="(item, i) in feedbacks" :key="i"
                    :class="feedbacks[0].code ==='error' ? 'd-flex align-items-center': 'text-success text-center h4'" class=" " style="font-weight: bolder">
                  <i :class="feedbacks[0].code ==='error' ? 'fas fa-times': ''" class=" fa-lg">&nbsp;</i>
                  <strong>{{ item.message }}</strong>
                </li>
              </div>
            </div>
      <div class="card" v-if="!this.randevu">
        <div class="card-body">
          <div class="card-title h2">Randevunuzu görmek için bilgilerinizi giriniz</div>
          <div>
            <div class="row">
              <div class="col-md-6 mx-auto">
                <div class="form-group">
                  <input type="text" v-model="code" placeholder="Randevu Kodunuz..." class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mt-2 mx-auto">
                <div class="form-group">
                  <button type="submit" @click="search" class="btn btn-outline-success btn-block text-uppercase">Randevu Sorgula</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card my-5" v-else>
        <div class="card-body">
          <div class="card-title h4">Randevu Detayı</div>
          <div class="input-group">
            <div class="col-3"><span>İsim</span></div>
            <div class="col-9"><span>: {{ randevu.fullName }}</span></div>
          </div>
          <div class="input-group">
            <div class="col-3"><span>Telefon</span></div>
            <div class="col-9"><span>: {{ randevu.phone }}</span></div>
          </div>
          <div class="input-group">
            <div class="col-3"><span>Email</span></div>
            <div class="col-9"><span>: {{ randevu.email }}</span></div>
          </div>
          <div class="input-group">
            <div class="col-3"><span>Tarih</span></div>
            <div class="col-9"><span>: {{ date[2] }}-{{ date[1] }}-{{ date[0] }}</span></div>
          </div>
          <div class="input-group">
            <div class="col-3"><span>Saat</span></div>
            <div class="col-9"><span>: {{ randevu.workingHour }}</span></div>
          </div>
          <div class="input-group" v-for="(note,i) in randevu.note" :key="i">
            <div class="col-3"><span>Not {{ i + 1 }} </span></div>
            <div class="col-9">
              <span>: {{ note.text }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "RandevuDetail",
  data() {
    return {
      code: '',
      randevu: '',
      date: '',
      feedbacks:[]

    }
  },
  methods: {
    search() {
      this.feedbacks=[]
      axios.post(`./api/appointment-detail`,{
        code:this.code
      })
          .then(res =>{
            console.log(res)
            if (res.data.status === false){
              this.feedbacks.push({code:"error",message:res.data.message})
            }else{
              this.date = res.data.date.split('-')
              this.randevu = res.data
            }
          }).catch(err => console.log(err))
    }
  },
  created() {
  }
}
</script>
<!--created_at: "2021-07-07 21:38:17 000000Z"-->
<style scoped>
li {
  list-style: none;
}
</style>
