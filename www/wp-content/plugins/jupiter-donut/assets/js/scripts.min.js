'use strict';
/**
 * Copyright Marc J. Schmidt. See the LICENSE file at the top-level
 * directory of this distribution and at
 * https://github.com/marcj/css-element-queries/blob/master/LICENSE.
 */
(function (root, factory) {
    if (typeof define === "function" && define.amd) {
        define(factory);
    } else if (typeof exports === "object") {
        module.exports = factory();
    } else {
        root.ResizeSensor = factory();
    }
}(typeof window !== 'undefined' ? window : this, function () {

    // Make sure it does not throw in a SSR (Server Side Rendering) situation
    if (typeof window === "undefined") {
        return null;
    }
    // Only used for the dirty checking, so the event callback count is limited to max 1 call per fps per sensor.
    // In combination with the event based resize sensor this saves cpu time, because the sensor is too fast and
    // would generate too many unnecessary events.
    var requestAnimationFrame = window.requestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        function (fn) {
            return window.setTimeout(fn, 20);
        };

    /**
     * Iterate over each of the provided element(s).
     *
     * @param {HTMLElement|HTMLElement[]} elements
     * @param {Function}                  callback
     */
    function forEachElement(elements, callback){
        var elementsType = Object.prototype.toString.call(elements);
        var isCollectionTyped = ('[object Array]' === elementsType
            || ('[object NodeList]' === elementsType)
            || ('[object HTMLCollection]' === elementsType)
            || ('[object Object]' === elementsType)
            || ('undefined' !== typeof jQuery && elements instanceof jQuery) //jquery
            || ('undefined' !== typeof Elements && elements instanceof Elements) //mootools
        );
        var i = 0, j = elements.length;
        if (isCollectionTyped) {
            for (; i < j; i++) {
                callback(elements[i]);
            }
        } else {
            callback(elements);
        }
    }

    /**
    * Get element size
    * @param {HTMLElement} element
    * @returns {Object} {width, height}
    */
    function getElementSize(element) {
        if (!element.getBoundingClientRect) {
            return {
                width: element.offsetWidth,
                height: element.offsetHeight
            }
        }

        var rect = element.getBoundingClientRect();
        return {
            width: Math.round(rect.width),
            height: Math.round(rect.height)
        }
    }

    /**
     * Class for dimension change detection.
     *
     * @param {Element|Element[]|Elements|jQuery} element
     * @param {Function} callback
     *
     * @constructor
     */
    var ResizeSensor = function(element, callback) {
        /**
         *
         * @constructor
         */
        function EventQueue() {
            var q = [];
            this.add = function(ev) {
                q.push(ev);
            };

            var i, j;
            this.call = function() {
                for (i = 0, j = q.length; i < j; i++) {
                    q[i].call();
                }
            };

            this.remove = function(ev) {
                var newQueue = [];
                for(i = 0, j = q.length; i < j; i++) {
                    if(q[i] !== ev) newQueue.push(q[i]);
                }
                q = newQueue;
            };

            this.length = function() {
                return q.length;
            }
        }

        /**
         *
         * @param {HTMLElement} element
         * @param {Function}    resized
         */
        function attachResizeEvent(element, resized) {
            if (!element) return;
            if (element.resizedAttached) {
                element.resizedAttached.add(resized);
                return;
            }

            element.resizedAttached = new EventQueue();
            element.resizedAttached.add(resized);

            element.resizeSensor = document.createElement('div');
            element.resizeSensor.dir = 'ltr';
            element.resizeSensor.className = 'resize-sensor';
            var style = 'position: absolute; left: -10px; top: -10px; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;';
            var styleChild = 'position: absolute; left: 0; top: 0; transition: 0s;';

            element.resizeSensor.style.cssText = style;
            element.resizeSensor.innerHTML =
                '<div class="resize-sensor-expand" style="' + style + '">' +
                    '<div style="' + styleChild + '"></div>' +
                '</div>' +
                '<div class="resize-sensor-shrink" style="' + style + '">' +
                    '<div style="' + styleChild + ' width: 200%; height: 200%"></div>' +
                '</div>';
            element.appendChild(element.resizeSensor);

            var position = window.getComputedStyle(element).getPropertyPriority('position');
            if ('absolute' !== position && 'relative' !== position && 'fixed' !== position) {
                element.style.position = 'relative';
            }

            var expand = element.resizeSensor.childNodes[0];
            var expandChild = expand.childNodes[0];
            var shrink = element.resizeSensor.childNodes[1];
            var dirty, rafId, newWidth, newHeight;
            var size = getElementSize(element);
            var lastWidth = size.width;
            var lastHeight = size.height;

            var reset = function() {
                //set display to block, necessary otherwise hidden elements won't ever work
                var invisible = element.offsetWidth === 0 && element.offsetHeight === 0;

                if (invisible) {
                    var saveDisplay = element.style.display;
                    element.style.display = 'block';
                }

                expandChild.style.width = '100000px';
                expandChild.style.height = '100000px';

                expand.scrollLeft = 100000;
                expand.scrollTop = 100000;

                shrink.scrollLeft = 100000;
                shrink.scrollTop = 100000;

                if (invisible) {
                    element.style.display = saveDisplay;
                }
            };
            element.resizeSensor.resetSensor = reset;

            var onResized = function() {
                rafId = 0;

                if (!dirty) return;

                lastWidth = newWidth;
                lastHeight = newHeight;

                if (element.resizedAttached) {
                    element.resizedAttached.call();
                }
            };

            var onScroll = function() {
                var size = getElementSize(element);
                var newWidth = size.width;
                var newHeight = size.height;
                dirty = newWidth != lastWidth || newHeight != lastHeight;

                if (dirty && !rafId) {
                    rafId = requestAnimationFrame(onResized);
                }

                reset();
            };

            var addEvent = function(el, name, cb) {
                if (el.attachEvent) {
                    el.attachEvent('on' + name, cb);
                } else {
                    el.addEventListener(name, cb);
                }
            };

            addEvent(expand, 'scroll', onScroll);
            addEvent(shrink, 'scroll', onScroll);

			// Fix for custom Elements
			requestAnimationFrame(reset);
        }

        forEachElement(element, function(elem){
            attachResizeEvent(elem, callback);
        });

        this.detach = function(ev) {
            ResizeSensor.detach(element, ev);
        };

        this.reset = function() {
            element.resizeSensor.resetSensor();
        };
    };

    ResizeSensor.reset = function(element, ev) {
        forEachElement(element, function(elem){
            elem.resizeSensor.resetSensor();
        });
    };

    ResizeSensor.detach = function(element, ev) {
        forEachElement(element, function(elem){
            if (!elem) return;
            if(elem.resizedAttached && typeof ev === "function"){
                elem.resizedAttached.remove(ev);
                if(elem.resizedAttached.length()) return;
            }
            if (elem.resizeSensor) {
                if (elem.contains(elem.resizeSensor)) {
                    elem.removeChild(elem.resizeSensor);
                }
                delete elem.resizeSensor;
                delete elem.resizedAttached;
            }
        });
    };

    return ResizeSensor;

}));

(function($) {
  'use strict';

  if (!$.isEmptyObject(window.MK)) {return}

  var MK = window.MK || {};
  window.MK = MK;
  MK.utils = window.MK.utils || {};

    /**
     * Gets user browser and its version
     * @return {Object} => {name, version}
     */
	MK.utils.browser = (function() {
        var dataBrowser = [
            {string: navigator.userAgent, subString: "Edge", identity: "Edge"},
            {string: navigator.userAgent, subString: "Chrome", identity: "Chrome"},
            {string: navigator.userAgent, subString: "MSIE", identity: "IE"},
            {string: navigator.userAgent, subString: "Trident", identity: "IE"},
            {string: navigator.userAgent, subString: "Firefox", identity: "Firefox"},
            {string: navigator.userAgent, subString: "Safari", identity: "Safari"},
            {string: navigator.userAgent, subString: "Opera", identity: "Opera"}
        ];

		var versionSearchString = null;
        var searchString = function (data) {
            for (var i = 0; i < data.length; i++) {
                var dataString = data[i].string;
                versionSearchString = data[i].subString;

                if (dataString.indexOf(data[i].subString) !== -1) {
                    return data[i].identity;
                }
            }
        };

        var searchVersion = function (dataString) {
            var index = dataString.indexOf(versionSearchString);
            if (index === -1) {
                return;
            }

            var rv = dataString.indexOf("rv:");
            if (versionSearchString === "Trident" && rv !== -1) {
                return parseFloat(dataString.substring(rv + 3));
            } else {
                return parseFloat(dataString.substring(index + versionSearchString.length + 1));
            }
        };

        var name = searchString(dataBrowser) || "Other";
        var version = searchVersion(navigator.userAgent) || searchVersion(navigator.appVersion) || "Unknown";

        // Expose for css
        $('html').addClass(name).addClass(name + version);


        return {
        	name : name,
        	version : version
        };

	})();

    /**
     * Gets user operating system
     * @return {String}
     */
	MK.utils.OS = (function() {
		if (navigator.appVersion.indexOf("Win")!=-1) return "Windows";
		if (navigator.appVersion.indexOf("Mac")!=-1) return "OSX";
		if (navigator.appVersion.indexOf("X11")!=-1) return "UNIX";
		if (navigator.appVersion.indexOf("Linux")!=-1) return "Linux";
	})();

    /**
     * Check if mobile device.
     * @return {Boolean}
     */
	MK.utils.isMobile = function() {
        // Problems with bigger tablets as users raport differences with behaviour. Switch to navigator sniffing
		// return ('ontouchstart' in document.documentElement) && matchMedia( '(max-width: 1024px)' ).matches;

        // http://www.abeautifulsite.net/detecting-mobile-devices-with-javascript/
        // if it still brings problem try to move to more sophisticated solution like
        // apachemobilefilter.org
        // detectright.com
        // web.wurfl.io
        //
        // Seems as best solution here:
        // hgoebl.github.io/mobile-detect.js

        function android() {
            return navigator.userAgent.match(/Android/i);
        }

        function blackBerry() {
            return navigator.userAgent.match(/BlackBerry/i);
        }

        function iOS() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        }

        function opera() {
            return navigator.userAgent.match(/Opera Mini/i);
        }

        function windows() {
            return navigator.userAgent.match(/IEMobile/i);
        }

        return (android() || blackBerry() || iOS() || opera() || windows() || matchMedia( '(max-width: 1024px)' ).matches);

	};

    /**
     * Check if menu is switched to responsive state based on user width settings
     * @return {Boolean}
     */
    MK.utils.isResponsiveMenuState = function() {
        return window.matchMedia( '(max-width: '+ mk_responsive_nav_width +'px)').matches;
    };



    MK.utils.getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    MK.utils.throttle = function( delay, fn ) {
      var last;
      var deferTimer;

      return function() {
          var context = this;
          var args = arguments;
          var now = +new Date;
          if( last && now < last + delay ) {
            clearTimeout( deferTimer );
            deferTimer = setTimeout( function() {
              last = now; fn.apply( context, args );
            }, delay );
          } else {
            last = now;
            fn.apply( context, args );
          }
      };
    };

    /**
	 * This should be invoked only on page load.
	 * Scrolls to anchor from  address bar
	 */
	MK.utils.scrollToURLHash = function() {
		var loc = window.location,
			hash = loc.hash;

		if ( hash.length && hash.substring(1).length ) {
			// !loading is added early after DOM is ready to prevent native jump to anchor
			hash = hash.replace( '!loading', '' );

			// Wait for one second before animating
			// Most of UI animations should be done by then and async operations complited
			setTimeout( function() {
				MK.utils.scrollToAnchor( hash );
			}, 1000 );

			// Right after reset back address bar
			setTimeout( function() {
				window.history.replaceState(undefined, undefined, hash);
			}, 1001);
		}
  };

  	/**
	 * Controls native scroll behaviour
	 * @return {Object} => {disable, enable}
	 */
	MK.utils.scroll = (function() {
    // 37 - left arror, 38 - up arrow, 39 right arrow, 40 down arrow
  var keys = [38, 40];

    function preventDefault(e) {
      e = e || window.event;
      e.preventDefault();
      e.returnValue = false;
    }

    function wheel(e) {
      preventDefault(e);
    }

    function keydown(e) {
        for (var i = keys.length; i--;) {
            if (e.keyCode === keys[i]) {
                preventDefault(e);
                return;
            }
        }
    }

    function disableScroll() {
        if (window.addEventListener) {
            window.addEventListener('DOMMouseScroll', wheel, false);
        }
        window.onmousewheel = document.onmousewheel = wheel;
        document.onkeydown = keydown;
    }

    function enableScroll() {
        if (window.removeEventListener) {
            window.removeEventListener('DOMMouseScroll', wheel, false);
        }
        window.onmousewheel = document.onmousewheel = document.onkeydown = null;
    }

    return {
      disable : disableScroll,
      enable  : enableScroll
    };

})();

MK.utils.launchIntoFullscreen = function ( element ) {
  if(element.requestFullscreen) {
     element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
};

MK.utils.exitFullscreen = function () {
  if(document.exitFullscreen) {
    document.exitFullscreen();
  } else if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if(document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  }
};

    /**
	 * Scroll Spy implementation. Spy dynamic offsets of elements or static pixel offset
	 * @param  {Number|Element}
	 * @param  {Object} => callback object {before, active, after}
	 */
	MK.utils.scrollSpy = function( toSpy, config ) {
		var $window   = $( window ),
	        container = $('.jupiterx-site')[0],
	        isObj     = ( typeof toSpy === 'object' ),
	        offset    = (isObj) ? MK.val.dynamicOffset( toSpy, config.position, config.threshold ) : function() { return toSpy; },
	        height    = (isObj) ? MK.val.dynamicHeight( toSpy ) : function() { return 0; },
	        cacheVals = {},
	        _p 		  = 'before'; // current position

		var checkPosition = function() {
	    	var s = MK.val.scroll(),
	    		o = offset(),
	    		h = height();

	        if( s < o && _p !== 'before' ) {
	        	// console.log( toSpy, 'before' );
	        	if( config.before ) config.before();
	        	_p = 'before';
	        }
	        else if( s >= o && s <= o + h && _p !== 'active' ) {
	        	// console.log( toSpy, 'active' );
	        	if( config.active ) config.active( o );
	        	_p = 'active';
	        }
	        else if( s > o + h && _p !== 'after' ) {
	        	// console.log( toSpy, 'after' );
	        	if( config.after) config.after( o + h );
	        	_p = 'after';
	        }
		};

		var rAF = function() {
			window.requestAnimationFrame( checkPosition );
		};

		var exportVals = function() {
			return cacheVals;
		};

		var updateCache = function() {
	    	var o = offset(),
	    		h = height();

	        cacheVals = {
	        	before : o - $window.height(),
	        	active : o,
	        	after : o + h
	        };
		};

		if( config.cache ) {
			config.cache( exportVals );
		}

	    checkPosition();
	    $window.on( 'load', checkPosition );
	    $window.on( 'resize', checkPosition );
	    $window.on( 'mouseup', checkPosition );
   		window.addResizeListener( container, checkPosition );

	    $window.on( 'scroll', rAF );

   		updateCache();
	    $window.on( 'load', updateCache );
	    $window.on( 'resize', updateCache );
   		window.addResizeListener( container, updateCache );
  };

  /**
   * Scrolls page to static pixel offset
   * @param  {Number}
   */
  MK.utils.scrollTo = function( offset ) {
    $('html, body').stop().animate({
      scrollTop: offset
    }, {
      duration: 1200,
    });
  };

  MK.utils.isElementInViewport = function( el ) {
    var elemTop = el.getBoundingClientRect().top;
  var isVisible = (elemTop < window.innerHeight);
  return isVisible;
};

	/**
	 * Scrolls to element passed in as object or DOM reference
	 * @param  {String|Object}
	 */
	MK.utils.scrollToAnchor = function( hash ) {
		// Escape meta-chars from hash name only.
		hash = hash.substring(1).replace(/[!"#$%&'()*+,./:;<=>?@[\]^`{|}~]/g, "\\$&");
		hash = "#" + hash;
		var $target = $( hash );
		// console.log( hash );

		if( ! $target.length ) return;

		var offset  = $target.offset().top;
		offset = offset - MK.val.offsetHeaderHeight( offset );

		if( hash === '#top-of-page' ) window.history.replaceState( undefined, undefined, ' ' );
		else window.history.replaceState( undefined, undefined, hash );

		MK.utils.scrollTo( offset );
	};

  /**
	 * Basic implementation of pub / sub pattern to avoid tight coupling with direct module communication
	 * @type {Object}
	 */
	MK.utils.eventManager = {};

	/**
	 * Subscribe to custom event and run callbacks
	 * @param  {String}
	 * @param  {Function}
	 *
	 * @usage MK.utils.eventManager.subscribe('event', function(e, params) {} )
	 */
	MK.utils.eventManager.subscribe = function(evt, func) {
		$(this).on(evt, func);
	};

	/**
	 * Unsubscribe from custom event
	 * @param  {String}
	 * @param  {Function}
	 */
	MK.utils.eventManager.unsubscribe = function(evt, func) {
		$(this).off(evt, func);
	};

	/**
	 * Publish custom event to notify appliaction about state change
	 * @param  {String}
	 *
	 * @usage MK.utils.eventManager.publish('event', {
	 *        	param: val
	 *        })
	 */
	MK.utils.eventManager.publish = function(evt, params) {
		$(this).trigger(evt, [params]);
  };

  /**
	 * Get all top offsets from jQuery collection
	 *
	 * @param  {$Objects}
	 * @return {Aray}
	 */
	MK.utils.offsets = function( $els ) {
		return $.map( $els, function( el ) {
			return $( el ).offset().top;
		});
  };

  /**
	 * Retrive from array of numbers first number that is higher than given parameter
	 *
	 * @param  {Number}
	 * @param  {Array}
	 * @return {Number}
	 */
	MK.utils.nextHigherVal = function( val, arr ) {
		var i = 0,
			higher = null;

		var check = function() {
			if( val > arr[ i ]) {
				i += 1;
				check();
			} else {
				higher = arr[ i ];
			}
		};
		check();

		return higher;
	};

}(jQuery));

(function($) {
	'use strict';

	var MK = window.MK || {};

	/**
	 * MK.core holds most important methods that bootstraps whole application
	 *
	 * @type {Object}
	 */
	MK.core = {};

	/**
	 * State for referance of already loaded script files
	 * @type {Array}
	 */
	var _loadedDependencies = [];

	/**
	 * State of queue represented as pairs of script ref => callback
	 * @type {Object}
	 */
	var _inQueue = {};

	/**
	 * Initializes all components in given scope (object or DOM reference) based on data attribute and 'pointer' css class '.js-el'.
	 * DOM work is reduced by single traversing for pointer class and later filtering through cached object. It expects init() method
	 * on every component. Component itself should be defined in MK.component namespace and assign to DOM element via data-mk-component.
	 * Use it once on DOM ready with document as a scope. For partial initialization after ajax operations pass as a scope element
	 * where new DOM was inserted.
	 *
	 * @param  {string|object}
	 */
	MK.core.initAll = function( scope ) {
		var $el = $( scope ).find( '.js-el' ), // single traversing
			$components = $el.filter( '[data-mk-component]' ),
			component = null;


		// initialize  component
		var init = function init(name, el) {
			var $el = $(el);

			if ( $el.data('init-' + name) ) return; // do not initialize the same module twice

			if ( typeof MK.component[ name ] !== 'function' ) console.log('Component init error: ', name);
			else {
				component = new MK.component[ name ]( el );
				component.init();
				$el.data('init-' + name, true); // mark as initialised
				// TODO add name
				MK.utils.eventManager.publish('component-inited');
			}
		};

		$components.each( function() {
			var self = this,
				$this = $( this ),
				names = $this.data( 'mk-component' );

			if( typeof names === 'string' ) {
				var name = names; // containes only single name. Keep it transparent.
				init(name, self);
			} else {
				names.forEach( function( name ) {
					init(name, self);
				});
			}
		});
	};

	/**
	 * Async loader for 3rd party plugins available from within theme or external CDNs / APIs.
	 * Take one argument as callback which is run when loading is finished. Also keeps track of already loaded scripts
	 * and prevent duplication. Holds in queue multiple callbacks that where defined in different places but depend on the
	 * same plugin.
	 *
	 * TODO: heavy test for multiple dependencies and crosssharing one dependency and different one dependency in queue,
	 * bulletproof with single dependency
	 *
	 * @example MK.core.loadDependencies([MK.core.path.plugins + 'plugin.js'], function() {
	 *          	// do something when plugin is loaded
	 * 			})
	 *
	 * @param  {array}
	 * @param  {function}
	 */
	MK.core.loadDependencies = function( dependencies, callback ) {
		var _callback = callback || function() {};

        if( !dependencies ) {
        	// If no dependencies defined then run _callback imidietelly
        	_callback();
        	return;
        }

		// Check for new dependencies
        var newDeps = dependencies.map( function( dep ) {
            if( _loadedDependencies.indexOf( dep ) === -1 ) {
            	 if( typeof _inQueue[ dep ] === 'undefined' ) {
        			// console.log( dep );
                	return dep;
                } else {
                	_inQueue[ dep ].push( _callback );
                	return true;
                }
            } else {
            	return false;
            }
        });

        // The dependency is not new but it's not resolved yet
        // Callback is added to queue that will be run after the script is loaded
        // Don't run callback just yet.
        if( newDeps[0] === true ) {
        	// console.log('Waiting for ' + dependencies[0]);
        	return;
        }

        // Dependency was loaded previously. We can run callback safely
        if( newDeps[0] === false ) {
        	_callback();
        	return;
        }

        // Create queue and relationship script -> callback array to track
        // all callbacks that waits for ths script
        var queue = newDeps.map( function( script ) {
        	// console.log( script );
        	_inQueue[ script ] = [ _callback ];
            return $.getCachedScript( script );
        });

        // Callbacks invoking
        var onLoad = function onLoad() {
        	var index;
        	newDeps.map( function( loaded ) {
        		_inQueue[ loaded ].forEach( function( callback ) {
        			callback();
        		});
        		delete _inQueue[ loaded ];
                _loadedDependencies.push( loaded );
        	});
        };

        // Run callbacks when promise is resolved
        $.when.apply( null, queue ).done( onLoad );
	};

	/**
	 * Single namespace for all paths recuired in application.
	 * @type {Object}
	 */
	MK.core.path = {
		theme: jupiterDonutVars.themeUrl,
		plugins: jupiterDonutVars.assetsUrl + '/lib/js/',
		ajaxUrl: jupiterDonutVars.ajaxUrl
	};


})(jQuery);


(function($) {
  'use strict';

  /**
   * Entry point of application. Runs all components
   */
  $( window ).on( 'load', function() {
      MK.core.initAll( document );
      MK.utils.scrollToURLHash();
  });

  /**
   * VC frontend editor. Init all components.
   */
  $( window ).on( 'vc_reload', function() {
      setTimeout(function(){
          MK.core.initAll( document );
      }, 100);
  });

  /**
   * Assign global click handlers
   */
  $( document ).on( 'click', '.js-smooth-scroll, .js-main-nav a', smoothScrollToAnchor);
  $( '.side_dashboard_menu a' ).on( 'click', smoothScrollToAnchor);

  function smoothScrollToAnchor( evt ) {
      var anchor = MK.utils.detectAnchor( this );
      var $this = $(evt.currentTarget);
      var loc = window.location;
      var currentPage = loc.origin + loc.pathname;
      var href = $this.attr( 'href' );
      var linkSplit = (href) ? href.split( '#' ) : '';
      var hrefPage  = linkSplit[0] ? linkSplit[0] : '';
      var hrefHash  = linkSplit[1] ? linkSplit[1] : '';

      if( anchor.length ) {
          if(hrefPage === currentPage || hrefPage === '') evt.preventDefault();
          MK.utils.scrollToAnchor( anchor );

      } else if( $this.attr( 'href' ) === '#' ) {
          evt.preventDefault();
      }
  }

}(jQuery));

'use strict';

/**
 * Copyright Marc J. Schmidt. See the LICENSE file at the top-level
 * directory of this distribution and at
 * https://github.com/marcj/css-element-queries/blob/master/LICENSE.
 */
(function (root, factory) {
    if (typeof define === "function" && define.amd) {
        define(['./ResizeSensor.js'], factory);
    } else if (typeof exports === "object") {
        module.exports = factory(require('./ResizeSensor.js'));
    } else {
        root.ElementQueries = factory(root.ResizeSensor);
        root.ElementQueries.listen();
    }
}(typeof window !== 'undefined' ? window : this, function (ResizeSensor) {

    /**
     *
     * @type {Function}
     * @constructor
     */
    var ElementQueries = function () {
        //<style> element with our dynamically created styles
        var cssStyleElement;

        //all rules found for element queries
        var allQueries = {};

        //association map to identify which selector belongs to a element from the animationstart event.
        var idToSelectorMapping = [];

        /**
         *
         * @param element
         * @returns {Number}
         */
        function getEmSize(element) {
            if (!element) {
                element = document.documentElement;
            }
            var fontSize = window.getComputedStyle(element, null).fontSize;
            return parseFloat(fontSize) || 16;
        }

        /**
         * Get element size
         * @param {HTMLElement} element
         * @returns {Object} {width, height}
         */
        function getElementSize(element) {
            if (!element.getBoundingClientRect) {
                return {
                    width: element.offsetWidth,
                    height: element.offsetHeight
                }
            }

            var rect = element.getBoundingClientRect();
            return {
                width: Math.round(rect.width),
                height: Math.round(rect.height)
            }
        }

        /**
         *
         * @copyright https://github.com/Mr0grog/element-query/blob/master/LICENSE
         *
         * @param {HTMLElement} element
         * @param {*} value
         * @returns {*}
         */
        function convertToPx(element, value) {
            var numbers = value.split(/\d/);
            var units = numbers[numbers.length - 1];
            value = parseFloat(value);
            switch (units) {
                case "px":
                    return value;
                case "em":
                    return value * getEmSize(element);
                case "rem":
                    return value * getEmSize();
                // Viewport units!
                // According to http://quirksmode.org/mobile/tableViewport.html
                // documentElement.clientWidth/Height gets us the most reliable info
                case "vw":
                    return value * document.documentElement.clientWidth / 100;
                case "vh":
                    return value * document.documentElement.clientHeight / 100;
                case "vmin":
                case "vmax":
                    var vw = document.documentElement.clientWidth / 100;
                    var vh = document.documentElement.clientHeight / 100;
                    var chooser = Math[units === "vmin" ? "min" : "max"];
                    return value * chooser(vw, vh);
                default:
                    return value;
                // for now, not supporting physical units (since they are just a set number of px)
                // or ex/ch (getting accurate measurements is hard)
            }
        }

        /**
         *
         * @param {HTMLElement} element
         * @param {String} id
         * @constructor
         */
        function SetupInformation(element, id) {
            this.element = element;
            var key, option, elementSize, value, actualValue, attrValues, attrValue, attrName;

            var attributes = ['min-width', 'min-height', 'max-width', 'max-height'];

            /**
             * Extracts the computed width/height and sets to min/max- attribute.
             */
            this.call = function () {
                // extract current dimensions
                elementSize = getElementSize(this.element);

                attrValues = {};

                for (key in allQueries[id]) {
                    if (!allQueries[id].hasOwnProperty(key)) {
                        continue;
                    }
                    option = allQueries[id][key];

                    value = convertToPx(this.element, option.value);

                    actualValue = option.property === 'width' ? elementSize.width : elementSize.height;
                    attrName = option.mode + '-' + option.property;
                    attrValue = '';

                    if (option.mode === 'min' && actualValue >= value) {
                        attrValue += option.value;
                    }

                    if (option.mode === 'max' && actualValue <= value) {
                        attrValue += option.value;
                    }

                    if (!attrValues[attrName]) attrValues[attrName] = '';
                    if (attrValue && -1 === (' ' + attrValues[attrName] + ' ').indexOf(' ' + attrValue + ' ')) {
                        attrValues[attrName] += ' ' + attrValue;
                    }
                }

                for (var k in attributes) {
                    if (!attributes.hasOwnProperty(k)) continue;

                    if (attrValues[attributes[k]]) {
                        this.element.setAttribute(attributes[k], attrValues[attributes[k]].substr(1));
                    } else {
                        this.element.removeAttribute(attributes[k]);
                    }
                }
            };
        }

        /**
         * @param {HTMLElement} element
         * @param {Object}      id
         */
        function setupElement(element, id) {
            if (!element.elementQueriesSetupInformation) {
                element.elementQueriesSetupInformation = new SetupInformation(element, id);
            }
            if (!element.elementQueriesSensor) {
                element.elementQueriesSensor = new ResizeSensor(element, function () {
                    element.elementQueriesSetupInformation.call();
                });
            }

            element.elementQueriesSetupInformation.call();
        }

        /**
         * Stores rules to the selector that should be applied once resized.
         *
         * @param {String} selector
         * @param {String} mode min|max
         * @param {String} property width|height
         * @param {String} value
         */
        function queueQuery(selector, mode, property, value) {
            if (typeof(allQueries[selector]) === 'undefined') {
                allQueries[selector] = [];
                // add animation to trigger animationstart event, so we know exactly when a element appears in the DOM

                var id = idToSelectorMapping.length;
                cssStyleElement.innerHTML += '\n' + selector + ' {animation: 0.1s element-queries;}';
                cssStyleElement.innerHTML += '\n' + selector + ' > .resize-sensor {min-width: '+id+'px;}';
                idToSelectorMapping.push(selector);
            }

            allQueries[selector].push({
                mode: mode,
                property: property,
                value: value
            });
        }

        function getQuery(container) {
            var query;
            if (document.querySelectorAll) query = (container) ? container.querySelectorAll.bind(container) : document.querySelectorAll.bind(document);
            if (!query && 'undefined' !== typeof $$) query = $$;
            if (!query && 'undefined' !== typeof jQuery) query = jQuery;

            if (!query) {
                throw 'No document.querySelectorAll, jQuery or Mootools\'s $$ found.';
            }

            return query;
        }

        /**
         * If animationStart didn't catch a new element in the DOM, we can manually search for it
         */
        function findElementQueriesElements(container) {
            var query = getQuery(container);

            for (var selector in allQueries) if (allQueries.hasOwnProperty(mode)) {
                // find all elements based on the extract query selector from the element query rule
                var elements = query(selector, container);

                for (var i = 0, j = elements.length; i < j; i++) {
                    setupElement(elements[i], selector);
                }
            }
        }

        /**
         *
         * @param {HTMLElement} element
         */
        function attachResponsiveImage(element) {
            var children = [];
            var rules = [];
            var sources = [];
            var defaultImageId = 0;
            var lastActiveImage = -1;
            var loadedImages = [];

            for (var i in element.children) {
                if (!element.children.hasOwnProperty(i)) continue;

                if (element.children[i].tagName && element.children[i].tagName.toLowerCase() === 'img') {
                    children.push(element.children[i]);

                    var minWidth = element.children[i].getAttribute('min-width') || element.children[i].getAttribute('data-min-width');
                    //var minHeight = element.children[i].getAttribute('min-height') || element.children[i].getAttribute('data-min-height');
                    var src = element.children[i].getAttribute('data-src') || element.children[i].getAttribute('url');

                    sources.push(src);

                    var rule = {
                        minWidth: minWidth
                    };

                    rules.push(rule);

                    if (!minWidth) {
                        defaultImageId = children.length - 1;
                        element.children[i].style.display = 'block';
                    } else {
                        element.children[i].style.display = 'none';
                    }
                }
            }

            lastActiveImage = defaultImageId;

            function check() {
                var imageToDisplay = false, i;

                for (i in children) {
                    if (!children.hasOwnProperty(i)) continue;

                    if (rules[i].minWidth) {
                        if (element.offsetWidth > rules[i].minWidth) {
                            imageToDisplay = i;
                        }
                    }
                }

                if (!imageToDisplay) {
                    //no rule matched, show default
                    imageToDisplay = defaultImageId;
                }

                if (lastActiveImage !== imageToDisplay) {
                    //image change

                    if (!loadedImages[imageToDisplay]) {
                        //image has not been loaded yet, we need to load the image first in memory to prevent flash of
                        //no content

                        var image = new Image();
                        image.onload = function () {
                            children[imageToDisplay].src = sources[imageToDisplay];

                            children[lastActiveImage].style.display = 'none';
                            children[imageToDisplay].style.display = 'block';

                            loadedImages[imageToDisplay] = true;

                            lastActiveImage = imageToDisplay;
                        };

                        image.src = sources[imageToDisplay];
                    } else {
                        children[lastActiveImage].style.display = 'none';
                        children[imageToDisplay].style.display = 'block';
                        lastActiveImage = imageToDisplay;
                    }
                } else {
                    //make sure for initial check call the .src is set correctly
                    children[imageToDisplay].src = sources[imageToDisplay];
                }
            }

            element.resizeSensor = new ResizeSensor(element, check);
            check();
        }

        function findResponsiveImages() {
            var query = getQuery();

            var elements = query('[data-responsive-image],[responsive-image]');
            for (var i = 0, j = elements.length; i < j; i++) {
                attachResponsiveImage(elements[i]);
            }
        }

        var regex = /,?[\s\t]*([^,\n]*?)((?:\[[\s\t]*?(?:min|max)-(?:width|height)[\s\t]*?[~$\^]?=[\s\t]*?"[^"]*?"[\s\t]*?])+)([^,\n\s\{]*)/mgi;
        var attrRegex = /\[[\s\t]*?(min|max)-(width|height)[\s\t]*?[~$\^]?=[\s\t]*?"([^"]*?)"[\s\t]*?]/mgi;

        /**
         * @param {String} css
         */
        function extractQuery(css) {
            var match, smatch, attrs, attrMatch;

            css = css.replace(/'/g, '"');
            while (null !== (match = regex.exec(css))) {
                smatch = match[1] + match[3];
                attrs = match[2];

                while (null !== (attrMatch = attrRegex.exec(attrs))) {
                    queueQuery(smatch, attrMatch[1], attrMatch[2], attrMatch[3]);
                }
            }
        }

        /**
         * @param {CssRule[]|String} rules
         */
        function readRules(rules) {
            var selector = '';

            if (!rules) {
                return;
            }

            if ('string' === typeof rules) {
                rules = rules.toLowerCase();
                if (-1 !== rules.indexOf('min-width') || -1 !== rules.indexOf('max-width')) {
                    extractQuery(rules);
                }
            } else {
                for (var i = 0, j = rules.length; i < j; i++) {
                    if (1 === rules[i].type) {
                        selector = rules[i].selectorText || rules[i].cssText;
                        if (-1 !== selector.indexOf('min-height') || -1 !== selector.indexOf('max-height')) {
                            extractQuery(selector);
                        } else if (-1 !== selector.indexOf('min-width') || -1 !== selector.indexOf('max-width')) {
                            extractQuery(selector);
                        }
                    } else if (4 === rules[i].type) {
                        readRules(rules[i].cssRules || rules[i].rules);
                    } else if (3 === rules[i].type) {
                        readRules(rules[i].styleSheet.cssRules);
                    }
                }
            }
        }

        var defaultCssInjected = false;

        /**
         * Searches all css rules and setups the event listener to all elements with element query rules..
         */
        this.init = function () {
            var animationStart = 'animationstart';
            if (typeof document.documentElement.style['webkitAnimationName'] !== 'undefined') {
                animationStart = 'webkitAnimationStart';
            } else if (typeof document.documentElement.style['MozAnimationName'] !== 'undefined') {
                animationStart = 'mozanimationstart';
            } else if (typeof document.documentElement.style['OAnimationName'] !== 'undefined') {
                animationStart = 'oanimationstart';
            }

            document.body.addEventListener(animationStart, function (e) {
                var element = e.target;
                var styles = window.getComputedStyle(element, null);

                if (-1 !== styles.getPropertyValue('animation-name').indexOf('element-queries')) {
                    element.elementQueriesSensor = new ResizeSensor(element, function () {
                        if (element.elementQueriesSetupInformation) {
                            element.elementQueriesSetupInformation.call();
                        }
                    });

                    var sensorStyles = window.getComputedStyle(element.resizeSensor, null);
                    var id = sensorStyles.getPropertyValue('min-width');
                    id = parseInt(id.replace('px', ''));
                    setupElement(e.target, idToSelectorMapping[id]);
                }
            });

            if (!defaultCssInjected) {
                cssStyleElement = document.createElement('style');
                cssStyleElement.type = 'text/css';
                cssStyleElement.innerHTML = '[responsive-image] > img, [data-responsive-image] {overflow: hidden; padding: 0; } [responsive-image] > img, [data-responsive-image] > img {width: 100%;}';

                //safari wants at least one rule in keyframes to start working
                cssStyleElement.innerHTML += '\n@keyframes element-queries { 0% { visibility: inherit; } }';
                document.getElementsByTagName('head')[0].appendChild(cssStyleElement);
                defaultCssInjected = true;
            }

            for (var i = 0, j = document.styleSheets.length; i < j; i++) {
                try {
                    if (document.styleSheets[i].href && 0 === document.styleSheets[i].href.indexOf('file://')) {
                        console.log("CssElementQueries: unable to parse local css files, " + document.styleSheets[i].href);
                    }

                    readRules(document.styleSheets[i].cssRules || document.styleSheets[i].rules || document.styleSheets[i].cssText);
                } catch (e) {
                }
            }

            // findElementQueriesElements();
            findResponsiveImages();
        };

        /**
         * Go through all collected rules (readRules()) and attach the resize-listener.
         * Not necessary to call it manually, since we detect automatically when new elements
         * are available in the DOM. However, sometimes handy for dirty DOM modifications.
         *
         * @param {HTMLElement} container only elements of the container are considered (document.body if not set)
         */
        this.findElementQueriesElements = function (container) {
            findElementQueriesElements(container);
        };

        this.update = function () {
            this.init();
        };
    };

    ElementQueries.update = function () {
        ElementQueries.instance.update();
    };

    /**
     * Removes all sensor and elementquery information from the element.
     *
     * @param {HTMLElement} element
     */
    ElementQueries.detach = function (element) {
        if (element.elementQueriesSetupInformation) {
            //element queries
            element.elementQueriesSensor.detach();
            delete element.elementQueriesSetupInformation;
            delete element.elementQueriesSensor;

        } else if (element.resizeSensor) {
            //responsive image

            element.resizeSensor.detach();
            delete element.resizeSensor;
        }
    };

    ElementQueries.init = function () {
        if (!ElementQueries.instance) {
            ElementQueries.instance = new ElementQueries();
        }

        ElementQueries.instance.init();
    };

    var domLoaded = function (callback) {
        /* Mozilla, Chrome, Opera */
        if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', callback, false);
        }
        /* Safari, iCab, Konqueror */
        else if (/KHTML|WebKit|iCab/i.test(navigator.userAgent)) {
            var DOMLoadTimer = setInterval(function () {
                if (/loaded|complete/i.test(document.readyState)) {
                    callback();
                    clearInterval(DOMLoadTimer);
                }
            }, 10);
        }
        /* Other web browsers */
        else window.onload = callback;
    };

    ElementQueries.findElementQueriesElements = function (container) {
        ElementQueries.instance.findElementQueriesElements(container);
    };

    ElementQueries.listen = function () {
        domLoaded(ElementQueries.init);
    };

    return ElementQueries;

}));

//////////////////////////////////////////////////////////////////////////
//
//   Init all scripts
//
//////////////////////////////////////////////////////////////////////////

// This is bad but we don't have other access to this scope.
// Ajax Portfolio  is defined as plugin and on success needs these to be reinited
// We'll refactor all of this.
window.ajaxInit = function() {
    mk_lightbox_init();
    // mk_click_events();
    // mk_social_share_global();
   // mk_social_share();
    mk_gallery();
    loop_audio_init();
};

window.ajaxDelayedInit = function() {
    mk_flexslider_init();
    // mk_portfolio_ajax();
};

$(document).ready(function() {
    mk_lightbox_init();
    // mk_login_form();
    mk_backgrounds_parallax();
    mk_flexslider_init();
    mk_event_countdown();
    mk_skill_meter();
    mk_milestone();
    // mk_ajax_search();
    // mk_hover_events();
    // mk_portfolio_ajax();
    // product_loop_add_cart();
  //  mk_social_share();
    // mk_portfolio_widget();
    mk_contact_form();
    mk_blog_carousel();
    // mk_header_searchform();
    // mk_click_events();
    // mk_text_typer();
    mk_tab_slider_func();

    $(window).on('load', function() {
        // mk_unfold_footer();
        // mk_tabs();
        // mk_accordion_toggles_tooltip();
        mk_gallery();
        mk_theatre_responsive_calculator();
        // mk_tabs_responsive();
        // mk_start_tour_resize();
        // mk_header_social_resize();
        mk_page_section_social_video_bg();
        loop_audio_init();
        mk_one_page_scroller();
        // mkPositionSidebar();

        setTimeout(function() {
            /*
                Somehow the values are not correctly updated for the screens
                and we need to put setTimeout to fix the issue
            */
            mk_mobile_tablet_responsive_calculator();
        }, 300);

        console.log("ready for rock");
    });


    var onDebouncedResize = function() {
        mk_theatre_responsive_calculator();
        mk_mobile_tablet_responsive_calculator();
        // mk_tabs_responsive();
        // mk_accordion_toggles_tooltip();
        // mk_start_tour_resize();
        // mk_header_social_resize();

        setTimeout(function() {
            // mk_unfold_footer();
        }, 300);
    };

    var debounceResize = null;
    $(window).on("resize", function() {
        if( debounceResize !== null ) { clearTimeout( debounceResize ); }
        debounceResize = setTimeout( onDebouncedResize, 300 );
    });

    var onDebouncedScroll = function() {
        mk_skill_meter();
        //TODO: Ask to Bart how we can call javascript component
        //mk_charts();
        mk_milestone();
    };

    var debounceScroll = null;
    $(window).on("scroll", function() {
        if( debounceScroll !== null ) { clearTimeout( debounceScroll ); }
        debounceScroll = setTimeout( onDebouncedScroll, 100 );
    });

    if (MK.utils.isMobile()) {
        $('body').addClass('no-transform');
    }

});

/* VC frontend editor  */
/* -------------------------------------------------------------------- */
$(window).on("vc_reload",function () {
    mk_flexslider_init();
    loop_audio_init();
    mk_tab_slider_func();
    mk_event_countdown();
    // videoLoadState();
    mk_page_section_social_video_bg();
    // mk_hover_events();

    setTimeout(function() {
        // mkPositionSidebar();
    }, 200);
});

// Replace this if new event for remove found.
$( document ).on( 'click', '.vc_control-btn-delete', function() {
    $( window ).trigger( 'vc_reload' );
} );

$( document ).on( 'sortupdate', '.ui-sortable', function() {
    $( window ).trigger( 'vc_reload' );
} );

/* Typer */
/* -------------------------------------------------------------------- */
function mk_text_typer() {

    "use strict";

    $('[data-typer-targets]').each(function() {
        var that = this;
        MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.typed.js' ], function() {
            var $this = $(that),
                $first_string = [$this.text()],
                $rest_strings = $this.attr('data-typer-targets').split(','),
                $strings = $first_string.concat($rest_strings);

            $this.text('');

            $this.typed({
                strings: $strings,
                typeSpeed: 30, // typing speed
                backDelay: 1200, // pause before backspacing
                loop: true, // loop on or off (true or false)
                loopCount: false, // number of loops, false = infinite
            });
        });
    });
}



/* Tab Slider */
/* -------------------------------------------------------------------- */

function mk_tab_slider_func() {

    "use strict";

    $('.mk-tab-slider').each(function() {
        var that = this;

        MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.swiper.js' ], function() {
            var $this = $(that),
                id = $this.data('id'),
                $autoplayTime = $this.data('autoplay'),
                $content = $('.mk-slider-content');

            var mk_tab_slider = $this.swiper({
                wrapperClass: 'mk-tab-slider-wrapper',
                slideClass: 'mk-tab-slider-item',
                calculateHeight: true,
                speed: 500,
                autoplay: $autoplayTime,
                onSlideChangeStart: function() {
                    $('.mk-tab-slider-nav[data-id="' + id + '"]').find(".active").removeClass('active')
                    $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").eq(mk_tab_slider.activeIndex).addClass('active')
                }
            });

            // Simple repaint for firefox issue (can't handle 100% height after plugin init)
            function repaintFirefox() {
                $content.css('display','block');
                setTimeout(function() {
                    mk_tab_slider.reInit();
                    $content.css('display','table');
                },100);
            }

            $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").first().addClass('active');

            $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").on('touchstart mousedown', function(e) {
                e.preventDefault()
                $('.mk-tab-slider-nav[data-id="' + id + '"]').find(".active").removeClass('active')
                $(this).addClass('active')
                mk_tab_slider.swipeTo($(this).index())
            });

            $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").click(function(e) {
                e.preventDefault();
            });

            repaintFirefox();
            $(window).on('resize', repaintFirefox);
        });

    });

}



