<template>
  <site-template>
    <span slot="menuesquerdo">
      <img :src="usuario.image" class="responsive-img">
    </span>

    <span slot="principal">

      <h2>Perfil</h2>

      <input type="text" placeholder="Nome" v-model="newUser.name">
      <input type="email" placeholder="E-mail" v-model="newUser.email">

        <div class="file-field input-field">
          <div class="btn">
            <span>Imagem</span>
            <input type="file" @change="salvaImagem">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>

      <input type="password" placeholder="Senha" v-model="newUser.password">
      <input type="password" placeholder="Confirme sua Senha" v-model="newUser.password_confirmation">
      <button class="btn" @click.prevent="perfil">Atualizar</button>

    </span>
  </site-template>
</template>

<script>
import SiteTemplate from "../../templates/SiteTemplate";

export default {
  name: 'Perfil',
  data() {
    return {
      newUser: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        image: ''
      },
      usuario: false
    }
  },
  components: {
    SiteTemplate
  },
  created(){
    console.log('created()');
    let usuarioAux = sessionStorage.getItem('usuario');
    if(usuarioAux) {
      this.usuario = JSON.parse(usuarioAux);
      this.newUser.name = this.usuario.name;
      this.newUser.email = this.usuario.email;
    }
  },
  methods: {
    salvaImagem(e) {
      let file = e.target.files || e.dataTransfer.files;

      if (!file.length) {
        return;
      }

      let reader = new FileReader();
      reader.onloadend = (e) => {
        this.newUser.image = e.target.result;
      };

      reader.readAsDataURL(file[0]);
    },

    perfil() {
      this.$http.put(this.$urlAPI + 'perfil', {
        name: this.newUser.name,
        email: this.newUser.email,
        password: this.newUser.password,
        password_confirmation: this.newUser.password_confirmation,
        image: this.newUser.image
      }, {
        "headers": {"authorization":"Bearer "+this.usuario.token}
      })
          .then(response => {
            if (response.data.status) {
              console.log(response.data)
              this.usuario = response.data.user;
              sessionStorage.setItem('usuario', JSON.stringify(this.usuario));
              alert('Informações atualizadas!');
              this.$router.push('/');
            } else if(response.data.status == false && response.data.validation) {
              // erros de validação
              console.log('erros de validação')
              let erros = '';
              for (let erro of Object.values(response.data.errors)) {
                erros += erro + " ";
              }
              alert(erros);
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
