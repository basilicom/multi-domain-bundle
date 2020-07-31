pimcore.registerNS("pimcore.plugin.BasilicomMultiDomainBundle");

pimcore.plugin.BasilicomMultiDomainBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.BasilicomMultiDomainBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("BasilicomMultiDomainBundle ready!");
    }
});

var BasilicomMultiDomainBundlePlugin = new pimcore.plugin.BasilicomMultiDomainBundle();