/* Edge One Pager */
/* -------------------------------------------------------------------- */
function mk_one_page_scroller() {

    "use strict";

    $('.mk-edge-one-pager').each(function() {
        var self = this;

        MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.fullpage.js' ], function() {

            var $this = $(self),
                anchorArr = [];

            $this.find('.section').each(function() {
                anchorArr.push($(this).attr('data-title'));
            });

            var scrollable = true;
            $this.find('.section').each(function() {
                var $section = $(this),
                    $content = $section.find('.edge-slide-content'),
                    sectionHeight = $section.height(),
                    contentHeight = $content.innerHeight();

                if((contentHeight + 30) > $(window).height()) {
                    scrollable = false;
                }
            });

            if(!scrollable){
                $this.find('.section').each(function() {
                    var $section = $(this);
                    $section.addClass('active').css({
                        'padding-bottom': '50px'
                    });
                });
            }

            if(scrollable) {
                $this.fullpage({
                    verticalCentered: false,
                    resize: true,
                    slidesColor: ['#ccc', '#fff'],
                    anchors: anchorArr,
                    scrollingSpeed: 600,
                    easing: 'easeInQuart',
                    menu: false,
                    navigation: true,
                    navigationPosition: 'right',
                    navigationTooltips: false,
                    slidesNavigation: true,
                    slidesNavPosition: 'bottom',
                    loopBottom: false,
                    loopTop: false,
                    loopHorizontal: true,
                    autoScrolling: true,
                    scrollOverflow: false,
                    css3: true,
                    paddingTop: 0,
                    paddingBottom: 0,
                    normalScrollElements: '.mk-header, .mk-responsive-wrap',
                    normalScrollElementTouchThreshold: 5,
                    keyboardScrolling: true,
                    touchSensitivity: 15,
                    continuousVertical: false,
                    animateAnchor: true,

                    onLeave: function(index, nextIndex, direction) {
                        var currentSkin = $this.find('.one-pager-slide').eq(nextIndex - 1).attr('data-header-skin');
                        MK.utils.eventManager.publish( 'firstElSkinChange', currentSkin );
                        $('#fullPage-nav').removeClass('light-skin dark-skin').addClass(currentSkin + '-skin');

                    },
                    afterRender: function() {

                        var $nav = $('#fullPage-nav');

                        setTimeout(function() {
                            var currentSkin = $this.find('.one-pager-slide').eq(0).attr('data-header-skin');
                            MK.utils.eventManager.publish( 'firstElSkinChange', currentSkin );
                            if($nav.length) $nav.removeClass('light-skin dark-skin').addClass(currentSkin + '-skin');
                        }, 300);

                        var $slide = $this.find('.section'),
                            headerHeight = MK.val.offsetHeaderHeight(0),
                            windowHeight = $(window).height();

                        $slide.height(windowHeight - headerHeight);

                        if($nav.length) {
                            $nav.css({
                                'top': 'calc(50% + ' + (headerHeight/2) + 'px)',
                                'marginTop': 0
                            });

                            var style = $this.attr('data-pagination');
                            $nav.addClass('pagination-' + style);
                        }

                        setTimeout(mk_one_pager_resposnive, 1000);
                    },
                    afterResize: function() {
                        var $slide = $this.find('.section'),
                            headerHeight = MK.val.offsetHeaderHeight(0),
                            windowHeight = $(window).height();

                        $slide.height(windowHeight - headerHeight);

                        $('#fullPage-nav').css({
                            'top': 'calc(50% + ' + (headerHeight/2) + 'px)',
                            'marginTop': 0
                        });

                        setTimeout(mk_one_pager_resposnive, 1000);
                        console.log('Reposition pager content.');
                    },
                });
            }

            // Linking to slides available for desktop and mobile scenarios
            function swipeTo(href, e) {
                href = '_' + href; // ensure a char before #
                if (!~href.indexOf('#')) return;
                var section = href.split('#')[1];
                if (~anchorArr.indexOf(section)) {
                    if (typeof e !== 'undefined') e.preventDefault();
                    if (scrollable) $.fn.fullpage.moveTo(section);
                    else MK.utils.scrollToAnchor('[data-title="'+section+'"]');
                }
            }

            // onload
            var loc = window.location;
            if(loc.hash) swipeTo(loc.hash);

            $(document).on('click', 'a', function(e) {
                var $link = $(e.currentTarget);
                swipeTo($link.attr('href'), e);
            });
        });
    });



}


function mk_one_pager_resposnive() {
    "use strict";

    $('.mk-edge-one-pager').each(function() {
        var $pager = $(this),
            headerHeight = MK.val.offsetHeaderHeight(0),
            windowHeight = $(window).height() - headerHeight;

        $pager.find('.one-pager-slide').each(function() {
            var $slide = $(this),
                $content = $slide.find('.edge-slide-content');

            if ($slide.hasClass('left_center') || $slide.hasClass('center_center') || $slide.hasClass('right_center')) {
                var contentHeight  = $content.height(),
                    distanceFromTop = (windowHeight - contentHeight) / 2;

                distanceFromTop  = (distanceFromTop < 50) ? 50 + headerHeight : distanceFromTop;

                $content.css('marginTop', distanceFromTop);
            }

            if ($slide.hasClass('left_bottom') || $slide.hasClass('center_bottom') || $slide.hasClass('right_bottom')) {
                var distanceFromTop = windowHeight - $content.height() - 90;
                $content.css('marginTop', (distanceFromTop));
            }
        });

        /**
         * Fix AM-2853
         *
         * @since 6.0.3
         *
         * At the init of Edge One Pager (EOP), EOP will render all image
         * background of each sections from top to bottom. In this case,
         * the page height will be more than screen height and the scroll
         * bar will appear. At the same time, the full width row will set
         * the container width into 100%. But, after all Slides are set up,
         * EOP height will be 100% of the screen height, so the scroll bar
         * will be disappeared. It's caused spacing issues on the left and
         * right side of the EOP container. To fix this, the row width
         * should be resized and row position should be readjusted.
         */
        var $row = $pager.parents( '.vc_row.vc_row-fluid.mk-fullwidth-true' );

        // Run only if the Edge One Pager is wrapped inside full width row.
        if ( $row.length > 0 ) {
            // Set the wrapper and row width.
            var $wrapper = $( '.mk-main-wrapper-holder' );
            var $grid = $row.children( '.mk-grid' );
            var rowWidth = $row.width();         // Original width.
            var wrapperWidth = $wrapper.width(); // The new width.

            // Run only if original width is smaller than the new width.
            if ( rowWidth >= wrapperWidth || $grid.length > 0 ) {
                return;
            }

            // Get the content left offset.
            var $content = $wrapper.find( '.theme-content' );
            var oriPos = $content.position();
            var oriPadLeft = $content.css( 'padding-left' );
            var oriLeft = parseInt( oriPos.left ) + parseInt( oriPadLeft );

            // Ensure the new width and left position is more than 0.
            if ( wrapperWidth <= 0 || oriLeft <= 0 ) {
                return;
            }

            // Resize the width and left position of row full width.
            $row.css({
                'width': wrapperWidth,
                'left': oriLeft * -1,
            });
        }
    });
}

/* Image Gallery */
/* -------------------------------------------------------------------- */

function mk_gallery() {

    "use strict";

    $('.mk-gallery .mk-gallery-item.hover-overlay_layer .item-holder').each(function() {
        var itemHolder = $(this),
            galleryDesc = itemHolder.find('.gallery-desc');

        function updatePosition() {
            var parentHeight = itemHolder.outerHeight(),
                contentHeight = galleryDesc.innerHeight();

            var paddingVal = (parentHeight - contentHeight) / 2;
            galleryDesc.css({
                'top': paddingVal,
                // 'padding-bottom': paddingVal
            });

            // console.log(parentHeight);
            // console.log(contentHeight);


        }
        updatePosition();

        $(window).on('resize', function() {
            setTimeout(function() {
                updatePosition();
            }, 1000);
        });
    });
    // Execute hover state for mk gallery item
    if ($(window).width() <= 1024) {
        $('.mk-gallery .mk-gallery-item').on('click', function (e) {
            var clicks = $(this).data('clicks');
            if (clicks) {
                // First click
                $(this).toggleClass('hover-state');
            } else {
                // Second click
                $(this).toggleClass('hover-state');
            }
            $(this).data("clicks", !clicks);
        });
    }
}

/* Theatre Slider Responsive Calculator */
/* -------------------------------------------------------------------- */

function mk_theatre_responsive_calculator() {
    var $laptopContainer = $(".laptop-theatre-slider");
    var $computerContainer = $(".desktop-theatre-slider");
    $laptopContainer.each(function() {
        var $this = $(this),
            $window = $(window),
            $windowWidth = $window.outerWidth(),
            $windowHeight = $window.outerHeight(),
            $width = $this.outerWidth(),
            $height = $this.outerHeight(),
            $paddingTop = 38,
            $paddingRight = 143,
            $paddingBottom = 78,
            $paddingLeft = 143;

        var $player = $this.find('.player-container');

        if ($windowWidth > $width) {
            $player.css({
                'padding-left': parseInt(($width * $paddingLeft) / 1200),
                'padding-right': parseInt(($width * $paddingRight) / 1200),
                'padding-top': parseInt(($height * $paddingTop) / 690),
                'padding-bottom': parseInt(($height * $paddingBottom) / 690),
            });
        }

    });

    $computerContainer.each(function() {
        var $this = $(this),
            $window = $(window),
            $windowWidth = $window.outerWidth(),
            $windowHeight = $window.outerHeight(),
            $width = $this.outerWidth(),
            $height = $this.outerHeight(),
            $paddingTop = 60,
            $paddingRight = 52,
            $paddingBottom = 290,
            $paddingLeft = 49;

        var $player = $this.find('.player-container');

        if ($windowWidth > $width) {
            $player.css({
                'padding-left': parseInt(($width * $paddingLeft) / 1200),
                'padding-right': parseInt(($width * $paddingRight) / 1200),
                'padding-top': parseInt(($height * $paddingTop) / 969),
                'padding-bottom': parseInt(($height * $paddingBottom) / 969),
            });
        }

    });

}

/* Mobile and Tablet Slideshow Responsive Calculator */
/* -------------------------------------------------------------------- */
function mk_mobile_tablet_responsive_calculator() {
    var $laptopSlideshow = $(".mk-laptop-slideshow-shortcode");
    var $lcdSlideshow = $(".mk-lcd-slideshow");

    if ($.exists(".mk-laptop-slideshow-shortcode")) {
        $laptopSlideshow.each(function() {
            var $this = $(this),
                $window = $(window),
                $windowWidth = $window.outerWidth(),
                $windowHeight = $window.outerHeight(),
                $width = $this.outerWidth(),
                $height = $this.outerHeight(),
                $paddingTop = 28,
                $paddingRight = 102,
                $paddingBottom = 52,
                $paddingLeft = 102;

            var $player = $this.find(".slideshow-container");

            $player.css({
                "padding-left": parseInt(($width * $paddingLeft) / 836),
                "padding-right": parseInt(($width * $paddingRight) / 836),
                "padding-top": parseInt(($height * $paddingTop) / 481),
                "padding-bottom": parseInt(($height * $paddingBottom) / 481),
            });

        });
    }

    if ($.exists(".mk-lcd-slideshow")) {
        $lcdSlideshow.each(function() {
            var $this = $(this),
                $window = $(window),
                $windowWidth = $window.outerWidth(),
                $windowHeight = $window.outerHeight(),
                $width = $this.outerWidth(),
                $height = $this.outerHeight(),
                $paddingTop = 35,
                $paddingRight = 39,
                $paddingBottom = 213,
                $paddingLeft = 36;

            var $player = $this.find(".slideshow-container");
            $player.css({
                "padding-left": parseInt(($width * $paddingLeft) / 886),
                "padding-right": parseInt(($width * $paddingRight) / 886),
                "padding-top": parseInt(($height * $paddingTop) / 713),
                "padding-bottom": parseInt(($height * $paddingBottom) / 713),
            });
        });
    }
}


/* Start a tour resize function */
/* -------------------------------------------------------------------- */
function mk_start_tour_resize() {

    $('.mk-header-start-tour').each(function() {

        var $windowWidth = $(document).width(),
            $this = $(this),
            $linkWidth = $this.width() + 15,
            $padding = ($windowWidth - mk_responsive_nav_width) / 2;



        function updateStartTour(){
            if($windowWidth < mk_responsive_nav_width){
                $this.removeClass('hidden');
                $this.addClass('show');
            }else{
                if($padding < $linkWidth){
                    $this.removeClass('show');
                    $this.addClass('hidden');
                }else{
                    $this.removeClass('hidden');
                    $this.addClass('show');
                }
            }
        }

        setTimeout(function() {
            updateStartTour();
        }, 300);
    });
}

/* Header social resize function */
/* -------------------------------------------------------------------- */
function mk_header_social_resize() {

    $('.mk-header-social.header-section').each(function() {

        var $windowWidth = $(document).width(),
            $this = $(this),
            $linkWidth = $this.width() + 15,
            $padding = ($windowWidth - mk_responsive_nav_width) / 2;



        function updateStartTour(){
            if($windowWidth < mk_responsive_nav_width){
                $this.removeClass('hidden');
                $this.addClass('show');
            }else{
                if($padding < $linkWidth){
                    $this.removeClass('show');
                    $this.addClass('hidden');
                }else{
                    $this.removeClass('hidden');
                    $this.addClass('show');
                }
            }
        }

        setTimeout(function() {
            updateStartTour();
        }, 300);
    });
}

/* Page Section Socail Video Player Controls */
/* -------------------------------------------------------------------- */

function mk_page_section_social_video_bg() {
    $(".mk-page-section.social-hosted").each(function() {
        var $container = $(this),
            $sound = $container.data('sound'),
            $source = $container.data('source'),
            player,
            timer = 1000;

        if ( $( 'body' ).hasClass( '.compose-mode' ) ) {
            timer = 2000;
        }

        if ($source == 'youtube') {
            var youtube = $container.find('iframe')[0];
            try {
                player = new YT.Player(youtube, {
                    events: {
                        'onReady': function () {
                           player.playVideo();
                           if($sound == false) {
                               player.mute();
                           }
                       }
                    }
                });


            } catch (e) {
            	console.log( e );
            }
        }
        if ($source == 'vimeo') {
            var vimeo = $container.find('iframe')[0];
            player = $f(vimeo);
            setTimeout(function() {
                player.api('play');
                if($sound === false) {
                    player.api('setVolume', 0);
                }
            }, timer);
        }

    });
}

// Pre RequireJS hot bug fixing

function videoLoadState() {
    $('.mk-section-video video').each(function() {
        var mkVideo = this;

        mkVideo.play();
        this.onload = fire();

        function fire() {
            setTimeout(function() {
                $(mkVideo).animate({
                    'opacity': 1
                }, 300);
            }, 1000);
        }
    });
}
videoLoadState();


// Gmap Widget
(function($) {

    $(window).on('load vc_reload', initialize);

    function initialize() {
        var $gmap = $('.gmap_widget');
        if($gmap.length && typeof google !== 'undefined') $gmap.each(run);
    }

    function run() {
        var $mapHolder = $(this);
        var myLatlng = new google.maps.LatLng($mapHolder.data('latitude'), $mapHolder.data('longitude'));
        var mapOptions = $mapHolder.data('options');
            mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
            mapOptions.center = myLatlng;
        var map = new google.maps.Map(this, mapOptions);

        new google.maps.Marker({
            position: myLatlng,
            map: map
        });
    }

}(jQuery));

// Instagram Widget
(function($) {

    $(window).on('load', function() {
        var $feeds = $('.mk-instagram-feeds');
        if($feeds.length) $feeds.each(run);
    });

    function run() {
        var options = $(this).data('options');
            options.template = '<a class="featured-image '+options.tmp_col+'-columns" href="{{link}}" target="_'+options.tmp_target+'"><div class="item-holder"><img src="{{image}}" /><div class="image-hover-overlay"></div></div></a>';
        var feed = new Instafeed(options);
        feed.run();
    }
}(jQuery));


// Flipbox backface visibility fix for chrome
(function($) {
     $(window).on('load', function() {
         setTimeout( function() {
            $('.chrome-flipbox-backface-fix').removeClass('chrome-flipbox-backface-fix');
         }, 300);
     });
}(jQuery));


/* Product in VC Tab Bug Fix
/* -------------------------------------------------------------------- */
(function($) {
    $(window).on('load', function() {
        $('.vc_tta-tab a').on('click', function() {
            setTimeout( function() {
                $(window).trigger('resize');
            }, 100);
        });
    });
}(jQuery));


/* Vertical menu fix when childrens exceed screen height
/* -------------------------------------------------------------------- */
(function($) {
    $(window).on('load', function() {
        $('#mk-vm-menu .menu-item-has-children, #mk-vm-menu .mk-vm-back').on('mouseenter', function() {
            var $header_inner = $(this).closest('.mk-header-inner'),
                $header_inner_height = $header_inner.outerHeight(),
                $header_bg = $header_inner.find('.mk-header-bg'),
                total_height = 0;
            $header_bg.css('height', '100%');
            setTimeout( function() {
                $header_inner.children(':visible').each(function() {
                    total_height += $(this).outerHeight(true);
                });
                total_height -= $header_bg.height();
                if ( total_height < $header_inner_height ) {
                    $header_bg.css('height', '100%');
                } else {
                    $header_bg.css('height', total_height + 'px');
                }
            }, 600);
        });
    });
}(jQuery));


/* Woocommerce varitions lightbox fix
/* -------------------------------------------------------------------- */
(function($) {
    $(window).on('load', function() {

        var $variations_form = $('.variations_form');

        if ( $variations_form.length ) {

            var $varitions_selects = $variations_form.find('.variations').find('.value').find('select');
            $varitions_selects.on('change', function() {

                // Woocommerce variations lightbox with galleries
                var $all_img_container = $('.mk-product-image .mk-woocommerce-main-image');
                if ( $all_img_container.length ) {
                    $( $all_img_container ).each( set_lightbox_href );
                }

            });
            $varitions_selects.trigger('change');

        }

    });

    function set_lightbox_href() {

        var $product_img = $( this ).find( 'img' ),
            $lightbox    = $( this ).find( '.mk-lightbox' );

        setTimeout( function() {
            var image_url    = $product_img.attr( 'src' ),
                image_suffix = image_url.substr( image_url.lastIndexOf('.') - image_url.length ), // Get image suffix
                image_url    = image_url.slice( 0 , image_url.lastIndexOf('-') ); // Remove image size
            $lightbox.attr('href', image_url + image_suffix );
        }, 300);
    }

}(jQuery));


/* Remove video section when on mobile */
/* -------------------------------------------------------------------- */
(function($) {
    if ( MK.utils.isMobile() ) {
      $('.mk-section-video video').remove();
    }
}(jQuery));


/* Yith AJAX Product Filter & Yith Infinite Scrolling Plugin Fix
/* -------------------------------------------------------------------- */
(function($) {
    $(window).on('load', function() {

        $(document).on( 'yith-wcan-ajax-filtered yith_infs_added_elem yith-wcan-ajax-reset-filtered', function(){
            setTimeout( function() {
                MK.utils.eventManager.publish('ajaxLoaded');
                MK.core.initAll( document );
            }, 1000 );
        });

        // Fixed YITH Filter plugin causes issue for dropdown sort
        $(document).on( 'yith-wcan-ajax-filtered yith-wcan-ajax-reset-filtered', function(){
            setTimeout( function() {
                $( '.woocommerce-ordering' ).on( 'change', 'select.orderby', function() {
                    $( this ).closest( 'form' ).submit();
                });
            }, 1000 );
        });

    });
}(jQuery));


