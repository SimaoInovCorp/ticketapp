export interface Inbox {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    operators_count?: number;
    operators?: { id: number; name: string }[];
}
