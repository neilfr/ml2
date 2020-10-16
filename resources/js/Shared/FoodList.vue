<template>
  <div>
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

export default {
    props:{
        food: Object,
        foods: Object,
    },
    data(){
        return{
            page: 1,
            selectedFood: null
        }
    },
    methods:{
        goToPageOne(){
            this.page=1;
            this.goToPage(1);
        },
        previousPage(){
            if(this.page>1){
                this.page--;
                this.goToPage();
            }
        },
        nextPage(){
            if(this.page<this.foods.meta.last_page){
                this.page++;
                this.goToPage();
            }
            console.log("next page this.page", this.page);
        },
        lastPage(){
            this.page = this.foods.meta.last_page;
            this.goToPage();
        },
        goToPage(){
            console.log("GOTOPAGE", this.page);
            console.log("food is", this.food.data.id);
            let url = `${this.$route("foods.show", this.food.data.id)}`;
            // url += `?descriptionSearch=${this.descriptionSearchText}`;
            // url += `&aliasSearch=${this.aliasSearchText}`;
            // url += `&foodgroupSearch=${this.foodgroupFilter}`;
            this.$inertia.visit(url, {
                data:{
                    'page':this.page
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    }
}
</script>
