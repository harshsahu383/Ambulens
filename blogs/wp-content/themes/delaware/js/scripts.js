(function ($) {
	'use strict';

	var delaware = delaware || {};
	delaware.init = function () {
		delaware.$body = $(document.body),
		delaware.$window = $(window),
		delaware.$header = $('#masthead');

        // Header
        this.activeHeaderMenu();
        this.canvasPanel();
        this.menuSideBar();

        // Scroll Top
        this.parallax();
        this.scrollTop();
        this.searchToggle();
		this.portfolioLayout();
		this.blogMasonryLayout();
		this.portfolioLoadingAjax();
		this.progressbarShortcode();
		this.tabs();

        // Widget Office
        this.widgetOffice();

        // Product Quantity
        this.productQuantity();

        // Blog Loading Ajax
        this.blogLoadingAjax();

        this.stickyHeader();
	};

	// Parallax
	delaware.parallax = function () {
		if ( delaware.$window.width() < 1200 ) {
			return;
		}

		$( '.page-header.parallax .featured-image' ).parallax( '50%', 0.6 );
	};

    // Menu Block Right
    delaware.scrollTop = function () {
        var $scrollTop = $('#scroll-top');
        delaware.$window.on('scroll',function () {
            if (delaware.$window.scrollTop() > delaware.$window.height()) {
                $scrollTop.addClass('show-scroll');
            } else {
                $scrollTop.removeClass('show-scroll');
            }
        });

        // Scroll effect button top
        $scrollTop.on('click', function (event) {
            event.preventDefault();
            $('html, body').stop().animate({
                    scrollTop: 0
                },
                800
            );
        });
    };

    // Search toggle
    delaware.searchToggle = function () {
        $('.dl-search-toggle').on('click', function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('show-search');
        });
    };

	// Portfolio Layout
	delaware.portfolioLayout = function() {
		if ( !delaware.$body.hasClass('delaware-portfolio-isotope') ) {
			return;
		}

		var $portfolioList = $('#delaware_portfolio_grid'),
			options = {
				itemSelector: '.portfolio-wrapper',
				layoutMode  : 'masonry'
			};

		if ( delaware.$body.hasClass('portfolio-layout-grid') ) {
			options = {
				itemSelector: '.portfolio-wrapper',
				layoutMode  : 'fitRows'
			};
		}

		$portfolioList.imagesLoaded(function() {
			$portfolioList.isotope(options);
		});

		$('#portfolio-cats-filters').on('click', 'span', function () {
			var filterValue = $(this).attr('data-filter');

            if ( $( this ).hasClass( 'active' ) ) {
                return;
            }

            $( this ).addClass('active').siblings().removeClass('active');

			$portfolioList.isotope({filter: filterValue});
		});

	};

	delaware.progressbarShortcode = function() {
		$('.delaware-progressbar').waypoint(function() {
			$('.delaware-progressbar .line-progress').addClass('animate_progress');

		}, { offset: '100%' });
	};

    delaware.tabs = function() {
        var tabWrapper = $('.tab-block-overview'),
            tabMnu = tabWrapper.find('.tab-nav  li'),
            tabContent = tabWrapper.find('.tab-content > .tab-panel');

        tabContent.not(':first-child').hide();

        tabMnu.each(function(i){
            $(this).attr('data-tab','tab'+i);
        });
        tabContent.each(function(i){
            $(this).attr('data-tab','tab'+i);
        });

        tabMnu.on( 'click', function(){
            var tabData = $(this).data('tab');
            tabWrapper.find(tabContent).hide();
            tabWrapper.find(tabContent).filter('[data-tab='+tabData+']').show();
        });

        $('.tab-nav > li').on( 'click', function(){
            var before = $('.tab-nav li.active' ),
                index = tabMnu.parent().index();

            before.removeClass('active');
            $(this).addClass('active');
            tabContent.filter( ':eq(' + index + ')' ).addClass( 'active' );
        });
    };

    // Blog Masonry Layout
    delaware.blogMasonryLayout = function() {

        if ( !delaware.$body.hasClass('blog-masonry') ) {
        	return;
        }
        $('#masonry_blog_list').imagesLoaded(function() {
            $('#masonry_blog_list').isotope({
                itemSelector: '.post',
                layoutMode  : 'masonry'
            });
        });

    };

    // Widget Office
    delaware.widgetOffice = function () {
        var $location = $('.office-location');
        $location.each(function() {
            var el = $(this),
                $tabs = el.find('.office-switcher ul li'),
                $first = $tabs.filter(':first'),
                $current = el.find('.office-switcher a'),
                $content = el.find('.topbar-office');
            $first.addClass('active');
            $content.filter(':first').addClass('active');

            $current.html($first.html());

            $current.on('click', function (e) {
                var $this = $(this);
                e.preventDefault();

                $this.parent().toggleClass('show-office');
            });

            $tabs.on('click', function () {
                var $this = $(this),
                    tab_id = $this.attr('data-tab');

                if ($this.hasClass('active')) {
                    return;
                }

                $current.html($this.html());
                $current.parent().toggleClass('show-office');

                $tabs.removeClass('active');
                $content.removeClass('active');

                $this.addClass('active');
                $('#' + tab_id).addClass('active');
            });
        });
    };

    // Loading Ajax
    delaware.blogLoadingAjax = function () {

        delaware.$body.on('click', '.blog-nav-ajax a.next', function (e) {
            e.preventDefault();

            if ($(this).data('requestRunning')) {
                return;
            }

            $(this).data('requestRunning', true);

            $(this).addClass('loading');

            var $pagination = $(this).closest('.paging-navigation' ),
                $products = $pagination.prev('.blog-list');

            $.get(
                $(this).attr('href'),
                function (response) {
                    var content = $(response).find('.blog-list').children('.dl-blog-wrapper'),
                        $pagination_html = $(response).find('.paging-navigation').html();
                    var $content = $(content);

                    $pagination.html($pagination_html);

                    if (delaware.$body.hasClass('blog-masonry')) {
                        $content.imagesLoaded(function () {
                            $products.append($content).isotope('insert', $content);

                            $pagination.find('.page-numbers.next').removeClass('loading');
                            $pagination.find('.page-numbers.next').data('requestRunning', false);
                        });

                    } else {
                        $products.append($content);

                        $pagination.find('.page-numbers.next').removeClass('loading');
                        $pagination.find('.page-numbers.next').data('requestRunning', false);
                    }

                    if (!$pagination.find('.page-numbers').hasClass('next')) {
                        $pagination.addClass('loaded');
                    }
                }
            );
        });
    };

    /**
     * Change product quantity
     */
    delaware.productQuantity = function () {
        delaware.$body.on('click', '.quantity .increase, .quantity .decrease', function (e) {
            e.preventDefault();

            var $this = $(this),
                $qty = $this.siblings('.qty'),
                current = parseInt($qty.val(), 10),
                min = parseInt($qty.attr('min'), 10),
                max = parseInt($qty.attr('max'), 10);

            min = min ? min : 1;
            max = max ? max : current + 1;

            if ($this.hasClass('decrease') && current > min) {
                $qty.val(current - 1);
                $qty.trigger('change');
            }
            if ($this.hasClass('increase') && current < max) {
                $qty.val(current + 1);
                $qty.trigger('change');
            }
        });
    };

	/**
	 * Portfolio ajax
	 */
	delaware.portfolioLoadingAjax = function () {

		if (!delaware.$body.hasClass('delaware-portfolio')) {
			return;
		}

		// Blog page
		delaware.$body.on('click', '.portfolio-nav-ajax a.next', function (e) {
			e.preventDefault();

			if ($(this).data('requestRunning')) {
				return;
			}

			$(this).data('requestRunning', true);

            $(this).addClass('loading');

            var $pagination = $(this).closest('.paging-navigation' ),
                $postList = $pagination.prev('#delaware_portfolio_grid');

			$.get(
				$(this).attr('href'),
				function (response) {
					var $content = $(response).find('#delaware_portfolio_grid').children('.portfolio-wrapper'),
						pagination_html = $(response).find('.paging-navigation').html();

					$pagination.html(pagination_html);

					$postList.append($content);

					$content.imagesLoaded(function () {
						$postList.isotope( 'appended', $content );
                        $pagination.find('.page-numbers.next').removeClass('loading');
                        $pagination.find('.page-numbers.next').data('requestRunning', false);
					});
				}
			);
		});
	};

    delaware.activeHeaderMenu = function () {
        var $el, leftPos, childWidth, newWidth, $origWidth, itemWidth, newItemWidth,
            $mainNav = delaware.$header.find('ul.menu');

        $mainNav.find('li:last-child').addClass('last-child');
        $mainNav.append('<li id="dl-active-menu" class="dl-active-menu"></li>');
        var $magicLine = $('#dl-active-menu'),
            space;

        $origWidth = 0;

        if ($mainNav.children('li.current-menu-item, li.current-menu-ancestor, li.current-menu-parent').length > 0) {

            itemWidth = $mainNav.children('li.current-menu-item, li.current-menu-ancestor, li.current-menu-parent').outerWidth();

            childWidth = $mainNav.children('li.current-menu-item, li.current-menu-ancestor, li.current-menu-parent').children('a').outerWidth();

            space = ((itemWidth - childWidth / 2)) / 2;

            childWidth = childWidth / 2;

            $magicLine
                .width(childWidth)
                .css('left', $mainNav.children('li.current-menu-item, li.current-menu-ancestor, li.current-menu-parent').position().left + space)
                .data('origLeft', $magicLine.position().left)
                .data('origWidth', $magicLine.width());

            $origWidth = $magicLine.data('origWidth');
        }

        $mainNav.children('li').on('hover',function () {
            $el = $(this);

            newItemWidth = $el.outerWidth();

            newWidth = $el.children('a').outerWidth();

            space = ((newItemWidth - newWidth / 2)) / 2;

            newWidth = newWidth / 2;

            leftPos = $el.position().left + space;
            $magicLine.stop().animate({
                left : leftPos,
                width: newWidth
            });

        }, function () {
            $magicLine.stop().animate({
                left : $magicLine.data('origLeft'),
                width: $origWidth
            });
        });

    };

    // Off Canvas Panel
    delaware.canvasPanel = function () {
        delaware.$header.on('click', '#icon-menu-mobile', function (e) {
            e.preventDefault();
            delaware.openCanvasPanel($('#menu-sidebar-panel'));
        });

        delaware.$body.on('click', '#off-canvas-layer, .close-canvas-panel', function (e) {
            e.preventDefault();
            delaware.closeCanvasPanel();
        });
    };

    delaware.openCanvasPanel = function ($panel) {
        delaware.$body.addClass('open-canvas-panel');
        $panel.addClass('open');
    };

    delaware.closeCanvasPanel = function () {
        delaware.$body.removeClass('open-canvas-panel');
        $('.delaware-off-canvas-panel').removeClass('open');
    };

    // Toggle Menu Sidebar
    delaware.menuSideBar = function () {
        var $container = $('#menu-sidebar-panel');
        $container.find('.menu .menu-item-has-children > a').prepend('<span class="toggle-menu-children"><span class="menu-arrow"></span></span>');
        $container.find('li.menu-item-has-children > a .toggle-menu-children').on('click', function (e) {
            e.preventDefault();

            $(this).closest('li').siblings().find('ul.sub-menu, ul.dropdown-submenu').slideUp();
            $(this).closest('li').siblings().removeClass('active');

            $(this).closest('li').children('ul.sub-menu, ul.dropdown-submenu').slideToggle();
            $(this).closest('li').toggleClass('active');

        });
    };

    // Sticky Header
    delaware.stickyHeader = function () {
        if (!delaware.$body.hasClass('header-sticky')) {
            return;
        }

        delaware.$window.on('scroll', function () {
            var scrollTop = 20,
                scroll = delaware.$window.scrollTop(),
                hHeader = delaware.$header.outerHeight(true),
                hTopbar = $('.topbar').outerHeight(true);

            hTopbar = hTopbar == undefined ? 0 : hTopbar;

            scrollTop = scrollTop + hHeader + hTopbar;

            if (scroll > scrollTop) {
                delaware.$header.addClass('minimized');
            } else {
                delaware.$header.removeClass('minimized');
            }
        });

        delaware.$window.on('resize', function () {
            var hHeader = delaware.$header.outerHeight(true),
                $h = $('#delaware-header-minimized');

            if (!delaware.$body.hasClass('header-transparent')) {
                $h.height(hHeader);
            }
        }).trigger('resize');
    };
    /**
     * Document ready
     */
    $(function () {
        delaware.init();
    });

})(jQuery);

