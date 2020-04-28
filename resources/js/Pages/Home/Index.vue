<template>
    <div class="container mx-auto px-4">
    <h1>Food List</h1>
        <inertia-link href="/foodsApi">show foods</inertia-link>

        <!--        <div class="flex" v-for="food in foods" :key="food.food_code">-->
<!--            <div class="w-12">{{food.food_code}}</div>-->
<!--            <div>{{food.food_description}}</div>-->
<!--        </div>-->
<!--        <h1>Nutrient List</h1>-->
<!--        <div class="flex" v-for="nutrient in nutrients" :key="nutrients.nutrient_name_id">-->
<!--            <div class="w-12">{{nutrient.nutrient_name_id}}</div>-->
<!--            <div>{{nutrient.nutrient_name}}</div>-->
<!--        </div>-->
    </div>
</template>

<script>
    // import Layout from "@/Shared/Layout";
    // import PageHeader from "@/Shared/PageHeader";

    export default {
        props:{
            // foods: Array,
            // nutrients: Array,
        }
        // layout: Layout,
        // components: {
        //     PageHeader
        // }
    };
</script>
