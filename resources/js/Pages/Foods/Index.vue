<template>
    <div class="container">
        <h1>made it</h1>
        <table>
            <tr>
                <th>Description</th>
                <th>KCal</th>
                <th>Protein</th>
                <th>Fat</th>
                <th>Carbohydrate</th>
                <th>Potassium</th>
            </tr>
            <tr v-for="food in foods.data" :key="food.id">
                <td>{{food.description}}</td>
                <td>{{food.kcal}}</td>
                <td>{{food.protein}}</td>
                <td>{{food.fat}}</td>
                <td>{{food.carbohydrate}}</td>
                <td>{{food.potassium}}</td>

            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        props:{
            foods: Object
        }
    };
</script>
