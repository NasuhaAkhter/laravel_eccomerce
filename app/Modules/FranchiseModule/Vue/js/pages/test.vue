<template>
    <div class="_main_content">
		<div class="row">
            <div class="col-12 col-md-12 col-lg-12">
				<div class="_box _b_radious3 _padd20">
               <div>
                <div class="google-map" ref="googleMap"></div>
                <template v-if="Boolean(this.google) && Boolean(this.map)">
                <slot
                    :google="google"
                    :map="map"
                />
                </template>
            </div>
				
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import GoogleMapsApiLoader from 'google-maps-api-loader'

export default {
  props: {
    mapConfig: Object,
    apiKey: String,
  },

  data() {
    return {
      google: null,
      map: null
    }
  },

  async mounted() {
    const googleMapApi = await GoogleMapsApiLoader({
      apiKey: `AIzaSyCESImrf5bZUSoI9ap0cu7LtezxYo5akhM`
    })
    this.google = googleMapApi
    this.initializeMap()
  },

  methods: {
    initializeMap() {
      const mapContainer = this.$refs.googleMap
      this.map = new this.google.maps.Map(
        mapContainer, this.mapConfig
      )
    }
  }
}
</script>

