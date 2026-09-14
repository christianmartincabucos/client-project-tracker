<template>
  <main class="page-shell">
    <section class="page-header">
      <div>
        <p class="eyebrow">Digital Agency</p>
        <h1>Client Project Tracker</h1>
        <p class="muted">Track client projects, priorities and delivery dates.</p>
      </div>
      <RouterLink class="button button-primary" to="/projects/new">+ New Project</RouterLink>
    </section>

    <section class="toolbar card">
      <input v-model="store.filters.search" @input="refresh" type="search" placeholder="Search client or project…" />
      <select v-model="store.filters.status" @change="refresh">
        <option value="">All statuses</option>
        <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
      </select>
      <select v-model="store.filters.priority" @change="refresh">
        <option value="">All priorities</option>
        <option v-for="priority in priorities" :key="priority" :value="priority">{{ priority }}</option>
      </select>
      <select v-model="store.filters.sort" @change="refresh">
        <option value="created_at">Newest added</option>
        <option value="project_name">Project name</option>
        <option value="client_name">Client name</option>
        <option value="start_date">Start date</option>
        <option value="due_date">Due date</option>
        <option value="priority">Priority</option>
        <option value="status">Status</option>
      </select>
      <button class="button button-secondary" @click="toggleDirection">{{ store.filters.direction === 'asc' ? '↑ Asc' : '↓ Desc' }}</button>
    </section>

    <div v-if="store.error" class="alert alert-error">{{ store.error }}</div>
    <div v-if="store.loading" class="card state">Loading projects…</div>

    <section v-else-if="store.projects.length" class="card table-wrap">
      <table>
        <thead>
          <tr><th>Project</th><th>Client</th><th>Status</th><th>Priority</th><th>Due</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-for="project in store.projects" :key="project.id">
            <td><strong>{{ project.projectName }}</strong><small>{{ project.description }}</small></td>
            <td>{{ project.clientName }}</td>
            <td><span class="badge" :class="statusClass(project.status)">{{ project.status }}</span></td>
            <td><span class="badge" :class="priorityClass(project.priority)">{{ project.priority }}</span></td>
            <td>{{ formatDate(project.dueDate) }}</td>
            <td class="actions">
              <RouterLink :to="`/projects/${project.id}/edit`">Edit</RouterLink>
              <button class="link-danger" @click="remove(project)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <section v-else class="card state">
      <h2>No projects found</h2>
      <p class="muted">Try changing your filters or create a new project.</p>
      <RouterLink class="button button-primary" to="/projects/new">Create Project</RouterLink>
    </section>
  </main>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useProjectsStore } from '../stores/projects'
import type { Project, ProjectPriority, ProjectStatus } from '../types/project'

const store = useProjectsStore()
const statuses: ProjectStatus[] = ['Planning', 'In Progress', 'On Hold', 'Completed']
const priorities: ProjectPriority[] = ['Low', 'Medium', 'High']

let timer: number | undefined
function refresh() {
  window.clearTimeout(timer)
  timer = window.setTimeout(() => store.fetchProjects(), 250)
}
function toggleDirection() {
  store.filters.direction = store.filters.direction === 'asc' ? 'desc' : 'asc'
  store.fetchProjects()
}
function formatDate(date: string | null) { return date ? new Date(`${date}T00:00:00`).toLocaleDateString() : '—' }
function statusClass(status: ProjectStatus) { return status.toLowerCase().replaceAll(' ', '-') }
function priorityClass(priority: ProjectPriority) { return priority.toLowerCase() }
async function remove(project: Project) {
  if (!window.confirm(`Delete “${project.projectName}”?`)) return
  await store.deleteProject(project.id)
}
onMounted(() => store.fetchProjects())
</script>
