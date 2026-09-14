<template>
  <form class="form-grid" @submit.prevent="submit">
    <label>
      Client Name *
      <input v-model.trim="form.clientName" type="text" placeholder="Acme Corporation" />
      <small v-if="errors.clientName" class="error-text">{{ errors.clientName[0] }}</small>
    </label>

    <label>
      Project Name *
      <input v-model.trim="form.projectName" type="text" placeholder="Corporate Website Redesign" />
      <small v-if="errors.projectName" class="error-text">{{ errors.projectName[0] }}</small>
    </label>

    <label class="full-width">
      Description
      <textarea v-model.trim="form.description" rows="4" placeholder="Project description"></textarea>
      <small v-if="errors.description" class="error-text">{{ errors.description[0] }}</small>
    </label>

    <label>
      Status *
      <select v-model="form.status">
        <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
      </select>
      <small v-if="errors.status" class="error-text">{{ errors.status[0] }}</small>
    </label>

    <label>
      Priority *
      <select v-model="form.priority">
        <option v-for="priority in priorities" :key="priority" :value="priority">{{ priority }}</option>
      </select>
      <small v-if="errors.priority" class="error-text">{{ errors.priority[0] }}</small>
    </label>

    <label>
      Start Date
      <input v-model="form.startDate" type="date" />
      <small v-if="errors.startDate" class="error-text">{{ errors.startDate[0] }}</small>
    </label>

    <label>
      Due Date
      <input v-model="form.dueDate" type="date" />
      <small v-if="errors.dueDate" class="error-text">{{ errors.dueDate[0] }}</small>
    </label>

    <div class="form-actions full-width">
      <RouterLink class="button button-secondary" to="/projects">Cancel</RouterLink>
      <button class="button button-primary" type="submit" :disabled="saving">
        {{ saving ? 'Saving…' : submitLabel }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { computed, reactive, watch } from 'vue'
import type {
  Project,
  ProjectPayload,
  ProjectPriority,
  ProjectStatus,
} from '../types/project'

const props = defineProps<{
  project?: Project
  errors?: Record<string, string[]>
  saving?: boolean
  submitLabel: string
}>()

const emit = defineEmits<{
  submit: [payload: ProjectPayload]
}>()

const statuses: ProjectStatus[] = [
  'Planning',
  'In Progress',
  'On Hold',
  'Completed',
]

const priorities: ProjectPriority[] = [
  'Low',
  'Medium',
  'High',
]

const errors = computed(() => props.errors ?? {})

const form = reactive<ProjectPayload>({
  clientName: '',
  projectName: '',
  description: '',
  status: 'Planning',
  priority: 'Medium',
  startDate: '',
  dueDate: '',
})

function populateForm(project?: Project) {
  form.clientName = project?.clientName ?? ''
  form.projectName = project?.projectName ?? ''
  form.description = project?.description ?? ''
  form.status = project?.status ?? 'Planning'
  form.priority = project?.priority ?? 'Medium'
  form.startDate = project?.startDate ?? ''
  form.dueDate = project?.dueDate ?? ''
}

watch(
  () => props.project,
  (project) => {
    populateForm(project)
  },
  { immediate: true },
)

function submit() {
  emit('submit', { ...form })
}
</script>