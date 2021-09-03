<template>
  <div class="row">
    <grid-vue class="input-field" tamanho="12">
      <input type="text" v-model="conteudo.titulo">
      <textarea v-if="conteudo.titulo.length > 3" placeholder="Conteúdo" v-model="conteudo.texto"
                class="materialize-textarea"></textarea>
      <input v-if="conteudo.titulo && conteudo.texto.length > 2" type="text" placeholder="Link" v-model="conteudo.link">
      <input v-if="conteudo.titulo && conteudo.texto.length > 2" type="text" placeholder="URL da Imagem"
             v-model="conteudo.imagem">
      <label>O que está acontecendo?</label>
    </grid-vue>
    <p class="right-align">
      <button v-if="conteudo.titulo && conteudo.texto"
              @click="addConteudo"
              class="btn waves-effect waves-light"
      >Publicar
      </button>
    </p>
  </div>
</template>

<script>
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'PublicarConteudoVue',
  props: ['usuario'],
  data() {
    return {
      conteudo: {
        titulo: '',
        texto: '',
        link: '',
        imagem: ''
      }
    }
  },
  components: {
    GridVue
  },
  methods: {
    addConteudo() {
      this.$http.post(this.$urlAPI + 'conteudo/adicionar', {
        titulo: this.conteudo.titulo,
        texto: this.conteudo.texto,
        link: this.conteudo.link,
        imagem: this.conteudo.imagem,
      }, {
        "headers": {"authorization": "Bearer " + this.usuario.token}
      }).then(response => {
        if (response.data.status) {

        }
      }).catch(error => {
        console.log(error);
        alert('Erro! Tente mais tarde');
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
