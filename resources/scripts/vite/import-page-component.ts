export function importPageComponent(name: string, pages: Record<string, any>) {
    for (const path in pages) {
        if (path.endsWith(`${name.replaceAll('.', '/')}.vue`)) {
            return typeof pages[path] === 'function' ? pages[path]() : pages[path];
        }
    }

    throw new Error(`页面不存在: ${name}`);
}
