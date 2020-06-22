import Vue from 'vue'

function registerComponents(requireComponent) {
    requireComponent.keys().forEach(filename => {
        const componentConfig = requireComponent(filename)

        const componentName = _.upperFirst(
            _.camelCase(filename.replace(/\.\/|\//, '').replace(/\.\w+$/, ''))
        )

        Vue.component(componentName, componentConfig.default || componentConfig)
    })
}

registerComponents(
    require.context('./layout', false, /\.vue$/)
)

export default { }
