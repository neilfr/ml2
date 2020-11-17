<template>
    <div class="container">
        <h1>Foods</h1>
        <!-- <main-food-list :foods="foods.data" @view="test" @edit="test" @favourite="foo"></main-food-list> -->
        <table>
            <tr>
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
            <tr v-for="food in foods.data" :key="food.id">
                <td>{{food.alias}}</td>
                <td>{{food.description}}</td>
                <td>{{food.kcal}}</td>
                <td>{{food.protein}}</td>
                <td>{{food.fat}}</td>
                <td>{{food.carbohydrate}}</td>
                <td>{{food.potassium}}</td>
                <td>{{food.base_quantity}}</td>
                <td>
                    <button v-if="food.editable" @click="show" :id="food.id">
                        Edit
                    </button>
                    <button v-if="!food.editable" @click="show" :id="food.id">
                        View
                    </button>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        components:{

        },
        props:{
            user: Object,
            foods: Object,
            foodgroups: Object,
            page: Number
        },
        mounted(){
            console.log("mounted");
            console.log(this.foods);
        },
        data() {
            return {
                // descriptionSearchText: '',
                // aliasSearchText: '',
                // foodgroupFilter: '',
                // favouritesFilter: '',
            }
        },
        methods:{
            show(e){
                let url = this.$route("users.foods.show", {
                    user: this.user.id,
                    food:e.target.id
                });
                this.$inertia.visit(url);
            },
        }
    };
</script>
