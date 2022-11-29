export const isMobile = (() => {
    const reference = ref();

    const _update = () => reference.value = screen.width <= 768;
    window.addEventListener('resize', _update);
    _update();

    return reference;
})();
