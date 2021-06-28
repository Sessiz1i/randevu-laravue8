<template>
  <div class="container">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>
    <div v-if="this.feedbacks.length >0">
      <ul>
        <div :class="this.feedbacks[0].code ==='error' ? 'alert-danger': 'alert-success'" class="alert align-items-center" role="alert">
          <li v-for="(item, i) in feedbacks" :key="i">
            <svg class="bi flex-shrink-0" width="24" height="24" role="img" :aria-label="item.code ==='error' ? 'Danger:' : 'Success:'">
              <use :xlink:href=" item.code ==='success' ? '#check-circle-fill': '#exclamation-triangle-fill'"/>
            </svg>
            <strong>&nbsp;&nbsp; {{ item.message }}</strong>
          </li>
        </div>
      </ul>
    </div>
    <div class="card col-md-9 mx-auto">
      <div class="card-body">
        <div class="card-title h2">Randevu Oluştur</div>
        <div v-if="complateForm">
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
                  <div class="card">
                    <label class="form-check-label" :for="'hour-'+i">
                      <div class="card-body py-2">
                        <div class="form-check">
                          <input v-if="data.isActive" class="form-check-input" :value="data.id" v-model="workingHour" type="radio" :id="'hour-'+i">
                          <input v-else disabled class="form-check-input text-decoration-line-through" :value="data.id" v-model="workingHour" type="radio" :id="'hour-'+i">
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
</template>

<script>
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
  beforeMount() {

    axios.get(`http://localhost:3000/api/working-hours`)
        .then(res => {
          this.workingHours = res.data
        }).catch(err => console.log(err))
  },
  methods: {
    store() {
      this.feedbacks = [];
      if (this.notification_type && this.name && this.email && this.validateEmail(this.email) && this.phone && this.workingHour) {
        axios.post('http://localhost:3000/api/appointment-store', {
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
            this.complateForm = false
            axios.get(`http://localhost:3000/api/working-hours`)
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
      axios.get(`http://localhost:3000/api/working-hours/${this.date}`)
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
