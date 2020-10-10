<template>
  <div>
      <table>
        <tr>
            <th>Alias</th>
            <th>Description</th>
            <th>KCal</th>
            <th>Protein</th>
            <th>Fat</th>
            <th>Carbohydrate</th>
            <th>Potassium</th>
            <th>Quantity</th>
        </tr>
        <tr v-for="ingredient in ingredients" :key="ingredient.id">
            <td>{{ingredient.alias}}</td>
            <td>{{ingredient.description}}</td>
            <td>{{ingredient.kcal}}</td>
            <td>{{ingredient.protein}}</td>
            <td>{{ingredient.fat}}</td>
            <td>{{ingredient.carbohydrate}}</td>
            <td>{{ingredient.potassium}}</td>
            <td @click="open(ingredient)">{{ingredient.quantity}}</td>
        </tr>
        <update-number-modal
            v-if="show"
            @close="close"
            :id="selectedIngredient.id"
            :initialValue="selectedIngredient.quantity"
            @update="update"
        />
      </table>
  </div>
</template>

<script>

import UpdateNumberModal from "@/Shared/UpdateNumberModal";

export default {
    components:{
        UpdateNumberModal
    },
    props:{
        ingredients: Array,
        foodId: Number
    },
    data(){
        return{
            show: false,
            selectedIngredient: null,
            url: null,
            params: null
        }
    },
    methods:{
        close(){
            this.show = false;
        },
        open(ingredient){
            this.selectedIngredient = ingredient;
            this.show = true;
        },
        update(value){
            this.$inertia.patch(this.$route("food.ingredient.update", {
                food : this.foodId,
                ingredient: this.selectedIngredient.id
            }), {
                quantity: value
            },{
                preserveScroll:true,
            }).then((res)=>{
                console.log("close!");
                this.close();
            });
        }
    }
}
</script>
