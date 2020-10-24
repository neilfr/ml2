<template>
  <div>
    <div class="grid grid-cols-2 gap-2">
        <p class="col-span-2" v-if="errors.description">{{errors.description}}</p>
        <label class="p-2" for="description">Description:</label>
        <input class="border rounded" id="description" type="text" :readonly="!food.data.editable" v-model="food.data.description">
        <p class="col-span-2" v-if="errors.alias">{{errors.alias}}</p>
        <label class="p-2" for="alias">Alias:</label>
        <input class="border rounded" id="alias" type="text" :readonly="!food.data.editable" v-model="food.data.alias"/>
        <p class="col-span-2" v-if="errors.kcal">{{errors.kcal}}</p>
        <label class="p-2" for="KCal">KCal:</label>
        <input class="border rounded" id="kcal" type="number" :readonly="!food.data.editable" v-model="food.data.kcal" min="0"/>
        <p class="col-span-2" v-if="errors.protein">{{errors.protein}}</p>
        <label class="p-2" for="Protein">Protein:</label>
        <input class="border rounded" id="protein" type="number" :readonly="!food.data.editable" v-model="food.data.protein" min="0"/>
        <p class="col-span-2" v-if="errors.fat">{{errors.fat}}</p>
        <label class="p-2" for="Fat">Fat:</label>
        <input class="border rounded" id="fat" type="number" :readonly="!food.data.editable" v-model="food.data.fat" min="0"/>
        <p class="col-span-2" v-if="errors.carbohydrate">{{errors.carbohydrate}}</p>
        <label class="p-2" for="Carbohydrate">Carbohydrate:</label>
        <input class="border rounded" id="carbohydrate" type="number" :readonly="!food.data.editable" v-model="food.data.carbohydrate" min="0"/>
        <p class="col-span-2" v-if="errors.potassium">{{errors.potassium}}</p>
        <label class="p-2" for="Potassium">Potassium:</label>
        <input class="border rounded" id="potassium" type="number" :readonly="!food.data.editable" v-model="food.data.potassium" min="0"/>
        <p v-if="errors.base_quantity">{{errors.base_quantity}}</p>
        <label class="p-2" for="Quantity">Quantity:</label>
        <input class="border rounded" id="base_quantity" type="number" :readonly="!food.data.editable" v-model="food.data.base_quantity" min="0"/>
    </div>
    <button @click="updateFood">Update Food</button>
    <button @click="cancelFoodUpdate">Cancel Food Update</button>
    <ingredients-list
        :food=food.data
        @remove="removeIngredient"/>
    <button @click="showFoods">Add Ingredient</button>
    <label for="foodgroups">Food Group:</label>
    <select name="foodgroups" id="foodgroups" v-model="foodgroupFilter" @change="updateFoodList">
        <option value="">All</option>
        <option v-for="foodgroup in foodgroups.data" :key="foodgroup.id" :value="foodgroup.id">
            {{ foodgroup.description }}
        </option>
    </select>
    <br/>
    <label for="descriptionSearch">Description Search:</label>
    <input type="text" name="descriptionSearch" id="descriptionSearch" @input="updateFoodList" v-model="descriptionSearchText"/>
    <br/>
    <label for="aliasSearch">Alias Search:</label>
    <input type="text" name="aliasSearch" id="aliasSearch" @input="updateFoodList" v-model="aliasSearchText"/>
    <food-list @pageUpdated="updateFoodList" @selectedFood="addFoodAsIngredient" :foods="foods"></food-list>
  </div>
</template>

<script>
import IngredientsList from "@/Shared/IngredientsList";
import FoodList from "@/Shared/FoodList";

export default {
    components:{
        IngredientsList,
        FoodList
    },
    props:{
        food: Object,
        foods: Object,
        foodgroups: Object,
        errors: Object,
    },
    data(){
        return {
            foodgroupFilter: '',
            aliasSearchText: '',
            descriptionSearchText: ''
        }
    },
    methods:{
        cancelFoodUpdate () {
            let url = `${this.$route("foods.index")}`;
                this.$inertia.visit(url, {
                    // preserveState: true,
                    preserveScroll: true,
                });
        },
        updateFood () {
            this.$inertia.patch(
                this.$route("foods.update", {
                    'food': this.food.data.id,
                }), this.food.data
            ).then(()=>{
                console.log("errors", this.errors.description);
            });
        },
        showFoods () {
            console.log("add new food as ingredient");
        },
        updateFoodList (page){
            let url = `${this.$route("foods.show", this.food.data.id)}`;

            url += `?descriptionSearch=${this.descriptionSearchText}`;
            url += `&aliasSearch=${this.aliasSearchText}`;
            url += `&foodgroupSearch=${this.foodgroupFilter}`;

            this.$inertia.visit(url, {
                data:{
                    'page':page
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
        addFoodAsIngredient(newIngredientFoodId) {
            this.$inertia.post(
                this.$route("food.ingredient.store", {
                    'food': this.food.data.id
                }), {
                    'ingredient_id':newIngredientFoodId
                }
            ).then(()=>{
                console.log("errors", this.errors.description);
            });
        },
        removeIngredient(ingredient){
            console.log("remove ingredient from show");
            console.log("food id", this.food.data.id);
            console.log("ingredient", ingredient);
            console.log("ingredientid", ingredient.id);

            this.$inertia.delete(this.$route("food.ingredient.destroy", {
                    'food': this.food.data.id,
                    'ingredient': ingredient.id
                }));
        }
    }
}
</script>
