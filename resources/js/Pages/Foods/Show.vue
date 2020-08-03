<template>
  <div>
      <label for="description">Description:</label>
      <input id="description" type="text" :value="food.data.description"/>
      <br/>
      <label for="alias">Alias:</label>
      <input id="alias" type="text" :value="food.data.alias"/>
      <br/>
      <label for="KCal">KCal:</label>
      <input id="kcal" type="text" :value="food.data.kcal"/>
      <br/>
      <label for="Protein">Protein:</label>
      <input id="protein" type="text" :value="food.data.protein"/>
      <br/>
      <label for="Fat">Fat:</label>
      <input id="fat" type="text" :value="food.data.fat"/>
      <br/>
      <label for="Carbohydrate">Carbohydrate:</label>
      <input id="carbohydrate" type="text" :value="food.data.carbohydrate"/>
      <br/>
      <label for="Potassium">Potassium:</label>
      <input id="potassium" type="text" :value="food.data.potassium"/>
      <br/>
      <label for="Quantity">Quantity:</label>
      <input id="quantity" type="text" :value="food.data.quantity"/>
  </div>
</template>

<script>
export default {
    props:{
        food: Object
    }
}
</script>
