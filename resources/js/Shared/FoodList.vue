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
            <td @click="selectFood" :id="food.id" :selectedFoodBaseQuantity="food.quantity" :data-base_quantity="food.base_quantity">{{food.description}}</td>
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
        foods: Object,
    },
    data(){
        return{
            page: 1,
            selectedFoodId: null,
            selectedFoodBaseQuantity: null
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
        },
        lastPage(){
            this.page = this.foods.meta.last_page;
            this.goToPage();
        },
        goToPage(){
            this.$emit('pageUpdated', this.page);
        },
        selectFood(e){
            // console.log("food",e.target);
            // console.log("id",e.target.id);
            // console.log("foo", this.selectedFoodBaseQuantity);
            // console.log("test",e.target.getAttribute('data-base_quantity'));

            this.$emit('selectedFood', e.target.id);
            // this.selectedFoodId=e.target.id;
            // console.log("selected food", this.selectedFoodId);
        }
    }
}
</script>
