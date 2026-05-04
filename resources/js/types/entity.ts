export interface Entity {
    id: number
    nif: string | null
    name: string
    phone: string | null
    mobile: string | null
    website: string | null
    email: string | null
    internal_notes?: string | null
    contacts_count?: number
}

export interface ContactRole {
    id: number
    name: string
}

export interface Contact {
    id: number
    name: string
    email: string | null
    phone: string | null
    mobile: string | null
    internal_notes?: string | null
    role: ContactRole | null
    entities: Pick<Entity, 'id' | 'name'>[]
}
