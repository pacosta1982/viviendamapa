
<template>
  <div>
    <div class="flex flex-wrap -mx-3 mb-2">
      <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
        <label
          class="block uppercase tracking-wide text-white text-xs font-bold mb-2"
          for="grid-state"
        >Departamento</label>
        <div class="relative">
          <select
            v-model="selectedCategory"
            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            id="grid-state"
          >
            <option value>TODOS</option>
            <option v-for="(d) in arrayDepartamento" :key="d.DptoId" :value="d.DptoId">{{d.DptoNom}}</option>
          </select>
          <div
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
          >
            <svg
              class="fill-current h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
            >
              <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
            </svg>
          </div>
        </div>
      </div>
      <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
        <label
          class="block uppercase tracking-wide text-white text-xs font-bold mb-2"
          for="grid-state"
        >Programa</label>
        <div class="relative">
          <select
            v-model="selectedProgram"
            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            id="grid-state"
          >
            <option value>TODOS</option>
            <option v-for="(p) in arrayProgramas" :key="p.id" :value="p.name">{{p.name}}</option>
          </select>
          <div
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
          >
            <svg
              class="fill-current h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
            >
              <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
            </svg>
          </div>
        </div>
      </div>
      <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
        <label
          class="block uppercase tracking-wide text-white text-xs font-bold mb-2"
          for="grid-city"
        >Sat/Empresa</label>
        <input
          v-model="inputSat"
          class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
          id="grid-city"
          type="text"
          placeholder="Ingrese Sat/Empresa"
        />
      </div>
    </div>

    <GmapMap
      :center="{lat:-23.5152472, lng:-58.4218982}"
      :zoom="7"
      map-type-id="terrain"
      style="width: 100%; height: 800px"
    >
      <gmap-info-window
        :options="infoOptions"
        :position="infoPosition"
        :opened="infoOpened"
        @closeclick="infoOpened=false"
      >
        <p>
          <strong>Proyecto: {{infoContent}}</strong>
          <br />
          Programa: {{infoProgram}}
          <br />
          Departamento: {{infoDepartamento}}
          <br />
          Distrito: {{infoDistrito}}
          <br />
          Sat/Empresa: {{infoSat}}
          <br />
          Estado: {{infoEstado}}
          <br />
          Total Viviendas: {{infoCasas}}
          <br />
          Monto Total: {{ infoMonto }}Gs.
          <br />
          Avance: {{infoAvance}} %
          <br />
        </p>
      </gmap-info-window>
      <GmapMarker
        :key="m.id"
        v-for="(m) in filteredProducts"
        :position="getPosition(m)"
        :clickable="true"
        @click="toggleInfo(m,m.id)"
      />
    </GmapMap>
  </div>
</template>
<script>
import { gmapApi } from "vue2-google-maps";

export default {
  props: ["data", "departamento", "programas"],
  data() {
    return {
      arrayProyecto: this.data,
      arrayDepartamento: this.departamento,
      arrayProgramas: this.programas,
      arrayFiltrado: [],
      filtros: {},
      infoPosition: null,
      infoContent: null,
      infoProgram: null,
      infoDepartamento: null,
      infoDistrito: null,
      infoSat: null,
      infoCasas: null,
      infoAvance: null,
      infoEstado: null,
      infoMonto: null,
      infoOpened: false,
      infoCurrentKey: null,
      selectedCategory: "",
      selectedProgram: "",
      inputSat: "",
      infoOptions: {
        pixelOffset: {
          width: 0,
          height: -35,
        },
      },
    };
  },
  methods: {
    getPosition: function (marker) {
      return {
        lat: parseFloat(marker.lat),
        lng: parseFloat(marker.lng),
      };
    },
    toggleInfo: function (marker, key) {
      this.infoPosition = this.getPosition(marker);
      this.infoContent = marker.proyecto;
      this.infoProgram = marker.programa;
      this.infoDepartamento = marker.departamento;
      this.infoDistrito = marker.distrito;
      this.infoSat = marker.sat;
      this.infoCasas = marker.cantidad_viviendas;
      this.infoAvance = marker.avance;
      this.infoMonto = marker.monto_total;
      this.infoEstado = marker.estado;

      if (this.infoCurrentKey == key) {
        this.infoOpened = !this.infoOpened;
      } else {
        this.infoOpened = true;
        this.infoCurrentKey = key;
      }
    },
  },
  computed: {
    google: gmapApi,
    filteredProducts() {
      const getValue = (value) =>
        typeof value === "string" ? value.toUpperCase() : value;
      const getSat = (value) =>
        typeof value === "string" ? value.toUpperCase() : value;
      this.arrayFiltrado = this.arrayProyecto;

      if (this.selectedCategory !== "") {
        this.filtros.departamento_id = [this.selectedCategory];
      } else {
        delete this.filtros.departamento_id;
      }

      if (this.selectedProgram !== "") {
        this.filtros.programa = [this.selectedProgram];
      } else {
        delete this.filtros.programa;
      }

      if (this.inputSat !== "") {
        this.filtros.sat = [this.inputSat];
      } else {
        delete this.filtros.sat;
      }

      function filterPlainArray(array, filters) {
        const filterKeys = Object.keys(filters);
        //console.log(filterKeys);
        return array.filter((item) => {
          return filterKeys.every((key) => {
            if (!filters[key].length) return true;
            if (key === "sat") {
              return filters[key].find(
                (filter) => getValue(item[key]).indexOf(getValue(filter)) >= 1
              );
            } //find(filter => getValue(filter).includes(getValue(item[key])));}
            else {
              return filters[key].find(
                (filter) => getValue(filter) === getValue(item[key])
              );
            } // === getValue(item[key]));
          });
        });
      }

      return filterPlainArray(this.arrayFiltrado, this.filtros);
    },
  },
};
</script>
