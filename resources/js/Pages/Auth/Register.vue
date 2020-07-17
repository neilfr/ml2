<template>
  <div>
    <h1>Register</h1>
    <label for="name">Name:</label>
    <input v-model="name" type="text" autofocus/>
    <br>
    <label for="email">Email:</label>
    <input name="email" id="email" v-model="email" type="email"/>
    <br>
    <label for="password">Password:</label>
    <input v-model="password" type="password" />
    <br>
    <button class="btn-indigo" @click="submit()">Register</button>
  </div>
</template>

<script>

export default {
  props: {
    errors: Object,
  },
  data() {
    return {
        name: '',
        email: '',
        password: ''
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
