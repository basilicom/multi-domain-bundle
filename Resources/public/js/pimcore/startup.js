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

pimcore.registerNS("pimcore.object.classes.data.multiDomainSelect");
pimcore.object.classes.data.multiDomainSelect = Class.create(pimcore.object.classes.data.select, {
    type: "multiDomainSelect",
    allowIn: {
        object: true
    },
    initialize: function (treeNode, initData) {
        this.type = "multiDomainSelect";
        this.initData(initData);
        this.treeNode = treeNode;
    },
    getTypeName: function () {
        return t("multiDomainSelect");
    },
    getGroup: function () {
        return "relation";
    },
    getIconClass: function () {
        return "pimcore_icon_gridconfig_class_attributes";
    }
});
