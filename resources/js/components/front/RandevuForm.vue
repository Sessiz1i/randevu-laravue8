<template>
  <div class="container">
    <div v-if="complateForm == false">
      <i class="far fa-check-circle text-success text-center mx-auto col-md-12" style="font-size: 250px; font-weight: bolder"></i>
    </div>
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
      <div class="card" v-if="complateForm">
        <div class="card-body">
          <div class="card-title h2">Randevu Oluştur</div>
          <div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <input type="text" v-model="name" placeholder="İsim Soyisim Giriniz..." class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="email" v-model="email" placeholder="E-mail Giriniz..." class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="tel" v-mask="'##-###-###-##-##'" v-model="phone" placeholder="Telefon Numarası Giriniz..." class="form-control">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <input type="date" @change="selectDate" :min="toDay" v-model="date" class="form-control">
                </div>
              </div>
              <div class="col-6 col-md-4 col-xl-3" v-for="(data,i) in workingHours" :key="i">
                <ul class="list-group">
                  <li class="mb-1 list-group">
                    <div class="card" v-bind:class="data.isActive ? '':'bg-secondary'">
                      <label class="form-check-label" :for="'hour-'+i">
                        <div class="card-body py-2">
                          <div class="form-check">
                            <input v-if="data.isActive" class="form-check-input" :value="data.hours" v-model="workingHour" type="radio" :id="'hour-'+i">
                            <input v-else disabled class="form-check-input text-decoration-line-through bg-gr" :value="data.id" v-model="workingHour" type="radio" :id="'hour-'+i">
                            <span v-if="data.isActive">{{ data.hours }}</span>
                            <span v-else style="text-decoration: line-through">{{ data.hours }}</span>
                          </div>
                        </div>
                      </label>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="col-md-12 mt-2">
                <div class="form-group">
                  <textarea type="date" v-model="text" rows="5" class="form-control" placeholder="Notunuzu Ekleyebilirsiniz..."></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-0">
                <label>Bildirim Türünü Seçiniz</label>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-4 col-xl-3">
                <ul class="list-group">
                  <li class="mb-1 list-group">
                    <div class="card">
                      <label class="form-check-label">
                        <div class="card-body py-2">
                          <div class="form-check">
                            <input class="form-check-input" value="0" v-model="notification_type" type="radio">
                            <span>Sms</span>
                          </div>
                        </div>
                      </label>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="col-6 col-md-4 col-xl-3">
                <ul class="list-group">
                  <li class="mb-1 list-group">
                    <div class="card">
                      <label class="form-check-label">
                        <div class="card-body py-2">
                          <div class="form-check">
                            <input class="form-check-input" value="1" v-model="notification_type" type="radio">
                            <span>Email</span>
                          </div>
                        </div>
                      </label>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mt-2">
                <div class="form-group">
                  <button type="submit" @click="store" class="btn btn-outline-success btn-block text-uppercase">Randevu oluştur</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import io from "socket.io-client"
const socket = io('http://localhost:3000')
export default {
  name: "RandevuForm",
  data() {
    return {
      name: null,
      email: null,
      phone: null,
      text: null,
      feedbacks: [],
      workingHour: null,
      notification_type: null,
      workingHours: [],
      toDay: new Date().toISOString().slice(0, 10),
      date: new Date().toISOString().slice(0, 10),
      complateForm: true
    }
  },
  created() {
    console.log()
  },
  beforeMount() {
      axios.get(`./api/working-hours`)
        .then(res => {
          this.workingHours = res.data
        })
        .catch(err => console.log(err))
  },
  methods: {
    store() {
      this.feedbacks = [];
      if (this.notification_type && this.name && this.email && this.validateEmail(this.email) && this.phone && this.workingHour) {
        axios.post('./api/appointment-store', {
          fullName: this.name,
          email: this.email,
          phone: this.phone,
          text: this.text,
          workingHour: this.workingHour,
          notification_type: this.notification_type,
          date: this.date
        }).then(res => {
          if (res.data.status === false) {
            this.feedbacks.push({code: "error", message: res.data.message})
          } else {
            this.feedbacks.push({code: "success", message: res.data.message})

            socket.emit('new_appointment_create')

            this.complateForm = false
            axios.get(`./api/working-hours`)
                .then(res => {
                  this.workingHours = res.data
                }).catch(err => console.log(err))
          }
        }).catch(err => console.log(err))
      }
      if (!this.name) {
        this.feedbacks.push({code: "error", message: "İsim Soyisim alanı gereklidir."})
      }
      if (!this.email) {
        this.feedbacks.push({code: "error", message: "Email alanı gereklidir."})
      }
      if (this.email && !this.validateEmail(this.email)) {
        this.feedbacks.push({code: "error", message: "Geçerli bir Email adresi giriniz."})
      }
      if (!this.phone) {
        this.feedbacks.push({code: "error", message: "Telefon alanı gereklidir."})
      }
      if (!this.workingHour) {
        this.feedbacks.push({code: "error", message: "Randevu saati seçilmelidir."})
      }
      if (!this.notification_type) {
        this.feedbacks.push({code: "error", message: "Bildirim türünü seçiniz"})
      }
    },
    selectDate() {
      axios.get(`./api/working-hours/${this.date}`)
          .then(res => {
            this.workingHours = res.data
          }).catch(err => console.log(err))
    },
    validateEmail(email) {
      const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
    }
  },
}
</script>

<style scoped>
li {
  list-style: none;
}
</style>
