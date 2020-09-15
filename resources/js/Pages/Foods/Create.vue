<template>
  <div>
        <form method="POST" @submit.prevent="submit">
            <div class="bg-red-400 grid grid-cols-2">
                <div>1</div>
                <div>2</div>
                <div>3</div>
                <div>4</div>
            </div>

            <div class="grid grid-cols-4">
                <div class="col-span-2">1</div>
                <div class="col-span-2">1</div>
                <div>1</div>
                <div>1</div>
                <div>1</div>
                <div>1</div>
                <p v-if="errors.description">{{errors.description}}</p>
                <label for="description">Description:</label>
                <input id="description" type="text" v-model="food.description">
                <br/>
                <p v-if="errors.alias">{{errors.alias}}</p>
                <label for="alias">Alias:</label>
                <input id="alias" type="text" v-model="food.alias"/>
                <br/>
                <p v-if="errors.kcal">{{errors.kcal}}</p>
                <label for="KCal">KCal:</label>
                <input id="kcal" type="number" v-model="food.kcal" min="0"/>
                <br/>
                <p v-if="errors.protein">{{errors.protein}}</p>
                <label for="Protein">Protein:</label>
                <input id="protein" type="number" v-model="food.protein" min="0"/>
                <br/>
                <p v-if="errors.fat">{{errors.fat}}</p>
                <label for="Fat">Fat:</label>
                <input id="fat" type="number" v-model="food.fat" min="0"/>
                <br/>
                <p v-if="errors.carbohydrate">{{errors.carbohydrate}}</p>
                <label for="Carbohydrate">Carbohydrate:</label>
                <input id="carbohydrate" type="number" v-model="food.carbohydrate" min="0"/>
                <br/>
                <p v-if="errors.potassium">{{errors.potassium}}</p>
                <label for="Potassium">Potassium:</label>
                <input id="potassium" type="number" v-model="food.potassium" min="0"/>
                <br/>
                <p v-if="errors.quantity">{{errors.quantity}}</p>
                <label for="Quantity">Quantity:</label>
                <input id="quantity" type="number" v-model="food.quantity" min="0"/>
            </div>
        </form>
        <button @click="store">Save</button>
        <button>Cancel</button>
  </div>
</template>

<script>
export default {
    props:{
        errors: Object
        // page: Object
    },
    data() {
        return {
            food:{
                description: '',
                alias: '',
                kcal: 0,
                fat: 0,
                protein: 0,
                carbohydrate: 0,
                potassium: 0,
                favourite: true,
                quantity: 0,
                foodsource_id: 2,
                foodgroup_id: 26,
                user_id: this.$page.auth.user.id
            }
        }
    },
    methods: {
        store(){
            console.log("food",this.food);
            this.$inertia.post(
                this.$route("foods.store"), this.food
            ).then(()=>{
                console.log("respond!");
                console.log("errors", this.errors.description);
            });
        },
        what(){
            console.log("what", this.food.description);

        }
    }
}
</script>
