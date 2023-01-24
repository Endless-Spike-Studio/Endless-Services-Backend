import route from "ziggy-js";

const request = await fetch('/api/routes');
const routes = await request.json();

export default <
    args extends Parameters<typeof route>
>(name?: args[0], params?: args[1], absolute?: args[2]) => {
    // @ts-ignore
    return route(name, params, absolute, routes);
};
