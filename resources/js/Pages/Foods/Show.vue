<template>
  <div>
      <div>
      <label for="description">Description:</label>
      <input id="description" type="text" :value="food.data.description" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="alias">Alias:</label>
      <input id="alias" type="text" :value="food.data.alias" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="KCal">KCal:</label>
      <input id="kcal" type="text" :value="food.data.kcal" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="Protein">Protein:</label>
      <input id="protein" type="text" :value="food.data.protein" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="Fat">Fat:</label>
      <input id="fat" type="text" :value="food.data.fat" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="Carbohydrate">Carbohydrate:</label>
      <input id="carbohydrate" type="text" :value="food.data.carbohydrate" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="Potassium">Potassium:</label>
      <input id="potassium" type="text" :value="food.data.potassium" :readonly="!(food.data.editable)"/>
      <br/>
      <label for="Quantity">Quantity:</label>
      <input id="quantity" type="text" :value="food.data.quantity" :readonly="!(food.data.editable)"/>
      </div>
      <button @click="save">Save</button>
      <button @click="close">Cancel</button>
  </div>
</template>

<script>
export default {
    props:{
        food: Object
    },
    methods:{
        close () {
            console.log("closing!");
        },
        save () {
            console.log("saving!");
        }
    }
}
</script>
