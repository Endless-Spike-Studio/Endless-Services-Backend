import useForm from "@inertiajs/vue3";

export type InertiaForm<
    T extends Record<string, unknown>
> = ReturnType<
    typeof useForm<T>
>;

export interface PaginatedData<T extends unknown> {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    data: T[];
}
