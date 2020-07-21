
<template>
<div>
    <label for="cars">Choose a car:</label>

<select v-model="selectedCategory" name="cars" id="cars">
  <option value="volvo">Volvo</option>
  <option value="CENTRAL">CENTRAL</option>
  <option value="CONCEPCION">CONCEPCION</option>
  <option value="audi">Audi</option>
</select>

    <GmapMap
    :center="{lat:-25.3006592, lng:-57.63591}"
    :zoom="7"
    map-type-id="terrain"
    style="width: 1200px; height: 800px"
    >
    <gmap-info-window :options="infoOptions" :position="infoPosition" :opened="infoOpened" @closeclick="infoOpened=false">
        <p><strong>Proyecto: {{infoContent}}</strong><br>
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
  props: ['data'],
  data(){
      return {
        arrayProyecto : this.data,
        infoPosition: null,
        infoContent: null,
        infoDepartamento: null,
        infoDistrito: null,
        infoSat: null,
        infoCasas: null,
        infoAvance: null,
        infoOpened: false,
        infoCurrentKey: null,
        selectedCategory: '',
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
    toggleInfo: function(marker, key) {
      this.infoPosition = this.getPosition(marker)
      this.infoContent = marker.proyecto
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
        if(this.selectedCategory === '') {
            return this.arrayProyecto;
        } else {
            const category = this.selectedCategory;
            return this.arrayProyecto
                        .filter((product) => product.departamento === category)
        }
    }
  }
}
</script>
