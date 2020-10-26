<template>
  <div>
    <div class="grid grid-cols-3 gap-2">
        <p class="col-span-2" v-if="errors.description">{{errors.description}}</p>
        <label class="p-2" for="description">Description:</label>
        <input class="border rounded col-span-2" id="description" type="text" :readonly="!food.data.editable" v-model="food.data.description">

        <p class="col-span-2" v-if="errors.alias">{{errors.alias}}</p>
        <label class="p-2" for="alias">Alias:</label>
        <input class="border rounded col-span-2" id="alias" type="text" :readonly="!food.data.editable" v-model="food.data.alias"/>

        <p class="col-span-2" v-if="errors.kcal">{{errors.kcal}}</p>
        <label class="p-2" for="kcal">KCal:</label>
        <input class="border rounded" id="kcal" type="number" :readonly="!food.data.editable" v-model="food.data.kcal" min="0"/>
        <input class ="border rounded" id="calc_kcal" type="number" readonly v-model="calculatedKCal">

        <p class="col-span-2" v-if="errors.protein">{{errors.protein}}</p>
        <label class="p-2" for="protein">Protein:</label>
        <input class="border rounded" id="protein" type="number" :readonly="!food.data.editable" v-model="food.data.protein" min="0"/>
        <input class ="border rounded" id="calc_protein" type="number" readonly v-model="calculatedProtein">

        <p class="col-span-2" v-if="errors.fat">{{errors.fat}}</p>
        <label class="p-2" for="fat">Fat:</label>
        <input class="border rounded" id="fat" type="number" :readonly="!food.data.editable" v-model="food.data.fat" min="0"/>
        <input class ="border rounded" id="calc_fat" type="number" readonly v-model="calculatedFat">

        <p class="col-span-2" v-if="errors.carbohydrate">{{errors.carbohydrate}}</p>
        <label class="p-2" for="carbohydrate">Carbohydrate:</label>
        <input class="border rounded" id="carbohydrate" type="number" :readonly="!food.data.editable" v-model="food.data.carbohydrate" min="0"/>
        <input class ="border rounded" id="calc_carbohydrate" type="number" readonly v-model="calculatedCarbohydrate">

        <p class="col-span-2" v-if="errors.potassium">{{errors.potassium}}</p>
        <label class="p-2" for="potassium">Potassium:</label>
        <input class="border rounded" id="potassium" type="number" :readonly="!food.data.editable" v-model="food.data.potassium" min="0"/>
        <input class ="border rounded" id="calc_potassium" type="number" readonly v-model="calculatedPotassium">

        <p class="col-span-2" v-if="errors.base_quantity">{{errors.base_quantity}}</p>
        <label class="p-2" for="base_quantity">Base Quantity:</label>
        <input class="border rounded" id="base_quantity" type="number" :readonly="!food.data.editable" v-model="food.data.base_quantity" min="0"/>
        <input class ="border rounded" id="calc_base_quantity" type="number" readonly v-model="calculatedBaseQuantity">

    </div>
    <button class="border rounded" @click="updateFood">Update Food</button>
    <button class="border rounded" @click="cancelFoodUpdate">Cancel Food Update</button>
    <button class="border rounded" @click="setToRecommendedValues">Set to Recommended Values</button>
    <ingredients-list
        :food=food.data
        @remove="removeIngredient"
    />
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
            descriptionSearchText: '',
            calculatedKCal: 0,
            calculatedFat: 0,
            calculatedProtein: 0,
            calculatedCarbohydrate: 0,
            calculatedPotassium: 0,
            calculatedBaseQuantity: 0
        }
    },
    mounted ()
    {
        this.calculatedKCal = this.food.data.ingredients.reduce((total,ingredient)=>{
            return total+ingredient.kcal;
        }, 0);
        this.calculatedFat = this.food.data.ingredients.reduce((total,ingredient)=>{
            return total+ingredient.fat;
        }, 0);
        this.calculatedProtein = this.food.data.ingredients.reduce((total,ingredient)=>{
            return total+ingredient.protein;
        }, 0);
        this.calculatedCarbohydrate = this.food.data.ingredients.reduce((total,ingredient)=>{
            return total+ingredient.carbohydrate;
        }, 0);
        this.calculatedPotassium = this.food.data.ingredients.reduce((total,ingredient)=>{
            return total+ingredient.potassium;
        }, 0);
        this.calculatedBaseQuantity = this.food.data.ingredients.reduce((total,ingredient)=>{
            return total+ingredient.quantity;
        }, 0);
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
                console.log("updated food");
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
                    'ingredient_id':newIngredientFoodId,
                },
                { preserveScroll: false, preserveState: false }
            )
            .then((res)=>{
                // this.updateFoodList();
            }
            );
        },
        removeIngredient(ingredient){
            this.$inertia.delete(this.$route("food.ingredient.destroy", {
                    'food': this.food.data.id,
                    'ingredient': ingredient.id
                }));
        },
        setToRecommendedValues(){
            this.food.data.kcal=this.calculatedKCal;
            this.food.data.fat=this.calculatedFat;
            this.food.data.protein=this.calculatedProtein;
            this.food.data.carbohydrate=this.calculatedCarbohydrate;
            this.food.data.potassium=this.calculatedPotassium;
            this.food.data.base_quantity=this.calculatedBaseQuantity;
        }
    }
}
</script>
