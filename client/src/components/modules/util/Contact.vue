<template>
  <div>
    <form action="//flipmail.co/api/FAVWnOUwUzAaNe2du3mQ" method="post">
      <div>
        <p class="text-xs-center grey--text text--darken-1 mt-2 mb-2" style="font-style:italic">Any feedback is appreciated</p>
      </div>
      <v-text-field label="Your name" name="name"></v-text-field>
      <v-text-field label="Your email" name="email" type="email"></v-text-field>
      <v-text-field label="Your message" name="message" multi-line></v-text-field>
      <v-btn type="submit" outline color="primary" block>Send</v-btn>
    </form>
  </div>
</template>

<script>
  export default {
    data () {
      return {
      }
    }
  }
</script>
