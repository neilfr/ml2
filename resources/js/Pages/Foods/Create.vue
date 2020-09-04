<template>
  <div>
      <div>
        {{ $page.auth.user.id }}
        </div>
        <form method="POST" @submit.prevent="submit">
            <input id="user" type="text" :value="$page.auth.user.id"/>
            <br/>
            <label for="description">Description:</label>
            <input id="description" type="text" :value="food.description"/>
            <br/>
            <label for="alias">Alias:</label>
            <input id="alias" type="text" :value="food.alias"/>
            <br/>
            <label for="KCal">KCal:</label>
            <input id="kcal" type="number" :value="food.kcal"/>
            <br/>
            <label for="Protein">Protein:</label>
            <input id="protein" type="number" :value="food.protein"/>
            <br/>
            <label for="Fat">Fat:</label>
            <input id="fat" type="number" :value="food.fat"/>
            <br/>
            <label for="Carbohydrate">Carbohydrate:</label>
            <input id="carbohydrate" type="number" :value="food.carbohydrate"/>
            <br/>
            <label for="Potassium">Potassium:</label>
            <input id="potassium" type="number" :value="food.potassium"/>
            <br/>
            <label for="Quantity">Quantity:</label>
            <input id="quantity" type="number" :value="food.quantity"/>
        </form>
        <button @click="store">Save</button>
        <button>Cancel</button>
  </div>
</template>

<script>
export default {
    props:{
        // page: Object
    },
    data() {
        return {
            food:{
                alias: '',
                description: '',
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
            console.log("store", this.food.kcal);
            console.log("user", this.$page.auth.user.id);
            this.$inertia.post(
                this.$route("foods.store", this.food)
            );
        }
    }
}
</script>