/* Toggle loading state in URL for anchor links.
 * - Add a filter to escape meta-chars from hash string.
/* -------------------------------------------------------------------- */
!function(e){var a=window.location,n=a.hash;if(n.length&&n.substring(1).length){var hSuf = n.substring(1).replace(/[!"#$%&'()*+,./:;<=>?@[\]^`{|}~]/g, "\\$&");var r=e(".vc_row, .mk-main-wrapper-holder, .mk-page-section, #comments"),t=r.filter("#"+hSuf);if(!t.length)return;n=n.replace("!loading","");var i=n+"!loading";a.hash=i}}(jQuery);


/* Determine the top spacing of sidebar for full-width page section & row.
/* -------------------------------------------------------------------- */
function mkPositionSidebar() {

	var themeContent = $( '.theme-content' ),
		lastFullWidthChild = themeContent.find( '.vc_row-full-width' ).last(),
		top,
		sidebar = $( '#theme-page > .mk-main-wrapper-holder > .theme-page-wrapper > #mk-sidebar' );

	if ( ! lastFullWidthChild.length ) {
		sidebar.removeAttr( 'style' );
		return;
	}

	top = lastFullWidthChild.offset().top - themeContent.offset().top;
	sidebar.css( 'padding-top', top );
}

(function($) {
	'use strict';

	$.exists = function(selector) {
	    return ($(selector).length > 0);
	};

	/**
	 * Helper to enable caching async scripts
	 * https://api.jquery.com/jquery.getscript/
	 * http://www.vrdmn.com/2013/07/overriding-jquerygetscript-to-include.html
	 * 
	 * @param  {String}   script url
	 * @param  {Function} callback     
	 */
	$.getCachedScript = function( url ) {
		var options = {
			dataType: "script",
			cache: true,
			url: url
		};
	 
	    // Use $.ajax() since it is more flexible than $.getScript
	    // Return the jqXHR object so we can chain callbacks
	  	return $.ajax( options );
	};



	// Fn to allow an event to fire after all images are loaded
	// usage:
	// $.ajax({
	//     cache: false,
	//     url: 'ajax/content.php',
	//     success: function(data) {
	//         $('#divajax').html(data).imagesLoaded().then(function(){
	//             // do stuff after images are loaded here
	//         });
	//     }
	// });
	$.fn.mk_imagesLoaded = function () {

	    // Edit: in strict mode, the var keyword is needed
	    var $imgs = this.find('img[src!=""]');
	    // if there's no images, just return an already resolved promise
	    if (!$imgs.length) {return $.Deferred().resolve().promise();}

	    // for each image, add a deferred object to the array which resolves when the image is loaded (or if loading fails)
	    var dfds = [];  
	    $imgs.each(function(){
	        var dfd = $.Deferred();
	        dfds.push(dfd);
	        var img = new Image();
	        img.onload = function(){dfd.resolve();};
	        img.onerror = function(){dfd.resolve();};
	        img.src = this.src;
	    });

	    // return a master promise object which will resolve when all the deferred objects have resolved
	    // IE - when all the images are loaded
	    return $.when.apply($,dfds);

	};

}(jQuery));
/**
* Detect Element Resize
*
* https://github.com/sdecima/javascript-detect-element-resize
* Sebastian Decima
*
* version: 0.5.3
**/

(function () {
	var attachEvent = document.attachEvent,
		stylesCreated = false;

	if (!attachEvent) {
		var requestFrame = (function(){
			var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame ||
								function(fn){ return window.setTimeout(fn, 20); };
			return function(fn){ return raf(fn); };
		})();

		var cancelFrame = (function(){
			var cancel = window.cancelAnimationFrame || window.mozCancelAnimationFrame || window.webkitCancelAnimationFrame ||
								   window.clearTimeout;
		  return function(id){ return cancel(id); };
		})();

		/* Detect CSS Animations support to detect element display/re-attach */
		var animation = false,
			animationstring = 'animation',
			keyframeprefix = '',
			animationstartevent = 'animationstart',
			domPrefixes = 'Webkit Moz O ms'.split(' '),
			startEvents = 'webkitAnimationStart animationstart oAnimationStart MSAnimationStart'.split(' '),
			pfx  = '';
		{
			var elm = document.createElement('fakeelement');
			if( elm.style.animationName !== undefined ) { animation = true; }

			if( animation === false ) {
				for( var i = 0; i < domPrefixes.length; i++ ) {
					if( elm.style[ domPrefixes[i] + 'AnimationName' ] !== undefined ) {
						pfx = domPrefixes[ i ];
						animationstring = pfx + 'Animation';
						keyframeprefix = '-' + pfx.toLowerCase() + '-';
						animationstartevent = startEvents[ i ];
						animation = true;
						break;
					}
				}
			}
		}

		var animationName = 'resizeanim';
		var animationKeyframes = '@' + keyframeprefix + 'keyframes ' + animationName + ' { from { opacity: 0; } to { opacity: 0; } } ';
		var animationStyle = keyframeprefix + 'animation: 1ms ' + animationName + '; ';
	}

	function createStyles() {
		if (!stylesCreated) {
			//opacity:0 works around a chrome bug https://code.google.com/p/chromium/issues/detail?id=286360
			var css = (animationKeyframes ? animationKeyframes : '') +
					'.resize-triggers { ' + (animationStyle ? animationStyle : '') + 'visibility: hidden; opacity: 0; } ' +
					'.resize-triggers, .resize-triggers > div, .contract-trigger:before { content: \" \"; display: block; position: absolute; top: 0; left: 0; height: 100%; width: 100%; overflow: hidden; } .resize-triggers > div { background: #eee; overflow: auto; } .contract-trigger:before { width: 200%; height: 200%; }',
				head = document.head || document.getElementsByTagName('head')[0],
				style = document.createElement('style');

			style.type = 'text/css';
			if (style.styleSheet) {
				style.styleSheet.cssText = css;
			} else {
				style.appendChild(document.createTextNode(css));
			}

			head.appendChild(style);
			stylesCreated = true;
		}
	}

	window.addResizeListener = function(element, fn){

    function resetTriggers(element){
			var triggers = element.__resizeTriggers__,
				expand = triggers.firstElementChild,
				contract = triggers.lastElementChild,
				expandChild = expand.firstElementChild;
			contract.scrollLeft = contract.scrollWidth;
			contract.scrollTop = contract.scrollHeight;
			expandChild.style.width = expand.offsetWidth + 1 + 'px';
			expandChild.style.height = expand.offsetHeight + 1 + 'px';
			expand.scrollLeft = expand.scrollWidth;
			expand.scrollTop = expand.scrollHeight;
		};

		function checkTriggers(element){
			return element.offsetWidth != element.__resizeLast__.width ||
						 element.offsetHeight != element.__resizeLast__.height;
		}

		function scrollListener(e){
			var element = this;
			resetTriggers(this);
			if (this.__resizeRAF__) cancelFrame(this.__resizeRAF__);
			this.__resizeRAF__ = requestFrame(function(){
				if (checkTriggers(element)) {
					element.__resizeLast__.width = element.offsetWidth;
					element.__resizeLast__.height = element.offsetHeight;
					element.__resizeListeners__.forEach(function(fn){
						fn.call(element, e);
					});
				}
			});
    };

    if (!element) {return}

		if (attachEvent) element.attachEvent('onresize', fn);
		else {
			if (!element.__resizeTriggers__) {
				if (getComputedStyle(element).position == 'static') element.style.position = 'relative';
				createStyles();
				element.__resizeLast__ = {};
				element.__resizeListeners__ = [];
				(element.__resizeTriggers__ = document.createElement('div')).className = 'resize-triggers';
				element.__resizeTriggers__.innerHTML = '<div class="expand-trigger"><div></div></div>' +
																						'<div class="contract-trigger"></div>';
				element.appendChild(element.__resizeTriggers__);
				resetTriggers(element);
				element.addEventListener('scroll', scrollListener, true);

				/* Listen for a css animation to detect element display/re-attach */
				animationstartevent && element.__resizeTriggers__.addEventListener(animationstartevent, function(e) {
					if(e.animationName == animationName)
						resetTriggers(element);
				});
			}
			element.__resizeListeners__.push(fn);
		}
	};

	window.removeResizeListener = function(element, fn){
		if (attachEvent) element.detachEvent('onresize', fn);
		else {
			element.__resizeListeners__.splice(element.__resizeListeners__.indexOf(fn), 1);
			if (!element.__resizeListeners__.length) {
					element.removeEventListener('scroll', scrollListener);
					element.__resizeTriggers__ = !element.removeChild(element.__resizeTriggers__);
			}
		}
	}
})();

(function($) {
	'use strict';

	var MK = window.MK || {};

	/**
	* 	MK.val is collection of Lambdas responsible for returning up to date values of method type like scrollY or el offset.
	* 	The Lambda is responsible for keeping track of value of a particular property, usually takes as argument an object
	* 	(or DOM reference) and internally creates and updates data that is returned as primitive value - through variable reference.
	*
	*  Benefits of this approach:
	*  - reduced DOM reads
	*  - auto-updating values without need for additional logic where methods are called
	*  - updating values when needed to be updated not read
	*
	*  Downsides:
	*  - Memory overhead with closures and keeping state in memory ( still beter than read state from DOM, but use wisely -
	*    do not use it when you really need static value on runtime )
	*/
	MK.val = {};

	/**
	* Current window offsetY position
	*
	* @uses   MK.val.scroll()
	* @return {number}
	*/
	MK.val.scroll = (function() {
		var offset = 0,
		$window = $(window),
		hasPageYOffset = (window.pageYOffset !== undefined),
		body = (document.documentElement || document.body.parentNode || document.body); // cross browser handling

		var update = function() {
			offset = hasPageYOffset ? window.pageYOffset : body.scrollTop;
		};

		var rAF = function() {
			window.requestAnimationFrame(update);
		};

		update();
		$window.on('load', update);
		$window.on('resize', update);
		$window.on('scroll', rAF);

		return function() {
			return offset;
		};
	})();


	/**
	* Changes number of percent to pixels based on viewport height
	*
	* @uses   MK.val.viewportPercentHeight({percent val})
	* @param  {number}
	* @return {number}
	*/
	MK.val.viewportPercentHeight = function(percent) {
		return $(window).height() * (percent / 100);
	};


	/**
	* Wordpress adminbar height based on wp media queries
	* @return {Number}
	*/
	MK.val.adminbarHeight = function() {
		if (php.hasAdminbar) {
			// apply WP native media-query and sizes
			return (window.matchMedia('( max-width: 782px )').matches) ? 46 : 32;
		} else {
			return 0;
		}
	};


	/**
	* Offset when header becomes sticky. Evaluates viewport % and header height to pixels for according options
	* @return {Number}
	*/
	MK.val.stickyOffset = (function() {
		var $header = $('.mk-header').not('.js-header-shortcode').first();

		// We need to have returning function even when header is disabled
		if (!$header.length) {
			return function() {
				return 0;
			};
		}



		var $toolbar = $header.find('.mk-header-toolbar'),
		config = $header.data(),
		hasToolbar = $toolbar.length,
		toolbarHeight = (hasToolbar) ? $toolbar.height() : 0,
		isVertical = (config.headerStyle === 4),
		headerHeight = (isVertical) ? 0 : config.height;

		var type = ((typeof config.stickyOffset === 'number') ? 'number' : false) ||
		((config.stickyOffset === 'header') ? 'header' : false) ||
		'percent';

		var stickyOffset = 0;
		var setOffset = function() {

			//we calculate toolbar height for When the device is changed Size
			//Toolbar height in responsive state is 0
			toolbarHeight = (hasToolbar) ? $toolbar.height() : 0;

			if (MK.utils.isResponsiveMenuState()) {
				headerHeight = config.responsiveHeight;

				if (hasToolbar) {
					if ($toolbar.is(':hidden')) {
						toolbarHeight = 0;
					}
				}
			}

			if (type === 'number') {
				stickyOffset = config.stickyOffset;
			} else if (type === 'header') {

				stickyOffset = headerHeight + toolbarHeight + MK.val.adminbarHeight(); // add all header components here, make them 0 if needed

			} else if (type === 'percent') {
				stickyOffset = MK.val.viewportPercentHeight(parseInt(config.stickyOffset));
			}
		};

		setOffset();
		$(window).on('resize', setOffset);

		return function() {
			return stickyOffset;
		};
	}());



	/**
	* Gets header height on particular offsetY position. Use to determine logic for fullHeight, smooth scroll etc.
	* Takes one parameter which is offset position we're interested in.
	*
	* @uses   MK.val.offsetHeaderHeight({offset val})
	* @param  {number}
	* @return {number}
	*/
	MK.val.offsetHeaderHeight = (function() { // Closure avoids multiple DOM reads. We need to fetch header config only once.
		var $header = $('.mk-header').not('.js-header-shortcode').first();

		// We need to have returning function even when header is disabled
		if (!$header.length) {
			return function() {
				return 0;
			};
		}

		var $toolbar = $header.find('.mk-header-toolbar'),
		config = $header.data(),
		stickyHeight = config.stickyHeight,
		desktopHeight = config.height,
		mobileHeight = config.responsiveHeight,
		isTransparent = $header.hasClass('transparent-header'),
		isSticky = config.stickyStyle.length,
		isStickyLazy = config.stickyStyle === 'lazy',
		isVertical = config.headerStyle === 4,
		hasToolbar = $toolbar.length,
		toolbarHeight = hasToolbar ? $toolbar.height() : 0,
		bufor = 5;

		/**
		 * The sticky section of header style 2 has fixed height.
		 * The stickey height option does not affect this header style.
		 */
		if ( config.headerStyle === 2 ) {
			stickyHeight = $header.find( '.mk-header-nav-container' ).outerHeight();
		}

		// if header has border bottom we can calculate that (for responsive state)
		var $innerHeader = $header.find('.mk-header-inner');
		var hasInnerHeader = $innerHeader.length;

		var headerHeight = function(offset) {

			toolbarHeight = hasToolbar ? $toolbar.height() : 0
			var stickyOffset = MK.val.stickyOffset();


			if (MK.utils.isResponsiveMenuState()) { //  Header avaible only on top for mobile

				if (hasToolbar && $toolbar.is(':hidden')) {
					toolbarHeight = 0;
				}

				//in responsive state , .mk-header-holder position's changed to "relative"
				//and header's border affected to offset,so borders must be calculated
				var headerBorder = 0;
				headerBorder = parseInt($innerHeader.css('border-bottom-width'));

				var totalHeight = mobileHeight + MK.val.adminbarHeight() + toolbarHeight + headerBorder;

				if (offset <= totalHeight) return totalHeight;
				else return MK.val.adminbarHeight();
			} else {
				if (offset <= stickyOffset) {
					if (isVertical) {
						if (hasToolbar) {
							return toolbarHeight + MK.val.adminbarHeight();
						} else {
							return MK.val.adminbarHeight();
						}
					} else if (isTransparent) {
						return MK.val.adminbarHeight();
					} else {
						return desktopHeight + toolbarHeight + MK.val.adminbarHeight();
					} // For any other return regular desktop height
				} else if (offset > stickyOffset) {
					if (isVertical) {
						return MK.val.adminbarHeight();
					} else if (!isSticky) {
						return MK.val.adminbarHeight();
					} else if (isStickyLazy) {
						return MK.val.adminbarHeight();
					} else if (isSticky) {
						return stickyHeight + MK.val.adminbarHeight();
					}
				}
			}
			// default to 0 to prevent errors ( need to return number )
			// Anyway make sure all scenarios are covered in IFs
			return 0;
		};

		return function(offset) {
			return headerHeight(offset - MK.val.adminbarHeight());
		};
	})();


	/**
	* Gets current offset of given element (passed as object or DOM reference) from top or bottom (default to top)
	* of screen  with possible threshold (default to 0)
	*
	* @uses   MK.val.dynamicOffset({obj reference}, {'top'|'bottom'}, {threshold val})
	* @param  {string|object}
	* @param  {string}
	* @param  {number}
	* @return {number}
	*/
	MK.val.dynamicOffset = function(el, position, threshold) {
		var $window = $(window),
		$el = $(el),
		pos = position || 'top',
		thr = threshold || 0,
		container = $('.jupiterx-site')[0],
		currentPos = 0;

		var offset = 0,
		winH = 0,
		rect = 0,
		x = 0;

		var update = function() {
			winH = $window.height();
			rect = $el[0].getBoundingClientRect();
			offset = (rect.top + MK.val.scroll());
			x = (pos === 'top') ? MK.val.offsetHeaderHeight(offset) : winH + (rect.height - thr);
			currentPos = offset - x - 1;
		};

		update();
		$window.on('load', update);
		$window.on('resize', update);
		window.addResizeListener(container, update);

		return function() {
			return currentPos;
		};
	};

	/**
	* Gets current height of given element (passed as object or DOM reference)
	*
	* @uses   MK.val.dynamicHeight({obj reference})
	* @param  {string|object}
	* @return {number}
	*/
	MK.val.dynamicHeight = function(el) {
		var $window = $(window),
		$el = $(el),
		container = $('.jupiterx-site')[0],
		currentHeight = 0;

		var update = function() {
			currentHeight = $el.outerHeight();
		};

		update();
		$window.on('load', update);
		$window.on('resize', update);
		window.addResizeListener(container, update);

		return function() {
			return currentHeight;
		};
	};

})(jQuery);

(function($) {
    'use strict';

    var Accordion = function(el) { 
        // Private
        var that = this,
            $el = $(el),
            initial = $el.data('initialindex'),
            timeout;

        // Public
        this.$el = $el;
        this.$single = $('.' + this.dom.single, $el);
        this.isExpendable = ($el.data('style') === 'toggle-action');

        // Init 
        this.bindClicks();
        // Reveal initial tab on load event (wait for possible images inside)
        $(window).on('load', function() {
            if( initial !== -1 ) that.show(that.$single.eq(initial))
        });
        $(window).on('resize', function() {
            clearTimeout(timeout);
            timeout = setTimeout(that.bindClicks.bind(that), 500);
        }); 
    }

    Accordion.prototype.dom = {
        // only class names please!
        single        : 'mk-accordion-single',
        tab           : 'mk-accordion-tab',
        pane          : 'mk-accordion-pane',
        current       : 'current',
        mobileToggle  : 'mobile-false',
        mobileBreakPoint : 767
    }

    Accordion.prototype.bindClicks = function() {
        // Prevent multiple events binding
        this.$single.off('click', '.' + this.dom.tab);

        if( !(window.matchMedia('(max-width: ' + this.dom.mobileBreakPoint +'px)').matches 
          && this.$el.hasClass(this.dom.mobileToggle)) ) {

            this.$single.on('click', '.' + this.dom.tab, this.handleEvent.bind(this));
            // When website is loaded in mobile view and resized to desktop 'current' will 
            // inherit display: none from css. Repair it by calling show() on this element
            var $current = $('.' + this.dom.current, this.$el);
            if($('.' + this.dom.pane, $current).css('display') === 'none') this.show($current);
        }
    }

    Accordion.prototype.handleEvent = function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $single = $(e.delegateTarget);

        if(!$single.hasClass(this.dom.current)) {
            this.show($single);
        }
        else {
            if(this.isExpendable) this.hide($single);
        }
    }

    Accordion.prototype.hide = function($single) {
        $single.removeClass(this.dom.current);
        $('.' + this.dom.pane, $single).slideUp();
    }

    Accordion.prototype.show = function($single) {
        // hide currently opened tab
        if(!this.isExpendable) {
            var that = this;
            this.hide($('.' + this.dom.current, that.$el));
        }

        $single.addClass(this.dom.current);
        $('.' + this.dom.pane, $single).slideDown( '', function() {
            window.scrollTo({ top: $(this)[0].offsetTop, left: 0, behavior: 'smooth' });
        } );
    }



    // ///////////////////////////////////////
    //
    // Apply to:
    //
    // ///////////////////////////////////////

	function init() {
		$('.mk-accordion').each(function() {
			new Accordion(this);
		});
	}

	init();
	$(window).on('vc_reload', init);

})(jQuery);
(function($) {
  'use strict';

  var MK = window.MK || {};
  window.MK = MK;
  MK.ui = window.MK.ui || {};

  var _ajaxUrl = MK.core.path.ajaxUrl;
  var _instances = {};

	MK.utils.ajaxLoader = function ajaxLoader(el) {
		// retrun a cached instance to have control over state from within multiple places
		// we may need for example to reset pageId when do filtering. It is really one instance that controls both filtering and pagination / load more
		var id = '#' + ($(el).attr('id'));
		if(typeof _instances[id] !== 'undefined') return _instances[id];

		// else lets start new instance
		this.id = id;
		this.el = el;
		this.isLoading = false;
		this.xhrCounter = 0;
	};

	MK.utils.ajaxLoader.prototype = {
		init: function init() {
			// prevent double initialization of we return an instance
			if ( this.initialized && typeof window.vc_iframe === 'undefined' ) {
				return;
			}

			this.createInstance();
			this.cacheElements();

			this.initialized = true;
		},

		cacheElements: function cacheElements() {
			this.$container = $(this.el);
			this.id = '#' + (this.$container.attr('id'));
	        this.categories = this.$container.data('loop-categories');

			this.data = {};
			this.data.action = 'mk_load_more';
	        this.data.query = this.$container.data('query');
	        this.data.atts = this.$container.data('loop-atts');
	        this.data.loop_iterator = this.$container.data('loop-iterator');
	        this.data.author = this.$container.data('loop-author');
	        this.data.posts = this.$container.data('loop-posts');
	        this.data.safe_load_more = this.$container.siblings('#safe_load_more').val();
	        this.data._wp_http_referer = this.$container.siblings('input[name="_wp_http_referer"]').val();
	        this.data.paged = 1;
	        this.data.maxPages = this.$container.data('max-pages');
	        this.data.term = this.categories;
		},

		createInstance: function() {
			_instances[this.id] = this;
		},

		load: function load(unique) {
			var self = this;
			var seq = ++this.xhrCounter;
            this.isLoading = true;

			// If mk-ajax-loaded-posts span exists, get the post ids
			if ( this.$container.siblings('.mk-ajax-loaded-posts').length ) {
				var loaded_posts = this.$container.siblings('.mk-ajax-loaded-posts').attr('data-loop-loaded-posts');

				// Do not send looaded posts for Classic Pagination Navigation
				if ( this.$container.attr('data-pagination-style') != 1 ) {
					self.data.loaded_posts = loaded_posts.split(',');
				}
			}

            return $.when(
	            $.ajax({
	                url 	: _ajaxUrl,
	                type 	: "POST",
	                data 	: self.data
	            })
	        ).done(function(response) {
	        	self.onDone(response, unique, seq);
	        });
		},

		onDone: function(response, unique, seq) {
			if(seq === this.xhrCounter) {
				var self = this;

				response = $.parseJSON(response);
				response.unique = unique;
				response.id = this.id;

				// If mk-ajax-loaded-posts span exists, update current post ids
				// with new post ids from server's response
				if ( this.$container.siblings('.mk-ajax-loaded-posts').length ) {
					this.$container.siblings('.mk-ajax-loaded-posts').attr('data-loop-loaded-posts', response.loaded_posts);
				}

	            this.setData({
	                maxPages: response.maxPages,
	                found_posts: response.found_posts,
	                loop_iterator: response.i
	            });

				// Preload images first by creating object from returned content.
				// mk_imagesLoaded() method will create a promise that gets resolved when all images inside are loaded.
				// Our ajaxLoad is somehow more similar to window.onload event now.
				$(response.content).mk_imagesLoaded().then(function() {
					MK.utils.eventManager.publish('ajaxLoaded', response);
		        	self.isLoading = false;
		        	self.initNewComponents();
				});

	        } else console.log('XHR request nr '+ seq +' aborted');

        },

		setData: function setData(atts) {
			for(var att in atts) {
				if(att === 'term' && atts[att] === '*') this.data.term = '';
				else this.data[att] = atts[att];
			}
		},

		getData: function getData(att) {
			return this.data[att];
		},

		initNewComponents: function initNewComponents() {
            // Legacy scripts reinit
            window.ajaxInit();
            setTimeout(window.ajaxDelayedInit, 1000);
            // New way to init apended things
            MK.core.initAll(this.el);
        }
	};

}(jQuery));

/* Background Parallax Effects */
/* -------------------------------------------------------------------- */

function mk_backgrounds_parallax() {

  "use strict";

  $('.mk-parallax-enabled').each(function () {
    var $this = $( this );
    if (!MK.utils.isMobile()) {
      MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.parallax.js' ], function() {
        $this.parallax("49%", 0.3);
      });
    }
  });

  $('.mk-fullwidth-slideshow.parallax-slideshow').each(function () {
    var $this = $( this );
    if (!MK.utils.isMobile()) {
      MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.parallax.js' ], function() {
        var speed_factor = $this.attr('data-speedFactor');
        $this.parallax("49%", speed_factor);
      });
    }
  });

}


var MK = window.MK || {};
window.MK = MK;
MK.component = window.MK.component || {};

MK.component.BackgroundImageSetter = (function ($) {

	'use strict';

	var module = {};


	/*---------------------------------------------------------------------------------*/
	/* Private Variables
	/*---------------------------------------------------------------------------------*/

	/**
	 *	Take all elements with data-mk-img-set attribute and evaluate best image according to given device orientation and resolution,
	 *	sets style for backround-image on the same node element.	 *
	 */

	var $win = $(window),
		// $layers = $('[data-mk-img-set]'),
		screen = getScreenSize(),
		orientation = getOrientation(),
		device = getDevice(),
		lastOrientation = orientation,
		lastDevice = device;


	/*---------------------------------------------------------------------------------*/
	/* Private Methods
	/*---------------------------------------------------------------------------------*/

	function run($layers) {
		$layers.filter( function() {
			return !this.hasAttribute("mk-img-loaded");
		}).each(applyBg);
	}

	// Keep our main side effect out of calculations so they can be run once before loop of applying bg as a result
	function applyBg() {
		var $this = $(this),
			imgs = $this.data('mk-img-set');

		$this.css('background-image', 'url('+ module.getImage(imgs) +')');
		$this.find('.mk-adaptive-image').attr('src', module.getImage(imgs));
	}

	// Keep track of current screen size while resizing but update device reference
	// and reapply backgrounds only when we discover switch point
	function handleResize($layers) {
		updateScreenSize();
		if(hasSwitched()) {
			updateDevice();
			run($layers);
		}
	}

	function getScreenSize() {
		return {
			w: $win.width(),
			h: $win.height()
		};
	}

	// Name our device classes and add them id which simply means which one is wider
	function getDevice() {
		if     (screen.w > 1024) 	return {class: 'desktop', id: 2};
		else if(screen.w > 736) 	return {class: 'tablet',  id: 1};
		else 					 	return {class: 'mobile',  id: 0};
	}

	function getOrientation() {
		if(screen.w > screen.h) 	return 'landscape';
		else 						return 'portrait';
	}

	function updateScreenSize() {
		screen = getScreenSize();
	}

	function updateDevice() {
		if(lastOrientation !== orientation) orientation = lastOrientation;
		// Switch device only if going from smaller size to bigger.
		// Bigger to smaller is perfectly handled by browsers and doesn't require change and reupload
		if(lastDevice.id > device.id) device = lastDevice;
	}

	function hasSwitched() {
		lastOrientation = getOrientation();
		lastDevice = getDevice();

		if(lastOrientation !== orientation || lastDevice.class !== device.class) return true;
		else return false;
	}


	/*---------------------------------------------------------------------------------*/
	/* Public Methods
	/*---------------------------------------------------------------------------------*/

	// As desired image might be not available we have to evaluate the best match.
	module.getImage = function (imgs) {
		if (imgs['responsive'] === 'false') {
			return (imgs['landscape']['desktop']) ? imgs['landscape']['desktop'] : (imgs['landscape']['external'] ? imgs['landscape']['external'] : '');

		}
		var hasOrientation = !!imgs[orientation];
		// there are only two orientations now and we may get them by string name if both are there
		// or by index of 0 if only one is available. Note Objects has no lexical order so we need to grab key name by its index.
		// Also we may have external file for each orientation which we don't scale internaly so we grab it as it is. If nothing found return an empty string
		var imgOriented = imgs[ (hasOrientation ? orientation : Object.keys(imgs)[0]) ],
			imgExact    = (imgOriented[device.class]) ? imgOriented[device.class] : (imgOriented['external'] ? imgOriented['external'] : '');
		return imgExact;
	}

	module.init = function ($layers) {

		// Run and bind
		run($layers);
		$layers.attr('mk-img-loaded', '');
	};

	module.onResize = function ($layers) {
		$win.on('resize', MK.utils.throttle( 500, function() {
			handleResize($layers);
		}));
	};

	return module;

}(jQuery));


jQuery(function($) {

	var init = function init() {
		// Get All Layers, Excluding Edge Slider and Page Section
		var $allLayers = $('[data-mk-img-set]').filter(function(index) {
			return !$(this).hasClass('mk-section-image') && !$(this).hasClass('background-layer') && !$(this).hasClass('mk-video-section-touch');
		});;

		// Handle the resize
		MK.component.BackgroundImageSetter.onResize($allLayers);

		// Set all the BG Layers
		MK.component.BackgroundImageSetter.init($allLayers);
	}
	init();

	$(window).on('vc_reload', init);

});

/* Blog, Portfolio Audio */
/* -------------------------------------------------------------------- */

function loop_audio_init() {
  if ($.exists('.jp-jplayer')) {
    $('.jp-jplayer.mk-blog-audio').each(function () {
      var $this = $( this );
      MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.jplayer.js' ], function() {
        var css_selector_ancestor = "#" + $this.siblings('.jp-audio').attr('id');
        var ogg_file, mp3_file, mk_theme_js_path;
        ogg_file = $this.attr('data-ogg');
        mp3_file = $this.attr('data-mp3');
        $this.jPlayer({
          ready: function () {
            $this.jPlayer("setMedia", {
              mp3: mp3_file,
              ogg: ogg_file
            });
          },
          play: function () { // To avoid both jPlayers playing together.
            $this.jPlayer("pauseOthers");
          }, 
          swfPath: mk_theme_js_path,
          supplied: "mp3, ogg",
          cssSelectorAncestor: css_selector_ancestor,
          wmode: "window"
        });
      });
    });
  }
}


/* Blog Loop Carousel Shortcode */
/* -------------------------------------------------------------------- */


function mk_blog_carousel() {

  "use strict";

  if (!$.exists('.mk-blog-showcase')) {
    return;
  }
  $('.mk-blog-showcase ul li').each(function () {

    $(this).mouseenter( function () {

      $(this).siblings('li').removeClass('mk-blog-first-el').end().addClass('mk-blog-first-el');

    });

  });


}




/**
 * Contact Form
 *
 * Mostly implemented in Vanilla JS instead of jQuery.
 */
function mk_contact_form() {
    "use strict";
    var mkContactForms = document.getElementsByClassName('mk-contact-form');
    if (mkContactForms.length === 0) {
        return;
    }
    var captchaImageHolder = $('.captcha-image-holder');
    var activeClassName = 'is-active';
    var invalidClassName = 'mk-invalid';
    for (var i = 0; i < mkContactForms.length; i++) {
        initializeForm(mkContactForms[i], activeClassName, invalidClassName);
    }
    if (captchaImageHolder.length > 0) {
        $(window).on('load', initializeCaptchas);
    }
    /**
     * Initialize mk forms. e.g add activeClassName for inputs.
     *
     * @param form
     * @param activeClassName
     * @param invalidClassName
     */
    function initializeForm(form, activeClassName, invalidClassName) {
        var inputs = getFormInputs(form);
        for (var i = 0; i < inputs.length; i++) {
            markActiveClass(inputs[i]);
        }
        form.addEventListener('submit', function(e) {
            validateForm(e, invalidClassName);
        });
        /**
         * Set activeClassName for the parent node of the inout
         */
        function setActiveClass() {
            addClass(this.parentNode, activeClassName);
        }
        /**
         * Unset activeClassName from the parent node of the input.
         * We need to unset activeClassName only if the data was empty.
         * e.g. in the line style of the mk-contact-form, we set labels position based on activeClassName.
         */
        function unsetActiveClass() {
            if (this.value === '') {
                removeClass(this.parentNode, activeClassName);
            }
        }
        /**
         * Add event listeners (focus,blur) for input to set and unset activeClassName.
         *
         * @param input
         */
        function markActiveClass(input) {
            input.addEventListener('focus', setActiveClass);
            input.addEventListener('blur', unsetActiveClass);
        }
    }
    /**
     * Validate form when it's submitted. If everything was valid, we with post form in ajax request.
     *
     * @param e
     * @param invalidClassName
     */
    function validateForm(e, invalidClassName) {
        e.preventDefault();
        var form = e.target || e.srcElement;
        var inputs = getFormInputs(form);
        var isValidForm = true;
        var hasCaptchaField = false;
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            input.value = String(input.value).trim();
            switch (input.type) {
                case 'hidden':
                    break;
                case 'checkbox':
                    isValidForm = validateCheckBox(input, invalidClassName) && isValidForm;
                    break;
                case 'email':
                    isValidForm = validateEmail(input, invalidClassName) && isValidForm;
                    break;
                case 'textarea':
                    isValidForm = validateText(input, invalidClassName) && isValidForm;
                    break;
                case 'text':
                    /**
                     * Some old browsers such as IE 8 and 9 detect email type as text.
                     * So, we need to extra check for data-type attribute
                     */
                    if (input.dataset.type === 'captcha') {
                        isValidForm = validateText(input, invalidClassName) && isValidForm;
                        hasCaptchaField = true;
                    } else if (input.dataset.type === 'email') {
                        isValidForm = validateEmail(input, invalidClassName) && isValidForm;
                    } else {
                        isValidForm = validateText(input, invalidClassName) && isValidForm;
                    }
                    break;
                default:
                    /**
                     * e.g. validating for radiobox, selectbox and etc.
                     */
                    console.warn('Implement validation for ' + input.name + ':' + input.type);
                    break;
            }
        }
        if (isValidForm) {
            if (hasCaptchaField) {
                validateCaptcha(form, invalidClassName, sendForm);
            } else {
                sendForm(form);
            }
        }
    }
    /**
     * Validate captcha of form. If everything was, we will execute captchaIsValidCallback which as sendForm().
     *
     * @param form
     * @param invalidClassName
     * @param captchaIsValidCallback
     * @returns boolean
     */
    function validateCaptcha(form, invalidClassName, captchaIsValidCallback) {
        var input = form.querySelectorAll('[data-type="captcha"]')[0];
        if (input.value.length === 0) {
            addClass(input, invalidClassName);
            return false;
        } else {
          jQuery.get( jupiterDonutVars.ajaxUrl, {
            action: 'jupiter_donut_validate_captcha',
            nonce: jupiterDonutVars.nonce,
            captcha: input.value,
          })
          .done(function(data) {
            loadCaptcha();
            input.value = '';

            if ( data.success !== true ) {
                addClass(input, invalidClassName);
                addClass(input, 'contact-captcha-invalid');
                removeClass(input, 'contact-captcha-valid');
                input.placeholder = data.data;
            } else {
                removeClass(input, invalidClassName);
                removeClass(input, 'contact-captcha-invalid');
                addClass(input, 'contact-captcha-valid');
                input.placeholder = data.data;
                captchaIsValidCallback(form);
            }
          })
        }
    }
    /**
     * Send submitted form.
     *
     * @param form
     */
    function sendForm(form) {
        var $form = $(form);
        var data = getFormData(form);
        progressButton.loader($form);
        $.post(jupiterDonutVars.ajaxUrl, data, function(response) {
            var res = JSON.parse(response);
            if (res.action_Status) {
                progressButton.success($form);
                $form.find('.text-input').val('');
                $form.find('textarea').val('');
                $form.find('input[type=checkbox]').attr("checked", false);
                $form.find('.contact-form-message').slideDown().addClass('state-success').html(res.message);
                setTimeout(function() {
                   $form.find('.contact-form-message').slideUp();
                }, 5000);
            } else {
                progressButton.error($form);
                $form.find('.contact-form-message').removeClass('state-success').html(res.message);
            }
        });
    }
    /**
     * Initialize all captcha images for first time. All captcha images is always same. e.g. if we have multiple form,
     * all of them will have the same image.
     * It will also add event listener for '.captcha-change-image' objects to reload the captcha.
     */
    function initializeCaptchas() {
        var captchaChangeImageButtons = document.getElementsByClassName('captcha-change-image');
        for (var i = 0; i < captchaChangeImageButtons.length; i++) {
            captchaChangeImageButtons[i].addEventListener('click', loadCaptcha);
        }
    }
    /**
     * Load captcha text and append the image to captcha container.
     * If it used as a callback, it will prevent default behave of the event.
     * e.g. loading new captcha by click on <a> without changing url.
     */
    function loadCaptcha(e) {
        if (e) {
            e.preventDefault();
        }
        $.post(jupiterDonutVars.ajaxUrl, {
            action: 'mk_create_captcha_image'
        }, appendImage);
        /**
         * The callback function for append or change old image src based on response. T
         * The captchaImageURL is the url of the captcha which is provided in ajax response of mk_create_captcha_image.
         * @param captchaImageURL
         */
        function appendImage(captchaImageURL) {
            if (captchaImageHolder.find('.captcha-image').length === 0) {
                captchaImageHolder.html('<img src="' + captchaImageURL + '" class="captcha-image" alt="captcha txt">');
            } else {
                captchaImageHolder.find('.captcha-image').attr("src", captchaImageURL + '?' + new Date().getTime());
            }
        }
    }
    /**
     * Get form inputs using querySelectorAll().
     * It returns <input> and <textarea> tags. If you need any other tags such as <select>, please update this function.
     *
     * @param form
     * @returns {NodeList}
     */
    function getFormInputs(form) {
        return form.querySelectorAll('input,textarea');
    }
    /**
     * Get data of the form inputs and textareas as a object.
     *
     * @param form
     * @returns {{action: string}}
     */
    function getFormData(form) {
        var data = {
            action: 'mk_contact_form'
        };
        var inputs = getFormInputs(form);
        for (var i = 0; i < inputs.length; i++) {
            data[inputs[i].name] = inputs[i].value;
        }
        return data;
    }
}
/* Ajax Login Form */
/* -------------------------------------------------------------------- */
function mk_login_form() {
    $('form.mk-login-form').each(function() {
        var $this = $(this);
        $this.on('submit', function(e) {
            $('p.mk-login-status', $this).show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxlogin',
                    'username': $('#username', $this).val(),
                    'password': $('#password', $this).val(),
                    'security': $('#security', $this).val()
                },
                success: function(data) {
                    $('p.mk-login-status', $this).text(data.message);
                    if (data.loggedin === true) {
                        document.location.href = ajax_login_object.redirecturl;
                    }
                }
            });
            e.preventDefault();
        });
    });
}
/* Progress Button */
/* -------------------------------------------------------------------- */
var progressButton = {
    loader: function(form) {
        MK.core.loadDependencies([MK.core.path.plugins + 'tweenmax.js'], function() {
            var $form = form,
                progressBar = $form.find(".mk-progress-button .mk-progress-inner"),
                buttonText = $form.find(".mk-progress-button .mk-progress-button-content"),
                progressButton = new TimelineLite();
            progressButton.to(progressBar, 0, {
                width: "100%",
                scaleX: 0,
                scaleY: 1
            }).to(buttonText, .3, {
                y: -5
            }).to(progressBar, 1.5, {
                scaleX: 1,
                ease: Power2.easeInOut
            }, "-=.1").to(buttonText, .3, {
                y: 0
            }).to(progressBar, .3, {
                scaleY: 0
            });
        });
    },
    success: function(form) {
        MK.core.loadDependencies([MK.core.path.plugins + 'tweenmax.js'], function() {
            var $form = form,
                buttonText = $form.find(".mk-button .mk-progress-button-content, .mk-contact-button .mk-progress-button-content"),
                successIcon = $form.find(".mk-progress-button .state-success"),
                progressButtonSuccess = new TimelineLite({
                    onComplete: hideSuccessMessage
                });
            progressButtonSuccess.to(buttonText, .3, {
                paddingRight: 20,
                ease: Power2.easeInOut
            }, "+=1").to(successIcon, .3, {
                opacity: 1
            }).to(successIcon, 2, {
                opacity: 1
            });

            function hideSuccessMessage() {
                progressButtonSuccess.reverse()
            }
        });
    },
    error: function(form) {
        MK.core.loadDependencies([MK.core.path.plugins + 'tweenmax.js'], function() {
            var $form = form,
                buttonText = $form.find(".mk-button .mk-progress-button-content, .mk-contact-button .mk-progress-button-content"),
                errorIcon = $form.find(".mk-progress-button .state-error"),
                progressButtonError = new TimelineLite({
                    onComplete: hideErrorMessage
                });
            progressButtonError.to(buttonText, .3, {
                paddingRight: 20
            }, "+=1").to(errorIcon, .3, {
                opacity: 1
            }).to(errorIcon, 2, {
                opacity: 1
            });

            function hideErrorMessage() {
                progressButtonError.reverse()
            }
        });
    }
};

/* Event Count Down */
/* -------------------------------------------------------------------- */

function mk_event_countdown() {
  if ($.exists('.mk-event-countdown')) {

    MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.countdown.js' ], function() {

      $('.mk-event-countdown').each(function () {
        var $this = $(this),
          $date = $this.attr('data-date'),
          $offset = $this.attr('data-offset');

        $this.downCount({
          date: $date,
          offset: $offset
        });
      });

    });
  }
}

/* Flexslider init */
/* -------------------------------------------------------------------- */

function mk_flexslider_init() {

  var $lcd = $('.mk-lcd-slideshow'),
      $laptop = $('.mk-laptop-slideshow-shortcode');

  if($lcd.length) $lcd.find('.mk-lcd-image').fadeIn();
  if($laptop.length) $laptop.find(".mk-laptop-image").fadeIn();

  $('.js-flexslider').each(function () {

    if ($(this).parents('.mk-tabs').length || $(this).parents('.mk-accordion').length) {
      $(this).removeData("flexslider");
    }

    var $this = $(this),
      $selector = $this.attr('data-selector'),
      $animation = $this.attr('data-animation'),
      $easing = $this.attr('data-easing'),
      $direction = $this.attr('data-direction'),
      $smoothHeight = $this.attr('data-smoothHeight') == "true" ? true : false,
      $slideshowSpeed = $this.attr('data-slideshowSpeed'),
      $animationSpeed = $this.attr('data-animationSpeed'),
      $controlNav = $this.attr('data-controlNav') == "true" ? true : false,
      $directionNav = $this.attr('data-directionNav') == "true" ? true : false,
      $pauseOnHover = $this.attr('data-pauseOnHover') == "true" ? true : false,
      $isCarousel = $this.attr('data-isCarousel') == "true" ? true : false;


    if ($selector !== undefined) {
      var $selector_class = $selector;
    } else {
      var $selector_class = ".mk-flex-slides > li";
    }

    if ($isCarousel === true) {
      var $itemWidth = parseInt($this.attr('data-itemWidth')),
        $itemMargin = parseInt($this.attr('data-itemMargin')),
        $minItems = parseInt($this.attr('data-minItems')),
        $maxItems = parseInt($this.attr('data-maxItems')),
        $move = parseInt($this.attr('data-move'));
    } else {
      var $itemWidth = $itemMargin = $minItems = $maxItems = $move = 0;
    }

    MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.flexslider.js' ], function() {
      $this.flexslider({
        selector: $selector_class,
        animation: $animation,
        easing: $easing,
        direction: $direction,
        smoothHeight: $smoothHeight,
        slideshow: true, // autoplay
        slideshowSpeed: $slideshowSpeed,
        animationSpeed: $animationSpeed,
        controlNav: $controlNav,
        directionNav: $directionNav,
        pauseOnHover: $pauseOnHover,
        prevText: "",
        nextText: "",
        itemWidth: $itemWidth,
        itemMargin: $itemMargin,
        minItems: $minItems,
        maxItems: $maxItems,
        move: $move
      });
    });

  });

}

(function( $ ) {
	'use strict';

	var val = MK.val;

	MK.component.FullHeight = function( el ) {
		var $window = $( window ),
			$this = $( el ),
			config = $this.data( 'fullheight-config' ),
			container = document.getElementById( 'mk-theme-container' ),
			minH = (config && config.min) ? config.min : 0,
			winH = null,
			height = null,
			update_count = 0,
			testing = MK.utils.getUrlParameter('testing'),
			offset = null;

		// We need to provide height on the same specificity level for workaround to IE bug
		// connect.microsoft.com/IE/feedback/details/802625/min-height-and-flexbox-flex-direction-column-dont-work-together-in-ie-10-11-preview
		// stackoverflow.com/questions/19371626/flexbox-not-centering-vertically-in-ie
		if(MK.utils.browser.name === ('IE' || 'Edge')) $this.css( 'height', '1px' );

		var update = function() {

			if(update_count === 0) {
				winH = $window.height();
				// for correct calculate
				offset = $this.offset().top - 1;
				height = Math.max(minH, winH - val.offsetHeaderHeight( offset ));
				$this.css( 'min-height', height );
				if(testing !== undefined )
				update_count++;
			}

		};

		// TODO remove scroll listener by dynamic offset reader
		var init = function() {
			update();
			$window.on( 'resize', update );
			$window.on( 'scroll', update );
			window.addResizeListener( container, update );
		};

		return {
			init : init
		};
	};

})( jQuery );


