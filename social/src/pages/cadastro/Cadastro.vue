<template>

  <login-template>

    <span slot="menuesquerdo">
      <img src="https://www.designerd.com.br/wp-content/uploads/2013/06/criar-rede-social.png" class="responsive-img">
    </span>

    <span slot="principal">

      <h2>Cadastro</h2>

      <input type="text" placeholder="Nome" v-model="newUser.name">
      <input type="email" placeholder="E-mail" v-model="newUser.email">
      <input type="password" placeholder="Senha" v-model="newUser.password">
      <input type="password" placeholder="Confirme sua Senha" v-model="newUser.password_confirmation">
      <button class="btn" @click.prevent="cadastro">Enviar</button>
      <router-link class="btn orange" to="/login">Já tenho conta</router-link>


    </span>
  </login-template>
</template>

<script>
import LoginTemplate from '@/templates/LoginTemplate'
import axios from "axios";

export default {
  name: 'Cadastro',
  data () {
    return {
      newUser: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  components:{
    LoginTemplate
  },
  methods: {
    cadastro() {
      this.$http.post(this.$urlAPI + 'cadastro', {
        name: this.newUser.name,
        email: this.newUser.email,
        password: this.newUser.password,
        password_confirmation: this.newUser.password_confirmation
      })
      .then(response => {
        if (response.data.status) {
          alert('Cadastro realizado com sucesso');
          sessionStorage.setItem('usuario', JSON.stringify(response.data.user));
          this.$router.push('/');
        } else if (response.data.status == false && response.data.validation) {
          // erros de validação
          console.log('erros de validação')
          let erros = '';
          for(let erro of Object.values(response.data.errors)){
            erros += erro +" ";
          }
          alert(erros);
        } else {
          alert('Erro ao cadastrar!');
        }
      })
      .catch(error => console.log(error))
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
