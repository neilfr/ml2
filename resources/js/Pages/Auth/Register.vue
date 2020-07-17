<template>
  <div class="p-6 bg-indigo-800 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md">
      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" method="POST" :action="$route('login')" @submit.prevent="submit">
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Register</h1>
          <div class="mx-auto mt-6 w-24 border-b-2" />
          <label for="email">Email:</label>
          <input name="email" id="email" v-model="form.email" class="mt-10" type="email" autofocus/>
          <br>
          <label for="password">Password:</label>
          <input v-model="form.password" class="mt-6" type="password" />
          <br>
        </div>
        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
          <button class="btn-indigo" type="submit">Login</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>

export default {
  metaInfo: { title: 'Login' },
  props: {
    errors: Object,
  },
  data() {
    return {
    //   form: {
    //     email: 'admin@example.com',
    //     password: 'tester',
    //   },
    }
  },
  methods: {
    submit() {
        console.log("hello!");
      this.$inertia.post(this.$route('login.attempt'), {
        email: this.form.email,
        password: this.form.password,
      })
    },
  },
}
</script>
