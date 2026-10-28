;
(function($) {
    $.fn.animeRateBar = function(options) {

        //real settings
        var settings = $.extend({}, $.fn.animeRateBar.defaults, options);

        return this.each(function(index, item) {
            var oSettings = $.extend({}, settings, $(this).data());

            oSettings.thisElement = $(this);
            oSettings.itemIndex = index;
            indefaults.settings = oSettings; //set indefaults

            loadUpdate();

            oSettings.loadInstance ? loadInstance() : null;

            return loadProgress();

        })

    }

    function loadUpdate() {
        var settings = indefaults.settings;

        //default settings
        var item = settings.thisElement;
        var runtime = settings.runtime;
        var delay = settings.delay;
        var addText = settings.addText;
        var breakPoints = settings.breakPoints;
        var breakClasses = settings.classes;
        var breakColors = settings.colors;
        var animAttrval = settings.animVal;
        var splitBar = settings.splitBar;
        var rotation = settings.rotation;
        var flatRate = settings.flatRate || true;
        var addPercent = settings.addPercent;
        var addUnit = settings.addUnit;
        var addLabel = settings.addLabel;
        var mirror = settings.mirror;
        var angle = settings.angle;

        indefaults.mirror = mirror;
        indefaults.angle = angle;
        indefaults.breakPoints = breakPoints;
        indefaults.breakColors = breakColors;
        indefaults.breakClasses = breakClasses;
        indefaults.breakClasses = breakClasses;

        //data-bkpoints = [], data-bkcolors = [] data-bkclass = []
        //calculate and update indefault.settings.(attr)
        var dataFunc = item.find("[data-func]");

        var rateBar = item.find("[data-func='progressbar']");
        var rateData = item.find("[data-func='prodata']");
        var dataToggle = item.find("[data-toggle]");
        var rateBox = item.find("[data-func='protext']");
        indefaults.settings.rateData = rateData;
        indefaults.settings.rateBar = rateBar;
        indefaults.settings.rateBox = rateBox;

        var toggleAttr = (dataToggle.data('toggle') == "") ? "class" : dataToggle.data('toggle');
        indefaults.settings.toggleAttr = settings.toggleAttr;

        var tagName = rateBar.prop("tagName").toLowerCase();
        var isPathTag = (tagName == 'path') ? true : false;
        indefaults.settings.isPathTag = isPathTag;

        //declare vars
        var startPoint, endPoint, markPoint;


        //get data marker
        if (rateData.attr("data-mark")) {
            markPoint = rateData.data('mark');
            markPoint = (markPoint == "" || markPoint == undefined) ? false : markPoint;
        } else {
            markPoint = false;
        }
        indefaults.settings.markPoint = markPoint; //set markpoint

        //get start point
        if (dataStart = rateData.attr("data-start")) {
            startPoint = (dataStart == "") ? 0 : dataStart;
        } else {
            startPoint = settings.startPoint;
        }

        //get end point
        if (dataEnd = rateData.attr("data-end")) {
            endPoint = (dataEnd == "") ? 100 : dataEnd;
        } else if (dataRate = rateData.attr("data-rate")) {
            endPoint = (dataRate == "" || dataRate == undefined) ? 100 : dataRate;
        } else {
            endPoint = settings.endPoint;
        }

        if (markPoint != false) {
            // fix start point, end point before setting them
            startPoint = fixPoint(startPoint, markPoint)
            endPoint = fixPoint(endPoint, markPoint);
        }

        //set startpoint and endpoint
        indefaults.settings.endPoint = endPoint;
        indefaults.endPoint2 = endPoint;

        indefaults.settings.startPoint = startPoint;
        indefaults.startPoint2 = startPoint;


        //get animated attr
        if (rateBar.attr("data-animval")) {
            if (dataFunc.data('animval') == "") {
                var animVal = isPathTag ? "stroke-dasharray" : null;
                animVal = (animAttrval != false && animVal == null) ? animAttrval : animVal;
            }
        } else {
            var animVal = (animAttrval != false) ? animAttrval : null;
        }
        indefaults.settings.animVal = animVal;

        //split progressbar path(only)
        if (isPathTag) {
            var barSplit = rateData.attr('data-split');
            barSplit = (barSplit == null) ? splitBar : barSplit;
            if (typeof barSplit !== "boolean") {
                barSplit = (barSplit == "true" || barSplit == "") ? true : false;
            }
            indefaults.settings.barSplit = barSplit;
        }



        //rotation
        var isRotate = rateData.attr('data-rotate');
        isRotate = (isRotate == null) ? rotation : isRotate;
        if (typeof isRotate !== "boolean") {
            isRotate = (isRotate == "true" || isRotate == "") ? true : false;
        }
        indefaults.settings.isRotate = isRotate;

        //rotation
        var isFlat = rateData.attr('data-flat');
        isFlat = (isFlat == null) ? flatRate : isFlat;
        if (typeof isRotate !== "boolean") {
            isFlat = (isFlat == "true" || isFlat == "") ? true : false;
        }

        indefaults.settings.isFlat = isFlat;

        //check for text percentage (text settings)
        var usePercent = rateData.data('percent');
        usePercent = (usePercent == null) ? addPercent : usePercent;
        if (typeof usePercent !== "boolean") {
            usePercent = (usePercent == "true") ? true : false;
        }
        var textPercent = (usePercent == true) ? (textLabel == null ? "%" : '') : '';
        indefaults.settings.addPercent = usePercent;
        indefaults.settings.textPercent = textPercent;

        var textUnit = rateData.data('unit');
        textUnit = (textUnit == null) ? addUnit : textUnit;
        indefaults.settings.textUnit = textUnit;

        var textLabel = rateData.data('label');
        textLabel = (textLabel == null) ? addLabel : textLabel;
        indefaults.settings.textLabel = textLabel;

        //check for textbox
        var rollTexts = (rateBox.length > 1) ? true : false;
        indefaults.settings.rollTexts = rollTexts;


        //reset radial progressbar to startpoint;
        if (isPathTag && animVal != null && animVal != "") {
            //get full path
            var pathIndex = item.index();
            var pathItem = item.find("path").get(0);
            var pathFull = pathItem.getTotalLength();

            var loadPoint = startPoint;

            if (isFlat) {
                var indefaultS = startPoint;
                var indefaultE = endPoint;

                loadPoint = (indefaultE > indefaultS) ? indefaultE : indefaultS;
                loadPoint = (startPoint / loadPoint) * 100;
            }

            var startRate = ((loadPoint - 0.5) / 100) * pathFull.toFixed(2);
            indefaults.settings.pathFull = pathFull;
        }
        return this;
    }

    function loadInstance() {
        //initiate animate bar

        //load instance default variables (indefaults)
        var settings = indefaults.settings;

        var item = settings.thisElement;
        var rateBar = settings.rateBar;
        var rateBox = settings.rateBox;
        var startPoint = settings.startPoint;
        var endPoint = settings.endPoint;
        var markPoint = settings.markPoint;
        var animVal = settings.animVal;
        var barSplit = settings.barSplit;
        var isPathTag = settings.isPathTag;
        var isFlat = settings.isFlat;
        var textLabel = settings.textLabel;
        var textUnit = settings.textUnit;
        var textPercent = settings.textPercent;
        var mirror = indefaults.mirror;
        var angle = indefaults.angle || '';

        var rangle = (angle != "") ? ' rotate(' + angle + 'deg) ' : "";
        var langle = (angle != "") ? ' rotate(-' + angle + 'deg) ' : "";

        if (mirror == "x") {
            //get item
            item.css({ 'transform': "scaleX(-1)" + rangle });
            item.find("xhtml").css({ 'transform': "scaleX(-1)" + langle });
        } else if (mirror == "y") {
            //get item
            item.css({ 'transform': "scaleY(-1)" + rangle });
            item.find("xhtml").css({ 'transform': "scaleY(-1)" + langle });
        } else if (mirror == "x,y" || mirror == "y,x") {
            //get item
            item.css({ 'transform': "scaleX(-1) scaleY(-1)" + rangle });
            item.find("xhtml").css({ 'transform': "scaleX(-1) scaleY(-1)" + langle });
        }

        //reset progressbar to startpoint;

        var loadPoint = startPoint;

        if (markPoint != false) {

            if (markPoint != 0 && markPoint != '0') {
                loadPoint = (startPoint / markPoint) * 100;
            } else {
                loadPoint = 0;
            }

        } else {
            if (isFlat) {
                var indefaultS = startPoint;
                var indefaultE = endPoint;

                loadPoint = (indefaultE > indefaultS) ? indefaultE : indefaultS;
                loadPoint = (loadPoint == 0) ? 1 : loadPoint;
                loadPoint = (startPoint / loadPoint) * 100;
            }
        }

        if (!isPathTag) {
            rateBar.css({ width: loadPoint + "%" });
            rateBox.html(loadPoint + textPercent + textUnit + textLabel);
        } else {
            if (animVal != null && animVal != "") {

                //get full path
                var pathIndex = item.index();
                var pathItem = item.find("path").get(0);

                var pathFull = pathItem.getTotalLength();
                var startRate = ((loadPoint - 0.5) / 100) * pathFull.toFixed(2);
                var xBar = barSplit ? startRate : pathFull;

                rateBar.attr({
                    [animVal]: startRate + "," + xBar
                });
                rateBox.html(loadPoint + textPercent + textUnit + textLabel);
            }
        }
        return this;
    }

    function isPercentage(value) {
        if (/^\d+%$/.test(value)) {
            return true;
        }
        return false;
    }

    function fixPoint(value, markPoint) {
        if (isPercentage(value)) {
            value.replace('%', '');
            value = parseFloat(value);
            if (markPoint != 0) {
                value = (value / 100) * markPoint;
            } else {
                value = 0;
            }

        }

        if (value > markPoint) {
            value = markPoint;
        }
        value.toString().replace('%', '');
        return value;
    }


    function loadProgress() {

        //load stored variables
        var iSettings = indefaults.settings;
        var item = iSettings.thisElement;
        var mirror = iSettings.mirror;
        var angle = iSettings.angle;
        var mirrorObject, textObject;

        //load values / points
        var runtime = iSettings.runtime;
        var textPercent = iSettings.textPercent;
        var textUnit = iSettings.textUnit;
        var textLabel = iSettings.textLabel;
        var startPoint = indefaults.startPoint2;
        var endPoint = indefaults.endPoint2;
        var markPoint = iSettings.markPoint;

        //add breaks (already added)
        var breakPoints = indefaults.breakPoints;
        var breakColors = indefaults.breakColors;
        var breakClasses = indefaults.breakClasses;


        //load animated boxes
        var rateBox = iSettings.rateBox;
        var rateBar = iSettings.rateBar;
        var pathFull = iSettings.pathFull;
        var rateData = iSettings.rateData;
        //console.log(pathFull)

        //load other required variables
        var tagName = iSettings.tagName;
        var isPathTag = iSettings.isPathTag;
        var isRotate = iSettings.isRotate;
        var isFlat = iSettings.isFlat;

        var barSplit = iSettings.barSplit;
        var animVal = iSettings.animVal;
        var toggleAttr = iSettings.toggleAttr;

        var settings = indefaults;

        var indefaultS = indefaults.startPoint2
        var indefaultE = indefaults.endPoint2;
        var nthPoint = (indefaultE > indefaultS) ? indefaultE : indefaultS;
        nthPoint = (markPoint != false) ? markPoint : nthPoint;

        var angle = (iSettings.angle != null) ? parseInt(iSettings.angle) : null;

        if (angle != null) {
            var rangle = ' rotate(' + (angle) + 'deg) ';
            var langle = (angle > 0) ? ' rotate(-' + (angle) + 'deg) ' : ' rotate(' + Math.abs(angle) + 'deg) ';
        } else {
            var rangle = '',
                langle = '';
        }

        if (mirror == "x") {
            if (angle != null) { langle = rangle }
            mirrorObject = { 'transform': "scaleX(-1)" + rangle };
            textObject = { 'transform': "scaleX(-1)" + langle };
        } else if (mirror == "y") {
            mirrorObject = { 'transform': "scaleY(-1)" + rangle };
            textObject = { 'transform': "scaleY(-1)" + langle };
        } else if (mirror == "xm") {
            mirrorObject = { 'transform': "scaleX(-1)" + langle };
            textObject = { 'transform': "scaleX(-1)" + rangle };
        } else if (mirror == "x,y" || mirror == "y,x") {
            mirrorObject = { 'transform': "scaleX(-1) scaleY(-1)" + rangle };
            textObject = { 'transform': "scaleX(-1) scaleY(-1)" + langle };
        } else {
            if (angle != null) {
                mirrorObject = { 'transform': rangle };
                textObject = { 'transform': langle };
            }
        }

        if (mirrorObject != null) { item.css(mirrorObject); }

        jQuery({ Counter: startPoint }).animate({ Counter: endPoint }, {
            duration: runtime,
            easing: 'swing',
            step: function() {
                indefaults.rollState = stepCounter = Math.ceil(this.Counter);

                startPoint = startPoint == -0 ? 0 : startPoint;

                if (isFlat) {
                    var nthRater = (stepCounter / nthPoint) * 100;
                } else {
                    var nthRater = stepCounter;
                }

                if (isPathTag) {
                    var $startPoint = (nthRater / 100) * pathFull;
                    var $endPoint = ((nthRater - 1) / 100) * pathFull;
                    var cBar = barSplit ? $endPoint : pathFull;

                    rateBar.attr({
                        [animVal]: $startPoint + "," + cBar
                    });
                } else {
                    var $startPoint = $endPoint = nthRater - 1;
                    rateBar.css({ width: $endPoint + "%" });
                }
                var rollangle = (nthRater / 100) * 360

                isRotate ? item.css({ "transform": "rotate(" + rollangle + "deg)" }) : null;

                var realendPoint = (startPoint > endPoint) ? nthRater - 1 : nthRater;

                var nstepCounter = stepCounter;
                if (parseFloat(endPoint) < parseFloat(startPoint)) {
                    nstepCounter = stepCounter - 1;
                } else {
                    nstepCounter = stepCounter;
                }
                rateBar.attr({ "data-point": nstepCounter });
                rateData.attr({ "data-point": nstepCounter });

                let step1 = '<div>' + nstepCounter + textPercent + textUnit + ' </div> ' + textLabel;
                let step2 = '<div>' + realendPoint + textPercent + textUnit + ' </div> ' + textLabel;

                var $textwrite = isFlat ? step1 : step2;

                //NOTE: remove text width hideLabel set as false later!!!

                if (textLabel.indexOf("text:") >= 0) {
                    textContent = textLabel.replace("text:", '');
                    //show only text
                    rateBox.html('<xhtml:span id="xhtml" class="in-block">' + textContent + '</xhtml>');
                } else {
                    if (textLabel == "rate") {
                        //show only rate (no text label)
                        $textwrite = isFlat ? nstepCounter + textPercent + textUnit : realendPoint + textPercent + textUnit;
                        rateBox.html('<xhtml:span id="xhtml" class="in-block"><span class="mybox-full xid">' + $textwrite + '</span><span class="mybox-full"></span></xhtml>');
                    } else if (textLabel == "none") {
                        //show none
                        rateBox.html('<xhtml:span id="xhtml" class="in-block"></xhtml>');
                    } else {
                        //show both
                        //if(textLabel != null){
                        var textCon = (textLabel == "") ? '<span class="in-block xid">' + $textwrite + '</span><span class="mybox"></span>' : $textwrite;
                        rateBox.html('<xhtml:span id="xhtml" class="in-block">' + textCon + '</xhtml>');
                        //}
                    }
                }

                if (textObject != null) { item.find("#xhtml").css(textObject) }

                //breakpoints
                if (breakPoints != null) {
                    if ((breakLength = breakPoints.constructor) === Array) {

                        breakPoints.forEach(function(items, index) {

                            if ((breakVal = breakPoints[index]) != null) {
                                //case1

                                if (breakVal <= nthRater) {
                                    if (breakClasses != null) {
                                        if (breakClasses[index] != null) {
                                            obClass = breakClasses[index];
                                            rateBar.attr({ "data-class": obClass });
                                            if ($(item).find('[data-toggle]').length > 0) {
                                                if (toggleAttr != null) {
                                                    $(item).find('[data-toggle]').attr({
                                                        [toggleAttr]: obClass
                                                    });
                                                }
                                            }
                                        }
                                    }

                                    if (breakColors != null) {
                                        if (breakColors[index] != null) {
                                            obColor = 'with' ; // breakColors[index];

                                            if (isPathTag) {
                                                rateBar.css({ "stroke": obColor });
                                            } else {
                                                rateBar.css({ "background-color": obColor });
                                            }

                                        }
                                    }
                                }

                            }

                        })
                    }
                }

            }

        })
        return this;
    }

    $.fn.animeRateBar.rollTo = function(rollPoint, delay) {
        // $.fn.animeRateBar.defaults.rollInto = rollPoint;

        loadUpdate();
        indefaults.startPoint2 = indefaults.rollState; // rollState
        indefaults.endPoint2 = rollPoint; //roll To (Into)
        return loadProgress();

    }

    //defaults settings
    $.fn.animeRateBar.defaults = {
        obData: null,
        breakPoints: null,
        classes: null,
        colors: null,
        delay: false,
        runtime: 500,
        thisElement: this,
        startPoint: 0,
        endPoint: 100,
        animVal: false,
        splitBar: false,
        addText: true,
        addPercent: false,
        addUnit: "",
        addLabel: "",
        mirror: false,
    }

    var indefaults = {
        settings: false,
        rollInto: false,
        startPoint2: false,
        endPoint2: false,
        rollState: false,
        pauseState: false,
    }

})(jQuery);

