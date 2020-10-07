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
        <update-ingredient-quantity-modal
            v-if="show"
            @close="close"
            :show="show"
            :id="selectedIngredient.id"
            :quantity="selectedIngredient.quantity"
        />
      </table>
  </div>
</template>

<script>
import UpdateIngredientQuantityModal from "@/Shared/UpdateIngredientQuantityModal";

export default {
    components:{
        UpdateIngredientQuantityModal
    },
    props:{
        ingredients: Array,
    },
    data(){
        return{
            show: false,
            selectedIngredient: null,
        }
    },
    methods:{
        close(){
            this.show = false;
            console.log("close method, show is set to:", this.show);
        },
        open(ingredient){
            console.log("open", ingredient.id);
            this.selectedIngredient = ingredient;
            this.show = true;
        }
    }
}
</script>
