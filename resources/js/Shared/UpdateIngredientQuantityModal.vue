<template>
  <div>
      <input :id="id" type="number" min="0" :value="quantity"/>
      <button @click="close">Close</button>
  </div>
</template>

<script>
export default {
    props:{
        show: Boolean,
        id: Number,
        quantity: Number,
    },
    watch:{
        show: {
            handler: function(show){
                console.log("show changed", show);
            }
        }
    },
    methods:{
        close(){
            console.log("close");
            this.$emit("close");
        }
    },
    data(){
        return {
            currentShow: false
        }
    }
}
</script>
