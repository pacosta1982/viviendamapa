
<template>
<div>
  <div class="row">
    <div class="col">
      <label for="cars">Departamento:</label>
      <select v-model="selectedCategory" name="cars" id="cars">
        <option value=""></option>
        <option v-for="(d) in arrayDepartamento" :key="d.DptoId" :value="d.DptoId">{{d.DptoNom}}</option>
      </select>
    </div>
    <div class="col">
      <label for="cars">Programa:</label>
      <select v-model="selectedProgram" name="programs" id="programs">
        <option value=""></option>
        <option v-for="(p) in arrayProgramas" :key="p.id" :value="p.name">{{p.name}}</option>
      </select>
    </div>

  </div>


    <GmapMap
    :center="{lat:-23.5152472, lng:-58.4218982}"
    :zoom="7"
    map-type-id="terrain"
    style="width: 100%; height: 900px"
    >
    <gmap-info-window :options="infoOptions" :position="infoPosition" :opened="infoOpened" @closeclick="infoOpened=false">
        <p><strong>Proyecto: {{infoContent}}</strong><br>
        Programa: {{infoProgram}}<br>
        Departamento: {{infoDepartamento}}<br>
        Distrito: {{infoDistrito}}<br>
        Sat/Empresa: {{infoSat}}<br>
        Total Viviendas: {{infoCasas}}<br>
        Avance: {{infoAvance}} %<br>
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
import {gmapApi} from 'vue2-google-maps'

export default {
  props: ['data','departamento','programas'],
  data(){
      return {
        arrayProyecto : this.data,
        arrayDepartamento : this.departamento,
        arrayProgramas : this.programas,
        arrayFiltrado : [],
        filtros : {},
        infoPosition: null,
        infoContent: null,
        infoProgram: null,
        infoDepartamento: null,
        infoDistrito: null,
        infoSat: null,
        infoCasas: null,
        infoAvance: null,
        infoOpened: false,
        infoCurrentKey: null,
        selectedCategory: '',
        selectedProgram: '',
        infoOptions: {
        pixelOffset: {
        width: 0,
        height: -35
        }
    },
      }
  },
  methods: {
    getPosition: function(marker) {
      return {
        lat: parseFloat(marker.lat),
        lng: parseFloat(marker.lng)
      }
    },
    filterArray(array, filters) {
    const filterKeys = Object.keys(filters);
    console.log('funciona');
        return array.filter(item => {
            // validates all filter criteria
            return filterKeys.every(key => {
            // ignores non-function predicates
            if (typeof filters[key] !== 'function') return true;
            return filters[key](item[key]);
            });
        });
    }
    ,
    toggleInfo: function(marker, key) {
      this.infoPosition = this.getPosition(marker)
      this.infoContent = marker.proyecto
      this.infoProgram = marker.programa
      this.infoDepartamento = marker.departamento
      this.infoDistrito = marker.distrito
      this.infoSat = marker.sat
      this.infoCasas = marker.cantidad_viviendas
      this.infoAvance = marker.avance

      if (this.infoCurrentKey == key) {
        this.infoOpened = !this.infoOpened
      } else {
        this.infoOpened = true
        this.infoCurrentKey = key
      }
    }
  },
  computed: {
    google: gmapApi,
    filteredProducts() {
        const getValue = value => (typeof value === 'string' ? value.toUpperCase() : value);
        this.arrayFiltrado = this.arrayProyecto;

        if(this.selectedCategory !== '') {
            this.filtros.departamento_id = [this.selectedCategory];
        }else{
            delete this.filtros.departamento_id
        }

        if(this.selectedProgram !== '') {
            this.filtros.programa = [this.selectedProgram];
        }else{
            delete this.filtros.programa;
        }

        function filterPlainArray(array, filters) {
        const filterKeys = Object.keys(filters);
        return array.filter(item => {
            // validates all filter criteria
            return filterKeys.every(key => {
            // ignores an empty filter
            if (!filters[key].length) return true;
            console.log(filters);
            return filters[key].find(filter => getValue(filter) === getValue(item[key]));
            });
        });
        }

        return filterPlainArray(this.arrayFiltrado, this.filtros);
    }
  }
}
</script>
