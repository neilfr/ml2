<template>
    <div>
        <div v-if="show">
            Modal
        </div>
        <button @click="close">close</button>
    </div>
</template>

<script>
export default {
    props:{
        show: {
            type:Boolean,
            default:false
        }
    },
    data () {
        return {
            foo: false
        }
    },
    methods:{
        close () {
            this.show='false';
            console.log("show is", this.show);
        }
    }
}
</script>
