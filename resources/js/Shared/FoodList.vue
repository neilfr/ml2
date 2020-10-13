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
        foods: Array,
        page: Number
    },
    data(){
        return{
            selectedFood: null,
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
            let url = `${this.$route("foods.index")}`;
            // url += `?descriptionSearch=${this.descriptionSearchText}`;
            // url += `&aliasSearch=${this.aliasSearchText}`;
            // url += `&foodgroupSearch=${this.foodgroupFilter}`;
            this.$inertia.visit(url, {
                data:{
                    'page':page
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    }
}
</script>
