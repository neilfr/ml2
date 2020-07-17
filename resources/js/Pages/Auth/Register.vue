<template>
  <div class="p-6 bg-indigo-800 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md">
      <!-- <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" method="POST" :action="$route('login')" @submit.prevent="submit"> -->
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Register</h1>
          <div class="mx-auto mt-6 w-24 border-b-2" />
            <label for="name">Name:</label>
          <input v-model="name" class="mt-6" type="text" autofocus/>
          <br>
          <label for="email">Email:</label>
          <input name="email" id="email" v-model="email" class="mt-10" type="email"/>
          <br>
          <label for="password">Password:</label>
          <input v-model="password" class="mt-6" type="password" />
          <br>
        </div>
        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
            <button class="btn-indigo" @click="submit()">Register</button>
        </div>
      <!-- </form> -->
    </div>
  </div>
</template>

<script>

export default {
  props: {
    errors: Object,
  },
  data() {
    return {
    //   form: {
        name: '',
        email: '',
        password: ''
    //   }
    }
  },
  methods: {
    submit() {
        // console.log("email", this.email);
        // console.log("password", this.password);
      this.$inertia.post(this.$route('user.store'), {
          name: this.name,
        email: this.email,
        password: this.password,
      })
    },
  },
}
</script>
