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
    <ingredients-list
        :ingredients="ingredients.data"/>
    <button @click="update">Update</button>
    <button @click="cancel">Cancel</button>
  </div>
</template>

<script>
import axios from "axios";
import IngredientsList from "@/Shared/IngredientsList";

export default {
    components:{
        IngredientsList
    },
    props:{
        food: Object,
        ingredients: Object,
        errors: Object
    },
    methods:{
        cancel () {
            let url = `${this.$route("foods.index")}`;
                this.$inertia.visit(url, {
                    preserveState: true,
                    preserveScroll: true,
                });
        },
        update () {
            this.$inertia.patch(
                this.$route("foods.update", {
                    'food': this.food.data.id
                }), this.food.data
            ).then(()=>{
                console.log("errors", this.errors.description);
            });
        }
    }
}
</script>