(function( $ ) {
	'use strict';

	var core  = MK.core,
		utils = MK.utils,
		path  = MK.core.path;


	MK.ui.FullScreenGallery = function( element, settings ) {
		this.element = element;
		this.config = settings;

		this.isFullScreen = false;
	};


	// preload slick PLUGIN TO USE THIS
	MK.ui.FullScreenGallery.prototype = {
		dom : {
			fullScrBtn 		: '.slick-full-screen',
			exitFullScrBtn 	: '.slick-minimize',
			playBtn 		: '.slick-play',
			pauseBtn 		: '.slick-pause',
			shareBtn 		: '.slick-share',
			socialShare 	: '.slick-social-share',
		    wrapper 		: '.slick-slider-wrapper',
			slider 			: '.slick-slides',
			slides 			: '.slick-slide',
			dots 			: '.slick-dot',
			active 			: '.slick-active',
			hiddenClass 	: 'jupiter-donut-is-hidden',
			dataId 			: 'slick-index'
		},

		tpl: {
			dot  : '<div class="slick-dot"></div>',
			next : '<a href="javascript:;" class="slick-next"> <svg width="33px" height="65px"> <polyline fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points=" 0.5,0.5 32.5,32.5 0.5,64.5"/> </svg> </a>',
			prev : '<a href="javascript:;" class="slick-prev"> <svg  width="33px" height="65px"> <polyline fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points=" 32.5,64.5 0.5,32.5 32.5,0.5"/> </svg> </a>'
		},

		init : function() {
			var self = this;

			// core.loadDependencies([ path.plugins + 'slick.js' ], function() {
				self.cacheElements();
				self.getViewportSizes();
				self.updateSizes( 'window' );
				self.create();
				// update cache with elements propagated by plugin
				self.updateCacheElements();
				self.createPagination();
				self.bindEvents();
			// });
		},

		create : function() {
			var self = this;

			this.slick = this.$gallery.slick({
		        dots: true,
		        arrows: true,
				infinite: true,
				speed: 300,
				slidesToShow: 1,
				centerMode: true,
				centerPadding: '0px',
				variableWidth: true,
				autoplay: false,
				autoplaySpeed: 3000,
        		useTransform: true,
                prevArrow: self.tpl.prev,
                nextArrow: self.tpl.next,
                customPaging: function(slider, i) {
                    return self.tpl.dot;
                },
			});
		},

		cacheElements : function() {
			this.$window = $( window );
			this.$gallery = $( this.element );

			this.$fullScrBtn = $( this.dom.fullScrBtn );
			this.$exitFullScrBtn = $( this.dom.exitFullScrBtn );
			this.$playBtn = $( this.dom.playBtn );
			this.$pauseBtn = $( this.dom.pauseBtn );
			this.$shareBtn = $( this.dom.shareBtn );
			this.$socialShare = $( this.dom.socialShare );

		    this.$wrapper = $( this.dom.wrapper );
			this.$slider = $( this.dom.slider );
			this.$slides = $( this.dom.slides );
			this.$imgs = this.$slides.find( 'img' );
			// store reference to initial images without slides appended by pugin
			// - needed for creating of pagination
			this.$originalImgs = this.$imgs;
		},

		updateCacheElements : function() {
			this.$slides = $( this.dom.slides );
			this.$imgs = this.$slides.find( 'img' );
			this.$dots = $( this.dom.dots );
		},

		bindEvents : function() {
			var self = this;
			this.$fullScrBtn.on( 'click', this.toFullScreen.bind( this ) );
			this.$exitFullScrBtn.on( 'click', this.exitFullScreen.bind( this ) );
			this.$playBtn.on( 'click', this.play.bind( this ) );
			this.$pauseBtn.on( 'click', this.pause.bind( this ) );
			this.$shareBtn.on( 'click', this.toggleShare.bind( this ) );
			this.$socialShare.on( 'click', 'a', this.socialShare.bind( this ) );
			this.$window.on( 'resize', this.onResize.bind( this ) );
			this.$window.on( 'keydown', function(e) {
				if(e.keyCode === 39) self.$gallery.slick('slickNext');
				if(e.keyCode === 37) self.$gallery.slick('slickPrev');
			});
			$( document ).on( 'fullscreenchange mozfullscreenchange webkitfullscreenchange msfullcreenchange', this.exitFullScreen.bind( this ) );
		},

		getViewportSizes : function() {
			this.screen = {
				w: screen.width,
				h: screen.height
			};
			this.window = {
				w: this.$window.width(),
				h: this.$window.height()
			};
		},

		updateSizes : function( viewport ) {
			this.$wrapper.width( this[ viewport ].w );
			this.$wrapper.height( '100%' );
			this.$imgs.height( '100%');
		},

		createPagination : function() {
			var self = this;
			this.$dots.each( function( i ) {
				var img = self.$originalImgs.eq( i ).attr( 'src' );

				$( this ).css({
					'background-image': 'url('+ img +')'
				});
			});
		},

		play : function(e) {
			e.preventDefault();
			this.$playBtn.addClass( this.dom.hiddenClass );
			this.$pauseBtn.removeClass( this.dom.hiddenClass );
			$( this.element ).slick( 'slickPlay' );
		},

		pause : function(e) {
			e.preventDefault();
			this.$pauseBtn.addClass( this.dom.hiddenClass );
			this.$playBtn.removeClass( this.dom.hiddenClass );
			$( this.element ).slick( 'slickPause' );
		},

		toggleShare : function(e) {
			e.preventDefault();
			this.$socialShare.toggleClass( this.dom.hiddenClass );
		},

		getCurentId : function() {
			return this.$slides.filter( this.dom.active ).data( this.dom.dataId );
		},

		toFullScreen : function() {
			var self = this;

			this.$fullScrBtn.addClass( this.dom.hiddenClass );
			this.$exitFullScrBtn.removeClass( this.dom.hiddenClass );

			this.$slider.hide().fadeIn( 500 );
			utils.launchIntoFullscreen( document.documentElement );
			this.updateSizes( 'screen' );
			$( this.element ).slick( 'slickGoTo', this.getCurentId(), true );

			// Update state with delay so we avoid triggering exitFullScreen fn from
			// fullscreenchange event
			setTimeout( function() {
				self.isFullScreen = true;
			}, 1000);
		},

		exitFullScreen : function() {
			if( this.isFullScreen ) {
				this.$exitFullScrBtn.addClass( this.dom.hiddenClass );
				this.$fullScrBtn.removeClass( this.dom.hiddenClass );

				utils.exitFullscreen();
				this.updateSizes( 'window' );
				$( this.element ).slick( 'slickGoTo', this.getCurentId(), true );

				this.isFullScreen = false;
			}

		},

		onResize : function() {
			this.getViewportSizes();
			this.updateSizes( this.isFullScreen ? 'screen' : 'window' );
			$( this.element ).slick( 'refresh' );
			$( this.element ).slick( 'slickGoTo', this.getCurentId(), true );
			this.updateCacheElements();
			this.createPagination();
		},

		socialShare : function( e ) {
			e.preventDefault();
			var $this = $( e.currentTarget ),
				network = $this.data( 'network' ),
				id = this.config.id,
				url = this.config.url,
				title = this.$wrapper.find( '.slick-title' ).text(),
				name;
				var picture = this.$slides.filter( this.dom.active ).children().first().attr( 'src' );
			switch( network ) {
				case 'facebook':
					url = 'https://www.facebook.com/sharer/sharer.php?picture=' + picture+'&u=' + url + '#id=' + id;
					name = 'Facebook Share';
					break;
				case 'twitter':
					url = 'http://twitter.com/intent/tweet?text=' + url + '#id=' + id;
					name = 'Twitter Share';
					break;
				case 'pinterest':
					url = 'http://pinterest.com/pin/create/bookmarklet/?media=' + picture + '&url=' + url + '&is_video=false&description=' + title;
					// other available link paranmeters: media, description
					name = 'Pinterest Share';
					break;

			}

       		window.open( url, name, "height=380 ,width=660, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0" );
		}
	};

})( jQuery );

(function($) {
    'use strict';

    MK.component.Grid = function( el ) {
    	var $container = $(el);
    	var config = $container.data( 'grid-config' );
        var isSlideshow = $container.closest('[data-mk-component="SwipeSlideshow"]').length;
        var miniGridConfig = {
            container: el,
            item: config.item + ':not(.is-hidden)',
            gutter: 0
        };

        var init = function init(){
            // Flags for cancelling usage goes first :
            // Quit early if we discover that Grid is used inside SwipeSlideshow as it brings bug with crossoverriding positioning
            // + grid is not really needed as we have single row all handled by slider.
            // It happens only in woocommerce carousel as of hardcoded Grid in loop-start.php
            if(isSlideshow) return;
	        MK.core.loadDependencies([ MK.core.path.plugins + 'minigrid.js' ], create);
        };

        // Remove el hidden without adding proper class
        var prepareForGrid = function prepareForGrid() {
            var $item = $(this);
            var isHidden = ($item.css('display') === 'none');
            if(isHidden) $item.addClass('is-hidden');
            else $item.removeClass('is-hidden');
        };

        var create = function create() {
            var timer = null;

	        function draw() {
                // Prevent plugin breaking when feeding it with hidden elements
                $container.find(config.item).each( prepareForGrid );
	            minigrid(miniGridConfig);
	        }

            function redraw() {
                if (timer) clearTimeout(timer);
                timer = setTimeout(draw, 100);
            }

            // init
	        draw();
            // If reinitializing drop existing event handler
            $(window).off('resize', redraw);
            $(window).on('resize mk-image-loaded', redraw);
            MK.utils.eventManager.subscribe('item-expanded', redraw);
            MK.utils.eventManager.subscribe('ajaxLoaded', redraw);
            MK.utils.eventManager.subscribe('staticFilter', redraw);
        };

        return {
         	init : init
        };
    };

})(jQuery);








