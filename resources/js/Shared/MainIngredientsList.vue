<template>
  <div>
      <table>
            <tr>
                <th>Favourite</th>
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
            <tr v-for="food in foods" :key="food.id">
                <td>
                    <input
                        type="checkbox"
                        :id="food.id"
                        :value="food.favourite"
                        :checked="food.favourite"
                        readonly
                    />
                </td>
                <td>{{food.alias}}</td>
                <td>{{food.description}}</td>
                <td>{{food.kcal}}</td>
                <td>{{food.protein}}</td>
                <td>{{food.fat}}</td>
                <td>{{food.carbohydrate}}</td>
                <td>{{food.potassium}}</td>
                <td>{{food.base_quantity}}</td>
                <td>
                    <button @click="selected" :id="food.id">
                        Select
                    </button>
                </td>
            </tr>
        </table>
  </div>
</template>

<script>
export default {
    props:{
        foods: Array,
    },
    methods:{
        selected (e) {
            console.log("selected:", e.target.id);
        }
    }
}
</script>
