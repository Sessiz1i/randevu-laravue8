<template>
  <div class="container">
    <div class="tab-content" id="pills-tabContent">
      <!-- Onay Bekleyen-->
      <div class="tab-pane fade show active" id="pills-waiting" role="tabpanel" aria-labelledby="pills-waiting-tab">
        <h1>Onay Bekleyen</h1>
        <AppointmentItem @openModal="openModal" @confirm="confirm" :items="this.waiting.data"/>
        <pagination :limit="1" :data="this.waiting" @pagination-change-page="getData"/>
      </div>
      <!-- Gün'ün Randevuları-->
      <div class="tab-pane fade" id="pills-today" role="tabpanel" aria-labelledby="pills-today-tab">
        <h1>Gün'ün Randevuları</h1>
        <AppointmentItem @openModal="openModal" :items="today.data"/>
        <pagination :limit="1" :data="today" @pagination-change-page="getData"/>
      </div>
      <!-- Gelecek Randevular-->
      <div class="tab-pane fade" id="pills-list" role="tabpanel" aria-labelledby="pills-list-tab">
        <h1>Gelecek Randevular</h1>
        <AppointmentItem @openModal="openModal" :items="allList.data"/>
        <pagination :limit="1" :data="allList" @pagination-change-page="getData"/>
      </div>
      <!-- Geçmiş Randevular-->
      <div class="tab-pane fade" id="pills-last" role="tabpanel" aria-labelledby="pills-last-tab">
        <h1>Geçmiş Randevular</h1>
        <AppointmentItem @openModal="openModal" :items="this.last.data"/>
        <pagination :limit="1" :data="last" @pagination-change-page="getData"/>
      </div>
      <!-- İptal Randevular-->
      <div class="tab-pane fade" id="pills-cancel" role="tabpanel" aria-labelledby="pills-cancel-tab">
        <h1>İptal Randevular</h1>
        <AppointmentItem @openModal="openModal" :items="cancel.data"/>
        <pagination :limit="1" :data="cancel" @pagination-change-page="getData"/>
      </div>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Randevu #{{ modalItem.id }} Detayları</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group">
              <div class="col-3"><span>İsim</span></div>
              <div class="col-9"><span>: {{ modalItem.fullName }}</span></div>
            </div>
            <div class="input-group">
              <div class="col-3"><span>Telefon</span></div>
              <div class="col-9"><span>: {{ modalItem.phone }}</span></div>
            </div>
            <div class="input-group">
              <div class="col-3"><span>Email</span></div>
              <div class="col-9"><span>: {{ modalItem.email }}</span></div>
            </div>
            <div class="input-group">
              <div class="col-3"><span>Tarih</span></div>
              <div class="col-9"><span>: {{ modalItem.date }}</span></div>
            </div>
            <div class="input-group">
              <div class="col-3"><span>Saat</span></div>
              <div class="col-9"><span>: {{ modalItem.workingHour }}</span></div>
            </div>
            <div class="input-group">
              <div class="col-3"><span>Bildirim</span></div>
              <div class="col-9">
                <span v-if="modalItem.notification_type === 0">: SMS</span>
                <span v-if="modalItem.notification_type === 1">: Email</span>
              </div>
            </div>
            <div class="input-group" v-for="(note,i) in modalNote" :key="i">
              <div class="col-3"><span>Not {{ i + 1 }} </span></div>
              <div class="col-9">
                <span>: {{ note.text }}</span>
              </div>
            </div>
            <div class="col-md-12 mt-2">
              <div class="form-group">
                <textarea type="date" v-model="text" rows="5" class="form-control" placeholder="Not Ekle..."></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button @click="addNote(modalItem.id)" type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import AppointmentItem from './AppointmentItem'
import io from "socket.io-client"

const socket = io('http://localhost:3000')
export default {
  components: {AppointmentItem},
  props: ['item'],
  data() {
    return {
      text: '',
      modalNote: [],
      modalItem: [],
      waiting: {
        data: []
      },
      today: {
        data: []
      },
      allList: {
        data: []
      },
      last: {
        data: []
      },
      cancel: {
        data: []
      },
    }
  },
  created() {
    this.getData()
    socket.on('admin_appointment_list', () => {
      console.log("Veri geldi")
      this.getData()
    })

  },
  methods: {
    addNote(id) {
      axios.post('./api/admin/appointment-note-store', {
        appointmentId: id,
        text: this.text
      }).then(res => {
        this.text = ''
        this.modalNote = res.data
      }).catch(err => console.log(err))

    },
    getData(page) {
      if (typeof page === 'undefined') {
        page = 1
      }
      axios.get(`http://randevu-laravue8.test/api/admin/all?page=${page}`)
          .then(res => {
            this.waiting = res.data.waiting
            this.today = res.data.today
            this.allList = res.data.allList
            this.last = res.data.last
            this.cancel = res.data.cancel
          })
    },
    confirm(id, type) {
      axios.post(`http://randevu-laravue8.test/api/admin/confirmation`, {
        id: id,
        type: type
      }).then(_ => this.getData())
          .catch(err => console.log(err))
    },
    openModal(item) {
      this.addNote(item.id)
      this.modalItem = item
    },
  }
}
</script>

<style>
.nav-link.active {
  color: #11d262;
}
</style>