/*!
 * imagesLoaded PACKAGED v4.1.1
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}("undefined"!=typeof window?window:this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},n=i[t]=i[t]||[];return-1==n.indexOf(e)&&n.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{},n=i[t]=i[t]||{};return n[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=i.indexOf(e);return-1!=n&&i.splice(n,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=0,o=i[n];e=e||[];for(var r=this._onceEvents&&this._onceEvents[t];o;){var s=r&&r[o];s&&(this.off(t,o),delete r[o]),o.apply(this,e),n+=s?0:1,o=i[n]}return this}},t}),function(t,e){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter")):t.imagesLoaded=e(t,t.EvEmitter)}(window,function(t,e){function i(t,e){for(var i in e)t[i]=e[i];return t}function n(t){var e=[];if(Array.isArray(t))e=t;else if("number"==typeof t.length)for(var i=0;i<t.length;i++)e.push(t[i]);else e.push(t);return e}function o(t,e,r){return this instanceof o?("string"==typeof t&&(t=document.querySelectorAll(t)),this.elements=n(t),this.options=i({},this.options),"function"==typeof e?r=e:i(this.options,e),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(function(){this.check()}.bind(this))):new o(t,e,r)}function r(t){this.img=t}function s(t,e){this.url=t,this.element=e,this.img=new Image}var h=t.jQuery,a=t.console;o.prototype=Object.create(e.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(t){"IMG"==t.nodeName&&this.addImage(t),this.options.background===!0&&this.addElementBackgroundImages(t);var e=t.nodeType;if(e&&d[e]){for(var i=t.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=t.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var d={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(t){var e=getComputedStyle(t);if(e)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(e.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,t),n=i.exec(e.backgroundImage)}},o.prototype.addImage=function(t){var e=new r(t);this.images.push(e)},o.prototype.addBackground=function(t,e){var i=new s(t,e);this.images.push(i)},o.prototype.check=function(){function t(t,i,n){setTimeout(function(){e.progress(t,i,n)})}var e=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(e){e.once("progress",t),e.check()}):void this.complete()},o.prototype.progress=function(t,e,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!t.isLoaded,this.emitEvent("progress",[this,t,e]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,t),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,t,e)},o.prototype.complete=function(){var t=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(t,[this]),this.emitEvent("always",[this]),this.jqDeferred){var e=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[e](this)}},r.prototype=Object.create(e.prototype),r.prototype.check=function(){var t=this.getIsImageComplete();return t?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&void 0!==this.img.naturalWidth},r.prototype.confirm=function(t,e){this.isLoaded=t,this.emitEvent("progress",[this,this.img,e])},r.prototype.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var t=this.getIsImageComplete();t&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(t,e){this.isLoaded=t,this.emitEvent("progress",[this,this.element,e])},o.makeJQueryPlugin=function(e){e=e||t.jQuery,e&&(h=e,h.fn.imagesLoaded=function(t,e){var i=new o(this,t,e);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});

// ==================================================
// fancyBox v3.5.7
//
// Licensed GPLv3 for open source use
// or fancyBox Commercial License for commercial use
//
// http://fancyapps.com/fancybox/
// Copyright 2019 fancyApps
//
// ==================================================
!function(t,e,n,o){"use strict";function i(t,e){var o,i,a,s=[],r=0;t&&t.isDefaultPrevented()||(t.preventDefault(),e=e||{},t&&t.data&&(e=h(t.data.options,e)),o=e.$target||n(t.currentTarget).trigger("blur"),(a=n.fancybox.getInstance())&&a.$trigger&&a.$trigger.is(o)||(e.selector?s=n(e.selector):(i=o.attr("data-fancybox")||"",i?(s=t.data?t.data.items:[],s=s.length?s.filter('[data-fancybox="'+i+'"]'):n('[data-fancybox="'+i+'"]')):s=[o]),r=n(s).index(o),r<0&&(r=0),a=n.fancybox.open(s,e,r),a.$trigger=o))}if(t.console=t.console||{info:function(t){}},n){if(n.fn.fancybox)return void console.info("fancyBox already initialized");var a={closeExisting:!1,loop:!1,gutter:50,keyboard:!0,preventCaptionOverlap:!0,arrows:!0,infobar:!0,smallBtn:"auto",toolbar:"auto",buttons:["zoom","slideShow","thumbs","close"],idleTime:3,protect:!1,modal:!1,image:{preload:!1},ajax:{settings:{data:{fancybox:!0}}},iframe:{tpl:'<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" allowfullscreen="allowfullscreen" allow="autoplay; fullscreen" src=""></iframe>',preload:!0,css:{},attr:{scrolling:"auto"}},video:{tpl:'<video class="fancybox-video" controls controlsList="nodownload" poster="{{poster}}"><source src="{{src}}" type="{{format}}" />Sorry, your browser doesn\'t support embedded videos, <a href="{{src}}">download</a> and watch with your favorite video player!</video>',format:"",autoStart:!0},defaultType:"image",animationEffect:"zoom",animationDuration:366,zoomOpacity:"auto",transitionEffect:"fade",transitionDuration:366,slideClass:"",baseClass:"",baseTpl:'<div class="fancybox-container" role="dialog" tabindex="-1"><div class="fancybox-bg"></div><div class="fancybox-inner"><div class="fancybox-infobar"><span data-fancybox-index></span>&nbsp;/&nbsp;<span data-fancybox-count></span></div><div class="fancybox-toolbar">{{buttons}}</div><div class="fancybox-navigation">{{arrows}}</div><div class="fancybox-stage"></div><div class="fancybox-caption"><div class="fancybox-caption__body"></div></div></div></div>',spinnerTpl:'<div class="fancybox-loading"></div>',errorTpl:'<div class="fancybox-error"><p>{{ERROR}}</p></div>',btnTpl:{download:'<a download data-fancybox-download class="fancybox-button fancybox-button--download" title="{{DOWNLOAD}}" href="javascript:;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.62 17.09V19H5.38v-1.91zm-2.97-6.96L17 11.45l-5 4.87-5-4.87 1.36-1.32 2.68 2.64V5h1.92v7.77z"/></svg></a>',zoom:'<button data-fancybox-zoom class="fancybox-button fancybox-button--zoom" title="{{ZOOM}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.7 17.3l-3-3a5.9 5.9 0 0 0-.6-7.6 5.9 5.9 0 0 0-8.4 0 5.9 5.9 0 0 0 0 8.4 5.9 5.9 0 0 0 7.7.7l3 3a1 1 0 0 0 1.3 0c.4-.5.4-1 0-1.5zM8.1 13.8a4 4 0 0 1 0-5.7 4 4 0 0 1 5.7 0 4 4 0 0 1 0 5.7 4 4 0 0 1-5.7 0z"/></svg></button>',close:'<button data-fancybox-close class="fancybox-button fancybox-button--close" title="{{CLOSE}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 10.6L6.6 5.2 5.2 6.6l5.4 5.4-5.4 5.4 1.4 1.4 5.4-5.4 5.4 5.4 1.4-1.4-5.4-5.4 5.4-5.4-1.4-1.4-5.4 5.4z"/></svg></button>',arrowLeft:'<button data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" title="{{PREV}}"><div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.28 15.7l-1.34 1.37L5 12l4.94-5.07 1.34 1.38-2.68 2.72H19v1.94H8.6z"/></svg></div></button>',arrowRight:'<button data-fancybox-next class="fancybox-button fancybox-button--arrow_right" title="{{NEXT}}"><div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.4 12.97l-2.68 2.72 1.34 1.38L19 12l-4.94-5.07-1.34 1.38 2.68 2.72H5v1.94z"/></svg></div></button>',smallBtn:'<button type="button" data-fancybox-close class="fancybox-button fancybox-close-small" title="{{CLOSE}}"><svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"/></svg></button>'},parentEl:"body",hideScrollbar:!0,autoFocus:!0,backFocus:!0,trapFocus:!0,fullScreen:{autoStart:!1},touch:{vertical:!0,momentum:!0},hash:null,media:{},slideShow:{autoStart:!1,speed:3e3},thumbs:{autoStart:!1,hideOnClose:!0,parentEl:".fancybox-container",axis:"y"},wheel:"auto",onInit:n.noop,beforeLoad:n.noop,afterLoad:n.noop,beforeShow:n.noop,afterShow:n.noop,beforeClose:n.noop,afterClose:n.noop,onActivate:n.noop,onDeactivate:n.noop,clickContent:function(t,e){return"image"===t.type&&"zoom"},clickSlide:"close",clickOutside:"close",dblclickContent:!1,dblclickSlide:!1,dblclickOutside:!1,mobile:{preventCaptionOverlap:!1,idleTime:!1,clickContent:function(t,e){return"image"===t.type&&"toggleControls"},clickSlide:function(t,e){return"image"===t.type?"toggleControls":"close"},dblclickContent:function(t,e){return"image"===t.type&&"zoom"},dblclickSlide:function(t,e){return"image"===t.type&&"zoom"}},lang:"en",i18n:{en:{CLOSE:"Close",NEXT:"Next",PREV:"Previous",ERROR:"The requested content cannot be loaded. <br/> Please try again later.",PLAY_START:"Start slideshow",PLAY_STOP:"Pause slideshow",FULL_SCREEN:"Full screen",THUMBS:"Thumbnails",DOWNLOAD:"Download",SHARE:"Share",ZOOM:"Zoom"},de:{CLOSE:"Schlie&szlig;en",NEXT:"Weiter",PREV:"Zur&uuml;ck",ERROR:"Die angeforderten Daten konnten nicht geladen werden. <br/> Bitte versuchen Sie es sp&auml;ter nochmal.",PLAY_START:"Diaschau starten",PLAY_STOP:"Diaschau beenden",FULL_SCREEN:"Vollbild",THUMBS:"Vorschaubilder",DOWNLOAD:"Herunterladen",SHARE:"Teilen",ZOOM:"Vergr&ouml;&szlig;ern"}}},s=n(t),r=n(e),c=0,l=function(t){return t&&t.hasOwnProperty&&t instanceof n},d=function(){return t.requestAnimationFrame||t.webkitRequestAnimationFrame||t.mozRequestAnimationFrame||t.oRequestAnimationFrame||function(e){return t.setTimeout(e,1e3/60)}}(),u=function(){return t.cancelAnimationFrame||t.webkitCancelAnimationFrame||t.mozCancelAnimationFrame||t.oCancelAnimationFrame||function(e){t.clearTimeout(e)}}(),f=function(){var t,n=e.createElement("fakeelement"),o={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(t in o)if(void 0!==n.style[t])return o[t];return"transitionend"}(),p=function(t){return t&&t.length&&t[0].offsetHeight},h=function(t,e){var o=n.extend(!0,{},t,e);return n.each(e,function(t,e){n.isArray(e)&&(o[t]=e)}),o},g=function(t){var o,i;return!(!t||t.ownerDocument!==e)&&(n(".fancybox-container").css("pointer-events","none"),o={x:t.getBoundingClientRect().left+t.offsetWidth/2,y:t.getBoundingClientRect().top+t.offsetHeight/2},i=e.elementFromPoint(o.x,o.y)===t,n(".fancybox-container").css("pointer-events",""),i)},b=function(t,e,o){var i=this;i.opts=h({index:o},n.fancybox.defaults),n.isPlainObject(e)&&(i.opts=h(i.opts,e)),n.fancybox.isMobile&&(i.opts=h(i.opts,i.opts.mobile)),i.id=i.opts.id||++c,i.currIndex=parseInt(i.opts.index,10)||0,i.prevIndex=null,i.prevPos=null,i.currPos=0,i.firstRun=!0,i.group=[],i.slides={},i.addContent(t),i.group.length&&i.init()};n.extend(b.prototype,{init:function(){var o,i,a=this,s=a.group[a.currIndex],r=s.opts;r.closeExisting&&n.fancybox.close(!0),n("body").addClass("fancybox-active"),!n.fancybox.getInstance()&&!1!==r.hideScrollbar&&!n.fancybox.isMobile&&e.body.scrollHeight>t.innerHeight&&(n("head").append('<style id="fancybox-style-noscroll" type="text/css">.compensate-for-scrollbar{margin-right:'+(t.innerWidth-e.documentElement.clientWidth)+"px;}</style>"),n("body").addClass("compensate-for-scrollbar")),i="",n.each(r.buttons,function(t,e){i+=r.btnTpl[e]||""}),o=n(a.translate(a,r.baseTpl.replace("{{buttons}}",i).replace("{{arrows}}",r.btnTpl.arrowLeft+r.btnTpl.arrowRight))).attr("id","fancybox-container-"+a.id).addClass(r.baseClass).data("FancyBox",a).appendTo(r.parentEl),a.$refs={container:o},["bg","inner","infobar","toolbar","stage","caption","navigation"].forEach(function(t){a.$refs[t]=o.find(".fancybox-"+t)}),a.trigger("onInit"),a.activate(),a.jumpTo(a.currIndex)},translate:function(t,e){var n=t.opts.i18n[t.opts.lang]||t.opts.i18n.en;return e.replace(/\{\{(\w+)\}\}/g,function(t,e){return void 0===n[e]?t:n[e]})},addContent:function(t){var e,o=this,i=n.makeArray(t);n.each(i,function(t,e){var i,a,s,r,c,l={},d={};n.isPlainObject(e)?(l=e,d=e.opts||e):"object"===n.type(e)&&n(e).length?(i=n(e),d=i.data()||{},d=n.extend(!0,{},d,d.options),d.$orig=i,l.src=o.opts.src||d.src||i.attr("href"),l.type||l.src||(l.type="inline",l.src=e)):l={type:"html",src:e+""},l.opts=n.extend(!0,{},o.opts,d),n.isArray(d.buttons)&&(l.opts.buttons=d.buttons),n.fancybox.isMobile&&l.opts.mobile&&(l.opts=h(l.opts,l.opts.mobile)),a=l.type||l.opts.type,r=l.src||"",!a&&r&&((s=r.match(/\.(mp4|mov|ogv|webm)((\?|#).*)?$/i))?(a="video",l.opts.video.format||(l.opts.video.format="video/"+("ogv"===s[1]?"ogg":s[1]))):r.match(/(^data:image\/[a-z0-9+\/=]*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg|ico)((\?|#).*)?$)/i)?a="image":r.match(/\.(pdf)((\?|#).*)?$/i)?(a="iframe",l=n.extend(!0,l,{contentType:"pdf",opts:{iframe:{preload:!1}}})):"#"===r.charAt(0)&&(a="inline")),a?l.type=a:o.trigger("objectNeedsType",l),l.contentType||(l.contentType=n.inArray(l.type,["html","inline","ajax"])>-1?"html":l.type),l.index=o.group.length,"auto"==l.opts.smallBtn&&(l.opts.smallBtn=n.inArray(l.type,["html","inline","ajax"])>-1),"auto"===l.opts.toolbar&&(l.opts.toolbar=!l.opts.smallBtn),l.$thumb=l.opts.$thumb||null,l.opts.$trigger&&l.index===o.opts.index&&(l.$thumb=l.opts.$trigger.find("img:first"),l.$thumb.length&&(l.opts.$orig=l.opts.$trigger)),l.$thumb&&l.$thumb.length||!l.opts.$orig||(l.$thumb=l.opts.$orig.find("img:first")),l.$thumb&&!l.$thumb.length&&(l.$thumb=null),l.thumb=l.opts.thumb||(l.$thumb?l.$thumb[0].src:null),"function"===n.type(l.opts.caption)&&(l.opts.caption=l.opts.caption.apply(e,[o,l])),"function"===n.type(o.opts.caption)&&(l.opts.caption=o.opts.caption.apply(e,[o,l])),l.opts.caption instanceof n||(l.opts.caption=void 0===l.opts.caption?"":l.opts.caption+""),"ajax"===l.type&&(c=r.split(/\s+/,2),c.length>1&&(l.src=c.shift(),l.opts.filter=c.shift())),l.opts.modal&&(l.opts=n.extend(!0,l.opts,{trapFocus:!0,infobar:0,toolbar:0,smallBtn:0,keyboard:0,slideShow:0,fullScreen:0,thumbs:0,touch:0,clickContent:!1,clickSlide:!1,clickOutside:!1,dblclickContent:!1,dblclickSlide:!1,dblclickOutside:!1})),o.group.push(l)}),Object.keys(o.slides).length&&(o.updateControls(),(e=o.Thumbs)&&e.isActive&&(e.create(),e.focus()))},addEvents:function(){var e=this;e.removeEvents(),e.$refs.container.on("click.fb-close","[data-fancybox-close]",function(t){t.stopPropagation(),t.preventDefault(),e.close(t)}).on("touchstart.fb-prev click.fb-prev","[data-fancybox-prev]",function(t){t.stopPropagation(),t.preventDefault(),e.previous()}).on("touchstart.fb-next click.fb-next","[data-fancybox-next]",function(t){t.stopPropagation(),t.preventDefault(),e.next()}).on("click.fb","[data-fancybox-zoom]",function(t){e[e.isScaledDown()?"scaleToActual":"scaleToFit"]()}),s.on("orientationchange.fb resize.fb",function(t){t&&t.originalEvent&&"resize"===t.originalEvent.type?(e.requestId&&u(e.requestId),e.requestId=d(function(){e.update(t)})):(e.current&&"iframe"===e.current.type&&e.$refs.stage.hide(),setTimeout(function(){e.$refs.stage.show(),e.update(t)},n.fancybox.isMobile?600:250))}),r.on("keydown.fb",function(t){var o=n.fancybox?n.fancybox.getInstance():null,i=o.current,a=t.keyCode||t.which;if(9==a)return void(i.opts.trapFocus&&e.focus(t));if(!(!i.opts.keyboard||t.ctrlKey||t.altKey||t.shiftKey||n(t.target).is("input,textarea,video,audio,select")))return 8===a||27===a?(t.preventDefault(),void e.close(t)):37===a||38===a?(t.preventDefault(),void e.previous()):39===a||40===a?(t.preventDefault(),void e.next()):void e.trigger("afterKeydown",t,a)}),e.group[e.currIndex].opts.idleTime&&(e.idleSecondsCounter=0,r.on("mousemove.fb-idle mouseleave.fb-idle mousedown.fb-idle touchstart.fb-idle touchmove.fb-idle scroll.fb-idle keydown.fb-idle",function(t){e.idleSecondsCounter=0,e.isIdle&&e.showControls(),e.isIdle=!1}),e.idleInterval=t.setInterval(function(){++e.idleSecondsCounter>=e.group[e.currIndex].opts.idleTime&&!e.isDragging&&(e.isIdle=!0,e.idleSecondsCounter=0,e.hideControls())},1e3))},removeEvents:function(){var e=this;s.off("orientationchange.fb resize.fb"),r.off("keydown.fb .fb-idle"),this.$refs.container.off(".fb-close .fb-prev .fb-next"),e.idleInterval&&(t.clearInterval(e.idleInterval),e.idleInterval=null)},previous:function(t){return this.jumpTo(this.currPos-1,t)},next:function(t){return this.jumpTo(this.currPos+1,t)},jumpTo:function(t,e){var o,i,a,s,r,c,l,d,u,f=this,h=f.group.length;if(!(f.isDragging||f.isClosing||f.isAnimating&&f.firstRun)){if(t=parseInt(t,10),!(a=f.current?f.current.opts.loop:f.opts.loop)&&(t<0||t>=h))return!1;if(o=f.firstRun=!Object.keys(f.slides).length,r=f.current,f.prevIndex=f.currIndex,f.prevPos=f.currPos,s=f.createSlide(t),h>1&&((a||s.index<h-1)&&f.createSlide(t+1),(a||s.index>0)&&f.createSlide(t-1)),f.current=s,f.currIndex=s.index,f.currPos=s.pos,f.trigger("beforeShow",o),f.updateControls(),s.forcedDuration=void 0,n.isNumeric(e)?s.forcedDuration=e:e=s.opts[o?"animationDuration":"transitionDuration"],e=parseInt(e,10),i=f.isMoved(s),s.$slide.addClass("fancybox-slide--current"),o)return s.opts.animationEffect&&e&&f.$refs.container.css("transition-duration",e+"ms"),f.$refs.container.addClass("fancybox-is-open").trigger("focus"),f.loadSlide(s),void f.preload("image");c=n.fancybox.getTranslate(r.$slide),l=n.fancybox.getTranslate(f.$refs.stage),n.each(f.slides,function(t,e){n.fancybox.stop(e.$slide,!0)}),r.pos!==s.pos&&(r.isComplete=!1),r.$slide.removeClass("fancybox-slide--complete fancybox-slide--current"),i?(u=c.left-(r.pos*c.width+r.pos*r.opts.gutter),n.each(f.slides,function(t,o){o.$slide.removeClass("fancybox-animated").removeClass(function(t,e){return(e.match(/(^|\s)fancybox-fx-\S+/g)||[]).join(" ")});var i=o.pos*c.width+o.pos*o.opts.gutter;n.fancybox.setTranslate(o.$slide,{top:0,left:i-l.left+u}),o.pos!==s.pos&&o.$slide.addClass("fancybox-slide--"+(o.pos>s.pos?"next":"previous")),p(o.$slide),n.fancybox.animate(o.$slide,{top:0,left:(o.pos-s.pos)*c.width+(o.pos-s.pos)*o.opts.gutter},e,function(){o.$slide.css({transform:"",opacity:""}).removeClass("fancybox-slide--next fancybox-slide--previous"),o.pos===f.currPos&&f.complete()})})):e&&s.opts.transitionEffect&&(d="fancybox-animated fancybox-fx-"+s.opts.transitionEffect,r.$slide.addClass("fancybox-slide--"+(r.pos>s.pos?"next":"previous")),n.fancybox.animate(r.$slide,d,e,function(){r.$slide.removeClass(d).removeClass("fancybox-slide--next fancybox-slide--previous")},!1)),s.isLoaded?f.revealContent(s):f.loadSlide(s),f.preload("image")}},createSlide:function(t){var e,o,i=this;return o=t%i.group.length,o=o<0?i.group.length+o:o,!i.slides[t]&&i.group[o]&&(e=n('<div class="fancybox-slide"></div>').appendTo(i.$refs.stage),i.slides[t]=n.extend(!0,{},i.group[o],{pos:t,$slide:e,isLoaded:!1}),i.updateSlide(i.slides[t])),i.slides[t]},scaleToActual:function(t,e,o){var i,a,s,r,c,l=this,d=l.current,u=d.$content,f=n.fancybox.getTranslate(d.$slide).width,p=n.fancybox.getTranslate(d.$slide).height,h=d.width,g=d.height;l.isAnimating||l.isMoved()||!u||"image"!=d.type||!d.isLoaded||d.hasError||(l.isAnimating=!0,n.fancybox.stop(u),t=void 0===t?.5*f:t,e=void 0===e?.5*p:e,i=n.fancybox.getTranslate(u),i.top-=n.fancybox.getTranslate(d.$slide).top,i.left-=n.fancybox.getTranslate(d.$slide).left,r=h/i.width,c=g/i.height,a=.5*f-.5*h,s=.5*p-.5*g,h>f&&(a=i.left*r-(t*r-t),a>0&&(a=0),a<f-h&&(a=f-h)),g>p&&(s=i.top*c-(e*c-e),s>0&&(s=0),s<p-g&&(s=p-g)),l.updateCursor(h,g),n.fancybox.animate(u,{top:s,left:a,scaleX:r,scaleY:c},o||366,function(){l.isAnimating=!1}),l.SlideShow&&l.SlideShow.isActive&&l.SlideShow.stop())},scaleToFit:function(t){var e,o=this,i=o.current,a=i.$content;o.isAnimating||o.isMoved()||!a||"image"!=i.type||!i.isLoaded||i.hasError||(o.isAnimating=!0,n.fancybox.stop(a),e=o.getFitPos(i),o.updateCursor(e.width,e.height),n.fancybox.animate(a,{top:e.top,left:e.left,scaleX:e.width/a.width(),scaleY:e.height/a.height()},t||366,function(){o.isAnimating=!1}))},getFitPos:function(t){var e,o,i,a,s=this,r=t.$content,c=t.$slide,l=t.width||t.opts.width,d=t.height||t.opts.height,u={};return!!(t.isLoaded&&r&&r.length)&&(e=n.fancybox.getTranslate(s.$refs.stage).width,o=n.fancybox.getTranslate(s.$refs.stage).height,e-=parseFloat(c.css("paddingLeft"))+parseFloat(c.css("paddingRight"))+parseFloat(r.css("marginLeft"))+parseFloat(r.css("marginRight")),o-=parseFloat(c.css("paddingTop"))+parseFloat(c.css("paddingBottom"))+parseFloat(r.css("marginTop"))+parseFloat(r.css("marginBottom")),l&&d||(l=e,d=o),i=Math.min(1,e/l,o/d),l*=i,d*=i,l>e-.5&&(l=e),d>o-.5&&(d=o),"image"===t.type?(u.top=Math.floor(.5*(o-d))+parseFloat(c.css("paddingTop")),u.left=Math.floor(.5*(e-l))+parseFloat(c.css("paddingLeft"))):"video"===t.contentType&&(a=t.opts.width&&t.opts.height?l/d:t.opts.ratio||16/9,d>l/a?d=l/a:l>d*a&&(l=d*a)),u.width=l,u.height=d,u)},update:function(t){var e=this;n.each(e.slides,function(n,o){e.updateSlide(o,t)})},updateSlide:function(t,e){var o=this,i=t&&t.$content,a=t.width||t.opts.width,s=t.height||t.opts.height,r=t.$slide;o.adjustCaption(t),i&&(a||s||"video"===t.contentType)&&!t.hasError&&(n.fancybox.stop(i),n.fancybox.setTranslate(i,o.getFitPos(t)),t.pos===o.currPos&&(o.isAnimating=!1,o.updateCursor())),o.adjustLayout(t),r.length&&(r.trigger("refresh"),t.pos===o.currPos&&o.$refs.toolbar.add(o.$refs.navigation.find(".fancybox-button--arrow_right")).toggleClass("compensate-for-scrollbar",r.get(0).scrollHeight>r.get(0).clientHeight)),o.trigger("onUpdate",t,e)},centerSlide:function(t){var e=this,o=e.current,i=o.$slide;!e.isClosing&&o&&(i.siblings().css({transform:"",opacity:""}),i.parent().children().removeClass("fancybox-slide--previous fancybox-slide--next"),n.fancybox.animate(i,{top:0,left:0,opacity:1},void 0===t?0:t,function(){i.css({transform:"",opacity:""}),o.isComplete||e.complete()},!1))},isMoved:function(t){var e,o,i=t||this.current;return!!i&&(o=n.fancybox.getTranslate(this.$refs.stage),e=n.fancybox.getTranslate(i.$slide),!i.$slide.hasClass("fancybox-animated")&&(Math.abs(e.top-o.top)>.5||Math.abs(e.left-o.left)>.5))},updateCursor:function(t,e){var o,i,a=this,s=a.current,r=a.$refs.container;s&&!a.isClosing&&a.Guestures&&(r.removeClass("fancybox-is-zoomable fancybox-can-zoomIn fancybox-can-zoomOut fancybox-can-swipe fancybox-can-pan"),o=a.canPan(t,e),i=!!o||a.isZoomable(),r.toggleClass("fancybox-is-zoomable",i),n("[data-fancybox-zoom]").prop("disabled",!i),o?r.addClass("fancybox-can-pan"):i&&("zoom"===s.opts.clickContent||n.isFunction(s.opts.clickContent)&&"zoom"==s.opts.clickContent(s))?r.addClass("fancybox-can-zoomIn"):s.opts.touch&&(s.opts.touch.vertical||a.group.length>1)&&"video"!==s.contentType&&r.addClass("fancybox-can-swipe"))},isZoomable:function(){var t,e=this,n=e.current;if(n&&!e.isClosing&&"image"===n.type&&!n.hasError){if(!n.isLoaded)return!0;if((t=e.getFitPos(n))&&(n.width>t.width||n.height>t.height))return!0}return!1},isScaledDown:function(t,e){var o=this,i=!1,a=o.current,s=a.$content;return void 0!==t&&void 0!==e?i=t<a.width&&e<a.height:s&&(i=n.fancybox.getTranslate(s),i=i.width<a.width&&i.height<a.height),i},canPan:function(t,e){var o=this,i=o.current,a=null,s=!1;return"image"===i.type&&(i.isComplete||t&&e)&&!i.hasError&&(s=o.getFitPos(i),void 0!==t&&void 0!==e?a={width:t,height:e}:i.isComplete&&(a=n.fancybox.getTranslate(i.$content)),a&&s&&(s=Math.abs(a.width-s.width)>1.5||Math.abs(a.height-s.height)>1.5)),s},loadSlide:function(t){var e,o,i,a=this;if(!t.isLoading&&!t.isLoaded){if(t.isLoading=!0,!1===a.trigger("beforeLoad",t))return t.isLoading=!1,!1;switch(e=t.type,o=t.$slide,o.off("refresh").trigger("onReset").addClass(t.opts.slideClass),e){case"image":a.setImage(t);break;case"iframe":a.setIframe(t);break;case"html":a.setContent(t,t.src||t.content);break;case"video":a.setContent(t,t.opts.video.tpl.replace(/\{\{src\}\}/gi,t.src).replace("{{format}}",t.opts.videoFormat||t.opts.video.format||"").replace("{{poster}}",t.thumb||""));break;case"inline":n(t.src).length?a.setContent(t,n(t.src)):a.setError(t);break;case"ajax":a.showLoading(t),i=n.ajax(n.extend({},t.opts.ajax.settings,{url:t.src,success:function(e,n){"success"===n&&a.setContent(t,e)},error:function(e,n){e&&"abort"!==n&&a.setError(t)}})),o.one("onReset",function(){i.abort()});break;default:a.setError(t)}return!0}},setImage:function(t){var o,i=this;setTimeout(function(){var e=t.$image;i.isClosing||!t.isLoading||e&&e.length&&e[0].complete||t.hasError||i.showLoading(t)},50),i.checkSrcset(t),t.$content=n('<div class="fancybox-content"></div>').addClass("fancybox-is-hidden").appendTo(t.$slide.addClass("fancybox-slide--image")),!1!==t.opts.preload&&t.opts.width&&t.opts.height&&t.thumb&&(t.width=t.opts.width,t.height=t.opts.height,o=e.createElement("img"),o.onerror=function(){n(this).remove(),t.$ghost=null},o.onload=function(){i.afterLoad(t)},t.$ghost=n(o).addClass("fancybox-image").appendTo(t.$content).attr("src",t.thumb)),i.setBigImage(t)},checkSrcset:function(e){var n,o,i,a,s=e.opts.srcset||e.opts.image.srcset;if(s){i=t.devicePixelRatio||1,a=t.innerWidth*i,o=s.split(",").map(function(t){var e={};return t.trim().split(/\s+/).forEach(function(t,n){var o=parseInt(t.substring(0,t.length-1),10);if(0===n)return e.url=t;o&&(e.value=o,e.postfix=t[t.length-1])}),e}),o.sort(function(t,e){return t.value-e.value});for(var r=0;r<o.length;r++){var c=o[r];if("w"===c.postfix&&c.value>=a||"x"===c.postfix&&c.value>=i){n=c;break}}!n&&o.length&&(n=o[o.length-1]),n&&(e.src=n.url,e.width&&e.height&&"w"==n.postfix&&(e.height=e.width/e.height*n.value,e.width=n.value),e.opts.srcset=s)}},setBigImage:function(t){var o=this,i=e.createElement("img"),a=n(i);t.$image=a.one("error",function(){o.setError(t)}).one("load",function(){var e;t.$ghost||(o.resolveImageSlideSize(t,this.naturalWidth,this.naturalHeight),o.afterLoad(t)),o.isClosing||(t.opts.srcset&&(e=t.opts.sizes,e&&"auto"!==e||(e=(t.width/t.height>1&&s.width()/s.height()>1?"100":Math.round(t.width/t.height*100))+"vw"),a.attr("sizes",e).attr("srcset",t.opts.srcset)),t.$ghost&&setTimeout(function(){t.$ghost&&!o.isClosing&&t.$ghost.hide()},Math.min(300,Math.max(1e3,t.height/1600))),o.hideLoading(t))}).addClass("fancybox-image").attr("src",t.src).appendTo(t.$content),(i.complete||"complete"==i.readyState)&&a.naturalWidth&&a.naturalHeight?a.trigger("load"):i.error&&a.trigger("error")},resolveImageSlideSize:function(t,e,n){var o=parseInt(t.opts.width,10),i=parseInt(t.opts.height,10);t.width=e,t.height=n,o>0&&(t.width=o,t.height=Math.floor(o*n/e)),i>0&&(t.width=Math.floor(i*e/n),t.height=i)},setIframe:function(t){var e,o=this,i=t.opts.iframe,a=t.$slide;t.$content=n('<div class="fancybox-content'+(i.preload?" fancybox-is-hidden":"")+'"></div>').css(i.css).appendTo(a),a.addClass("fancybox-slide--"+t.contentType),t.$iframe=e=n(i.tpl.replace(/\{rnd\}/g,(new Date).getTime())).attr(i.attr).appendTo(t.$content),i.preload?(o.showLoading(t),e.on("load.fb error.fb",function(e){this.isReady=1,t.$slide.trigger("refresh"),o.afterLoad(t)}),a.on("refresh.fb",function(){var n,o,s=t.$content,r=i.css.width,c=i.css.height;if(1===e[0].isReady){try{n=e.contents(),o=n.find("body")}catch(t){}o&&o.length&&o.children().length&&(a.css("overflow","visible"),s.css({width:"100%","max-width":"100%",height:"9999px"}),void 0===r&&(r=Math.ceil(Math.max(o[0].clientWidth,o.outerWidth(!0)))),s.css("width",r||"").css("max-width",""),void 0===c&&(c=Math.ceil(Math.max(o[0].clientHeight,o.outerHeight(!0)))),s.css("height",c||""),a.css("overflow","auto")),s.removeClass("fancybox-is-hidden")}})):o.afterLoad(t),e.attr("src",t.src),a.one("onReset",function(){try{n(this).find("iframe").hide().unbind().attr("src","//about:blank")}catch(t){}n(this).off("refresh.fb").empty(),t.isLoaded=!1,t.isRevealed=!1})},setContent:function(t,e){var o=this;o.isClosing||(o.hideLoading(t),t.$content&&n.fancybox.stop(t.$content),t.$slide.empty(),l(e)&&e.parent().length?((e.hasClass("fancybox-content")||e.parent().hasClass("fancybox-content"))&&e.parents(".fancybox-slide").trigger("onReset"),t.$placeholder=n("<div>").hide().insertAfter(e),e.css("display","inline-block")):t.hasError||("string"===n.type(e)&&(e=n("<div>").append(n.trim(e)).contents()),t.opts.filter&&(e=n("<div>").html(e).find(t.opts.filter))),t.$slide.one("onReset",function(){n(this).find("video,audio").trigger("pause"),t.$placeholder&&(t.$placeholder.after(e.removeClass("fancybox-content").hide()).remove(),t.$placeholder=null),t.$smallBtn&&(t.$smallBtn.remove(),t.$smallBtn=null),t.hasError||(n(this).empty(),t.isLoaded=!1,t.isRevealed=!1)}),n(e).appendTo(t.$slide),n(e).is("video,audio")&&(n(e).addClass("fancybox-video"),n(e).wrap("<div></div>"),t.contentType="video",t.opts.width=t.opts.width||n(e).attr("width"),t.opts.height=t.opts.height||n(e).attr("height")),t.$content=t.$slide.children().filter("div,form,main,video,audio,article,.fancybox-content").first(),t.$content.siblings().hide(),t.$content.length||(t.$content=t.$slide.wrapInner("<div></div>").children().first()),t.$content.addClass("fancybox-content"),t.$slide.addClass("fancybox-slide--"+t.contentType),o.afterLoad(t))},setError:function(t){t.hasError=!0,t.$slide.trigger("onReset").removeClass("fancybox-slide--"+t.contentType).addClass("fancybox-slide--error"),t.contentType="html",this.setContent(t,this.translate(t,t.opts.errorTpl)),t.pos===this.currPos&&(this.isAnimating=!1)},showLoading:function(t){var e=this;(t=t||e.current)&&!t.$spinner&&(t.$spinner=n(e.translate(e,e.opts.spinnerTpl)).appendTo(t.$slide).hide().fadeIn("fast"))},hideLoading:function(t){var e=this;(t=t||e.current)&&t.$spinner&&(t.$spinner.stop().remove(),delete t.$spinner)},afterLoad:function(t){var e=this;e.isClosing||(t.isLoading=!1,t.isLoaded=!0,e.trigger("afterLoad",t),e.hideLoading(t),!t.opts.smallBtn||t.$smallBtn&&t.$smallBtn.length||(t.$smallBtn=n(e.translate(t,t.opts.btnTpl.smallBtn)).appendTo(t.$content)),t.opts.protect&&t.$content&&!t.hasError&&(t.$content.on("contextmenu.fb",function(t){return 2==t.button&&t.preventDefault(),!0}),"image"===t.type&&n('<div class="fancybox-spaceball"></div>').appendTo(t.$content)),e.adjustCaption(t),e.adjustLayout(t),t.pos===e.currPos&&e.updateCursor(),e.revealContent(t))},adjustCaption:function(t){var e,n=this,o=t||n.current,i=o.opts.caption,a=o.opts.preventCaptionOverlap,s=n.$refs.caption,r=!1;s.toggleClass("fancybox-caption--separate",a),a&&i&&i.length&&(o.pos!==n.currPos?(e=s.clone().appendTo(s.parent()),e.children().eq(0).empty().html(i),r=e.outerHeight(!0),e.empty().remove()):n.$caption&&(r=n.$caption.outerHeight(!0)),o.$slide.css("padding-bottom",r||""))},adjustLayout:function(t){var e,n,o,i,a=this,s=t||a.current;s.isLoaded&&!0!==s.opts.disableLayoutFix&&(s.$content.css("margin-bottom",""),s.$content.outerHeight()>s.$slide.height()+.5&&(o=s.$slide[0].style["padding-bottom"],i=s.$slide.css("padding-bottom"),parseFloat(i)>0&&(e=s.$slide[0].scrollHeight,s.$slide.css("padding-bottom",0),Math.abs(e-s.$slide[0].scrollHeight)<1&&(n=i),s.$slide.css("padding-bottom",o))),s.$content.css("margin-bottom",n))},revealContent:function(t){var e,o,i,a,s=this,r=t.$slide,c=!1,l=!1,d=s.isMoved(t),u=t.isRevealed;return t.isRevealed=!0,e=t.opts[s.firstRun?"animationEffect":"transitionEffect"],i=t.opts[s.firstRun?"animationDuration":"transitionDuration"],i=parseInt(void 0===t.forcedDuration?i:t.forcedDuration,10),!d&&t.pos===s.currPos&&i||(e=!1),"zoom"===e&&(t.pos===s.currPos&&i&&"image"===t.type&&!t.hasError&&(l=s.getThumbPos(t))?c=s.getFitPos(t):e="fade"),"zoom"===e?(s.isAnimating=!0,c.scaleX=c.width/l.width,c.scaleY=c.height/l.height,a=t.opts.zoomOpacity,"auto"==a&&(a=Math.abs(t.width/t.height-l.width/l.height)>.1),a&&(l.opacity=.1,c.opacity=1),n.fancybox.setTranslate(t.$content.removeClass("fancybox-is-hidden"),l),p(t.$content),void n.fancybox.animate(t.$content,c,i,function(){s.isAnimating=!1,s.complete()})):(s.updateSlide(t),e?(n.fancybox.stop(r),o="fancybox-slide--"+(t.pos>=s.prevPos?"next":"previous")+" fancybox-animated fancybox-fx-"+e,r.addClass(o).removeClass("fancybox-slide--current"),t.$content.removeClass("fancybox-is-hidden"),p(r),"image"!==t.type&&t.$content.hide().show(0),void n.fancybox.animate(r,"fancybox-slide--current",i,function(){r.removeClass(o).css({transform:"",opacity:""}),t.pos===s.currPos&&s.complete()},!0)):(t.$content.removeClass("fancybox-is-hidden"),u||!d||"image"!==t.type||t.hasError||t.$content.hide().fadeIn("fast"),void(t.pos===s.currPos&&s.complete())))},getThumbPos:function(t){var e,o,i,a,s,r=!1,c=t.$thumb;return!(!c||!g(c[0]))&&(e=n.fancybox.getTranslate(c),o=parseFloat(c.css("border-top-width")||0),i=parseFloat(c.css("border-right-width")||0),a=parseFloat(c.css("border-bottom-width")||0),s=parseFloat(c.css("border-left-width")||0),r={top:e.top+o,left:e.left+s,width:e.width-i-s,height:e.height-o-a,scaleX:1,scaleY:1},e.width>0&&e.height>0&&r)},complete:function(){var t,e=this,o=e.current,i={};!e.isMoved()&&o.isLoaded&&(o.isComplete||(o.isComplete=!0,o.$slide.siblings().trigger("onReset"),e.preload("inline"),p(o.$slide),o.$slide.addClass("fancybox-slide--complete"),n.each(e.slides,function(t,o){o.pos>=e.currPos-1&&o.pos<=e.currPos+1?i[o.pos]=o:o&&(n.fancybox.stop(o.$slide),o.$slide.off().remove())}),e.slides=i),e.isAnimating=!1,e.updateCursor(),e.trigger("afterShow"),o.opts.video.autoStart&&o.$slide.find("video,audio").filter(":visible:first").trigger("play").one("ended",function(){Document.exitFullscreen?Document.exitFullscreen():this.webkitExitFullscreen&&this.webkitExitFullscreen(),e.next()}),o.opts.autoFocus&&"html"===o.contentType&&(t=o.$content.find("input[autofocus]:enabled:visible:first"),t.length?t.trigger("focus"):e.focus(null,!0)),o.$slide.scrollTop(0).scrollLeft(0))},preload:function(t){var e,n,o=this;o.group.length<2||(n=o.slides[o.currPos+1],e=o.slides[o.currPos-1],e&&e.type===t&&o.loadSlide(e),n&&n.type===t&&o.loadSlide(n))},focus:function(t,o){var i,a,s=this,r=["a[href]","area[href]",'input:not([disabled]):not([type="hidden"]):not([aria-hidden])',"select:not([disabled]):not([aria-hidden])","textarea:not([disabled]):not([aria-hidden])","button:not([disabled]):not([aria-hidden])","iframe","object","embed","video","audio","[contenteditable]",'[tabindex]:not([tabindex^="-"])'].join(",");s.isClosing||(i=!t&&s.current&&s.current.isComplete?s.current.$slide.find("*:visible"+(o?":not(.fancybox-close-small)":"")):s.$refs.container.find("*:visible"),i=i.filter(r).filter(function(){return"hidden"!==n(this).css("visibility")&&!n(this).hasClass("disabled")}),i.length?(a=i.index(e.activeElement),t&&t.shiftKey?(a<0||0==a)&&(t.preventDefault(),i.eq(i.length-1).trigger("focus")):(a<0||a==i.length-1)&&(t&&t.preventDefault(),i.eq(0).trigger("focus"))):s.$refs.container.trigger("focus"))},activate:function(){var t=this;n(".fancybox-container").each(function(){var e=n(this).data("FancyBox");e&&e.id!==t.id&&!e.isClosing&&(e.trigger("onDeactivate"),e.removeEvents(),e.isVisible=!1)}),t.isVisible=!0,(t.current||t.isIdle)&&(t.update(),t.updateControls()),t.trigger("onActivate"),t.addEvents()},close:function(t,e){var o,i,a,s,r,c,l,u=this,f=u.current,h=function(){u.cleanUp(t)};return!u.isClosing&&(u.isClosing=!0,!1===u.trigger("beforeClose",t)?(u.isClosing=!1,d(function(){u.update()}),!1):(u.removeEvents(),a=f.$content,o=f.opts.animationEffect,i=n.isNumeric(e)?e:o?f.opts.animationDuration:0,f.$slide.removeClass("fancybox-slide--complete fancybox-slide--next fancybox-slide--previous fancybox-animated"),!0!==t?n.fancybox.stop(f.$slide):o=!1,f.$slide.siblings().trigger("onReset").remove(),i&&u.$refs.container.removeClass("fancybox-is-open").addClass("fancybox-is-closing").css("transition-duration",i+"ms"),u.hideLoading(f),u.hideControls(!0),u.updateCursor(),"zoom"!==o||a&&i&&"image"===f.type&&!u.isMoved()&&!f.hasError&&(l=u.getThumbPos(f))||(o="fade"),"zoom"===o?(n.fancybox.stop(a),s=n.fancybox.getTranslate(a),c={top:s.top,left:s.left,scaleX:s.width/l.width,scaleY:s.height/l.height,width:l.width,height:l.height},r=f.opts.zoomOpacity,
"auto"==r&&(r=Math.abs(f.width/f.height-l.width/l.height)>.1),r&&(l.opacity=0),n.fancybox.setTranslate(a,c),p(a),n.fancybox.animate(a,l,i,h),!0):(o&&i?n.fancybox.animate(f.$slide.addClass("fancybox-slide--previous").removeClass("fancybox-slide--current"),"fancybox-animated fancybox-fx-"+o,i,h):!0===t?setTimeout(h,i):h(),!0)))},cleanUp:function(e){var o,i,a,s=this,r=s.current.opts.$orig;s.current.$slide.trigger("onReset"),s.$refs.container.empty().remove(),s.trigger("afterClose",e),s.current.opts.backFocus&&(r&&r.length&&r.is(":visible")||(r=s.$trigger),r&&r.length&&(i=t.scrollX,a=t.scrollY,r.trigger("focus"),n("html, body").scrollTop(a).scrollLeft(i))),s.current=null,o=n.fancybox.getInstance(),o?o.activate():(n("body").removeClass("fancybox-active compensate-for-scrollbar"),n("#fancybox-style-noscroll").remove())},trigger:function(t,e){var o,i=Array.prototype.slice.call(arguments,1),a=this,s=e&&e.opts?e:a.current;if(s?i.unshift(s):s=a,i.unshift(a),n.isFunction(s.opts[t])&&(o=s.opts[t].apply(s,i)),!1===o)return o;"afterClose"!==t&&a.$refs?a.$refs.container.trigger(t+".fb",i):r.trigger(t+".fb",i)},updateControls:function(){var t=this,o=t.current,i=o.index,a=t.$refs.container,s=t.$refs.caption,r=o.opts.caption;o.$slide.trigger("refresh"),r&&r.length?(t.$caption=s,s.children().eq(0).html(r)):t.$caption=null,t.hasHiddenControls||t.isIdle||t.showControls(),a.find("[data-fancybox-count]").html(t.group.length),a.find("[data-fancybox-index]").html(i+1),a.find("[data-fancybox-prev]").prop("disabled",!o.opts.loop&&i<=0),a.find("[data-fancybox-next]").prop("disabled",!o.opts.loop&&i>=t.group.length-1),"image"===o.type?a.find("[data-fancybox-zoom]").show().end().find("[data-fancybox-download]").attr("href",o.opts.image.src||o.src).show():o.opts.toolbar&&a.find("[data-fancybox-download],[data-fancybox-zoom]").hide(),n(e.activeElement).is(":hidden,[disabled]")&&t.$refs.container.trigger("focus")},hideControls:function(t){var e=this,n=["infobar","toolbar","nav"];!t&&e.current.opts.preventCaptionOverlap||n.push("caption"),this.$refs.container.removeClass(n.map(function(t){return"fancybox-show-"+t}).join(" ")),this.hasHiddenControls=!0},showControls:function(){var t=this,e=t.current?t.current.opts:t.opts,n=t.$refs.container;t.hasHiddenControls=!1,t.idleSecondsCounter=0,n.toggleClass("fancybox-show-toolbar",!(!e.toolbar||!e.buttons)).toggleClass("fancybox-show-infobar",!!(e.infobar&&t.group.length>1)).toggleClass("fancybox-show-caption",!!t.$caption).toggleClass("fancybox-show-nav",!!(e.arrows&&t.group.length>1)).toggleClass("fancybox-is-modal",!!e.modal)},toggleControls:function(){this.hasHiddenControls?this.showControls():this.hideControls()}}),n.fancybox={version:"3.5.7",defaults:a,getInstance:function(t){var e=n('.fancybox-container:not(".fancybox-is-closing"):last').data("FancyBox"),o=Array.prototype.slice.call(arguments,1);return e instanceof b&&("string"===n.type(t)?e[t].apply(e,o):"function"===n.type(t)&&t.apply(e,o),e)},open:function(t,e,n){return new b(t,e,n)},close:function(t){var e=this.getInstance();e&&(e.close(),!0===t&&this.close(t))},destroy:function(){this.close(!0),r.add("body").off("click.fb-start","**")},isMobile:/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),use3d:function(){var n=e.createElement("div");return t.getComputedStyle&&t.getComputedStyle(n)&&t.getComputedStyle(n).getPropertyValue("transform")&&!(e.documentMode&&e.documentMode<11)}(),getTranslate:function(t){var e;return!(!t||!t.length)&&(e=t[0].getBoundingClientRect(),{top:e.top||0,left:e.left||0,width:e.width,height:e.height,opacity:parseFloat(t.css("opacity"))})},setTranslate:function(t,e){var n="",o={};if(t&&e)return void 0===e.left&&void 0===e.top||(n=(void 0===e.left?t.position().left:e.left)+"px, "+(void 0===e.top?t.position().top:e.top)+"px",n=this.use3d?"translate3d("+n+", 0px)":"translate("+n+")"),void 0!==e.scaleX&&void 0!==e.scaleY?n+=" scale("+e.scaleX+", "+e.scaleY+")":void 0!==e.scaleX&&(n+=" scaleX("+e.scaleX+")"),n.length&&(o.transform=n),void 0!==e.opacity&&(o.opacity=e.opacity),void 0!==e.width&&(o.width=e.width),void 0!==e.height&&(o.height=e.height),t.css(o)},animate:function(t,e,o,i,a){var s,r=this;n.isFunction(o)&&(i=o,o=null),r.stop(t),s=r.getTranslate(t),t.on(f,function(c){(!c||!c.originalEvent||t.is(c.originalEvent.target)&&"z-index"!=c.originalEvent.propertyName)&&(r.stop(t),n.isNumeric(o)&&t.css("transition-duration",""),n.isPlainObject(e)?void 0!==e.scaleX&&void 0!==e.scaleY&&r.setTranslate(t,{top:e.top,left:e.left,width:s.width*e.scaleX,height:s.height*e.scaleY,scaleX:1,scaleY:1}):!0!==a&&t.removeClass(e),n.isFunction(i)&&i(c))}),n.isNumeric(o)&&t.css("transition-duration",o+"ms"),n.isPlainObject(e)?(void 0!==e.scaleX&&void 0!==e.scaleY&&(delete e.width,delete e.height,t.parent().hasClass("fancybox-slide--image")&&t.parent().addClass("fancybox-is-scaling")),n.fancybox.setTranslate(t,e)):t.addClass(e),t.data("timer",setTimeout(function(){t.trigger(f)},o+33))},stop:function(t,e){t&&t.length&&(clearTimeout(t.data("timer")),e&&t.trigger(f),t.off(f).css("transition-duration",""),t.parent().removeClass("fancybox-is-scaling"))}},n.fn.fancybox=function(t){var e;return t=t||{},e=t.selector||!1,e?n("body").off("click.fb-start",e).on("click.fb-start",e,{options:t},i):this.off("click.fb-start").on("click.fb-start",{items:this,options:t},i),this},r.on("click.fb-start","[data-fancybox]",i),r.on("click.fb-start","[data-fancybox-trigger]",function(t){n('[data-fancybox="'+n(this).attr("data-fancybox-trigger")+'"]').eq(n(this).attr("data-fancybox-index")||0).trigger("click.fb-start",{$trigger:n(this)})}),function(){var t=null;r.on("mousedown mouseup focus blur",".fancybox-button",function(e){switch(e.type){case"mousedown":t=n(this);break;case"mouseup":t=null;break;case"focusin":n(".fancybox-button").removeClass("fancybox-focus"),n(this).is(t)||n(this).is("[disabled]")||n(this).addClass("fancybox-focus");break;case"focusout":n(".fancybox-button").removeClass("fancybox-focus")}})}()}}(window,document,jQuery),function(t){"use strict";var e={youtube:{matcher:/(youtube\.com|youtu\.be|youtube\-nocookie\.com)\/(watch\?(.*&)?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*))(.*)/i,params:{autoplay:1,autohide:1,fs:1,rel:0,hd:1,wmode:"transparent",enablejsapi:1,html5:1},paramPlace:8,type:"iframe",url:"https://www.youtube-nocookie.com/embed/$4",thumb:"https://img.youtube.com/vi/$4/hqdefault.jpg"},vimeo:{matcher:/^.+vimeo.com\/(.*\/)?([\d]+)(.*)?/,params:{autoplay:1,hd:1,show_title:1,show_byline:1,show_portrait:0,fullscreen:1},paramPlace:3,type:"iframe",url:"//player.vimeo.com/video/$2"},instagram:{matcher:/(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,type:"image",url:"//$1/p/$2/media/?size=l"},gmap_place:{matcher:/(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(((maps\/(place\/(.*)\/)?\@(.*),(\d+.?\d+?)z))|(\?ll=))(.*)?/i,type:"iframe",url:function(t){return"//maps.google."+t[2]+"/?ll="+(t[9]?t[9]+"&z="+Math.floor(t[10])+(t[12]?t[12].replace(/^\//,"&"):""):t[12]+"").replace(/\?/,"&")+"&output="+(t[12]&&t[12].indexOf("layer=c")>0?"svembed":"embed")}},gmap_search:{matcher:/(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(maps\/search\/)(.*)/i,type:"iframe",url:function(t){return"//maps.google."+t[2]+"/maps?q="+t[5].replace("query=","q=").replace("api=1","")+"&output=embed"}}},n=function(e,n,o){if(e)return o=o||"","object"===t.type(o)&&(o=t.param(o,!0)),t.each(n,function(t,n){e=e.replace("$"+t,n||"")}),o.length&&(e+=(e.indexOf("?")>0?"&":"?")+o),e};t(document).on("objectNeedsType.fb",function(o,i,a){var s,r,c,l,d,u,f,p=a.src||"",h=!1;s=t.extend(!0,{},e,a.opts.media),t.each(s,function(e,o){if(c=p.match(o.matcher)){if(h=o.type,f=e,u={},o.paramPlace&&c[o.paramPlace]){d=c[o.paramPlace],"?"==d[0]&&(d=d.substring(1)),d=d.split("&");for(var i=0;i<d.length;++i){var s=d[i].split("=",2);2==s.length&&(u[s[0]]=decodeURIComponent(s[1].replace(/\+/g," ")))}}return l=t.extend(!0,{},o.params,a.opts[e],u),p="function"===t.type(o.url)?o.url.call(this,c,l,a):n(o.url,c,l),r="function"===t.type(o.thumb)?o.thumb.call(this,c,l,a):n(o.thumb,c),"youtube"===e?p=p.replace(/&t=((\d+)m)?(\d+)s/,function(t,e,n,o){return"&start="+((n?60*parseInt(n,10):0)+parseInt(o,10))}):"vimeo"===e&&(p=p.replace("&%23","#")),!1}}),h?(a.opts.thumb||a.opts.$thumb&&a.opts.$thumb.length||(a.opts.thumb=r),"iframe"===h&&(a.opts=t.extend(!0,a.opts,{iframe:{preload:!1,attr:{scrolling:"no"}}})),t.extend(a,{type:h,src:p,origSrc:a.src,contentSource:f,contentType:"image"===h?"image":"gmap_place"==f||"gmap_search"==f?"map":"video"})):p&&(a.type=a.opts.defaultType)});var o={youtube:{src:"https://www.youtube.com/iframe_api",class:"YT",loading:!1,loaded:!1},vimeo:{src:"https://player.vimeo.com/api/player.js",class:"Vimeo",loading:!1,loaded:!1},load:function(t){var e,n=this;if(this[t].loaded)return void setTimeout(function(){n.done(t)});this[t].loading||(this[t].loading=!0,e=document.createElement("script"),e.type="text/javascript",e.src=this[t].src,"youtube"===t?window.onYouTubeIframeAPIReady=function(){n[t].loaded=!0,n.done(t)}:e.onload=function(){n[t].loaded=!0,n.done(t)},document.body.appendChild(e))},done:function(e){var n,o,i;"youtube"===e&&delete window.onYouTubeIframeAPIReady,(n=t.fancybox.getInstance())&&(o=n.current.$content.find("iframe"),"youtube"===e&&void 0!==YT&&YT?i=new YT.Player(o.attr("id"),{events:{onStateChange:function(t){0==t.data&&n.next()}}}):"vimeo"===e&&void 0!==Vimeo&&Vimeo&&(i=new Vimeo.Player(o),i.on("ended",function(){n.next()})))}};t(document).on({"afterShow.fb":function(t,e,n){e.group.length>1&&("youtube"===n.contentSource||"vimeo"===n.contentSource)&&o.load(n.contentSource)}})}(jQuery),function(t,e,n){"use strict";var o=function(){return t.requestAnimationFrame||t.webkitRequestAnimationFrame||t.mozRequestAnimationFrame||t.oRequestAnimationFrame||function(e){return t.setTimeout(e,1e3/60)}}(),i=function(){return t.cancelAnimationFrame||t.webkitCancelAnimationFrame||t.mozCancelAnimationFrame||t.oCancelAnimationFrame||function(e){t.clearTimeout(e)}}(),a=function(e){var n=[];e=e.originalEvent||e||t.e,e=e.touches&&e.touches.length?e.touches:e.changedTouches&&e.changedTouches.length?e.changedTouches:[e];for(var o in e)e[o].pageX?n.push({x:e[o].pageX,y:e[o].pageY}):e[o].clientX&&n.push({x:e[o].clientX,y:e[o].clientY});return n},s=function(t,e,n){return e&&t?"x"===n?t.x-e.x:"y"===n?t.y-e.y:Math.sqrt(Math.pow(t.x-e.x,2)+Math.pow(t.y-e.y,2)):0},r=function(t){if(t.is('a,area,button,[role="button"],input,label,select,summary,textarea,video,audio,iframe')||n.isFunction(t.get(0).onclick)||t.data("selectable"))return!0;for(var e=0,o=t[0].attributes,i=o.length;e<i;e++)if("data-fancybox-"===o[e].nodeName.substr(0,14))return!0;return!1},c=function(e){var n=t.getComputedStyle(e)["overflow-y"],o=t.getComputedStyle(e)["overflow-x"],i=("scroll"===n||"auto"===n)&&e.scrollHeight>e.clientHeight,a=("scroll"===o||"auto"===o)&&e.scrollWidth>e.clientWidth;return i||a},l=function(t){for(var e=!1;;){if(e=c(t.get(0)))break;if(t=t.parent(),!t.length||t.hasClass("fancybox-stage")||t.is("body"))break}return e},d=function(t){var e=this;e.instance=t,e.$bg=t.$refs.bg,e.$stage=t.$refs.stage,e.$container=t.$refs.container,e.destroy(),e.$container.on("touchstart.fb.touch mousedown.fb.touch",n.proxy(e,"ontouchstart"))};d.prototype.destroy=function(){var t=this;t.$container.off(".fb.touch"),n(e).off(".fb.touch"),t.requestId&&(i(t.requestId),t.requestId=null),t.tapped&&(clearTimeout(t.tapped),t.tapped=null)},d.prototype.ontouchstart=function(o){var i=this,c=n(o.target),d=i.instance,u=d.current,f=u.$slide,p=u.$content,h="touchstart"==o.type;if(h&&i.$container.off("mousedown.fb.touch"),(!o.originalEvent||2!=o.originalEvent.button)&&f.length&&c.length&&!r(c)&&!r(c.parent())&&(c.is("img")||!(o.originalEvent.clientX>c[0].clientWidth+c.offset().left))){if(!u||d.isAnimating||u.$slide.hasClass("fancybox-animated"))return o.stopPropagation(),void o.preventDefault();i.realPoints=i.startPoints=a(o),i.startPoints.length&&(u.touch&&o.stopPropagation(),i.startEvent=o,i.canTap=!0,i.$target=c,i.$content=p,i.opts=u.opts.touch,i.isPanning=!1,i.isSwiping=!1,i.isZooming=!1,i.isScrolling=!1,i.canPan=d.canPan(),i.startTime=(new Date).getTime(),i.distanceX=i.distanceY=i.distance=0,i.canvasWidth=Math.round(f[0].clientWidth),i.canvasHeight=Math.round(f[0].clientHeight),i.contentLastPos=null,i.contentStartPos=n.fancybox.getTranslate(i.$content)||{top:0,left:0},i.sliderStartPos=n.fancybox.getTranslate(f),i.stagePos=n.fancybox.getTranslate(d.$refs.stage),i.sliderStartPos.top-=i.stagePos.top,i.sliderStartPos.left-=i.stagePos.left,i.contentStartPos.top-=i.stagePos.top,i.contentStartPos.left-=i.stagePos.left,n(e).off(".fb.touch").on(h?"touchend.fb.touch touchcancel.fb.touch":"mouseup.fb.touch mouseleave.fb.touch",n.proxy(i,"ontouchend")).on(h?"touchmove.fb.touch":"mousemove.fb.touch",n.proxy(i,"ontouchmove")),n.fancybox.isMobile&&e.addEventListener("scroll",i.onscroll,!0),((i.opts||i.canPan)&&(c.is(i.$stage)||i.$stage.find(c).length)||(c.is(".fancybox-image")&&o.preventDefault(),n.fancybox.isMobile&&c.parents(".fancybox-caption").length))&&(i.isScrollable=l(c)||l(c.parent()),n.fancybox.isMobile&&i.isScrollable||o.preventDefault(),(1===i.startPoints.length||u.hasError)&&(i.canPan?(n.fancybox.stop(i.$content),i.isPanning=!0):i.isSwiping=!0,i.$container.addClass("fancybox-is-grabbing")),2===i.startPoints.length&&"image"===u.type&&(u.isLoaded||u.$ghost)&&(i.canTap=!1,i.isSwiping=!1,i.isPanning=!1,i.isZooming=!0,n.fancybox.stop(i.$content),i.centerPointStartX=.5*(i.startPoints[0].x+i.startPoints[1].x)-n(t).scrollLeft(),i.centerPointStartY=.5*(i.startPoints[0].y+i.startPoints[1].y)-n(t).scrollTop(),i.percentageOfImageAtPinchPointX=(i.centerPointStartX-i.contentStartPos.left)/i.contentStartPos.width,i.percentageOfImageAtPinchPointY=(i.centerPointStartY-i.contentStartPos.top)/i.contentStartPos.height,i.startDistanceBetweenFingers=s(i.startPoints[0],i.startPoints[1]))))}},d.prototype.onscroll=function(t){var n=this;n.isScrolling=!0,e.removeEventListener("scroll",n.onscroll,!0)},d.prototype.ontouchmove=function(t){var e=this;return void 0!==t.originalEvent.buttons&&0===t.originalEvent.buttons?void e.ontouchend(t):e.isScrolling?void(e.canTap=!1):(e.newPoints=a(t),void((e.opts||e.canPan)&&e.newPoints.length&&e.newPoints.length&&(e.isSwiping&&!0===e.isSwiping||t.preventDefault(),e.distanceX=s(e.newPoints[0],e.startPoints[0],"x"),e.distanceY=s(e.newPoints[0],e.startPoints[0],"y"),e.distance=s(e.newPoints[0],e.startPoints[0]),e.distance>0&&(e.isSwiping?e.onSwipe(t):e.isPanning?e.onPan():e.isZooming&&e.onZoom()))))},d.prototype.onSwipe=function(e){var a,s=this,r=s.instance,c=s.isSwiping,l=s.sliderStartPos.left||0;if(!0!==c)"x"==c&&(s.distanceX>0&&(s.instance.group.length<2||0===s.instance.current.index&&!s.instance.current.opts.loop)?l+=Math.pow(s.distanceX,.8):s.distanceX<0&&(s.instance.group.length<2||s.instance.current.index===s.instance.group.length-1&&!s.instance.current.opts.loop)?l-=Math.pow(-s.distanceX,.8):l+=s.distanceX),s.sliderLastPos={top:"x"==c?0:s.sliderStartPos.top+s.distanceY,left:l},s.requestId&&(i(s.requestId),s.requestId=null),s.requestId=o(function(){s.sliderLastPos&&(n.each(s.instance.slides,function(t,e){var o=e.pos-s.instance.currPos;n.fancybox.setTranslate(e.$slide,{top:s.sliderLastPos.top,left:s.sliderLastPos.left+o*s.canvasWidth+o*e.opts.gutter})}),s.$container.addClass("fancybox-is-sliding"))});else if(Math.abs(s.distance)>10){if(s.canTap=!1,r.group.length<2&&s.opts.vertical?s.isSwiping="y":r.isDragging||!1===s.opts.vertical||"auto"===s.opts.vertical&&n(t).width()>800?s.isSwiping="x":(a=Math.abs(180*Math.atan2(s.distanceY,s.distanceX)/Math.PI),s.isSwiping=a>45&&a<135?"y":"x"),"y"===s.isSwiping&&n.fancybox.isMobile&&s.isScrollable)return void(s.isScrolling=!0);r.isDragging=s.isSwiping,s.startPoints=s.newPoints,n.each(r.slides,function(t,e){var o,i;n.fancybox.stop(e.$slide),o=n.fancybox.getTranslate(e.$slide),i=n.fancybox.getTranslate(r.$refs.stage),e.$slide.css({transform:"",opacity:"","transition-duration":""}).removeClass("fancybox-animated").removeClass(function(t,e){return(e.match(/(^|\s)fancybox-fx-\S+/g)||[]).join(" ")}),e.pos===r.current.pos&&(s.sliderStartPos.top=o.top-i.top,s.sliderStartPos.left=o.left-i.left),n.fancybox.setTranslate(e.$slide,{top:o.top-i.top,left:o.left-i.left})}),r.SlideShow&&r.SlideShow.isActive&&r.SlideShow.stop()}},d.prototype.onPan=function(){var t=this;if(s(t.newPoints[0],t.realPoints[0])<(n.fancybox.isMobile?10:5))return void(t.startPoints=t.newPoints);t.canTap=!1,t.contentLastPos=t.limitMovement(),t.requestId&&i(t.requestId),t.requestId=o(function(){n.fancybox.setTranslate(t.$content,t.contentLastPos)})},d.prototype.limitMovement=function(){var t,e,n,o,i,a,s=this,r=s.canvasWidth,c=s.canvasHeight,l=s.distanceX,d=s.distanceY,u=s.contentStartPos,f=u.left,p=u.top,h=u.width,g=u.height;return i=h>r?f+l:f,a=p+d,t=Math.max(0,.5*r-.5*h),e=Math.max(0,.5*c-.5*g),n=Math.min(r-h,.5*r-.5*h),o=Math.min(c-g,.5*c-.5*g),l>0&&i>t&&(i=t-1+Math.pow(-t+f+l,.8)||0),l<0&&i<n&&(i=n+1-Math.pow(n-f-l,.8)||0),d>0&&a>e&&(a=e-1+Math.pow(-e+p+d,.8)||0),d<0&&a<o&&(a=o+1-Math.pow(o-p-d,.8)||0),{top:a,left:i}},d.prototype.limitPosition=function(t,e,n,o){var i=this,a=i.canvasWidth,s=i.canvasHeight;return n>a?(t=t>0?0:t,t=t<a-n?a-n:t):t=Math.max(0,a/2-n/2),o>s?(e=e>0?0:e,e=e<s-o?s-o:e):e=Math.max(0,s/2-o/2),{top:e,left:t}},d.prototype.onZoom=function(){var e=this,a=e.contentStartPos,r=a.width,c=a.height,l=a.left,d=a.top,u=s(e.newPoints[0],e.newPoints[1]),f=u/e.startDistanceBetweenFingers,p=Math.floor(r*f),h=Math.floor(c*f),g=(r-p)*e.percentageOfImageAtPinchPointX,b=(c-h)*e.percentageOfImageAtPinchPointY,m=(e.newPoints[0].x+e.newPoints[1].x)/2-n(t).scrollLeft(),v=(e.newPoints[0].y+e.newPoints[1].y)/2-n(t).scrollTop(),y=m-e.centerPointStartX,x=v-e.centerPointStartY,w=l+(g+y),$=d+(b+x),S={top:$,left:w,scaleX:f,scaleY:f};e.canTap=!1,e.newWidth=p,e.newHeight=h,e.contentLastPos=S,e.requestId&&i(e.requestId),e.requestId=o(function(){n.fancybox.setTranslate(e.$content,e.contentLastPos)})},d.prototype.ontouchend=function(t){var o=this,s=o.isSwiping,r=o.isPanning,c=o.isZooming,l=o.isScrolling;if(o.endPoints=a(t),o.dMs=Math.max((new Date).getTime()-o.startTime,1),o.$container.removeClass("fancybox-is-grabbing"),n(e).off(".fb.touch"),e.removeEventListener("scroll",o.onscroll,!0),o.requestId&&(i(o.requestId),o.requestId=null),o.isSwiping=!1,o.isPanning=!1,o.isZooming=!1,o.isScrolling=!1,o.instance.isDragging=!1,o.canTap)return o.onTap(t);o.speed=100,o.velocityX=o.distanceX/o.dMs*.5,o.velocityY=o.distanceY/o.dMs*.5,r?o.endPanning():c?o.endZooming():o.endSwiping(s,l)},d.prototype.endSwiping=function(t,e){var o=this,i=!1,a=o.instance.group.length,s=Math.abs(o.distanceX),r="x"==t&&a>1&&(o.dMs>130&&s>10||s>50);o.sliderLastPos=null,"y"==t&&!e&&Math.abs(o.distanceY)>50?(n.fancybox.animate(o.instance.current.$slide,{top:o.sliderStartPos.top+o.distanceY+150*o.velocityY,opacity:0},200),i=o.instance.close(!0,250)):r&&o.distanceX>0?i=o.instance.previous(300):r&&o.distanceX<0&&(i=o.instance.next(300)),!1!==i||"x"!=t&&"y"!=t||o.instance.centerSlide(200),o.$container.removeClass("fancybox-is-sliding")},d.prototype.endPanning=function(){var t,e,o,i=this;i.contentLastPos&&(!1===i.opts.momentum||i.dMs>350?(t=i.contentLastPos.left,e=i.contentLastPos.top):(t=i.contentLastPos.left+500*i.velocityX,e=i.contentLastPos.top+500*i.velocityY),o=i.limitPosition(t,e,i.contentStartPos.width,i.contentStartPos.height),o.width=i.contentStartPos.width,o.height=i.contentStartPos.height,n.fancybox.animate(i.$content,o,366))},d.prototype.endZooming=function(){var t,e,o,i,a=this,s=a.instance.current,r=a.newWidth,c=a.newHeight;a.contentLastPos&&(t=a.contentLastPos.left,e=a.contentLastPos.top,i={top:e,left:t,width:r,height:c,scaleX:1,scaleY:1},n.fancybox.setTranslate(a.$content,i),r<a.canvasWidth&&c<a.canvasHeight?a.instance.scaleToFit(150):r>s.width||c>s.height?a.instance.scaleToActual(a.centerPointStartX,a.centerPointStartY,150):(o=a.limitPosition(t,e,r,c),n.fancybox.animate(a.$content,o,150)))},d.prototype.onTap=function(e){var o,i=this,s=n(e.target),r=i.instance,c=r.current,l=e&&a(e)||i.startPoints,d=l[0]?l[0].x-n(t).scrollLeft()-i.stagePos.left:0,u=l[0]?l[0].y-n(t).scrollTop()-i.stagePos.top:0,f=function(t){var o=c.opts[t];if(n.isFunction(o)&&(o=o.apply(r,[c,e])),o)switch(o){case"close":r.close(i.startEvent);break;case"toggleControls":r.toggleControls();break;case"next":r.next();break;case"nextOrClose":r.group.length>1?r.next():r.close(i.startEvent);break;case"zoom":"image"==c.type&&(c.isLoaded||c.$ghost)&&(r.canPan()?r.scaleToFit():r.isScaledDown()?r.scaleToActual(d,u):r.group.length<2&&r.close(i.startEvent))}};if((!e.originalEvent||2!=e.originalEvent.button)&&(s.is("img")||!(d>s[0].clientWidth+s.offset().left))){if(s.is(".fancybox-bg,.fancybox-inner,.fancybox-outer,.fancybox-container"))o="Outside";else if(s.is(".fancybox-slide"))o="Slide";else{if(!r.current.$content||!r.current.$content.find(s).addBack().filter(s).length)return;o="Content"}if(i.tapped){if(clearTimeout(i.tapped),i.tapped=null,Math.abs(d-i.tapX)>50||Math.abs(u-i.tapY)>50)return this;f("dblclick"+o)}else i.tapX=d,i.tapY=u,c.opts["dblclick"+o]&&c.opts["dblclick"+o]!==c.opts["click"+o]?i.tapped=setTimeout(function(){i.tapped=null,r.isAnimating||f("click"+o)},500):f("click"+o);return this}},n(e).on("onActivate.fb",function(t,e){e&&!e.Guestures&&(e.Guestures=new d(e))}).on("beforeClose.fb",function(t,e){e&&e.Guestures&&e.Guestures.destroy()})}(window,document,jQuery),function(t,e){"use strict";e.extend(!0,e.fancybox.defaults,{btnTpl:{slideShow:'<button data-fancybox-play class="fancybox-button fancybox-button--play" title="{{PLAY_START}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 5.4v13.2l11-6.6z"/></svg><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8.33 5.75h2.2v12.5h-2.2V5.75zm5.15 0h2.2v12.5h-2.2V5.75z"/></svg></button>'},slideShow:{autoStart:!1,speed:3e3,progress:!0}});var n=function(t){this.instance=t,this.init()};e.extend(n.prototype,{timer:null,isActive:!1,$button:null,init:function(){var t=this,n=t.instance,o=n.group[n.currIndex].opts.slideShow;t.$button=n.$refs.toolbar.find("[data-fancybox-play]").on("click",function(){t.toggle()}),n.group.length<2||!o?t.$button.hide():o.progress&&(t.$progress=e('<div class="fancybox-progress"></div>').appendTo(n.$refs.inner))},set:function(t){var n=this,o=n.instance,i=o.current;i&&(!0===t||i.opts.loop||o.currIndex<o.group.length-1)?n.isActive&&"video"!==i.contentType&&(n.$progress&&e.fancybox.animate(n.$progress.show(),{scaleX:1},i.opts.slideShow.speed),n.timer=setTimeout(function(){o.current.opts.loop||o.current.index!=o.group.length-1?o.next():o.jumpTo(0)},i.opts.slideShow.speed)):(n.stop(),o.idleSecondsCounter=0,o.showControls())},clear:function(){var t=this;clearTimeout(t.timer),t.timer=null,t.$progress&&t.$progress.removeAttr("style").hide()},start:function(){var t=this,e=t.instance.current;e&&(t.$button.attr("title",(e.opts.i18n[e.opts.lang]||e.opts.i18n.en).PLAY_STOP).removeClass("fancybox-button--play").addClass("fancybox-button--pause"),t.isActive=!0,e.isComplete&&t.set(!0),t.instance.trigger("onSlideShowChange",!0))},stop:function(){var t=this,e=t.instance.current;t.clear(),t.$button.attr("title",(e.opts.i18n[e.opts.lang]||e.opts.i18n.en).PLAY_START).removeClass("fancybox-button--pause").addClass("fancybox-button--play"),t.isActive=!1,t.instance.trigger("onSlideShowChange",!1),t.$progress&&t.$progress.removeAttr("style").hide()},toggle:function(){var t=this;t.isActive?t.stop():t.start()}}),e(t).on({"onInit.fb":function(t,e){e&&!e.SlideShow&&(e.SlideShow=new n(e))},"beforeShow.fb":function(t,e,n,o){var i=e&&e.SlideShow;o?i&&n.opts.slideShow.autoStart&&i.start():i&&i.isActive&&i.clear()},"afterShow.fb":function(t,e,n){var o=e&&e.SlideShow;o&&o.isActive&&o.set()},"afterKeydown.fb":function(n,o,i,a,s){var r=o&&o.SlideShow;!r||!i.opts.slideShow||80!==s&&32!==s||e(t.activeElement).is("button,a,input")||(a.preventDefault(),r.toggle())},"beforeClose.fb onDeactivate.fb":function(t,e){var n=e&&e.SlideShow;n&&n.stop()}}),e(t).on("visibilitychange",function(){var n=e.fancybox.getInstance(),o=n&&n.SlideShow;o&&o.isActive&&(t.hidden?o.clear():o.set())})}(document,jQuery),function(t,e){"use strict";var n=function(){for(var e=[["requestFullscreen","exitFullscreen","fullscreenElement","fullscreenEnabled","fullscreenchange","fullscreenerror"],["webkitRequestFullscreen","webkitExitFullscreen","webkitFullscreenElement","webkitFullscreenEnabled","webkitfullscreenchange","webkitfullscreenerror"],["webkitRequestFullScreen","webkitCancelFullScreen","webkitCurrentFullScreenElement","webkitCancelFullScreen","webkitfullscreenchange","webkitfullscreenerror"],["mozRequestFullScreen","mozCancelFullScreen","mozFullScreenElement","mozFullScreenEnabled","mozfullscreenchange","mozfullscreenerror"],["msRequestFullscreen","msExitFullscreen","msFullscreenElement","msFullscreenEnabled","MSFullscreenChange","MSFullscreenError"]],n={},o=0;o<e.length;o++){var i=e[o];if(i&&i[1]in t){for(var a=0;a<i.length;a++)n[e[0][a]]=i[a];return n}}return!1}();if(n){var o={request:function(e){e=e||t.documentElement,e[n.requestFullscreen](e.ALLOW_KEYBOARD_INPUT)},exit:function(){t[n.exitFullscreen]()},toggle:function(e){e=e||t.documentElement,this.isFullscreen()?this.exit():this.request(e)},isFullscreen:function(){return Boolean(t[n.fullscreenElement])},enabled:function(){return Boolean(t[n.fullscreenEnabled])}};e.extend(!0,e.fancybox.defaults,{btnTpl:{fullScreen:'<button data-fancybox-fullscreen class="fancybox-button fancybox-button--fsenter" title="{{FULL_SCREEN}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5 16h3v3h2v-5H5zm3-8H5v2h5V5H8zm6 11h2v-3h3v-2h-5zm2-11V5h-2v5h5V8z"/></svg></button>'},fullScreen:{autoStart:!1}}),e(t).on(n.fullscreenchange,function(){var t=o.isFullscreen(),n=e.fancybox.getInstance();n&&(n.current&&"image"===n.current.type&&n.isAnimating&&(n.isAnimating=!1,n.update(!0,!0,0),n.isComplete||n.complete()),n.trigger("onFullscreenChange",t),n.$refs.container.toggleClass("fancybox-is-fullscreen",t),n.$refs.toolbar.find("[data-fancybox-fullscreen]").toggleClass("fancybox-button--fsenter",!t).toggleClass("fancybox-button--fsexit",t))})}e(t).on({"onInit.fb":function(t,e){var i;if(!n)return void e.$refs.toolbar.find("[data-fancybox-fullscreen]").remove();e&&e.group[e.currIndex].opts.fullScreen?(i=e.$refs.container,i.on("click.fb-fullscreen","[data-fancybox-fullscreen]",function(t){t.stopPropagation(),t.preventDefault(),o.toggle()}),e.opts.fullScreen&&!0===e.opts.fullScreen.autoStart&&o.request(),e.FullScreen=o):e&&e.$refs.toolbar.find("[data-fancybox-fullscreen]").hide()},"afterKeydown.fb":function(t,e,n,o,i){e&&e.FullScreen&&70===i&&(o.preventDefault(),e.FullScreen.toggle())},"beforeClose.fb":function(t,e){e&&e.FullScreen&&e.$refs.container.hasClass("fancybox-is-fullscreen")&&o.exit()}})}(document,jQuery),function(t,e){"use strict";var n="fancybox-thumbs";e.fancybox.defaults=e.extend(!0,{btnTpl:{thumbs:'<button data-fancybox-thumbs class="fancybox-button fancybox-button--thumbs" title="{{THUMBS}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M14.59 14.59h3.76v3.76h-3.76v-3.76zm-4.47 0h3.76v3.76h-3.76v-3.76zm-4.47 0h3.76v3.76H5.65v-3.76zm8.94-4.47h3.76v3.76h-3.76v-3.76zm-4.47 0h3.76v3.76h-3.76v-3.76zm-4.47 0h3.76v3.76H5.65v-3.76zm8.94-4.47h3.76v3.76h-3.76V5.65zm-4.47 0h3.76v3.76h-3.76V5.65zm-4.47 0h3.76v3.76H5.65V5.65z"/></svg></button>'},thumbs:{autoStart:!1,hideOnClose:!0,parentEl:".fancybox-container",axis:"y"}},e.fancybox.defaults);var o=function(t){this.init(t)};e.extend(o.prototype,{$button:null,$grid:null,$list:null,isVisible:!1,isActive:!1,init:function(t){var e=this,n=t.group,o=0;e.instance=t,e.opts=n[t.currIndex].opts.thumbs,t.Thumbs=e,e.$button=t.$refs.toolbar.find("[data-fancybox-thumbs]");for(var i=0,a=n.length;i<a&&(n[i].thumb&&o++,!(o>1));i++);o>1&&e.opts?(e.$button.removeAttr("style").on("click",function(){e.toggle()}),e.isActive=!0):e.$button.hide()},create:function(){var t,o=this,i=o.instance,a=o.opts.parentEl,s=[];o.$grid||(o.$grid=e('<div class="'+n+" "+n+"-"+o.opts.axis+'"></div>').appendTo(i.$refs.container.find(a).addBack().filter(a)),o.$grid.on("click","a",function(){i.jumpTo(e(this).attr("data-index"))})),o.$list||(o.$list=e('<div class="'+n+'__list">').appendTo(o.$grid)),e.each(i.group,function(e,n){t=n.thumb,t||"image"!==n.type||(t=n.src),s.push('<a href="javascript:;" tabindex="0" data-index="'+e+'"'+(t&&t.length?' style="background-image:url('+t+')"':'class="fancybox-thumbs-missing"')+"></a>")}),o.$list[0].innerHTML=s.join(""),"x"===o.opts.axis&&o.$list.width(parseInt(o.$grid.css("padding-right"),10)+i.group.length*o.$list.children().eq(0).outerWidth(!0))},focus:function(t){var e,n,o=this,i=o.$list,a=o.$grid;o.instance.current&&(e=i.children().removeClass("fancybox-thumbs-active").filter('[data-index="'+o.instance.current.index+'"]').addClass("fancybox-thumbs-active"),n=e.position(),"y"===o.opts.axis&&(n.top<0||n.top>i.height()-e.outerHeight())?i.stop().animate({scrollTop:i.scrollTop()+n.top},t):"x"===o.opts.axis&&(n.left<a.scrollLeft()||n.left>a.scrollLeft()+(a.width()-e.outerWidth()))&&i.parent().stop().animate({scrollLeft:n.left},t))},update:function(){var t=this;t.instance.$refs.container.toggleClass("fancybox-show-thumbs",this.isVisible),t.isVisible?(t.$grid||t.create(),t.instance.trigger("onThumbsShow"),t.focus(0)):t.$grid&&t.instance.trigger("onThumbsHide"),t.instance.update()},hide:function(){this.isVisible=!1,this.update()},show:function(){this.isVisible=!0,this.update()},toggle:function(){this.isVisible=!this.isVisible,this.update()}}),e(t).on({"onInit.fb":function(t,e){var n;e&&!e.Thumbs&&(n=new o(e),n.isActive&&!0===n.opts.autoStart&&n.show())},"beforeShow.fb":function(t,e,n,o){var i=e&&e.Thumbs;i&&i.isVisible&&i.focus(o?0:250)},"afterKeydown.fb":function(t,e,n,o,i){var a=e&&e.Thumbs;a&&a.isActive&&71===i&&(o.preventDefault(),a.toggle())},"beforeClose.fb":function(t,e){var n=e&&e.Thumbs;n&&n.isVisible&&!1!==n.opts.hideOnClose&&n.$grid.hide()}})}(document,jQuery),function(t,e){"use strict";function n(t){var e={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;","`":"&#x60;","=":"&#x3D;"};return String(t).replace(/[&<>"'`=\/]/g,function(t){return e[t]})}e.extend(!0,e.fancybox.defaults,{btnTpl:{share:'<button data-fancybox-share class="fancybox-button fancybox-button--share" title="{{SHARE}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M2.55 19c1.4-8.4 9.1-9.8 11.9-9.8V5l7 7-7 6.3v-3.5c-2.8 0-10.5 2.1-11.9 4.2z"/></svg></button>'},share:{url:function(t,e){return!t.currentHash&&"inline"!==e.type&&"html"!==e.type&&(e.origSrc||e.src)||window.location},
tpl:'<div class="fancybox-share"><h1>{{SHARE}}</h1><p><a class="fancybox-share__button fancybox-share__button--fb" href="https://www.facebook.com/sharer/sharer.php?u={{url}}"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m287 456v-299c0-21 6-35 35-35h38v-63c-7-1-29-3-55-3-54 0-91 33-91 94v306m143-254h-205v72h196" /></svg><span>Facebook</span></a><a class="fancybox-share__button fancybox-share__button--tw" href="https://twitter.com/intent/tweet?url={{url}}&text={{descr}}"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m456 133c-14 7-31 11-47 13 17-10 30-27 37-46-15 10-34 16-52 20-61-62-157-7-141 75-68-3-129-35-169-85-22 37-11 86 26 109-13 0-26-4-37-9 0 39 28 72 65 80-12 3-25 4-37 2 10 33 41 57 77 57-42 30-77 38-122 34 170 111 378-32 359-208 16-11 30-25 41-42z" /></svg><span>Twitter</span></a><a class="fancybox-share__button fancybox-share__button--pt" href="https://www.pinterest.com/pin/create/button/?url={{url}}&description={{descr}}&media={{media}}"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m265 56c-109 0-164 78-164 144 0 39 15 74 47 87 5 2 10 0 12-5l4-19c2-6 1-8-3-13-9-11-15-25-15-45 0-58 43-110 113-110 62 0 96 38 96 88 0 67-30 122-73 122-24 0-42-19-36-44 6-29 20-60 20-81 0-19-10-35-31-35-25 0-44 26-44 60 0 21 7 36 7 36l-30 125c-8 37-1 83 0 87 0 3 4 4 5 2 2-3 32-39 42-75l16-64c8 16 31 29 56 29 74 0 124-67 124-157 0-69-58-132-146-132z" fill="#fff"/></svg><span>Pinterest</span></a></p><p><input class="fancybox-share__input" type="text" value="{{url_raw}}" onclick="select()" /></p></div>'}}),e(t).on("click","[data-fancybox-share]",function(){var t,o,i=e.fancybox.getInstance(),a=i.current||null;a&&("function"===e.type(a.opts.share.url)&&(t=a.opts.share.url.apply(a,[i,a])),o=a.opts.share.tpl.replace(/\{\{media\}\}/g,"image"===a.type?encodeURIComponent(a.src):"").replace(/\{\{url\}\}/g,encodeURIComponent(t)).replace(/\{\{url_raw\}\}/g,n(t)).replace(/\{\{descr\}\}/g,i.$caption?encodeURIComponent(i.$caption.text()):""),e.fancybox.open({src:i.translate(i,o),type:"html",opts:{touch:!1,animationEffect:!1,afterLoad:function(t,e){i.$refs.container.one("beforeClose.fb",function(){t.close(null,0)}),e.$content.find(".fancybox-share__button").click(function(){return window.open(this.href,"Share","width=550, height=450"),!1})},mobile:{autoFocus:!1}}}))})}(document,jQuery),function(t,e,n){"use strict";function o(){var e=t.location.hash.substr(1),n=e.split("-"),o=n.length>1&&/^\+?\d+$/.test(n[n.length-1])?parseInt(n.pop(-1),10)||1:1,i=n.join("-");return{hash:e,index:o<1?1:o,gallery:i}}function i(t){""!==t.gallery&&n("[data-fancybox='"+n.escapeSelector(t.gallery)+"']").eq(t.index-1).focus().trigger("click.fb-start")}function a(t){var e,n;return!!t&&(e=t.current?t.current.opts:t.opts,""!==(n=e.hash||(e.$orig?e.$orig.data("fancybox")||e.$orig.data("fancybox-trigger"):""))&&n)}n.escapeSelector||(n.escapeSelector=function(t){return(t+"").replace(/([\0-\x1f\x7f]|^-?\d)|^-$|[^\x80-\uFFFF\w-]/g,function(t,e){return e?"\0"===t?"�":t.slice(0,-1)+"\\"+t.charCodeAt(t.length-1).toString(16)+" ":"\\"+t})}),n(function(){!1!==n.fancybox.defaults.hash&&(n(e).on({"onInit.fb":function(t,e){var n,i;!1!==e.group[e.currIndex].opts.hash&&(n=o(),(i=a(e))&&n.gallery&&i==n.gallery&&(e.currIndex=n.index-1))},"beforeShow.fb":function(n,o,i,s){var r;i&&!1!==i.opts.hash&&(r=a(o))&&(o.currentHash=r+(o.group.length>1?"-"+(i.index+1):""),t.location.hash!=="#"+o.currentHash&&(s&&!o.origHash&&(o.origHash=t.location.hash),o.hashTimer&&clearTimeout(o.hashTimer),o.hashTimer=setTimeout(function(){"replaceState"in t.history?(t.history[s?"pushState":"replaceState"]({},e.title,t.location.pathname+t.location.search+"#"+o.currentHash),s&&(o.hasCreatedHistory=!0)):t.location.hash=o.currentHash,o.hashTimer=null},300)))},"beforeClose.fb":function(n,o,i){i&&!1!==i.opts.hash&&(clearTimeout(o.hashTimer),o.currentHash&&o.hasCreatedHistory?t.history.back():o.currentHash&&("replaceState"in t.history?t.history.replaceState({},e.title,t.location.pathname+t.location.search+(o.origHash||"")):t.location.hash=o.origHash),o.currentHash=null)}}),n(t).on("hashchange.fb",function(){var t=o(),e=null;n.each(n(".fancybox-container").get().reverse(),function(t,o){var i=n(o).data("FancyBox");if(i&&i.currentHash)return e=i,!1}),e?e.currentHash===t.gallery+"-"+t.index||1===t.index&&e.currentHash==t.gallery||(e.currentHash=null,e.close()):""!==t.gallery&&i(t)}),setTimeout(function(){n.fancybox.getInstance()||i(o())},50))})}(window,document,jQuery),function(t,e){"use strict";var n=(new Date).getTime();e(t).on({"onInit.fb":function(t,e,o){e.$refs.stage.on("mousewheel DOMMouseScroll wheel MozMousePixelScroll",function(t){var o=e.current,i=(new Date).getTime();e.group.length<2||!1===o.opts.wheel||"auto"===o.opts.wheel&&"image"!==o.type||(t.preventDefault(),t.stopPropagation(),o.$slide.hasClass("fancybox-animated")||(t=t.originalEvent||t,i-n<250||(n=i,e[(-t.deltaY||-t.deltaX||t.wheelDelta||-t.detail)<0?"next":"previous"]())))})}})}(document,jQuery);

/* jQuery fancybox lightbox */
/* -------------------------------------------------------------------- */
function mk_lightbox_init() {

  $(".mk-lightbox").fancybox({
    loop: true,
  });

}


