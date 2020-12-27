<template>
    <div class="container">
        <h1>Ingredients</h1>
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
        <main-ingredients-list :foods="foods.data"></main-ingredients-list>
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
    import MainIngredientsList from "@/Shared/MainIngredientsList";
    export default {
        components:{
            MainIngredientsList,
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
                let url = `${this.$route("ingredients.index")}`;
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
            }
        }
    };
</script>

