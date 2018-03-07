var _ioq = _ioq || [];

function IoExampleIntelEvent(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;
    var $;
    this.exampleFClickCount = 0;

    this.init = function init() {
        console.log('IoExampleIntelEvent::init');
    };

    this.domReady = function domReady() {
        console.log('IoExampleIntelEvent::domReady');
        $ = jQuery;

        // gets object if selected
        var $obj = $('.intel-example-addon.example-e');
        io('admin:setBindTarget', $obj);
        $obj.on('click', this.handleExampleEClick);

        var $obj = $('.intel-example-addon.example-f');
        io('admin:setBindTarget', $obj);
        $obj.on('click', function (e) {
            io('exampleIntelEvent:handleExampleFClick', e);
        });
    };

    this.handleExampleEClick = function (e) {
        console.log('IoExampleIntelEvent::handleExampleEClick');

        var evtDef = ioq.eventDefs[_ioq.eventDefsIndex['intel_example_addon_e']];
        ioq.eventHandler(e, evtDef);
    };

    this.handleExampleFClick = function (e) {
        console.log('IoExampleIntelEvent::handleExampleFClick');

        var evtDef = ioq.eventDefs[_ioq.eventDefsIndex['intel_example_addon_f']];

        // increment count
        this.exampleFClickCount++;
        evtDef.eventValue = this.exampleFClickCount;

        ioq.eventHandler(e, evtDef);
    };

    this.init();

    // add domReady callback
    _ioq.push(['addCallback', 'domReady', this.domReady, this]);
}

_ioq.push(['providePlugin', 'exampleIntelEvent', IoExampleIntelEvent, {}]);