(function($, window){
    'use strict';

    var scrollY = MK.val.scroll; 
    var dynamicHeight = MK.val.dynamicHeight;

    var $window = $(window);
    var $containers = $('.js-loop');

    $containers.each( pagination );

	$window.on( 'vc_reload', function() {
		$('.js-loop').each( pagination );
	} );

    function pagination() {
        var unique = Date.now();
        var $container = $(this);
        var $superContainer = $container.parent(); // should contain clearing so it stretches with floating children
        var $loadBtn = $container.siblings('.js-loadmore-holder').find('.js-loadmore-button');
        var $loadScroll = $('.js-load-more-scroll');
        var style = $container.data('pagination-style');
        var maxPages = $container.data('max-pages');
        var id = '#' + ($container.attr('id'));
        var ajaxLoader = new MK.utils.ajaxLoader(id);
        var isLoadBtn = (style === 2);
        var isInfiniteScroll = (style === 3); // add flag for last container
        var scrollCheckPoint = null;
        var isHandlerBinded = false;

        ajaxLoader.init();

        init();

        function init() {
            MK.utils.eventManager.subscribe('ajaxLoaded', onLoad);
            bindHandlers();
            if( isInfiniteScroll ) scrollCheckPoint = spyScrollCheckPoint();

			$window.on( 'vc_reload', function() {
				$window.off('scroll', handleScroll);
			} );
        }

        function bindHandlers() {
            if( isLoadBtn ) $loadBtn.on('click', handleClick);
            if( isInfiniteScroll ) $window.on('scroll', handleScroll); 
            isHandlerBinded = true;
        }

        function unbindHandlers() {
            if( isLoadBtn ) $loadBtn.off('click', handleClick);
            if( isInfiniteScroll ) $window.off('scroll', handleScroll);
            isHandlerBinded = false;
        }

        function handleClick(e) {
            e.preventDefault();
            if(!ajaxLoader.isLoading) loadMore();
        }

        function handleScroll() {
            if((scrollY() > scrollCheckPoint()) && !ajaxLoader.isLoading) loadMore();
        }

        function loadMore() {
            loadingIndicatorStart();
            var page = ajaxLoader.getData('paged');
            ajaxLoader.setData({paged: ++page});
            ajaxLoader.load(unique);
        }

        function onLoad(e, response) {
            if( typeof response !== 'undefined' && response.id === id) {
                // Checking found posts helps to fix all pagination styles 
                if( ajaxLoader.getData('found_posts') <= 0 && ajaxLoader.getData('paged') >= ajaxLoader.getData('maxPages')) loadingIndicatorHide();
                else loadingIndicatorShow();
                if(response.unique === unique) $container.append(response.content);
                loadingIndicatorStop();
            }
        }

        function loadingIndicatorStart() {
            if(isLoadBtn) $loadBtn.addClass('is-active');
            else if(isInfiniteScroll) MK.ui.loader.add('.js-load-more-scroll');

        }

        function loadingIndicatorStop() {
            if(isLoadBtn) $loadBtn.removeClass('is-active');
            else if(isInfiniteScroll) MK.ui.loader.remove('.js-load-more-scroll');
        }

        function loadingIndicatorShow() {
            if(isHandlerBinded) return;
            if(isLoadBtn) $loadBtn.show();
            else if(isInfiniteScroll) $loadScroll.show();
            bindHandlers();
        }

        function loadingIndicatorHide() {
            if(!isHandlerBinded) return;
            if(isLoadBtn) $loadBtn.hide();
            else if(isInfiniteScroll) $loadScroll.hide();
            unbindHandlers();
        }


        function spyScrollCheckPoint() {
            var containerO = 0;
            var containerH = dynamicHeight( $superContainer );
            var winH = dynamicHeight( window );
 
            var setVals = function() {
                containerO = $superContainer.offset().top;
            };

            setVals();
            $window.on('resize', function() { requestAnimationFrame(setVals); });

            return function() {
                return (containerH() + containerO) - (winH() * 2);
            };
        }
    }

})(jQuery, window);
(function($) {
  'use strict';

  var MK = window.MK || {};
  window.MK = MK;
  MK.component = window.MK.component || {};

	// Check if it's inside hidden parent
	// Cannot be position: fixed
	function isHidden(el) {
	    return (el.offsetParent === null);
	}

	MK.component.Masonry = function(el) {
		var $window = $(window);
		var $container = $(el);
		var config = $container.data( 'masonry-config' );
		var $masonryItems = $container.find(config.item);
		var cols = config.cols || 8;
		var $filterItems = null; // assign only when apply filter
    var wall = null;

        var init = function init() {
          MK.core.loadDependencies([ MK.core.path.plugins + 'freewall.js' ], onDepLoad);
        };

        var onDepLoad = function onDepLoad() {
          masonry();

        	// Events
	        $window.on('resize', onResize);
            MK.utils.eventManager.subscribe('ajaxLoaded', onPostAddition);
			MK.utils.eventManager.subscribe('staticFilter', resize);
        };

	    var masonry = function masonry() {
	    	// Quit for hidden elements for now.
        if(isHidden(el)) return;

	    	var newCols;
	    	if(window.matchMedia( '(max-width:600px)' ).matches) newCols = 2;
	    	else if(window.matchMedia( '(max-width:850px)' ).matches) newCols = 4;
	    	else newCols = cols;

        var colW = $container.width() / newCols;

        wall = new Freewall( config.container );

	        // We need to pass settings to a plugin via reset method. Strange but works fine.
			wall.reset({
				selector: config.item + ':not(.is-hidden)',
				gutterX: 0, // set default gutter to 0 and again - apply margins to item holders in css
				gutterY: 0,
				cellW: colW,
				cellH: colW
			});

	        wall.fillHoles();
          wall.fitWidth();

	        $masonryItems.each(function() {
	        	$(this).data('loaded', true);
	        });
        };


		// Clear attributes after the plugin. It's API method dosn't handle it properly
		var destroyContainer = function destroyContainer() {
			$container.removeAttr('style')
				 .removeData('wall-height')
				 .removeData('wall-width')
				 .removeData('min-width')
				 .removeData('total-col')
				 .removeData('total-row')
				 .removeAttr('data-wall-height')
				 .removeAttr('data-wall-width')
				 .removeAttr('data-min-width')
				 .removeAttr('data-total-col')
				 .removeAttr('data-total-row');
		};

		var destroyItem = function destroyItem() {
			var $item = $(this);
			$item.removeAttr('style')
				 .removeData('delay')
				 .removeData('height')
				 .removeData('width')
				 .removeData('state')
				 .removeAttr('data-delay')
				 .removeAttr('data-height')
				 .removeAttr('data-width')
				 .removeAttr('data-state');
		};

		var destroyAll = function destroyAll() {
	    	if( !wall ) return;
    		wall.destroy(); // API destroy
    		destroyContainer();
    		$masonryItems.each( destroyItem ); // run our deeper destroy
		};

		var onResize = function onResize() {
			requestAnimationFrame(resize);
		};

        var refresh = function refresh() {
	    	if( !wall ) return;
	    	setTimeout(wall.fitWidth.bind(wall), 5);
        };

        var resize = function resize() {
        	destroyAll();
	    	masonry();
        };

        var onPostAddition = function onPostAddition() {
        	$masonryItems = $container.find(config.item);

        	$masonryItems.each(function() {
        		var $item = $(this),
        			isLoaded = $item.data('loaded');

        		if(!isLoaded) $item.css('visibility', 'hidden');
        	});


        	$container.mk_imagesLoaded().then(function() {
        		destroyAll();
        		masonry();
        	});
        };

        return {
         	init : init
        };
	};

}(jQuery));

/* Milestone Number Shortcode */
/* -------------------------------------------------------------------- */

function mk_milestone() {

  "use strict";

  if( !$.exists('.mk-milestone') ) return;

  $('.mk-milestone').each(function () {
    var $this = $(this),
      stop_number = $this.find('.milestone-number').attr('data-stop'),
      animation_speed = parseInt($this.find('.milestone-number').attr('data-speed'));

    var build = function() {
      if (!$this.hasClass('scroll-animated')) {
        $this.addClass('scroll-animated');

        $({
          countNum: $this.find('.milestone-number').text()
        }).animate({
          countNum: stop_number
        }, {
          duration: animation_speed,
          easing: 'linear',
          step: function () {
            $this.find('.milestone-number').text(Math.floor(this.countNum));
          },
          complete: function () {
            $this.find('.milestone-number').text(this.countNum);
          }
        });
      }
    };

    if ( !MK.utils.isMobile() ) {
      // refactored only :in-viewport logic. rest is to-do
      MK.utils.scrollSpy( this, {
          position: 'bottom',
          after: build
      });
    } else {
      build();
    }

  });

}




