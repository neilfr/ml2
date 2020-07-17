<template>
  <div>
        <div>Who Am I?  {{whoAmI}}</div>
          <h1>Login</h1>
          <label for="email">Email:</label>
          <input name="email" id="email" v-model="form.email" type="email" autofocus/>
          <br>
          <label for="password">Password:</label>
          <input v-model="form.password" type="password" />
          <br>
          <button class="btn-indigo" @click="submit()">Login</button>
        <a :href="this.$route('register')">Register</a>
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
