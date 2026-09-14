import { defineStore } from 'pinia'
import api from '../services/api'
import type { Project, ProjectFilters, ProjectPayload } from '../types/project'

const defaultFilters = (): ProjectFilters => ({
  search: '',
  status: '',
  priority: '',
  sort: 'created_at',
  direction: 'asc',
})

export const useProjectsStore = defineStore('projects', {
  state: () => ({
    projects: [] as Project[],
    loading: false,
    saving: false,
    error: '',
    validationErrors: {} as Record<string, string[]>,
    filters: defaultFilters(),
  }),
  actions: {
    async fetchProjects() {
      this.loading = true
      this.error = ''
      try {
        const { data } = await api.get<{ data: Project[] }>('/projects', { params: this.filters })
        this.projects = data.data
      } catch (error: any) {
        this.error = error?.response?.data?.message ?? 'Unable to load projects.'
      } finally {
        this.loading = false
      }
    },
    async createProject(payload: ProjectPayload) {
      this.saving = true
      this.validationErrors = {}
      try {
        const { data } = await api.post<{ data: Project }>('/projects', payload)
        this.projects.unshift(data.data)
        return true
      } catch (error: any) {
        this.validationErrors = error?.response?.data?.errors ?? {}
        this.error = error?.response?.data?.message ?? 'Unable to create project.'
        return false
      } finally {
        this.saving = false
      }
    },
    async updateProject(id: number, payload: ProjectPayload) {
      this.saving = true
      this.validationErrors = {}
      try {
        const { data } = await api.put<{ data: Project }>(`/projects/${id}`, payload)
        const index = this.projects.findIndex((project) => project.id === id)
        if (index !== -1) this.projects[index] = data.data
        return true
      } catch (error: any) {
        this.validationErrors = error?.response?.data?.errors ?? {}
        this.error = error?.response?.data?.message ?? 'Unable to update project.'
        return false
      } finally {
        this.saving = false
      }
    },
    async deleteProject(id: number) {
      this.error = ''
      try {
        await api.delete(`/projects/${id}`)
        this.projects = this.projects.filter((project) => project.id !== id)
        return true
      } catch (error: any) {
        this.error = error?.response?.data?.message ?? 'Unable to delete project.'
        return false
      }
    },
    resetErrors() {
      this.error = ''
      this.validationErrors = {}
    },
  },
})