function autoRatePoint(elem) {
    elem = (elem != null) ? elem : ".rate-point";
    if ($(elem).length > 0) {
        var rdata = $(this);
        $(elem).each(function() {
            var dataUpdate, ratePoint = $(this);
            dataUpdate = $(ratePoint).find("item").attr("data-self")
            if (dataUpdate != null) {
                selfUpdate = (dataUpdate < 1500) ? 1500 : parseInt(dataUpdate);
                radialRateInitiate(ratePoint)
                // setInterval(function() {
                //     radialRateInitiate(ratePoint)
                // }, selfUpdate)
            } else {
                radialRateInitiate(ratePoint)
            }

        })

    }

}

function radialRateInitiate(ratePoint) {
    var item = ratePoint.find("item");
    var runtime = (item.data("time")) ? parseInt(item.data("time")) : 4500;
 

    var addLabel = item.data("label") ? "<text class='data-label mybox font8 calibri'>" + item.data("label") + "</text>" : '';
    var bkPoints = item.data("bkpoints") ? item.data("bkpoints").split(" ") : false;
    var bkClass = item.data("bkclass") ? item.data("bkclass").split(" ") : false;
    var bkColors = item.data("bkcolors") ? item.data("bkcolors").split(" ") : false;
    var addText = item.data("text") || false;
    var addUnit = item.data("unit") || false;
    var mirror = item.data("mirror") || false;
    var runType = item.data("run");
    var angle = item.data("angle");
    var validRuns = ['scroll', 'load', 'none', 'load-scroll', 'auto'];
    var autoRun = validRuns.includes(runType) ? runType : 'load';
    autoRun = (autoRun == 'load-scroll') ? "load scroll" : autoRun;

    if ($(item).data('self') && item.data('point')) {
        console.log("running");

        var dataPoint = item.attr('data-point') || 0;
        var dataOldPoint = isNaN(parseInt(dataPoint)) ? 0 : parseInt(dataPoint);
        var dataNewPoint = item.attr("data-newpoint") ? item.attr("data-newpoint") : dataOldPoint;
        dataNewPoint = isNaN(parseInt(dataNewPoint)) ? 0 : parseInt(dataNewPoint);

        ratePoint.find('item').attr({ "data-end": dataNewPoint });
        ratePoint.find('item').attr({ "data-start": dataOldPoint });

        var dataStart = ratePoint.find('item').attr("data-start");
        var dataStop = ratePoint.find('item').attr("data-end");

        if (dataStart == dataStop) { return; }

    }
    var objectItem = {
        runtime: runtime,
        obData: (item.data("obdata") || false),
        breakPoints: bkPoints,
        classes: bkClass,
        colors: bkColors,
        delay: (item.data("delay") || false),
        animVal: (item.data("animval") || 'stroke-dasharray'),
        mirror: mirror,
        addText: addText,
        addUnit: addUnit,
        addLabel: addLabel,
        mirror: mirror,
        angle: angle
    }

    if (autoRun != 'none') {

        if (autoRun == "auto") { ratePoint.animeRateBar(objectItem); return true; }
        $(window).bind(autoRun, function() {
            if (autoRun == "scroll") {
                //get distance of item from top
                var windowScrollPoint = ratePoint.scrollTop();
                var radialPoint = ratePoint.offset().top;
                var radialDistance = radialPoint - windowScrollPoint;
                if (radialDistance < 550) {
                    ratePoint.animeRateBar(objectItem);
                } else {
                    executed = false;
                }
            } else {
                ratePoint.animeRateBar(objectItem);
            }
        })
    }
}

/* animation for a rate line bar*/
;
 