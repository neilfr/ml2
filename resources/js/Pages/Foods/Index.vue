<template>
    <div class="container">
        <h1>Foods</h1>
        <button @click="add">Add</button>
        <br/>
        <label for="foodgroups">Food Group:</label>
        <select name="foodgroups" id="foodgroups" v-model="foodgroupFilter" @change="goToPageOne">
            <option value="">All</option>
            <option v-for="foodgroup in foodgroups.data" :key="foodgroup.id" :value="foodgroup.id">
                {{ foodgroup.description }}
            </option>
        </select>
        <br>
        <label for="descriptionSearch">Description Search:</label>
        <input type="text" name="descriptionSearch" id="descriptionSearch" @input="goToPageOne" v-model="descriptionSearchText"/>
        <br/>
        <label for="aliasSearch">Alias Search:</label>
        <input type="text" name="aliasSearch" id="aliasSearch" @input="goToPageOne" v-model="aliasSearchText"/>
        <div class="flex">
            <p>Favourites:</p>
            <div class="ml-2">
                <label for="favouriteYes">Yes</label>
                <input type="radio" name="favourites" id="favouriteYes" value="yes" v-model="favouritesFilter" @change="goToPageOne">
                <label for="favouriteNo">No</label>
                <input type="radio" name="favourites" id="favouriteNo" value="no" checked v-model="favouritesFilter" @change="goToPageOne">
            </div>
        </div>
        <main-food-list :foods="foods.data" @view="test" @edit="test" @favourite="foo"></main-food-list>
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
        <div>
            <button @click="goToPageOne">First</button>
            <button @click="previousPage">Previous</button>
            <button @click="nextPage">Next</button>
            <button @click="lastPage">Last</button>
        </div>
        <div>
            <p>Page: {{foods.meta.current_page}} of {{foods.meta.last_page}}</p>
        </div>
    </div>
</template>

<script>
    import MainFoodList from "@/Shared/MainFoodList";
    export default {
        components:{
            MainFoodList,
        },
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
                favouritesFilter: '',
            }
        },
        methods:{
            setFavourite(e){
                this.$inertia.patch(
                    this.$route("foods.update", e.target.id),
                    {
                        favourite: e.target.checked
                    },
                    {
                        preserveScroll: true,
                    });
            },
            foo(e){
                console.log("foo", e.target);
                this.$inertia.patch(
                    this.$route("foods.update", e.target.id),
                    {
                        favourite: e.target.checked
                    },
                    {
                        preserveScroll: true,
                    });
            },
            test(){
                console.log("SHOW");
            },
            test2(){
                console.log("CHANGED");
            },
            goToPageOne(){
                this.goToPage(1);
            },
            previousPage(){
                if(this.page>1) this.goToPage(this.page-1);
            },
            nextPage(){
                if(this.page<this.foods.meta.last_page) this.goToPage(this.page+1);
            },
            lastPage(){
                this.goToPage(this.foods.meta.last_page);
            },
            goToPage(page){
                let url = `${this.$route("foods.index")}`;
                url += `?descriptionSearch=${this.descriptionSearchText}`;
                url += `&aliasSearch=${this.aliasSearchText}`;
                url += `&foodgroupSearch=${this.foodgroupFilter}`;
                url += `&favouritesFilter=${this.favouritesFilter}`;
                this.$inertia.visit(url, {
                    data:{
                        'page':page
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            show(e){
                let url = `${this.$route("foods.show", e.target.id)}`;
                this.$inertia.visit(url);
            },
            add(){
                console.log("create!");
                let url = `${this.$route("foods.create")}`;
                this.$inertia.visit(url);
            }
        }
    };
</script>
