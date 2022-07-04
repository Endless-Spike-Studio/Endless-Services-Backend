import route from "@/scripts/route"
import {Component, Inertia} from "@inertiajs/inertia"
import {computed, ComputedRef, h, ref} from "vue"
import {get} from "lodash-es"
import {usePage} from "@inertiajs/inertia-vue3"
import {NIcon} from "naive-ui"
import {RouteParamsWithQueryOverload} from "ziggy-js"

export const isMobile = ref(false)

call(() => {
    function update() {
        isMobile.value = window.innerWidth < 768
    }

    window.addEventListener('resize', update)
    update()
})

export function call(callback: Function) {
    return callback()
}

export function toRoute(target: string) {
    if (route().current() !== target) {
        const url = route(target)
        Inertia.visit(url)
    }
}

export function toRouteWithParams(target: string, params: RouteParamsWithQueryOverload) {
    if (route().current() !== target) {
        const url = route(target, params)
        Inertia.visit(url)
    }
}

export function toURL(url: string | null) {
    if (url) {
        open(url)
    }
}

export function getProp<TValue>(key: string, defaultValue?: any): ComputedRef<TValue> {
    return computed(() => {
        const props = usePage().props.value
        return get(props, key, defaultValue)
    })
}

export function renderIcon(icon: Component) {
    return () => h(NIcon, null, {
        default: () => h(icon as string, null)
    })
}

export function formatTime(time: string, defaultText?: string) {
    if (!time) {
        return defaultText
    }

    return new Date(time).toLocaleString()
}
