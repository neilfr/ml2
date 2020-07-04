<template>
  <div>
    <h1>Welcome</h1>
    <p>Hello {{ user.name }}, welcome to your first Inertia app!</p>
  </div>
</template>

<script>
  import Layout from './Layout'

  export default {
    // Using a render function
    layout: (h, page) => h(Layout, [page]),

    // Using the shorthand
    layout: Layout,

    props: {
      user: Object,
    },
  }
</script>