(function($) {
	'use strict';

	MK.component.Pagination = function(el) {
		this.el = el;
	};

	MK.component.Pagination.prototype = {
		init: function init() {
			this.cacheElements();
			this.bindEvents();
			this.onInitLoad();
		},

		cacheElements: function cacheElements() {
			this.lastId = 1;
			this.unique = Date.now();
			this.$pagination = $(this.el);
			this.$container = this.$pagination.prev('.js-loop');
			this.$pageLinks = this.$pagination.find('.js-pagination-page');
			this.$nextLink = this.$pagination.find('.js-pagination-next');
			this.$prevLink = this.$pagination.find('.js-pagination-prev');
			this.$current = this.$pagination.find('.js-current-page');
			this.$maxPages = this.$pagination.find('.pagination-max-pages'); // TODO change in DOM and here to js class
			this.containerId = '#' + this.$container.attr('id');
			this.pagePathname = window.location.pathname;
			this.pageSearch = window.location.search;
			this.popState = false;
			this.ajaxLoader = new MK.utils.ajaxLoader('#' + this.$container.attr('id'));
			this.ajaxLoader.init();
		},

		bindEvents: function bindEvents() {
			this.$pageLinks.on('click', this.pageClick.bind(this));
			this.$nextLink.on('click', this.nextClick.bind(this));
			this.$prevLink.on('click', this.prevClick.bind(this));
			MK.utils.eventManager.subscribe('ajaxLoaded', this.onLoad.bind(this));
		},

		pageClick: function pageClick(e) {
			e.preventDefault();
			var $this = $(e.currentTarget);
			var id = parseFloat($this.attr('data-page-id'));

			if(id > this.ajaxLoader.getData('maxPages') || id < 1) return;
			this.load(id, $this);
			this.updatePagedNumUrl( id );
		},

		nextClick: function nextClick(e) {
			e.preventDefault();
			if(this.ajaxLoader.getData('paged') === this.ajaxLoader.getData('maxPages')) return;
			this.load(++this.lastId, $(e.currentTarget));
			this.updatePagedNumUrl( this.lastId );
		},

		prevClick: function prevClick(e) {
			e.preventDefault();
			if(this.ajaxLoader.getData('paged') === 1) return;
			this.load(--this.lastId, $(e.currentTarget));
			this.updatePagedNumUrl( this.lastId );
		},

		load: function load(id, $el) {
			this.lastId = id;
			this.ajaxLoader.setData({paged: id});
			this.ajaxLoader.load(this.unique);
			this.removeIndicator();
			MK.ui.loader.add($el);
		},

    onLoad: function success(e, response) {
      if (typeof response !== 'undefined' && response.id === this.containerId) {
        this.updatePagination();
        this.lastId = this.ajaxLoader.getData('paged');

        if (response.unique === this.unique) {
          this.removeIndicator();
          this.scrollPage();
          this.$container.html(response.content);
        }
      }
    },

        updatePagination: function updatePagination() {
        	var self = this;

        	// Hide / show arrows
        	var isFirst = (this.ajaxLoader.getData('paged') === 1);
        	var isLast = (this.ajaxLoader.getData('paged') === this.ajaxLoader.getData('maxPages'));

        	if(isFirst) this.$prevLink.addClass('is-vis-hidden');
        	else this.$prevLink.removeClass('is-vis-hidden');

        	if(isLast) this.$nextLink.addClass('is-vis-hidden');
        	else this.$nextLink.removeClass('is-vis-hidden');

			// X of Y
			this.$current.html(this.ajaxLoader.getData('paged'));
			this.$maxPages.html(this.ajaxLoader.getData('maxPages'));

			// Move overfloating items
			var displayItems = 10;
			var centerAt = displayItems / 2;

			if(this.ajaxLoader.getData('maxPages') > displayItems) {
				this.$pageLinks.each(function(i) {

					var id = self.lastId - centerAt;
						id = Math.max(id, 1);
						id = Math.min(id, self.ajaxLoader.getData('maxPages') - displayItems + 1);
						id = id + i;

					$(this).html( id ).attr('data-page-id', id).show();

					if(i === 0 && id > 1) $(this).html('...');
					if(i === displayItems - 1 && id < self.ajaxLoader.getData('maxPages')) $(this).html('...');
				});
			} else {
				this.$pageLinks.each(function(i) {
					var $link = $(this);
					var id = i + 1;

					$link.html(id).attr('data-page-id', id);

					if( self.ajaxLoader.getData('maxPages') === 1) {
						self.$pageLinks.hide();
					} else {
						if(i > self.ajaxLoader.getData('maxPages') - 1) $link.hide();
						else $link.show();
					}

				});
			}

        	// Highlight current only
			this.$pageLinks.filter('[data-page-id="' + this.ajaxLoader.getData('paged') + '"]' ).addClass('current-page')
				 .siblings().removeClass('current-page');

        },

    scrollPage: function scrollPage() {
      var containerOffset = this.$container.offset().top;
      var offset = containerOffset - MK.val.offsetHeaderHeight( containerOffset ) - 20;

      this.$container.find('a:first').focus()
      MK.utils.scrollTo( offset );
    },

    removeIndicator: function removeIndicator() {
      MK.ui.loader.remove('.js-pagination-page, .js-pagination-next, .js-pagination-prev');
    },

		/**
		 * Set some actions when archive/category page is loaded. Actions list:
		 * - Select the correct paged ID on pagination list.
		 * - Set current paged ID on the label.
		 * - Add event listener onpopstate for handling prev/next button of Browser URL.
		 * - Set info for updatePagedNumUrl() about request comes from popstate.
		 *
		 * @since 5.9.8
		 */
		onInitLoad: function onInitLoad() {
			var initPagedID = this.$pagination.data( 'init-pagination' );
			if ( initPagedID && initPagedID > 1 ) {
				this.$current.html( initPagedID );
				this.$pageLinks.filter( '[data-page-id="' + initPagedID + '"]' ).addClass( 'current-page' ).siblings().removeClass( 'current-page' );
			}

			// Run popstate only if it's supported by the browser.
			if ( 'onpopstate' in window ) {
				var thisPop = this;
				window.onpopstate = function( event ) {
					var id = 1;

					// At start, state is always null. So, we should check it before processing.
					if ( typeof event.state === 'object' && event.state ) {
						var state = event.state;

						// Set paged ID for updating page.
						if ( state.hasOwnProperty( 'MkPagination' ) ) {
							var currentState = state.MkPagination;
							if ( currentState.hasOwnProperty( 'paged' ) ) {
								id = parseFloat( currentState.paged );
							}
						}
					} else {
						id = parseFloat( thisPop.getURLPagedID() );
					}

					// Tell updatePagedNumUrl() if request come from popstate.
					thisPop.popState = true;
					thisPop.$pageLinks.filter( '[data-page-id="' + id + '"]' ).trigger( 'click' );
				}
			}
		},

		/**
		 * Update current pagination browser URL by adding/changing paged number. Only run if
		 * the browser support pushState and the request not coming from popstate.
		 *
		 * WordPress has some ways to set paged number:
		 * 1. page/[number], paged=[number] will be directed here.
		 * 2. page=[number]
		 * So, we should check which one the request is used here.
		 *
		 * @since 5.9.8
		 */
		updatePagedNumUrl: function updatePagedNumUrl( id ) {
			// Check pushState browser support and ignore if request come from popstate.
			if ( 'history' in window && 'pushState' in history && id && ! this.popState ) {
				var fullPage = this.pagePathname + this.pageSearch;
				var isQueryPage = false;

				// Style 1 - /page/[number], as default value.
				var newPage = 'page/' + id + '/';
				var expPage = /page\/\d+\/?/;
				var result = this.pagePathname.match( /\/page\/\d+/ );
				var isPagedExist = ( result ) ? true : false;

				// Style 2 - ?page=[number], only run if /page/ is not exist and URL query var exist.
				if ( ! isPagedExist && this.pageSearch ) {
					isQueryPage = this.pageSearch.match( /page\=\d+/ );
					if ( isQueryPage ) {
						newPage = 'page=' + id;
						expPage = /page\=\d+/;
					}
				}

				// If page number is 1, remove paged number from URL.
				if ( id === 1 ) {
					newPage = '';
					if ( isQueryPage ) {
						expPage = ( this.pageSearch.match( /\&+/ ) ) ? /page\=\d+\&?/ : /\?page\=\d+\&?/;
					}
				}

				// Set new pathname. Do replacement only if the new pathname contains paged number.
				var newURL = this.pagePathname + newPage + this.pageSearch;
				if ( fullPage.match( expPage ) ) {
					newURL = fullPage.replace( expPage, newPage );
				}

				// Set history state and return popstate back to false.
				var historyState = {
					MkPagination: {
						url: newURL,
						paged: id
					}
				}
				this.popState = false;

				// Push new pathname to display/hide the paged number.
				window.history.pushState( historyState, null, newURL );
			}
			this.popState = false;
		},

		/**
		 * Get current URL page ID. Notes:
		 * 1. page/[number], paged=[number] will be directed here.
		 * 2. page=[number]
		 *
		 * @return {integer} Current paged ID. Default 1.
		 */
		getURLPagedID: function getURLPagedID() {
			var pathname = window.location.pathname;
			var search = window.location.search;
			var pagedId = 1;
			var result = '';
			var isPagedExist = false;

			// Search based on style 1.
			result = pathname.match( /\/page\/(\d+)/ );
			if ( result ) {
				isPagedExist = true;
				pagedId = ( result.hasOwnProperty( 1 ) ) ? result[1] : 1;
			}

			// Search based on style 2.
			if ( ! isPagedExist && search ) {
				result = search.match( /page\=(\d+)/ );
				if ( result ) {
					isPagedExist = true;
					pagedId = ( result.hasOwnProperty( 1 ) ) ? result[1] : 1;
				}
			}

			return pagedId;
		}
	};

}(jQuery));

(function( $ ) {
	'use strict';

  var MK = window.MK || {};
  window.MK = MK;
  MK.component = window.MK.component || {};

  var val = MK.val,
    utils = MK.utils;

	MK.component.Parallax = function( el ) {
		var self = this,
			$this = $( el ),
        	obj = $this[0],
			$window = $( window ),
		    container = $('.jupiterx-main')[0],
			config = $this.data( 'parallax-config' ),
			$holder = $( config.holder ),
			headerHeight = null,
			offset = null,
			elHeight = null,
			ticking = false,
			isMobile = null;


		var clientRect = null;

		var update = function() {
			// Clear styles to check for natural styles
			// then apply position and size
			obj.style.transform = null;
			obj.style.top = null;
			obj.style.bottom = null;

			isMobile = MK.utils.isMobile();

			if( isMobile ) {
        		$this.css( 'height', '' );
				return;
			}

			clientRect = $this[ 0 ].getBoundingClientRect();
			offset = clientRect.top;
			elHeight = clientRect.height;
			// headerHeight = val.offsetHeaderHeight( offset );
			headerHeight = 150;
      offset = offset - headerHeight + val.scroll();

			setPosition();
			setSize( );
		};


        var h = 0,
        	winH = 0,
        	proportion = 0,
        	height = 0;

        // Position and background attachement should me moved to CSS but we repair it high specificity here as styles are not reliable currently
        var setSize = function() {
        	$this.css( 'height', '' );
        	winH = $window.height() - headerHeight;
        	h = obj.getBoundingClientRect().height;

        	if( config.speed <= 1 && config.speed > 0 ) {
        		if( offset === 0 ) {
	        		$this.css({
	        			backgroundAttachment: 'scroll',
	        			'will-change': 'transform'
	        		});
        		} else {
	        		$this.css({
						height : h + ( (winH - h) * config.speed ),
	        			backgroundAttachment: 'scroll',
	        			'will-change': 'transform'
	        		});
	        	}

        	} else if ( config.speed > 1 && h <= winH ) {
        		$this.css({
        			// good for full heights - 2 because it's viewable by 2 screen heights
        			height: ( winH  +  ( ( winH * config.speed ) - winH ) * 2 ),
        			top: -( ( winH * config.speed ) - winH ),
        			backgroundAttachment: 'scroll',
        			'will-change': 'transform'
        		});

        	} else if ( config.speed > 1 && h > winH ) {
        		proportion = h / winH;
        		height = ( winH  +  ( ( winH * config.speed ) - winH ) * (1 + proportion) );

        		$this.css({
        			height: height,
        			top: -( height - (winH * config.speed) ),
        			backgroundAttachment: 'scroll',
        			'will-change': 'transform'
        		});

        	} else if ( config.speed < 0 && h >= winH ) {
        		height = h * (1  - config.speed);
        		$this.css({
					height: height + (height - h),
        			top: h - height,
        			backgroundAttachment: 'scroll',
        			'will-change': 'transform'
        		});

        	} else if ( config.speed < 0 && h < winH ) {
        		// candidate to change
        		var display = (winH + h) / winH;
        		height = h * -config.speed * display;
        		$this.css({
					height: h + (height * 2),
        			top: -height,
        			backgroundAttachment: 'scroll',
        			'will-change': 'transform'
        		});
        	}
        };


		var currentPoint = null,
			progressVal = null,
			startPoint = null,
			endPoint = null,
			$opacityLayer = config.opacity ? $this.find( config.opacity ) : null,
			scrollY = null;

		var setPosition = function() {
			startPoint = offset - winH;
			endPoint = offset + elHeight + winH - headerHeight;
			scrollY = val.scroll();

			if( scrollY < startPoint || scrollY > endPoint ) {
				ticking = false;
				return;
			}

			currentPoint = (( -offset + scrollY ) * config.speed);

            $this.css({
              	'-webkit-transform': 'translateY(' + currentPoint + 'px) translateZ(0)',
              	'-moz-transform': 'translateY(' + currentPoint + 'px) translateZ(0)',
              	'-ms-transform': 'translateY(' + currentPoint + 'px) translateZ(0)',
              	'-o-transform': 'translateY(' + currentPoint + 'px) translateZ(0)',
              	'transform': 'translateY(' + currentPoint + 'px) translateZ(0)'
            });

			ticking = false;
		};


		var requestTick = function() {
			if( !ticking && !isMobile ) {
				ticking = true;
				window.requestAnimationFrame( setPosition );
			}
		};


		var init = function() {
			update();
			setTimeout(update, 100);
			$window.on( 'load', update );
			$window.on( 'resize', update );
	        window.addResizeListener( container, update );

			$window.on( 'scroll', requestTick );
		};


		return {
			init : init
		};
	};

})( jQuery );

(function($) {
	'use strict';

  var MK = window.MK || {};
  window.MK = MK;
  MK.component = window.MK.component || {};
  MK.ui = window.MK.ui || {};

	MK.component.Preloader = function(el) {
		this.el = el;
	};

	MK.component.Preloader.prototype = {
		init: function init() {
			this.cacheElements();
			this.bindEvents();
		},

		cacheElements: function cacheElements() {
			this.$preloader = $(this.el);
		},

		bindEvents: function bindEvents() {
			this.onLoad(); // all components inited on page load
		},

		onLoad: function onLoad() {
			setTimeout(this.hidePreloader.bind(this), 300);
		},

		hidePreloader: function hidePreloader() {
			this.$preloader.hide();
		}
	};

}(jQuery));

(function($) {
	'use strict';

	// Image added for proportional scaling
	MK.ui.loader = {
		tpl : function() {
			return '<div class="mk-loading-indicator">' +
						'<div class="mk-loading-indicator__inner">' +
							'<div class="mk-loading-indicator__icon"></div>' +
							'<img style="height:100%; width:auto;" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">' +
						'</div>' +
					'</div>';
		},

		add : function(item) {
			$(item).append(this.tpl);
		},

		remove : function(item) {
			if(item) $(item).find('.mk-loading-indicator').remove();
			else $('.mk-loading-indicator').remove();
		}
	};

}(jQuery));

var MK = window.MK || {};
window.MK = MK;
MK.component = window.MK.component || {};

MK.component.ResponsiveImageSetter = (function ($) {

	'use strict';

	var module = {};


	/*---------------------------------------------------------------------------------*/
	/* Private Variables
	/*---------------------------------------------------------------------------------*/

	var viewportClass = getViewportClass();
	var isRetina = window.devicePixelRatio >= 2;


	/*---------------------------------------------------------------------------------*/
	/* Private Methods
	/*---------------------------------------------------------------------------------*/

	function run($imgs) {
		$imgs.filter( function() {
			return !this.hasAttribute("mk-img-src-setted");
		}).each(setSrcAttr);
	}

	function setSrcAttr() {
		var $img = $(this);
		var set = $img.data('mk-image-src-set');
		// Set src attribute to img link suitable for our logic. It will load the image.
		if(set['responsive'] === 'false' && isRetina && set['2x']) $img.attr('src', set['2x']);
		else if(set['responsive'] === 'false') $img.attr('src', set.default);
		else if(viewportClass === 1 && isRetina && set['2x']) $img.attr('src', set['2x']); // default x2 for retina
		else if(viewportClass === 0 && set.mobile) $img.attr('src', set.mobile);
    else $img.attr('src', set.default);

    $img.load(function() {
      $(window).trigger('mk-image-loaded')
    })
	}

	function getViewportClass() {
		if(window.matchMedia('(max-width: 736px)').matches) return 0;
		else return 1;
	}

	function handleResize($imgs) {
		if(!$imgs.length) return; // Do not run if empty collection
		var currentViewportClass = getViewportClass();
		// We don't need to reload bigger images when screen size is decreasing as browser already performs resize operation.
		// Run update on for increasing screen size
		if( currentViewportClass > viewportClass) {
			viewportClass = currentViewportClass; // update for further reference
			run($imgs);
		}
	}


	/*---------------------------------------------------------------------------------*/
	/* Public Methods
	/*---------------------------------------------------------------------------------*/

	module.init = function ($imgs) {

		// Do not run if empty collection
		if(!$imgs.length) return;

		// Run and bind to events
		run($imgs);
	    $imgs.attr('mk-img-src-setted', '');

	};

	module.onResize = function ($imgs) {

		$(window).on( 'resize', MK.utils.throttle( 500, function() {
			handleResize($imgs);
		}));


	};


	module.handleAjax = function () {
    	setTimeout(function ajaxDelayedCallback() { // give it a chance to insert content first
	    	var $newImgs = $('img[data-mk-image-src-set]').filter( function() {
				return !this.hasAttribute("mk-lazyload");
			});
			if(!$newImgs.length) return;
	    	run($newImgs);
    	}, 100);
    }


	return module;

}(jQuery));


jQuery(function($) {

	var init = function init() {
		// Get All Responsive Images
		var $allImages = $('img[data-mk-image-src-set]').filter(function(index) {
			var isNotPortfolioImage = !$(this).hasClass('portfolio-image'),
				isNotBlogImage = $(this).closest('.mk-blog-container').length == 0,
				isNotSwiperImage = !$(this).hasClass('swiper-slide-image'),
				isNotGalleryImage = !$(this).hasClass('mk-gallery-image');
			return isNotPortfolioImage && isNotBlogImage && isNotSwiperImage && isNotGalleryImage;
		});;

		// Handle the resize
		MK.component.ResponsiveImageSetter.onResize($allImages);

		MK.component.ResponsiveImageSetter.init($allImages);

		MK.utils.eventManager.subscribe('ajaxLoaded', MK.component.ResponsiveImageSetter.handleAjax ); // ajax loops
		MK.utils.eventManager.subscribe('ajax-preview', MK.component.ResponsiveImageSetter.handleAjax ); // ajax portfolio
		MK.utils.eventManager.subscribe('quickViewOpen', MK.component.ResponsiveImageSetter.handleAjax );
	}
	init();
	$(window).on('vc_reload', init);

});



(function( $ ) {
	'use strict';

  var MK = window.MK || {};
  window.MK = MK;
  MK.utils = window.MK.utils || {};
  MK.val = window.MK.val || {};

	/**
	 * Keep track of top Level sections so we can easly skip to next one.
	 * We must be explicit about DOM level to nested sections.
	 * The list of sections is static. If you'd need to refreh it on ajax etc do it with pub/sub (not really needed now).
	 * We keep track for the same sections in Footer for mutating window location with '!loading' to prevent native anchor behaviour.
	 */
	var $topLevelSections = $('.jupiterx-main > .vc_row, .jupiterx-main > .mk-main-wrapper-holder, .jupiterx-main > .mk-page-section');

  $( document ).on( 'click', '.mk-skip-to-next', function() {
    var $this = $( this ),

    /**
     * Static height of button + the space to the bottom of the container.
     *
     * @TODO Possible to calculate dynamically.
     */
    btnHeight = $this.hasClass( 'edge-skip-slider' ) ? 150 : 76,
    offset = $this.offset().top + btnHeight,
    nextOffset = MK.utils.nextHigherVal( MK.utils.offsets( $topLevelSections ), [offset] );

    MK.utils.scrollTo( nextOffset  );
  });

})( jQuery );


(function($) {

    'use strict';

    var SkillDiagram = function( el ) {
        this.el = el;
    }

    SkillDiagram.prototype = {
        init : function() {
            this.cacheElements();
            this.createDiagram();
            this.$skills.each( this.createSkill.bind( this ) );
        },

        cacheElements : function() {
            this.$el = $( this.el );
            this.$skills = this.$el.find( '.mk-meter-arch');
            this.config  = this.$el.data();
            this.config.radius = this.config.dimension / 2;
        },

        random : function( l, u ) {
            return Math.floor( ( Math.random() * ( u - l + 1 ) ) + l );
        },

        createDiagram : function() {
            var self = this;
            $(this.el).find('svg').remove();

            this.diagram = Raphael( this.el, this.config.dimension, this.config.dimension );

            // Make svg scalable in different screen sizes
            this.diagram.setViewBox(0,0,this.config.dimension,this.config.dimension,true);
            this.diagram.setSize('90%', '90%');

            this.diagram.circle( this.config.radius, this.config.radius, 80 ).attr({
                stroke: 'none',
                fill: this.config.circleColor
            });

            // Export title
            this.title = this.diagram.text( this.config.radius, this.config.radius, this.config.defaultText ).attr({
                font: "22px helvetica",
                fill: this.config.defaultTextColor
            }).toFront();

            this.diagram.customAttributes.arc = function(value, color, rad){
                var v = 3.6 * value,
                    alpha = v == 360 ? 359.99 : v,
                    r  = self.random( 91, 240 ),
                    a  = (r - alpha) * Math.PI/180,
                    b  = r * Math.PI/180,
                    sx = self.config.radius + rad * Math.cos(b),
                    sy = self.config.radius - rad * Math.sin(b),
                    x  = self.config.radius + rad * Math.cos(a),
                    y  = self.config.radius - rad * Math.sin(a),
                    path = [['M', sx, sy], ['A', rad, rad, 0, +(alpha > 180), 1, x, y]];

                return {
                    path: path,
                    stroke: color
                }
            }
        },

        createSkill : function( id, el ) {
            var self   = this,
                $this  = $( el ),
                config = $this.data(),
                radMin = 72,
                radVal = 27,
                newRad = radMin + ( radVal * (id + 1) );

            var $path = this.diagram.path().attr({
                'stroke-width': 28,
                arc: [config.percent, config.color, newRad]
            });

            $path.mouseover( function() {
                self.showSkill( this, config.name, config.percent );
            }).mouseout( function() {
                self.hideSkill( this )
            });
        },

        showSkill : function( self, name, percent ) {
            var $this = self,
                time = 250;

            //solves IE problem
            if(Raphael.type != 'VML') $this.toFront();

            $this.animate({
                'stroke-width': 50,
                'opacity': 0.9,
            }, 800, 'elastic' );

            this.title.stop()
                .animate({ opacity: 0 }, time, '>', function(){
                    this.attr({ text: name + '\n' + percent + '%' }).animate({ opacity: 1 }, time, '<');
                }).toFront();
        },

        hideSkill : function( self ) {
            var $this = self,
                self = this,
                time = 250;

            $this.stop().animate({
                'stroke-width': 28,
                opacity: 1
            }, time * 4, 'elastic' );

            self.title.stop()
                .animate({ opacity: 0 }, time, '>', function(){
                    self.title.attr({ text: self.config.defaultText })
                    .animate({ opacity: 1 }, time, '<');
                });
        }
    }

    var init = function init() {
        if( typeof Raphael === 'undefined' ) return;
        $( '.mk-skill-diagram' ).each( function() {
            var diagram = new SkillDiagram( this );
                diagram.init();
        });
    }

    init();
    $(window).on('vc_reload', init);

})(jQuery);

/* Skill Meter and Charts */
/* -------------------------------------------------------------------- */
function mk_skill_meter() {
  "use strict";
  if ($.exists('.mk-skill-meter')) {
        if (!MK.utils.isMobile()) {
            $(".mk-skill-meter .progress-outer").each(function() {
                var $this = $(this);

                var build = function() {
                    if (!$this.hasClass('scroll-animated')) {
                        $this.addClass('scroll-animated');
                        $this.animate({
                            width: $this.attr("data-width") + '%'
                        }, 2000);
                    }
                };

                MK.utils.scrollSpy( this, {
                    position: 'bottom',
                    after: build
                });
            });
        } else {
            $(".mk-skill-meter .progress-outer").each(function() {
                var $this = $(this);
                if (!$this.hasClass('scroll-animated')) {
                    $this.addClass('scroll-animated');
                    $this.css({
                        width: $(this).attr("data-width") + '%'
                    });
                }
            });
        }
    }
}

// function mk_charts() {
//     "use strict";

//     if( !$.exists('.mk-chart') ) return;

//     MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.easyPieChart.js' ], function() {

//         $('.mk-chart').each(function() {

//             var $this = $(this),
//                 $parent_width = $(this).parent().width(),
//                 $chart_size = parseInt($this.attr('data-barSize'));

//             if ($parent_width < $chart_size) {
//                 $chart_size = $parent_width;
//                 $this.css('line-height', $chart_size);
//                 $this.find('i').css({
//                     'line-height': $chart_size + 'px'
//                 });
//                 $this.css({
//                     'line-height': $chart_size + 'px'
//                 });
//             }

//             var build = function() {
//                 $this.easyPieChart({
//                     animate: 1300,
//                     lineCap: 'butt',
//                     lineWidth: $this.attr('data-lineWidth'),
//                     size: $chart_size,
//                     barColor: $this.attr('data-barColor'),
//                     trackColor: $this.attr('data-trackColor'),
//                     scaleColor: 'transparent',
//                     onStep: function(value) {
//                         this.$el.find('.chart-percent span').text(Math.ceil(value));
//                     }
//                 });
//             };

//             // refactored only :in-viewport logic. rest is to-do
//             MK.utils.scrollSpy( this, {
//                 position: 'bottom',
//                 after: build
//             });


//         });
//     });
// }

