<template>
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
       Sat/Empresa: {{infoSat}}
    </p>
</gmap-info-window>
<GmapMarker
    :key="m.id"
    v-for="(m) in arrayProyecto"
    :position="getPosition(m)"
    :clickable="true"
    @click="toggleInfo(m,m.id)"
  />
</GmapMap>
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
        infoOpened: false,
        infoCurrentKey: null,
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

      if (this.infoCurrentKey == key) {
        this.infoOpened = !this.infoOpened
      } else {
        this.infoOpened = true
        this.infoCurrentKey = key
      }
    }
  },
  computed: {
    google: gmapApi
  }
}
</script>
