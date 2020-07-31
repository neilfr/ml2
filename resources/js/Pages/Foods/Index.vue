<template>
    <div class="container">
        <h1>Foods</h1>
        <label for="foodgroups">Food Group:</label>
        <select name="foodgroups" id="foodgroups" v-model="foodgroupFilter" @change="search">
            <option v-for="foodgroup in foodgroups.data" :key="foodgroup.id" :value="foodgroup.id">
                {{ foodgroup.description }}
            </option>
        </select>
        <br>
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
                <th>Quantity</th>
            </tr>
            <tr v-for="food in foods.data" :key="food.id">
                <td>
                    <input
                        type="checkbox"
                        :id="food.id"
                        :value="food.favourite"
                        :checked="food.favourite"
                        @change="setFavourite"
                    />
                </td>
                <td>{{food.alias}}</td>
                <td>{{food.description}}</td>
                <td>{{food.kcal}}</td>
                <td>{{food.protein}}</td>
                <td>{{food.fat}}</td>
                <td>{{food.carbohydrate}}</td>
                <td>{{food.potassium}}</td>
                <td>{{food.quantity}}</td>
            </tr>
        </table>
        <div>
            <button @click="previousPage">Previous</button>
            <button @click="nextPage">Next</button>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            foods: Object,
            foodgroups: Object,
            page: Number
        },
        data() {
            return {
                descriptionSearchText: '',
                aliasSearchText: '',
                foodgroupFilter: '',
            }
        },
        methods:{
            search(){
                let url = `${this.$route("foods.index")}`;
                url += `?descriptionSearch=${this.descriptionSearchText}`;
                url += `&aliasSearch=${this.aliasSearchText}`;
                url += `&foodgroupSearch=${this.foodgroupFilter}`;
                this.$inertia.visit(url, {
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            setFavourite(e){
                this.$inertia.patch(
                    this.$route("foods.update", e.target.id),
                    {
                        favourite: e.target.checked
                    },
                    {
                        replace: true,
                        preserveState: true,
                        preserveScroll: true,
                    });
            },
            previousPage(){
                console.log("previous");
                let newPage=this.page-1;
                let url = `${this.$route("foods.index")}`;
                url += `?descriptionSearch=${this.descriptionSearchText}`;
                url += `&aliasSearch=${this.aliasSearchText}`;
                url += `&foodgroupSearch=${this.foodgroupFilter}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':newPage
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            nextPage(){
                console.log("next");
                let newPage=this.page+1;
                let url = `${this.$route("foods.index")}`;
                url += `?descriptionSearch=${this.descriptionSearchText}`;
                url += `&aliasSearch=${this.aliasSearchText}`;
                url += `&foodgroupSearch=${this.foodgroupFilter}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':newPage
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }
    };
</script>
