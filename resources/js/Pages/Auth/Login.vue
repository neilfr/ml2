<template>
  <div>
        <div>Who Am I?  {{whoAmI}}</div>
          <h1>Login</h1>
          <label for="email">Email:</label>
          <input name="email" id="email" v-model="email" type="email" autofocus/>
          <br>
          <label for="password">Password:</label>
          <input v-model="password" type="password" />
          <br>
        <label for="remember">Remember me:</label>
          <input name="remember" id="remember" v-model="remember" type="checkbox"/>
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
        email: 'admin@example.com',
        password: 'tester',
        remember: null,
    }
  },
  methods: {
    submit() {
      this.$inertia.post(this.$route('login'), {
        email: this.email,
        password: this.password,
        remember: this.remember
      })
    },
  },
}
</script>