(function($) {
  'use strict';

  var MK = window.MK || {};
  window.MK = MK;
  MK.ui = window.MK.ui || {};

	//
	// Constructor
	//
	// /////////////////////////////////////////////////////////

	MK.ui.Slider = function( container, config ) {

		var defaults = {
				slide 				: '.mk-slider-slide',
	            nav 	     		: '.mk-slider-nav',
                effect              : 'roulete',
                ease 				: 'easeOutQuart', // should not be changed, remove
                slidesPerView       : 1,
                slidesToView        : 1,
                transitionTime      : 700,
                displayTime         : 3000,
                autoplay            : true,
                hasNav              : true,
                hasPagination       : true,
                paginationTpl 		: '<span></span>',
                paginationEl 		: '#pagination',
                draggable           : true,
                fluidHeight 		: false,
                pauseOnHover		: false,
                lazyload			: false,
                activeClass 		: 'is-active',
                edgeSlider	 		: false,
                spinnerTpl 			: '<div class="mk-slider-spinner-wrap"><div class="mk-slider-spinner-fallback"></div><svg class="mk-slider-spinner" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="mk-slider-spinner-path" fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>',
                onInitialize 		: function() {},
                onAfterSlide 		: function( id ) {},
                onBeforeSlide 		: function( id ) {}
		};

		this.state = {
			id 						: 0,
			moveForward 			: true,
			running   				: false,
            zIFlow					: null,
            stop 					: false,
		};

		this.config = $.extend( defaults, config );
		this.container = container;

		this.initPerView = this.config.slidesPerView;

		// Timer holder
		this.activeTimer = null;
		this.autoplay = null;
		this.timer = null;
		this.timerRemaining = parseInt(this.config.displayTime);

		// Boolean 'Em All, Making sure it's not string
		this.config.lazyload = JSON.parse(this.config.lazyload);
		this.config.edgeSlider = JSON.parse(this.config.edgeSlider);

		// Image Loader Instance
		this.imageLoader = null;

		// Add abort command to imagesLoaded, Placing it inside script makes it to work with different versions
		// of imagesLoaded if loaded by other Plugins
		imagesLoaded.prototype.abort = function() {
			this.progress = this.complete = function() { };
		};
	};



	//
	// Shared methods
	//
	// /////////////////////////////////////////////////////////

	MK.ui.Slider.prototype = {

		init : function() {
			this.setPerViewItems();
			this.cacheElements();
			this.getSlideSize();
			this.bindEvents();
            this.setSize();
			this.setPos();

			// Hack for preparing 'prev' on first click if needed
			this.updateId( -1 );
			this.updateId( 1 );

			this.val = this.dynamicVal();
			this.timeline = this.prepareTimeline( this.config.transitionTime );

			this.timeline.build();

			if( this.config.hasPagination ) { this.buildPagination(); }

			if( this.config.autoplay && document.hasFocus() ) { this.setTimer(); }

			if( typeof this.config.onInitialize === 'function' ) {
				this.config.onInitialize( this.slides );
			}

			if( this.config.fluidHeight === true ) {
				$( this.slides ).height( 'auto' );
				$( this.container ).css( 'transition', 'height ' + 200 + 'ms ease-out' );
				this.setHeight( 0 );
			}


			if( this.config.fluidHeight === 'toHighest' ) {
				this.setHeightToHighest();
			}

			// Create timer per slide if required
			$(this.slides).each(this.createTimer);

			// If it's Edge Slider and Lazy Load is enabled
			if ( this.config.lazyload && this.config.edgeSlider ) {

				// If It's not a Video Slide
				if ( $(this.slides[this.state.id]).find('video').length === 0 ) {
					// Set the first slide's BG image
					var $slideImg = $(this.slides[this.state.id]).children('[data-mk-img-set]');
					MK.component.BackgroundImageSetter.init( $slideImg );
				}
				$(this.config.spinnerTpl).prependTo( this.$slides );

			} else {

				// Set all slides's BG images
				MK.component.BackgroundImageSetter.init( $(this.slides).children('[data-mk-img-set]') );

			}



		},


		cacheElements : function () {
			this.container = this.isNode( this.container ) ? this.container
				: document.querySelectorAll( this.container )[0];
			this.slides = this.container.querySelectorAll( this.config.slide );
			this.$slides = $(this.slides);

			if( this.config.hasNav ) { this.$nav = $( this.config.nav ); }
			if( this.config.hasPagination ) { this.$pagination = $( this.config.paginationEl ); }
		},


		bindEvents : function() {
			var $window = $( window );

			if( this.config.slidesPerView > 1 ) { $window.on( 'resize', this.setPerViewItems.bind( this ) ); }
			if( this.config.hasNav ) { this.eventsNav(); }
			if( this.config.hasPagination ) { this.eventsPag(); }
			if( this.config.draggable ) { this.dragHandler(); }
			if( this.config.autoplay ) {
				$window.on( 'focus', this.windowActive.bind( this ) );
				$window.on( 'blur', this.windowInactive.bind( this ) );
			}
			if( this.config.pauseOnHover ) {
				$(this.container).on( 'mouseleave', this.setTimer.bind( this ) );
				$(this.container).on( 'mouseenter', this.unsetTimer.bind( this ) );
			}
			if( this.config.fluidHeight === 'toHighest' ) {
				$window.on( 'resize', this.setHeightToHighest.bind( this ) );
			}
		},


		setPerViewItems: function() {
			if(window.matchMedia( '(max-width: 500px)' ).matches) { this.config.slidesPerView = 1; }
			else if(window.matchMedia( '(max-width: 767px)' ).matches && this.initPerView >= 2 ) { this.config.slidesPerView = 2; }
			else if(window.matchMedia( '(max-width: 1024px)' ).matches && this.initPerView >= 3 ) { this.config.slidesPerView = 3; }
			else { this.config.slidesPerView = this.initPerView; }

        	if( typeof this.slides === 'undefined' ) return;
			this.getSlideSize();
			this.setSize();
			this.setPos();
			this.timeline = this.prepareTimeline( this.config.transitionTime );
			this.timeline.build();
		},


		eventsNav : function() {
			this.$nav.on( 'click', 'a', this.handleNav.bind( this ) );
		},


		eventsPag : function() {
			this.$pagination.on( 'click', 'a', this.handlePagination.bind( this ) );
		},


		handleNav : function( e ) {
			e.preventDefault();

			if( this.state.running ) { return; }
			this.state.running = true;

			var $this = $( e.currentTarget ),
				moveForward = $this.data( 'direction' ) === 'next';


			if( this.config.autoplay ) {
				this.unsetTimer();
				setTimeout( this.setTimer.bind( this ), this.config.transitionTime );
			}

			this.state.moveForward = moveForward;
			this.timeline.build();
			this.timeline.play();

			this.setActive( this.nextId( moveForward ? 1 : -1 ) );
			if( this.config.fluidHeight ) { this.setHeight( this.nextId( moveForward ? 1 : -1 ) ); }
		},


		handlePagination : function( e ) {
			e.preventDefault();

			var $this = $( e.currentTarget ),
				id = $this.index();

			this.goTo( id );
		},


		reset: function() {
			this.state.stop = true;
			this.state.id = 0;
			this.setPos();
			this.unsetTimer();
			this.setTimer();
		},


		goTo : function(id) {
			if( this.state.running ) { return; }
			this.state.running = true;

			var lastId = this.state.id;

			if( lastId === id ) {
				return;
			} else if( lastId < id ) {
				this.state.moveForward = true;
			} else {
				this.state.moveForward = false;
			}

			if( this.config.autoplay ) {
				this.unsetTimer();
				setTimeout( this.setTimer.bind( this ), this.config.transitionTime );
			}

			this.timeline.build( Math.abs( lastId - id ) );
			this.timeline.play();

			this.setActive( id );
			if( this.config.fluidHeight ) { this.setHeight( id ); }
		},


		windowActive : function() {
			this.setTimer(false, true);
			$(this.container).removeClass('is-paused');
		},


		windowInactive : function() {
			this.unsetTimer();
			$(this.container).addClass('is-paused');
		},


		updateId : function( val ) {
			this.state.id = this.nextId(val);
		},

		nextId : function( val ) {
			var len = this.slides.length,
				insertVal = this.state.id + val;
				insertVal = ( insertVal >= 0 ) ? insertVal : len + val;
				insertVal = ( insertVal >= len ) ? 0 : insertVal;

			return insertVal;
		},


		setStyle : function( obj, style ) {
            var hasT = style.transform,
            	t = {
	                x       : ( hasT ) ? style.transform.translateX : null,
	                y       : ( hasT ) ? style.transform.translateY : null,
	                scale   : ( hasT ) ? style.transform.scale 		: null,
	                rotate  : ( hasT ) ? style.transform.rotate 	: null,
	                rotateX : ( hasT ) ? style.transform.rotateX 	: null,
	                rotateY : ( hasT ) ? style.transform.rotateY 	: null
           		},
				z  = 'translateZ(0)',
            	x  = (t.x) ?  'translateX(' + t.x + '%)' 		: 'translateX(0)',
                y  = (t.y) ?  'translateY(' + t.y + '%)' 		: 'translateY(0)',
                s  = (t.scale)  ?  'scale(' + t.scale + ')' 	: 'scale(1)',
                r  = (t.rotate) ? 'rotate(' + t.rotate + 'deg)' : 'rotate(0)',
                rX = (t.rotateX) ? 'rotateX(' + t.rotateX + 'deg)' : '',
                rY = (t.rotateY) ? 'rotateY(' + t.rotateY + 'deg)' : '',

           		o = style.opacity,
           		h = style.height,
           		w = style.width;

            var c = z + x + y  + s + r + rX + rY;

            if( c.length ) {
	            obj.style.webkitTransform 	= c;
	            obj.style.msTransform 		= c;
	            obj.style.transform 		= c;
	        }

            if( typeof o === 'number' ) { obj.style.opacity = o; }
            if( h ) { obj.style.height  = h + '%'; }
            if( w ) { obj.style.width   = w + '%'; }
		},


		setPos : function() {
        	if( typeof this.slides === 'undefined' ) return;
		    var id 			= this.state.id,
		    	i 			= 0,
		    	len 		= this.slides.length,
		    	animation 	= this.animation[ this.config.effect ],
		    	axis 		= animation.axis,
				animNext	= animation.next,
				animActi 	= animation.active,
				animPrev 	= animation.prev,
                perView 	= this.config.slidesPerView,
                slideId 	= null,
                zIFlow 		= null,
                style 		= {};

            style.transform = {};


            for( ; i < len; i += 1 ) {
                if(i < perView) {
                	// Position for visible slides. Apply active styles
                	style = animActi;
                    style.transform[ 'translate' + axis ] = i * 100;
                } else {
                	// Rest slides move after edge based on axis and moveForward. Apply Next / Prev styles
                	style = this.state.moveForward ? animNext : animPrev;
                    style.transform[ 'translate' + axis ] =  this.state.moveForward ? perView * 100 : -100;
                }

                this.slides[ i ].style.zIndex = 0;

                slideId = ( i + id ) % len;
                this.setStyle( this.slides[ slideId ], style );
            }
		},


        // When we're setting animation along Y axis we're going to set up height
        // otherwise width. It is shared amongst all slides
        setSize : function() {
        	if( typeof this.slides === 'undefined' ) return;
        	var i = 0,
		    	len = this.slides.length,
		    	axis = this.animation[ this.config.effect ].axis,
                slideSize = this.slideSize,
        		style = {};

            if( axis === 'Y' ) {
                style.height = slideSize[ axis ];
            } else {
                style.width = slideSize[ axis ];
            }

            for( ; i < len; i += 1 ) {
                this.setStyle( this.slides[ i ], style );
            }
        },


        setHeight : function( id ) {
			var $slides = $( this.slides ),
				$activeSlide = $slides.eq( id );

        	var currentHeight = $activeSlide.height();
        	$( this.container ).height( currentHeight );
        },


        setHeightToHighest : function() {
        	// this is becouse of alliginig woocommrece carousel. Too much DOM
        	// Refactor someday
			var $slides = $( this.slides ),
				height = 0;

        	$slides.each(function() {
        		height = Math.max(height, $(this).find('> div').outerHeight());
        	});

        	$( this.container ).height( height );
        },


        // Little utility inspired by GreenSock.
        // We export this to this.timeline on init.
        prepareTimeline : function( time ) {
			var self 		= this,
				iteration 	= 0,
            	totalIter 	= time / (1000 / 60),
            	animLoop 	= [],
            	aL 			= 0, // animation length
            	loops 		= 1,
				ease 		= this.config.ease,
				currentStyle, timeProg,
				build, move, add, play, reverse, progress, kill;


			// Build constants, run them only once
			// take out possibly
			var len 		= this.slides.length,
				perView   	= this.config.slidesPerView,
				animation 	= this.animation[ this.config.effect ],
				animAxis 	= animation.axis,
				animNext	= animation.next,
				animActi 	= animation.active,
				animPrev 	= animation.prev,
				style 		= {},
				slideId 	= null,
				zIFlow 		= null;

				style.transform = {};


			build = function( repeats ) {
				var currentEase = ease;
				loops = repeats || loops;

				// console.log('build', loops);

				if( !loops ) { return; }
				if( loops > 1 ) {
					currentEase = 'linearEase';
				}

				// clean before running new build
				kill();
				// set new positions
				self.setPos();

				var id = self.state.id,
					moveForward = self.state.moveForward,
					i = 0,
					axisMove = (moveForward) ? -100 : 100;

				for( ; i <= perView; i += 1 ) {
					slideId = ( (moveForward) ? i + id : i + id - 1 ) % len;
					slideId = ( slideId < 0 ) ? len + slideId : slideId;

					if( i === 0 ) {
						style = moveForward ? animPrev : animActi;
					} else if( i === perView ) {
						style = moveForward ? animActi : animNext;
					} else {
						style = animActi;
	            	}

               	 	zIFlow = (self.state.moveForward) ? animNext.zIndex : animPrev.zIndex;
	                if( zIFlow ) {
	                	// console.log( zIFlow );
	                	self.slides[ slideId ].style.zIndex = (zIFlow === '+') ? i + 1 : len - i;
	                }

					style.transform[ 'translate' + animAxis ] = axisMove;
	            	add( self.slides[ slideId ], style, currentEase );
				}
			};

			add = function( slide, toStyles, ease ) {
				if( typeof slide === 'undefined' ) {
					throw 'Add at least one slide';
				}

	            var fromStyles = slide.style,
					style = self.refStyle( toStyles, fromStyles );

				animLoop.push( [slide, style, ease] );
				aL += 1;
			};

			move = function( startProg, mode ) {
				var currentTotalIter = totalIter;

				if( loops > 1 ) {
				 	currentTotalIter = totalIter / 5;
				}

				if( !self.state.running ) { self.state.running = true; }

				if( startProg ) {
					// update iteration val to cached outside var
					// ceil to handle properly play after mouse up / touch end
					iteration = Math.ceil(startProg * currentTotalIter);
				}

				timeProg = iteration / currentTotalIter;
				progress( timeProg );

				// Break loop
				if( iteration >= currentTotalIter && mode === 'play' ||
					iteration <= 0 && mode === 'reverse' ) {

					self.state.running = false;
					iteration = 0;
					kill();
	            	self.updateId( self.state.moveForward ? 1 : -1 );
					// If we're creating multiple animation loop we trigger outside only first pass to start all game.
					// the rest are triggered as callback
					loops -= 1;
					if( loops > 0 ) {
						build();
						play();
					}

					// if we run all loops reset back the default value
					if( !loops ) {
						loops = 1;
						self.timerRemaining = parseInt(self.config.displayTime);
						self.config.onAfterSlide( self.state.id );
					}

					return;
				}

				// Run in given mode
				if( mode === 'play') {
					iteration += 1;
				} else {
					iteration -= 1;
				}

				requestAnimationFrame( function() {
					if(self.state.stop) return;
					move( 0, mode );
				});
			};

			play = function( startProg ) {

				var $nextSlide = $(self.slides[ self.nextId(self.state.moveForward ? 1 : -1) ] );

				// If it's Edge Slider and Lazy Load is enabled and It's not a Video Slide
				if ( self.config.lazyload && self.config.edgeSlider ) {

					// Set the next slide's BG Image
					var $slideImg = $nextSlide.find('[data-mk-img-set]');
					if ( $slideImg.length ) {
						MK.component.BackgroundImageSetter.init( $slideImg );
					}

				}

				self.config.onBeforeSlide( self.nextId(self.state.moveForward ? 1 : -1) );
				var start = startProg || 0;
				iteration = 0;
				self.state.stop = false;
				move( start, 'play' );

			};

			reverse = function( startProg ) {
				var start = startProg || 1;
				move( start, 'reverse' );
			};

			progress = function( progVal ) {
            	var aI = 0,
            		currentStyle;

				for( aI; aI < aL; aI++ ) {
					if( progVal !== 1 && progVal !== 0 ) {
						currentStyle = self.currentStyle( progVal, animLoop[ aI ][ 1 ], animLoop[ aI ][ 2 ] );
					} else if( progVal === 1) {
						currentStyle = self.currentStyle( progVal, animLoop[ aI ][ 1 ], 'linearEase' );
					} else if ( progVal === 0 ) {
						currentStyle = self.currentStyle( progVal, animLoop[ aI ][ 1 ], 'linearEase' );
					}
					self.setStyle( animLoop[ aI ][ 0 ], currentStyle );
				}
			};

			// Clear previous loop
			kill = function() {
				animLoop = [];
            	aL = 0;
			};


			return {
				build 		: build,
				add 		: add,
				play 		: play,
				reverse 	: reverse,
				progress 	: progress
			};
		},


		// Build reference styles.
		// Return object with array containig initial style and change of its value
		// as required for easing functions
		refStyle : function( toStyles, fromStyles ) {
			var axis = this.animation[ this.config.effect ].axis,
            	style = {},
				initVal, changeVal, endVal, dynamicEnd, styleProp, transProp, transform;

			for( styleProp in toStyles ) {

				if( styleProp === 'transform' ) {
					transform = this.getTransforms( fromStyles );
					style.transform = {};

					for( transProp in toStyles.transform ) {
						// don't care about z
						if( transProp === 'translateZ' ) { continue; }

						initVal = transform[ transProp ] || 0; // if it is undefined it means it's 0
						dynamicEnd = ( transProp === 'translate' + axis ) ? initVal : 0;
						endVal  = toStyles.transform[ transProp ] + dynamicEnd; // it is dynamic, based on slide position in current set
						changeVal = endVal - initVal;
						style.transform[ transProp ] = [ initVal, changeVal ];
					}
				} else if( styleProp === 'zIndex' ) {
					// console.log( 'z' );
					continue;
				} else {
					initVal = parseFloat( fromStyles[ styleProp ] ) || 0; // if it is undefined it means it's 0
					endVal  = toStyles[ styleProp ];
					changeVal = endVal - initVal;
					style[ styleProp ] =  [ initVal, changeVal ];
				}
			}

			return style;
		},


		currentStyle : function( progress, style, ease ) {
			var self = this,
				currentStyle = {},
            	currentVals, styleProp, transProp, prog;

			// Redo same loop but construct currentStyle object out of cached values
			for( styleProp in style ) {

				if( styleProp === 'transform' ) {
					currentStyle.transform = {};

					for( transProp in style.transform ) {
						// remove this line. double check first if needed by logging
						if( transProp === 'translateZ' ) { continue; }

						currentVals = style.transform[ transProp ];
						currentStyle.transform[ transProp ] =
							// (currentIteration, startValue, changeInValue, totalIterations)
								self.ease[ ease ]( progress, currentVals[ 0 ], currentVals[ 1 ], 1 );
					}
				} else {
					currentVals = style[ styleProp ];
					currentStyle[ styleProp ] =
						self.ease[ ease ]( progress, currentVals[ 0 ], currentVals[ 1 ], 1 );
				}
			}

			return currentStyle;
		},


		setActive : function( id ) {
			var $slides = $( this.slides ),
				className = this.config.activeClass;

			$slides.removeClass( className );

			if( this.config.hasPagination ) {
				var $pagination = this.$pagination.find( 'a' );
				$pagination.removeClass( className );
				$pagination.eq( id ).addClass( className );
			}

			if( this.activeTimer ) {
				clearTimeout( this.activeTimer );
				if ( this.imageLoader ) {
					this.imageLoader.abort();
				}
			}


			var self = this;

			this.activeTimer = setTimeout( function() {

				var $currentSlide = $slides.eq( id );

				if ( self.config.lazyload && self.config.edgeSlider ) {  // If it's Edge Slider and Lazy Load is enabled

					if ( $currentSlide.find('.mk-section-video').length && $currentSlide.children('.mk-video-section-touch').length ) {  // If it's a Video Slide and has a Preview image

						var imgSet = $currentSlide.children('.mk-video-section-touch').data('mk-img-set');
						var exactImg = MK.component.BackgroundImageSetter.getImage( imgSet );
						var $bgImage = $('<img>').attr('src', exactImg );

						self.imageLoader = imagesLoaded( $bgImage[0], function( instance ) {
							$currentSlide.children('.mk-slider-spinner-wrap').addClass('mk-slider-spinner-wrap-hidden');
							setTimeout( function() {
						 		$currentSlide.children('.mk-slider-spinner-wrap').hide();
						 	}, 200);
							$currentSlide.addClass( className );
					 	});

					} else if ( $currentSlide.find('.mk-section-video').length && $currentSlide.children('.mk-video-section-touch').length === 0 ) { // If it's a Video Slide and has NOT a Preview image

						$currentSlide.children('.mk-slider-spinner-wrap').addClass('mk-slider-spinner-wrap-hidden');
						setTimeout( function() {
					 		$currentSlide.children('.mk-slider-spinner-wrap').hide();
					 	}, 200);
						$currentSlide.addClass( className );

					} else {  // If it's a Image Slide

						if ( $currentSlide.children('[data-mk-img-set]').length ) {
							// Get the matching Image URL to start lazy loading
							var imgSet = $currentSlide.children('[data-mk-img-set]').data('mk-img-set');
							var exactImg = MK.component.BackgroundImageSetter.getImage( imgSet );
							var $bgImage = $('<img>').attr('src', exactImg );

							// Prevent counting time on slide until the image loads
							self.unsetTimer();
							self.imageLoader = imagesLoaded( $bgImage[0], function( instance ) {
								// Hide spinner, Continue counting time on slide and show the content
							 	$currentSlide.children('.mk-slider-spinner-wrap').addClass('mk-slider-spinner-wrap-hidden');
							 	setTimeout( function() {
							 		$currentSlide.children('.mk-slider-spinner-wrap').hide();
							 	}, 200);
								self.setTimer(false, false, $currentSlide.data('timer') || Number(self.config.displayTime) );
								$currentSlide.addClass( className );
							});
						} else {
							$currentSlide.children('.mk-slider-spinner-wrap').addClass('mk-slider-spinner-wrap-hidden');
						 	setTimeout( function() {
						 		$currentSlide.children('.mk-slider-spinner-wrap').hide();
						 	}, 200);
							self.setTimer(false, false, $currentSlide.data('timer') || Number(self.config.displayTime) );
							$currentSlide.addClass( className );
						}

					}

				} else {

					$currentSlide.addClass( className );

				}


			}, this.config.transitionTime );
		},

		createTimer : function() {

			var $slide = $(this),
				video = $slide.find('video').get(0);

			if(video) {
				// A hacky but reliable way to ge the video duration
				var interval = setInterval( function() {
					// If the metadata is ready
					if ( video.readyState > 0 ) {
						$slide.data('timer', (video.duration * 1000));
						$slide.attr('data-timer', (video.duration * 1000));
						clearInterval(interval);
					}
				}, 100);
			}

		},

		setTimer : function( isFirst, isPaused, fixed_time ) {
			// check for custom timer
			var customTimer = this.$slides.eq(this.nextId(this.state.moveForward ? 1 : -1)).data('timer'),
				trans = parseInt( this.config.transitionTime ),
				interval = customTimer ? customTimer : parseInt( this.config.displayTime ),
				timer = interval + trans;

			var self  = this,
				first = isFirst || true,
				fixed_time = fixed_time || 0,
				create, run;

			this.timer = true;
			this.lastSetTimer = Date.now();

			create = function() {

				if( self.autoplay ) { clearTimeout( self.autoplay ); }
				if( !self.timer ) {
					return;
				}
				self.state.moveForward = true;
				self.timeline.build();
				self.timeline.play();
				self.setActive( self.nextId( 1 ) );
				if( self.config.fluidHeight ) { self.setHeight( self.nextId( 1 ) ); }
				first = false;
				self.lastSetTimer = Date.now();

				run();
			};

			run = function(newInterval) {
				// check for custom timer
				customTimer = self.$slides.eq(self.nextId(self.state.moveForward ? 1 : -1)).data('timer');
				interval = customTimer ? customTimer : parseInt( self.config.displayTime );
				timer = interval + trans; // update timer with current val

				var time = newInterval || timer;
				self.autoplay = setTimeout( create, time );
			};

			if ( fixed_time ) {
				run( fixed_time );
			} else if (isPaused) {
				run( this.timerRemaining );
			} else {
				run();
			}
		},


		unsetTimer : function() {
			this.timer = false;
			this.lastUnsetTimer = Date.now();
			this.timerRemaining -= this.lastUnsetTimer - this.lastSetTimer;
			if( this.autoplay ) { clearTimeout( this.autoplay ); }
		},


		buildPagination : function() {
			var i   = 0,
				len = this.slides.length,
				tpl = '';

			for( ; i < len; i += 1 ) {
				tpl += '<a href="javascript:;">' + this.config.paginationTpl + '</a>';
			}

			this.$pagination.html( tpl );
			this.setActive( 0 );
		},


		getSlideSize : function() {
			this.slideSize = {
                X: 100 / this.config.slidesPerView,
                Y: 100 / this.config.slidesPerView
            };
		},


		getTransforms : function( style ) {
			// console.log( style );
		    var transform = style.transform || style.webkitTransform || style.mozTransform,
		    	regex = /(\w+)\(([^)]*)\)/g,
				match,
				T = {};

			if( typeof transform !== 'string' ) {
				throw 'Transform prop is not a string.';
			}

		    if( !transform ) { return; }

			// Run regex assignment
			while( match = regex.exec( transform ) ) {
				T[ match[ 1 ] ] = parseFloat( match[ 2 ] );
			}

		    return T;
		},

		isNode : function( o ) {
			return (
		    	typeof Node === "object" ? o instanceof Node :
		   			o && typeof o === "object" && typeof o.nodeType === "number" && typeof o.nodeName==="string"
		  	);
		},


		dragHandler : function() {
			var self = this,
				$container = $( this.container ),
				prevBuild = false,
				nextBuild = false,
				dragging = false,
				buffor = 5, // helpful for decoupling with click events
				dragStart, dragMove, dragEnd, progress;

			progress = function( moveX ) {
				return moveX / self.val.viewportW();
			};

			dragStart = function( moveX, startX ) {
				// console.log( 'start', moveX, startX );
			};

			dragMove = function( moveX ) {
				// console.log('move');
				if( self.state.running ) return;

				// Don't need to check for existance here

				if( moveX < -buffor ) {

					if( !nextBuild ) {
						self.state.moveForward = true;
						self.timeline.build();
						nextBuild = true;
						prevBuild = false;
						self.unsetTimer();
					} else {
						// turn progress into positive val
						self.timeline.progress( -progress( moveX ) );
					}
					dragging = true;
				} else if( moveX > buffor ) {

					if( !prevBuild ) {
						self.state.moveForward = false;
						self.timeline.build();
						prevBuild = true;
						nextBuild = false;
						self.unsetTimer();
					} else {
						self.timeline.progress( progress( moveX ) );
					}
					dragging = true;
				}
			};

			dragEnd = function( moveX ) {
				if( dragging ) {
					var prog = progress( moveX ),
						absProg = prog < 0 ? -prog : prog;

					if( absProg > 0.1 ) {
						self.timeline.play( absProg );
						self.setActive( self.nextId( prog < 0 ? 1 : -1 ) );
						if( self.config.fluidHeight ) { self.setHeight( self.nextId( prog < 0 ? 1 : -1 ) ); }
					} else {
						self.timeline.reverse( absProg );
						// eventually move this to reverse callbacks
						if(prog < 0) {
							self.updateId( -1 );
						} else {
							self.updateId( 1 );
						}
					}

					prevBuild = false;
					nextBuild = false;
					dragging = false;
					if( self.config.autoplay ) { self.setTimer( false ); }
				}
			};

			this.drag( $container, dragStart, dragMove, dragEnd );
		},


		drag : function( $el, startFn, moveFn, stopFn ) {

		    var touchX, touchY, movX, movY, go, evt,
		   		prevent, start, move, stop;

		    prevent = function( e ) {
		        e.preventDefault();
		    };

		    start = function( e ) {
		        // $el.on("touchmove", prevent);
		        $el.on("mousemove", prevent);
		        $el.on("touchmove", move);
		        $el.on("mousemove", move);

		        evt = (e.type === 'touchstart') ? e.originalEvent.touches[0] : e;
		        touchX = evt.pageX;

		        if(typeof startFn === 'function') {
		        	startFn(movX, touchX);
		        }
		    };

		    move = function( e ) {
		        evt = (e.type === 'touchmove') ? e.originalEvent.touches[0] : e;
		        movX = evt.pageX - touchX;

	        	if(typeof moveFn === 'function') {
		        	moveFn(movX);
		        }
		    };

		    stop = function( e ) {
		        // $el.off("touchmove", prevent);
		        $el.off("mousemove", prevent);
		        $el.off("touchmove", move);
		        $el.off("mousemove", move);

		    	if(typeof stopFn === 'function') {
		        	stopFn(movX);
		        }
		    };

		    $el.on("touchstart", start);
		    $el.on("mousedown", start);
		    $el.on("touchend", stop);
		    $el.on("touchleave", stop);
		    $el.on("touchcancel", stop);
		    $el.on("mouseup", stop);
		    $el.on("mouseleave", stop);
		},


		dynamicVal : function() {
			var $window = $( window ),
				update,
				getViewportW, viewportW;

			update = function() {
 				viewportW = $window.width();
			};

			getViewportW = function() {
				return viewportW;
			};

			update();
			$window.on( 'load', update );
			$window.on( 'resize', update );

			return {
				viewportW : getViewportW
			};
		}
	};



	//
	// Set of default animations
	//
	// /////////////////////////////////////////////////////////

	MK.ui.Slider.prototype.animation = {

        slide : {
        	axis : 'X',
            next : { transform: {} },
            active : { transform: {} },
            prev : { transform: {} }
        },

        vertical_slide : {
        	axis : 'Y',
            next : { transform: {} },
            active : { transform: {} },
            prev : { transform: {} }
        },

        perspective_flip : {
        	axis : 'Y',
            next : {
            	transform: {
            		rotateX : 80
            	}
            },
            active : {
            	transform: {
            		rotateX : 0
            	}
            },
            prev : {
            	transform: {
            		rotateX : 0
            	}
            }
        },

        zoom : {
			axis : 'Z',
            next: {
                opacity	: 0,
                transform : {
	                scale : 0.9
	            }
            },
            active: {
                opacity	: 1,
                transform : {
	                scale : 1
	            }
            },
            prev: {
                opacity	: 0,
                transform : {
	                scale : 1.1
	            }
            }
        },

        fade : {
			axis : 'Z',
            next: {
                opacity	: 0,
                transform : {}
            },
            active: {
                opacity	: 1,
                transform : {}
            },
            prev: {
                opacity	: 0,
                transform : {}
            }
        },

        kenburned : {
			axis : 'Z',
            next: {
                opacity	: 0,
                transform : {}
            },
            active: {
                opacity	: 1,
                transform : {}
            },
            prev: {
                opacity	: 0,
                transform : {}
            }
        },

        zoom_out : {
			axis : 'Z',
            next: {
				zIndex : '+',
                opacity	: 1,
                transform : {
	                translateY : 100,
	                scale : 1
	            }
            },
            active: {
                opacity	: 1,
                transform : {
	                translateY : 0,
	                scale : 1
	            }
            },
            prev: {
				zIndex : '+',
                opacity	: 0,
                transform : {
	                translateY : 0,
	                scale : 0.5
	            }
            }
        },

        // Problem with Z-Flow
        horizontal_curtain : {
			axis : 'Z',
            next: {
				zIndex : '+',
                transform : {
	                translateX : 100,
	            }
            },
            active: {
                transform : {
	                translateX : 0,
	            }
            },
            prev: {
				zIndex : '+',
                transform : {
	                translateX : -70,
	            }
            }
        },

		roulete : {
			axis : 'X',
            next: {
                opacity	: 0.5,
                transform : {
	                scale : 0.5,
	                rotate : 10,
	                translateY : 20
	            }
            },
            active: {
                opacity	: 1,
                transform : {
	                scale : 1,
	                rotate : 0,
	                translateY : 0
	            }
            },
            prev: {
                opacity	: 0.3,
                transform : {
	                scale : 0.5,
	                rotate : -10,
	                translateY : 20
	            }
            }
		}
	};



	//
	// Penner's easing library
	//
	// /////////////////////////////////////////////////////////

	MK.ui.Slider.prototype.ease = {
		/*
		 *
		 * TERMS OF USE - EASING EQUATIONS
		 *
		 * Open source under the BSD License.
		 *
		 * Copyright Â© 2001 Robert Penner
		 * All rights reserved.
		 *
		 * Redistribution and use in source and binary forms, with or without modification,
		 * are permitted provided that the following conditions are met:
		 *
		 * Redistributions of source code must retain the above copyright notice, this list of
		 * conditions and the following disclaimer.
		 * Redistributions in binary form must reproduce the above copyright notice, this list
		 * of conditions and the following disclaimer in the documentation and/or other materials
		 * provided with the distribution.
		 *
		 * Neither the name of the author nor the names of contributors may be used to endorse
		 * or promote products derived from this software without specific prior written permission.
		 *
		 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
		 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
		 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
		 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
		 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
		 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
		 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
		 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
		 * OF THE POSSIBILITY OF SUCH DAMAGE.
		 *
		 */
		linearEase : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * currentIteration / totalIterations + startValue;
		},

		easeInQuad : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * (currentIteration /= totalIterations) * currentIteration + startValue;
		},

		easeOutQuad : function(currentIteration, startValue, changeInValue, totalIterations) {
			return -changeInValue * (currentIteration /= totalIterations) * (currentIteration - 2) + startValue;
		},

		easeInOutQuad : function(currentIteration, startValue, changeInValue, totalIterations) {
			if ((currentIteration /= totalIterations / 2) < 1) {
				return changeInValue / 2 * currentIteration * currentIteration + startValue;
			}
			return -changeInValue / 2 * ((--currentIteration) * (currentIteration - 2) - 1) + startValue;
		},

		easeInCubic : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * Math.pow(currentIteration / totalIterations, 3) + startValue;
		},

		easeOutCubic : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * (Math.pow(currentIteration / totalIterations - 1, 3) + 1) + startValue;
		},

		easeInOutCubic : function(currentIteration, startValue, changeInValue, totalIterations) {
			if ((currentIteration /= totalIterations / 2) < 1) {
				return changeInValue / 2 * Math.pow(currentIteration, 3) + startValue;
			}
			return changeInValue / 2 * (Math.pow(currentIteration - 2, 3) + 2) + startValue;
		},

		easeInQuart : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * Math.pow (currentIteration / totalIterations, 4) + startValue;
		},

		easeOutQuart : function(currentIteration, startValue, changeInValue, totalIterations) {
			return -changeInValue * (Math.pow(currentIteration / totalIterations - 1, 4) - 1) + startValue;
		},

		easeInOutQuart : function(currentIteration, startValue, changeInValue, totalIterations) {
			if ((currentIteration /= totalIterations / 2) < 1) {
				return changeInValue / 2 * Math.pow(currentIteration, 4) + startValue;
			}
			return -changeInValue/2 * (Math.pow(currentIteration - 2, 4) - 2) + startValue;
		},

		easeInQuint : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * Math.pow (currentIteration / totalIterations, 5) + startValue;
		},

		easeOutQuint : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * (Math.pow(currentIteration / totalIterations - 1, 5) + 1) + startValue;
		},

		easeInOutQuint : function(currentIteration, startValue, changeInValue, totalIterations) {
			if ((currentIteration /= totalIterations / 2) < 1) {
				return changeInValue / 2 * Math.pow(currentIteration, 5) + startValue;
			}
			return changeInValue / 2 * (Math.pow(currentIteration - 2, 5) + 2) + startValue;
		},

		easeInSine : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * (1 - Math.cos(currentIteration / totalIterations * (Math.PI / 2))) + startValue;
		},

		easeOutSine : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * Math.sin(currentIteration / totalIterations * (Math.PI / 2)) + startValue;
		},

		easeInOutSine : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue / 2 * (1 - Math.cos(Math.PI * currentIteration / totalIterations)) + startValue;
		},

		easeInExpo : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * Math.pow(2, 10 * (currentIteration / totalIterations - 1)) + startValue;
		},

		easeOutExpo : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * (-Math.pow(2, -10 * currentIteration / totalIterations) + 1) + startValue;
		},

		easeInOutExpo : function(currentIteration, startValue, changeInValue, totalIterations) {
			if ((currentIteration /= totalIterations / 2) < 1) {
				return changeInValue / 2 * Math.pow(2, 10 * (currentIteration - 1)) + startValue;
			}
			return changeInValue / 2 * (-Math.pow(2, -10 * --currentIteration) + 2) + startValue;
		},

		easeInCirc : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * (1 - Math.sqrt(1 - (currentIteration /= totalIterations) * currentIteration)) + startValue;
		},

		easeOutCirc : function(currentIteration, startValue, changeInValue, totalIterations) {
			return changeInValue * Math.sqrt(1 - (currentIteration = currentIteration / totalIterations - 1) * currentIteration) + startValue;
		},

		easeInOutCirc : function(currentIteration, startValue, changeInValue, totalIterations) {
			if ((currentIteration /= totalIterations / 2) < 1) {
				return changeInValue / 2 * (1 - Math.sqrt(1 - currentIteration * currentIteration)) + startValue;
			}
			return changeInValue / 2 * (Math.sqrt(1 - (currentIteration -= 2) * currentIteration) + 1) + startValue;
		}
	};

})(jQuery);

(function($) {
	'use strict';
  var MK = window.MK || {};
  window.MK = MK;
  MK.ui = window.MK.ui || {};

	MK.component.Sortable = function(el) {
		this.el = el;
	};

	MK.component.Sortable.prototype = {
		init: function init() {
			this.cacheElements();
			this.bindEvents();
		},

		cacheElements: function cacheElements() {
			this.unique = Date.now();
			this.$filter = $(this.el);
			this.config = this.$filter.data('sortable-config');

			this.ajaxLoader = new MK.utils.ajaxLoader(this.config.container);
			this.ajaxLoader.init();

			this.$container = $( this.config.container );
			this.$navItems = this.$filter.find('a');
			this.$filterItems = this.$container.find(this.config.item);
		},

		bindEvents: function bindEvents() {
			this.$navItems.on('click', this.handleClick.bind(this));
			MK.utils.eventManager.subscribe('ajaxLoaded', this.onLoad.bind(this));
		},

		handleClick: function handleClick(e) {
			e.preventDefault();

			var $item = $(e.currentTarget);
			var term = $item.data('filter');

			this.$navItems.removeClass('current');
			$item.addClass('current');

			if(this.config.mode === 'ajax') this.inDB(term, $item);
	        else this.inPage(term);
		},

		inDB: function inDB(term, $item) {
			// Add load indicator only for long requests
			MK.ui.loader.remove(this.$filter);
			MK.ui.loader.add($item);

			// If mk-ajax-loaded-posts span exists and one of the filter is clicked,
			// clear post ids
			if ( this.$container.siblings('.mk-ajax-loaded-posts').length ) {
				this.$container.siblings('.mk-ajax-loaded-posts').attr('data-loop-loaded-posts', '');
			}

			this.ajaxLoader.setData({
				paged: 1,
				term: term
			});
            this.ajaxLoader.load(this.unique);
		},

		inPage: function inPage(term) {
			var $filterItems = this.$container.find(this.config.item);
			$filterItems.removeClass('is-hidden'); // show all first
			// Replace all ', ' with ', .'. It's used to add '.' as class selector of each category.
			var className = term.replace( /, /g, ", ." );
			if(term !== '*') $filterItems.not( '.' + className ).addClass('is-hidden'); // hide filtered
			MK.utils.eventManager.publish('staticFilter');
		},

		onLoad: function onLoad(e, response) {
			if(this.config.mode === 'static') {
				this.$navItems.removeClass('current').first().addClass('current');
			}
			if( typeof response !== 'undefined' &&  response.id === this.config.container) {
				MK.ui.loader.remove(this.$filter);
				if(response.unique === this.unique) {
		            this.$container.html(response.content);
					this.ajaxLoader.setData({paged: 1});
				}
			}
		}
	};

})(jQuery);

(function($) {
    'use strict';

    MK.component.Tabs = function( el ) {
        var defaults = {
            activeClass : 'is-active'
        };

        this.config = defaults;
        this.el = el;
    };

    MK.component.Tabs.prototype = {

        init : function() {
            this.cacheElements();
            this.bindEvents();
        },

        cacheElements : function() {
            this.$this  = $( this.el );
            this.$tabs  = this.$this.find( '.mk-tabs-tab' );
            this.$panes = this.$this.find( '.mk-tabs-pane' );
            this.currentId = 0;
        },

        bindEvents : function() {
            var self = this;

            this.$tabs.on( 'click', this.switchPane.bind( this ) );
        },

        switchPane : function( evt ) {
            evt.preventDefault();

            var clickedId = $( evt.currentTarget ).index();

            this.hide( this.currentId );
            this.show( clickedId );

            // Update current id
            this.currentId = clickedId;

            // Notify rest of the app
            MK.utils.eventManager.publish('item-expanded');            
        },

        show : function( id ) {
            this.$tabs.eq( id ).addClass( this.config.activeClass );
            this.$panes.eq( id ).addClass( this.config.activeClass );
        },

        hide : function( id ) {
            this.$tabs.eq( id ).removeClass( this.config.activeClass );
            this.$panes.eq( id ).removeClass( this.config.activeClass );
        }
    };

})(jQuery);


/* Tabs */
/* -------------------------------------------------------------------- */

function mk_tabs() {

  // "use strict";

  // if ($.exists('.mk-tabs, .mk-news-tab, .mk-woo-tabs')) {
  //   $(".mk-tabs, .mk-news-tab, .mk-woo-tabs").tabs();

  //    $('.mk-tabs').on('click', function () {
  //      $('.mk-theme-loop').isotope('layout');
  //    });

  //   $('.mk-tabs.vertical-style').each(function () {
  //     $(this).find('.mk-tabs-pane').css('minHeight', $(this).find('.mk-tabs-tabs').height() - 1);
  //   });

  // }
}

function mk_tabs_responsive(){
  // $('.mk-tabs, .mk-news-tab').each(function () {
  //   $this = $(this);
  //   if ($this.hasClass('mobile-true')) {
  //     if (window.matchMedia('(max-width: 767px)').matches)
  //     {
  //         $this.tabs("destroy");
  //     } else {
  //       $this.tabs();
  //     }
  //   }
  // });
  
}


(function($) {
  'use strict';

  $(document).on('click', function(e) {
    $('.mk-toggle-trigger').removeClass('mk-toggle-active');
  });

  function toggle(e) {
      e.preventDefault();
      e.stopPropagation();
      var $this = $(e.currentTarget);

      if (!$this.hasClass('mk-toggle-active')) {

        $('.mk-box-to-trigger').fadeOut(200);
        $this.parent().find('.mk-box-to-trigger').fadeIn(250);
        $('.mk-toggle-trigger').removeClass('mk-toggle-active');
        $this.addClass('mk-toggle-active');

      } else {

        $('.mk-box-to-trigger').fadeOut(200);
        $this.removeClass('mk-toggle-active');

      }
  }

  function assignToggle() {
    // wait for ajax response propagation and insertion
    setTimeout(function() {
      $('.mk-toggle-trigger').off('click', toggle);
      $('.mk-toggle-trigger').on('click', toggle);
    }, 100);
  }

  assignToggle();
  MK.utils.eventManager.subscribe('ajaxLoaded', assignToggle);
  MK.utils.eventManager.subscribe('ajax-preview', assignToggle);

  $(window).on('vc_reload', function(){
    assignToggle();
    MK.utils.eventManager.subscribe('ajaxLoaded', assignToggle);
    MK.utils.eventManager.subscribe('ajax-preview', assignToggle);
  });

}(jQuery));
(function($) {
    'use strict';

    var init = function init() {
        var Toggle = function(el) {
        var that = this,
            $el = $(el);

            this.$el = $el;
            $el.on('click', function() {
              $el.hasClass('active-toggle') ? that.close() : that.open()
            });
        };

        Toggle.prototype.dom = {
            pane   : 'mk-toggle-pane',
            active : 'active-toggle'
        };

        Toggle.prototype.open = function() {
            var $this = this.$el;
            $this.addClass(this.dom.active);
            $this.siblings('.' + this.dom.pane).slideDown(200);
        };

        Toggle.prototype.close = function() {
            var $this = this.$el;
            $this.removeClass(this.dom.active);
            $this.siblings('.' + this.dom.pane).slideUp(200);
        };

        // Apply to.
        var $toggle = $('.mk-toggle-title');

        if(!$toggle.length) return;

        $toggle.each(function() {
            new Toggle(this);
        });
    }
    $(window).on('load vc_reload', init);

})(jQuery);

/**
 * Add class to tag. It's vanilla js instead of jQuery.
 * @param tag
 * @param className
 */
function addClass(tag, className) {
  tag.className += ' ' + className;
}

/**
* Remove class from tag. It's vanilla js instead of jQuery.
* Replacing should be with g for replacing all occurrence.
*
* @param tag
* @param className
*/
function removeClass(tag, className) {
  tag.className = tag.className.replace(new RegExp(className, 'g'), '');
}

/**
 * Validate email address.
 *
 * @param input
 * @param invalidClassName
 * @returns boolean
 */
function validateEmail(input, invalidClassName) {
    var value = input.value.trim();
    if ((input.required || value.length > 0) && !/^([a-z0-9_\.\-\+]+)@([\da-z\.\-]+)\.([a-z\.]{2,63})$/i.test(value)) {
        if (invalidClassName) {
            addClass(input, invalidClassName);
        }
        return false;
    } else {
        if (invalidClassName) {
            removeClass(input, invalidClassName);
        }
        return true;
    }
}
/**
 * Validate text entry.
 *
 * @param input
 * @param invalidClassName
 * @returns boolean
 */
function validateText(input, invalidClassName) {
    var value = input.value.trim();
    if (input.required && value.length === 0) {
        if (invalidClassName) {
            addClass(input, invalidClassName);
        }
        return false;
    } else {
        if (invalidClassName) {
            removeClass(input, invalidClassName);
        }
        return true;
    }
}
/**
 * Validate Checkbox.
 *
 * @param input
 * @param invalidClassName
 * @returns boolean
 */
function validateCheckBox(input, invalidClassName) {
    if (input.required && input.checked == false) {
        if (invalidClassName) {
            addClass(input, invalidClassName);
        }
        return false;
    } else {
        if (invalidClassName) {
            removeClass(input, invalidClassName);
        }
        return true;
    }
}
/**
 * If we're running under Node for testing purpose.
 */
if (typeof exports !== 'undefined') {
    exports.validateEmail = validateEmail;
    exports.validateText = validateText;
}
(function( $ ) {
    'use strict';

    if( MK.utils.isMobile() ) {
        $('.mk-animate-element').removeClass('mk-animate-element');
        return;
    }


    var init = function init() {
      var $rootLevelEls = $('.js-master-row, .widget');
        $rootLevelEls.each( spyViewport );
        $rootLevelEls.each( function rootLevelEl() {
            var $animateEl = $(this).find( '.mk-animate-element' );
            $animateEl.each( spyViewport );

            /**
             * Firefox has known issue where horizontal scrollbar will appear if an
             * element uses animation CSS. The solution should be set the element
             * position as fixed or overflow-x as hidden. Position fixed is not possible
             * to use because it's only cause other big problems. The best way is
             * set overflow-x as hidden in the page content container #theme-page.
             *
             * NOTE: The problem is spotted on Right To Left viewport only. So, it's
             *       limited to '.right-to-left' selector only for now to avoid other
             *       problems. Please extend the functionallity if it's happen in
             *       other viewport animation effect.
             */
            var browserName  = MK.utils.browser.name;
            if ( browserName === 'Firefox' ) {
                var $rightToLeft = $( this ).find( '.right-to-left' );
                if ( $rightToLeft.length > 0 ) {
                    $( '#theme-page' ).css( 'overflow-x', 'hidden' );
                }
            }
        });
    };

    var spyViewport = function spyViewport(i) {
        var self = this;

        MK.utils.scrollSpy( this, {
            position  : 'bottom',
            threshold : 200,
            after     : function() {
                animate.call(self, i);
            }
        });
    };

    var animate = function animate(i) {
        var $this = $(this);

        setTimeout(function() {
            $this.addClass( 'mk-in-viewport' );
        }, 100 * i);
    };


    $(window).on('load vc_reload', init);

}(jQuery));
