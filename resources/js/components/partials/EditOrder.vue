<template>
  <div style="width: 100%">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a
          class="nav-link active"
          id="client-tab"
          data-toggle="tab"
          href="#client"
          role="tab"
          aria-controls="client"
          aria-selected="true"
        >Cliente</a>
      </li>
      <li class="nav-item">
        <a
          class="nav-link"
          id="reception-tab"
          data-toggle="tab"
          href="#reception"
          role="tab"
          aria-controls="reception"
          aria-selected="false"
        >Recepción</a>
      </li>
      <li v-if="reparacion" class="nav-item">
        <a
          class="nav-link"
          id="repair-tab"
          data-toggle="tab"
          href="#repair"
          role="tab"
          aria-controls="repair"
          aria-selected="false"
        >Revisión</a>
      </li>
      <li v-if="entrega.fechaEntrega" class="nav-item">
        <a
          class="nav-link"
          id="delivery-tab"
          data-toggle="tab"
          href="#delivery"
          role="tab"
          aria-controls="delivery"
          aria-selected="false"
        >Entrega</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div
        class="tab-pane fade show active"
        id="client"
        role="tabpanel"
        aria-labelledby="client-tab"
      >
        <div class="row p-3">
          <div class="col-6">
            <!-- Cedula Nuevo Cliente -->
            <label>Cédula</label>
            <div class="input-group">
              <input
                v-model="cliente.cedula"
                type="text"
                class="form-control"
                placeholder="Cedula del cliente"
                aria-label="Cedula del cliente"
                :class="[ errors.cedula ? 'is-invalid' : '' ]" 
              >
              <message-error :message="errors.cedula"></message-error>
            </div>
          </div>
          <!-- Cedula Nuevo Cliente -->
          <div class="col-6">
            <!-- Nombres -->
            <div class="form-group">
              <label>Nombres</label>
              <input
                :readonly="!isAdmin"
                v-model="cliente.nombres"
                type="text"
                class="form-control"
                :class="[ errors.nombres ? 'is-invalid' : '' ]"  
                @input="setFlag(0)"
              >
              <message-error :message="errors.nombres"></message-error>
            </div>
          </div>
          <!-- Nombres -->
          <div class="col-6">
            <!-- Apellidos -->
            <div class="form-group">
              <label>Apellidos</label>
              <input
                :readonly="!isAdmin"
                v-model="cliente.apellidos"
                type="text"
                class="form-control"
                :class="[ errors.apellidos ? 'is-invalid' : '' ]"   
                @input="setFlag(0)"
              >
              <message-error :message="errors.apellidos"></message-error>
            </div>
          </div>
          <!-- Apellidos -->
          <div class="col-6">
            <!-- Teléfono -->
            <div class="form-group">
              <label>Teléfono</label>
              <input
                :readonly="!isAdmin"
                v-model="cliente.telefono"
                type="text"
                class="form-control"
                :class="[ errors.telefono ? 'is-invalid' : '' ]"  
                @input="setFlag(0)"
              >
               <message-error :message="errors.telefono"></message-error>
            </div>
          </div>
          <!-- Teléfono -->
          <div class="col-6">
            <!-- Direcciones -->
            <div class="form-group">
              <label>Dirección</label>
              <select :disabled="!isAdmin" class="custom-select" :class="[ errors.identificador_direccion ? 'is-invalid' : '' ]"  @change="setFlag(0)">
                <option value>Selecione una dirección</option>
                <option
                  v-for="address in addresses"
                  :key="'addr-'+address.identificador"
                  :selected="address.nombre === cliente.nombre_direccion"
                  :value="address.identificador"
                >{{address.nombre}}</option>
              </select>
              <message-error :message="errors.identificador_direccion"></message-error>
            </div>
          </div>
          <!-- Direcciones -->
          <div class="col-6">
            <!-- Area -->
            <div class="form-group">
              <label>Área</label>
              <select :disabled="!isAdmin" class="custom-select"                 :class="[ errors.identificador_area ? 'is-invalid' : '' ]" @change="setFlag(0)">
                <option value>Selecione una area</option>
                <option
                  v-for="area in areas"
                  :key="'area-'+area.identificador"
                  :selected="area.nombre === cliente.nombre_area"
                  :value="area.identificador"
                >{{area.nombre}}</option>
              </select>
              <message-error :message="errors.identificador_area"></message-error>
            </div>
          </div>
          <!-- Area -->
        </div>
        <a @click.prevent="saveChanges" href="#" class="btn btn-success m-3 float-right">Guardar</a>
      </div>
      <div class="tab-pane fade" id="reception" role="tabpanel" aria-labelledby="reception-tab">
        <div class="row p-3">
          <div class="col-6">
            <div class="form-group">
              <label>Equipo</label>
              <select :disabled="!isAdmin" class="custom-select" required>
                <option value>Selecione una dispositivo</option>
                <option
                  v-for="device in devices"
                  :key="'dev-'+device.identificador"
                  :selected="device.nombre === equipo.nombre"
                  :value="device.identificador"
                >{{device.nombre}}</option>
              </select>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Marca</label>
              <select :disabled="!isAdmin" class="custom-select" required>
                <option value>Selecione una marca</option>
                <option
                  v-for="brand in brands"
                  :key="'brand-'+brand.identificador"
                  :selected="brand.nombre === equipo.marca"
                  :value="brand.identificador"
                >{{brand.nombre}}</option>
              </select>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Modelo</label>
              <input
                :readonly="!isAdmin"
                v-model="equipo.modelo"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          <div v-if="equipo.bienNacional" class="col-6">
            <div class="form-group">
              <label>Bien nacional</label>
              <input
                :readonly="!isAdmin"
                v-model="equipo.bienNacional"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          

          <div class="col-6">
            <div class="form-group">
              <label>Tecnico</label>
              <input
                :readonly="!isAdmin"
                v-model="tecnicoRecepcion.nombre"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Bien nacional</label>
              <input
                :readonly="!isAdmin"
                v-model="equipo.bienNacional"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>
        </div>
        <a @click.prevent="saveChanges" href="#" class="btn btn-success m-3 float-right">Guardar</a>
      </div>
      <div class="tab-pane fade" id="repair" role="tabpanel" aria-labelledby="repair-tab">
        <div class="row p-3">
          <div class="col-6">
            <div class="form-group">
              <label>Fecha de Revisión</label>
              <input
                :readonly="!isAdmin"
                type="date"
                v-model="reparacion.fechaCreacion"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Estado</label>
              <input
                :readonly="!isAdmin"
                v-model="estados[reparacion.estado]"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Tecnico</label>
              <input
                :readonly="!isAdmin"
                v-model="tecnico"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-12">
            <div class="form-group">
              <label>Detalle</label>
              <textarea
                v-model="reparacion.detalle"
                type="text"
                class="form-control"
                required
                style="resize: none"
              ></textarea>
            </div>
          </div>
        </div>

        <a @click.prevent="saveChanges" href="#" class="btn btn-success m-3 float-right">Guardar</a>
      </div>
      <div class="tab-pane fade" id="delivery" role="tabpanel" aria-labelledby="delivery-tab">
        <div class="row p-3">
          <div class="col-6">
            <div class="form-group">
              <label>Fecha de Entrega</label>
              <input
                :readonly="!isAdmin"
                type="date"
                v-model="entrega.fechaEntrega"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Cedula Retiro</label>
              <input
                v-if="entrega.cedulaEntrega"
                v-model="entrega.cedulaEntrega"
                type="text"
                class="form-control"
                required
              >
              <input
                :readonly="!isAdmin"
                v-else
                v-model="cliente.cedula"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Nombre Retiro</label>
              <input
                :readonly="!isAdmin"
                v-model="entrega.nombreEntrega"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label>Tecnico</label>
              <input
                :readonly="!isAdmin"
                v-model="entrega.tecnicoEntrega"
                type="text"
                class="form-control"
                required
              >
            </div>
          </div>
        </div>
        <a @click.prevent="saveChanges" href="#" class="btn btn-success m-3 float-right">Guardar</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  import MessageError from '../partials/messageError';
  name: "edit-order",
  props: ["id"],
  components:{
    MessageError
  },
  data() {
    return {
      errors: [],
      cliente: "",
      equipo: "",
      reparacion: "",
      areas: "",
      addresses: "",
      brands: "",
      devices: "",
      estados: {
        repaired: "Reparado",
        "without repair": "Sin reparación"
      },
      tecnico: "",
      entrega: {
        fechaEntrega: null,
        nombreEntrega: null,
        cedulaEntrega: null,
        tecnicoEntrega: null
      },
      isAdmin: this.$session.get("isAdmin"),
      flags: [0, 0, 0, 0]
    };
  },
  mounted() {
    this.getOrder();
    this.getAddresses();
    this.getAreas();
    this.getSubDevice();
    this.getBrands();
    this.getUsers();
  },
  methods: {
    formatDate(date) {
      if (date == "" || date == null) return "";
      let fecha = date.split(" ")[0].split("-");

      return `${fecha[2]}-${fecha[1]}-${fecha[0]}`;
    },
    finishEditing() {
      eventBus.$emit("editingOrder", false);
    },
    getOrder() {
      axios
        .get("api/orders/" + this.id)
        .then(response => {
          console.log(response.data.data);
          this.cliente = response.data.data.cliente.data;
          this.equipo = response.data.data.equipo.data;
          this.reparacion = response.data.data.reparacion.data;

          this.reparacion.fechaCreacion = this.formatDate(
            response.data.data.fechaCreacion
          );

          this.tecnico =
            this.reparacion.tecnico.data.nombres +
            " " +
            this.reparacion.tecnico.data.apellidos;

          this.entrega = {
            cedulaEntrega: response.data.data.cedulaEntrega,
            nombreEntrega: response.data.data.nombreEntrega,
            fechaEntrega: this.formatDate(response.data.data.fechaEntrega),
            tecnicoEntrega: response.data.data.tecnicoEntrega
          };

          this.tecnicoRecepcion = response.data.data.tecnico.data;
          this.tec;

          this.getTec();
        })
        .catch(error => {
          console.log(error);
        });
    },
    getUsers() {
      this.$emit("loading-data", true);
      axios
        .get("/api/users/")
        .then(response => {
          this.$emit("loading-data", false);
        })
        .catch(error => {
          this.$emit("error", error);
          this.$emit("loading-data", false);
        });
    },
    getAreas() {
      axios
        .get("api/areas")
        .then(response => {
          this.areas = response.data.data;
        })
        .catch(error => {
          console.log(error);
          this.areas = [];
        });
    },
    getAddresses() {
      axios
        .get("/api/addresses")
        .then(response => {
          this.addresses = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    getSubDevice() {
      axios
        .get("/api/sub-devices")
        .then(response => {
          this.devices = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    getBrands() {
      axios
        .get("/api/brands")
        .then(response => {
          this.brands = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    setFlag(tab) {
      this.flags[tab] += 1;
    },

    saveChanges() {
      console.log(this.flags);

      if (this.flags[0]) this.updateClient();
      if (this.flags[1]) this.updateRecepcion();
      if (this.flags[2]) this.updateRevicion();
      if (this.flags[3]) this.updateDelivery();
    }
  }
};
</script>

<style>
#myTabContent {
  border: 1px solid #dee2e6 !important;
  border-top: none !important;
}
.nav-link.active {
  background-color: var(--dark) !important;
  color: white !important;
}
#myTab .nav-link {
  color: var(--dark);
}
</style>
