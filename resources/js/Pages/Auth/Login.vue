<template>
  <div class="p-6 bg-indigo-800 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md">
        <div>Who Am I?  {{whoAmI}}</div>
      <!-- <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" method="POST" :action="$route('login')" @submit.prevent="submit"> -->
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Login</h1>
          <div class="mx-auto mt-6 w-24 border-b-2" />
          <label for="email">Email:</label>
          <input name="email" id="email" v-model="form.email" class="mt-10" type="email" autofocus/>
          <br>
          <label for="password">Password:</label>
          <input v-model="form.password" class="mt-6" type="password" />
          <br>
        </div>
        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
            <button class="btn-indigo" @click="submit()">Login</button>
        </div>
        <a :href="this.$route('register')">Register</a>
      <!-- </form> -->
    </div>
  </div>
</template>

<script>

export default {
  props: {
    errors: Object,
    whoAmI: String
  },
  data() {
    return {
      form: {
        email: 'admin@example.com',
        password: 'tester',
      }
    }
  },
  methods: {
    submit() {
      this.$inertia.post(this.$route('login'), {
        email: this.form.email,
        password: this.form.password,
      })
    },
  },
}
</script>
