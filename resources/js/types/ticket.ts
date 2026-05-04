import type { Inbox } from './inbox';

export interface TicketStatus {
    id: number;
    name: string;
    color: string;
    sort_order: number;
    is_default: boolean;
    is_closed: boolean;
}

export interface TicketType {
    id: number;
    name: string;
}

export interface TicketAttachment {
    id: number;
    original_name: string;
    url: string;
    mime_type: string | null;
    size: number;
}

export interface TicketMessage {
    id: number;
    body: string;
    is_internal: boolean;
    author: { id: number; name: string; role: string } | null;
    attachments: TicketAttachment[];
    created_at: string;
}

export interface ActivityLog {
    id: number;
    action: string;
    payload: Record<string, unknown> | null;
    user: { id: number; name: string } | null;
    created_at: string;
}

export interface Ticket {
    id: number;
    number: string;
    subject: string;
    inbox: Inbox | null;
    type: TicketType | null;
    status: TicketStatus | null;
    operator: { id: number; name: string } | null;
    entity: { id: number; name: string } | null;
    contact: { id: number; name: string; email: string | null } | null;
    knowledge_emails: string[];
    messages?: TicketMessage[];
    activity_logs?: ActivityLog[];
    messages_count: number;
    created_by: { id: number; name: string } | null;
    created_at: string;
    updated_at: string;
}
