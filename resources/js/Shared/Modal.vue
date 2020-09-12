<template>
    <div>
        <div v-if="show">
            Modal
        </div>
    </div>
</template>

<script>
export default {
    props:{
        show: Boolean
    },
    data () {
        return {
            foo: false
        }
    },
    methods:{

    }
}
</script>
