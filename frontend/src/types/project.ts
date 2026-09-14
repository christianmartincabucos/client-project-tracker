export type ProjectStatus = 'Planning' | 'In Progress' | 'On Hold' | 'Completed'
export type ProjectPriority = 'Low' | 'Medium' | 'High'

export interface Project {
  id: number
  clientName: string
  projectName: string
  description: string | null
  status: ProjectStatus
  priority: ProjectPriority
  startDate: string | null
  dueDate: string | null
}

export interface ProjectPayload {
  clientName: string
  projectName: string
  description?: string | null
  status: ProjectStatus
  priority: ProjectPriority
  startDate?: string | null
  dueDate?: string | null
}

export type ProjectFilters = {
  search: string
  status: '' | ProjectStatus
  priority: '' | ProjectPriority
  sort: string
  direction: 'asc' | 'desc'
}
