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
            <th>Actions</th>
        </tr>
        <tr v-for="ingredient in food.ingredients" :key="ingredient.food_ingredient_id">
            <td>{{ingredient.alias}}</td>
            <td>{{ingredient.description}}</td>
            <td>{{ingredient.kcal}}</td>
            <td>{{ingredient.protein}}</td>
            <td>{{ingredient.fat}}</td>
            <td>{{ingredient.carbohydrate}}</td>
            <td>{{ingredient.potassium}}</td>
            <td>{{ingredient.quantity}}</td>
            <td><button @click="open(ingredient)">Edit</button><button @click="removeIngredient(ingredient)">Delete</button></td>
        </tr>
        <update-number-modal
            v-if="show"
            @close="close"
            :id="selectedIngredient.id"
            :initialValue="selectedIngredient.quantity"
            @update="update"
        />
      </table>
    <button @click="showFoods">Add Ingredient</button>
    <ingredient-add v-if="showIngredientAdd" :foodgroups="foodgroups" :foods="foods" :food="food"></ingredient-add>
  </div>
</template>

<script>

import UpdateNumberModal from "@/Shared/UpdateNumberModal";
import IngredientAdd from "@/Shared/IngredientAdd";

export default {
    components:{
        UpdateNumberModal,
        IngredientAdd
    },
    props:{
        food:Object,
        foodgroups:Object,
        foods:Object
    },
    data(){
        return{
            showIngredientAdd: false,
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
                food : this.food.id,
                ingredient: this.selectedIngredient.id
            }), {
                quantity: value
            },{
                preserveScroll:true,
                preserveState:false
            }).then((res)=>{
                this.close();
            });
        },
        removeIngredient(ingredient){
            this.$inertia.delete(this.$route("food.ingredient.destroy", {
                    'food': this.food.id,
                    'ingredient': ingredient.id
                }));
        },
        showFoods () {
            this.showIngredientAdd=true;
        },
    }
}
</script>
