<template>
    <div class="container">
        <h1>Foods</h1>
        <label for="foodgroups">Food Group:</label>
        <select name="foodgroups" id="foodgroups" v-on:change="foodgroupFilter" v-model="foodgroupSearchId">
            <option v-for="foodgroup in foodgroups.data" :key="foodgroup.id" :value="foodgroup.id">{{foodgroup.description}}</option>
        </select>
        <br/>
        <label for="descriptionSearch">Description Search:</label>
        <input type="text" name="descriptionSearch" id="descriptionSearch" @input="search" v-model="descriptionSearchText"/>
        <br/>
        <label for="aliasSearch">Alias Search:</label>
        <input type="text" name="aliasSearch" id="aliasSearch" @input="search" v-model="aliasSearchText"/>
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
            </tr>
            <tr v-for="food in foods.data" :key="food.id">
                <td>
                    <input
                        type="checkbox"
                        :value="food.favourite"
                        :checked="food.favourite"
                    />
                </td>
                <td>{{food.alias}}</td>
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
    import { throttle } from "lodash";
    export default {
        props:{
            foods: Object,
            foodgroups: Object
        },
        data() {
            return {
                descriptionSearchText: '',
                aliasSearchText: '',
                foodgroupSearchId: 0
            }
        },
        methods:{
            foo(){
                console.log("foo!");
            },
            search: throttle(function(e) {
                let url = `${this.$route("foods.index")}`;
                url += `?descriptionSearch=${this.descriptionSearchText}`;
                url += `&aliasSearch=${this.aliasSearchText}`;
                url += `&foodgroupSearch=${this.foodgroupSearchId}`;
                this.$inertia.visit(url, {
                    preserveState: true,
                    preserveScroll: true,
                });
            }, 500),
            foodgroupFilter(){
                let url = `${this.$route("foods.index")}`;
                url += `?descriptionSearch=${this.descriptionSearchText}`;
                url += `&aliasSearch=${this.aliasSearchText}`;
                url += `&foodgroupSearch=${this.foodgroupSearchId}`;
                this.$inertia.visit(url, {
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }
    };
</script>
