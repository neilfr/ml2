<template>
  <div>
      <div>
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
      </div>
      <button @click="store">Save</button>
      <button>Cancel</button>
  </div>
</template>

<script>
export default {
    data() {
        return {
            food:{
                description: '',
                alias: '',
                kcal: 0,
                potassium: 0,
                fat: 0,
                protein: 0,
                carbohydrate: 0,
                quantity: 0
            }
        }
    },
    methods: {
        store(){
            console.log("store", food);
            // this.$inertia.post(
            //     this.$route("foods.store", food)
            // );
        }
    }
}
</script>
