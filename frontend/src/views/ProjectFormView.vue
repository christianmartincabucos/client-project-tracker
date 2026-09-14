<template>
  <main class="page-shell narrow">
    <section class="page-header">
      <div>
        <p class="eyebrow">Projects</p>
        <h1>{{ editing ? 'Edit Project' : 'Create Project' }}</h1>
        <p class="muted">Keep project details accurate and delivery dates realistic.</p>
      </div>
    </section>

    <div v-if="store.error" class="alert alert-error">{{ store.error }}</div>
    <section class="card form-card">
      <ProjectForm
        :project="project"
        :errors="store.validationErrors"
        :saving="store.saving"
        :submit-label="editing ? 'Save Changes' : 'Create Project'"
        @submit="save"
      />
    </section>
  </main>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ProjectForm from '../components/ProjectForm.vue'
import { useProjectsStore } from '../stores/projects'
import type { Project, ProjectPayload } from '../types/project'

const store = useProjectsStore()
const route = useRoute()
const router = useRouter()
const project = ref<Project | undefined>()
const editing = computed(() => Boolean(route.params.id))

onMounted(async () => {
  store.resetErrors()
  if (!editing.value) return
  await store.fetchProjects()
  project.value = store.projects.find((item) => item.id === Number(route.params.id))
  if (!project.value) router.replace('/projects')
})

async function save(payload: ProjectPayload) {
  const valid = payload.clientName && payload.projectName && payload.startDate && payload.dueDate
    ? new Date(payload.dueDate) >= new Date(payload.startDate)
    : true

  if (!valid) {
    store.validationErrors = { dueDate: ['The due date must be on or after the start date.'] }
    return
  }

  const success = editing.value
    ? await store.updateProject(Number(route.params.id), payload)
    : await store.createProject(payload)

  if (success) router.push('/projects')
}
</script>
