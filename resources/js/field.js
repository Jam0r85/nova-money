Nova.booting((Vue, router) => {
    Vue.component('index-nova-money', require('./components/IndexField'));
    Vue.component('detail-nova-money', require('./components/DetailField'));
    Vue.component('form-nova-money', require('./components/FormField'));
})
