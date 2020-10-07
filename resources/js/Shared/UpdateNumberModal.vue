<template>
  <div>
        <input :id="id" type="number" min="0" :value="value"/>
        <button @click="update">Update</button>
        <button @click="cancel">Cancel</button>
  </div>
</template>

<script>
import axios from "axios";

export default {
    props:{
        show: Boolean,
        id: Number,
        value: Number,
        url: String,
        field: String

    },
    methods:{
        cancel(){
            this.$emit("close");
        },
        update(){
            console.log("url",this.url);
            console.log("field",this.field);
            console.log("value",this.value);
            let payload = {};
            payload[this.field]= this.value;
            console.log("payload", payload);
            // this.$emit("close");

            axios.get(this.$route('foods.index')).then((res)=>{
                console.log("got response", res.data);
            });

            // axios.patch(this.$route('food.ingredient.update', {
            //     food: 503368,
            //     ingredient: 2
            // }),{
            //     quantity: 201
            // }).then(()=>{
            //     this.$emit("close");
            // });
            // axios.patch(this.url,payload).then(()=>{
            //     this.$emit("close");
            // });
        }
    },
    data(){
        return {
            currentShow: false
        }
    }
}
</script>
