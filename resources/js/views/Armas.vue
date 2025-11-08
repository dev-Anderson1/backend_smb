<template>
  <v-container class="pa-6">
    <v-row class="align-center justify-space-between mb-4">
      <h1 class="text-h5 font-weight-bold">Cadastro de Armas</h1>
      <v-btn color="primary" prepend-icon="mdi-plus" @click="openDialog">
        Nova Arma
      </v-btn>
    </v-row>

    <!-- 🧾 Tabela de armas -->
    <v-data-table
      :headers="headers"
      :items="armas"
      :loading="loading"
      class="elevation-2"
      item-value="id"
      no-data-text="Nenhuma arma cadastrada"
    >
      <template #item.acao="{ item }">
        <v-btn
          icon="mdi-pencil"
          size="small"
          color="primary"
          variant="text"
          @click="editArma(item)"
        ></v-btn>
        <v-btn
          icon="mdi-delete"
          size="small"
          color="error"
          variant="text"
          @click="deleteArma(item.id)"
        ></v-btn>
      </template>
    </v-data-table>

    <!-- 🧩 Modal de cadastro -->
    <v-dialog v-model="dialog" persistent max-width="500px">
      <v-card>
        <v-card-title class="text-h6 font-weight-bold">
          {{ editMode ? 'Editar Arma' : 'Nova Arma' }}
        </v-card-title>

        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-select
              v-model="form.modelo_id"
              :items="modelos"
              item-title="nome"
              item-value="id"
              label="Modelo"
              :rules="[v => !!v || 'Campo obrigatório']"
            />

            <v-select
              v-model="form.municao_id"
              :items="municoes"
              item-title="calibre"
              item-value="id"
              label="Tipo de Munição"
              :rules="[v => !!v || 'Campo obrigatório']"
            />

            <v-select
              v-model="form.carregador_id"
              :items="carregadores"
              item-title="descricao"
              item-value="id"
              label="Carregador"
              :rules="[v => !!v || 'Campo obrigatório']"
            />
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="closeDialog">Cancelar</v-btn>
          <v-btn color="primary" @click="saveArma">
            {{ editMode ? 'Salvar Alterações' : 'Cadastrar' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// ✅ Estado reativo
const armas = ref([])
const modelos = ref([])
const municoes = ref([])
const carregadores = ref([])
const loading = ref(false)
const dialog = ref(false)
const editMode = ref(false)
const valid = ref(false)
const form = ref({
  id: null,
  modelo_id: '',
  municao_id: '',
  carregador_id: ''
})
const formRef = ref(null)

// ✅ Cabeçalhos da tabela
const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Modelo', key: 'modelo.nome' },
  { title: 'Munição ID', key: 'municao_id' },
  { title: 'Carregador ID', key: 'carregador_id' },
  { title: 'Ações', key: 'acao', sortable: false }
]

// ✅ Carregar dados iniciais
onMounted(() => {
  fetchArmas()
  fetchModelos()
  fetchMunicoes()
  fetchCarregadores()
})

// ================================
// 📡 Requisições à API Laravel
// ================================

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // ajuste conforme seu backend
})

// Buscar armas
const fetchArmas = async () => {
  loading.value = true
  try {
    const { data } = await api.get('/armas')
    armas.value = data
  } catch (err) {
    console.error('Erro ao carregar armas', err)
  } finally {
    loading.value = false
  }
}

// Buscar selects
const fetchModelos = async () => {
  const { data } = await api.get('/modelo-armas')
  modelos.value = data
}
const fetchMunicoes = async () => {
  const { data } = await api.get('/municoes')
  municoes.value = data
}
const fetchCarregadores = async () => {
  const { data } = await api.get('/carregadores')
  carregadores.value = data
}

// ================================
// 💾 CRUD Funções
// ================================

// Abrir modal novo
const openDialog = () => {
  editMode.value = false
  resetForm()
  dialog.value = true
}

// Fechar modal
const closeDialog = () => {
  dialog.value = false
}

// Salvar nova arma
const saveArma = async () => {
  const isValid = await formRef.value.validate()
  if (!isValid) return

  try {
    if (editMode.value) {
      await api.put(`/armas/${form.value.id}`, form.value)
    } else {
      await api.post('/armas', form.value)
    }
    await fetchArmas()
    dialog.value = false
  } catch (err) {
    console.error('Erro ao salvar arma', err)
  }
}

// Editar
const editArma = (arma) => {
  form.value = { ...arma }
  editMode.value = true
  dialog.value = true
}

// Deletar
const deleteArma = async (id) => {
  if (!confirm('Tem certeza que deseja excluir esta arma?')) return
  try {
    await api.delete(`/armas/${id}`)
    await fetchArmas()
  } catch (err) {
    console.error('Erro ao excluir arma', err)
  }
}

// Resetar formulário
const resetForm = () => {
  form.value = { id: null, modelo_id: '', municao_id: '', carregador_id: '' }
}
</script>
