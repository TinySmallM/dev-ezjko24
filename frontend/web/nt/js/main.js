"use strict";

$(function () {
  /* Шапка */
  floatHeader();
  /* Поиск */

  showSearch();
  /* Десктопное меню */

  showNav();
  showNavSubcategories();
  /* Мобильное меню */

  //initModals('#btn-pull', resetToDefaultMobileNav);
  closingAnInstanceModal(767, '#mobile-nav');
  moveElementsMobileNav();
  /* Главный слайдер */

  initSlider();
  /* Переключение предложений на главной странице */

  //switchOffers();
  //initCarouselOffers();
  /* Просмотренные товары на странице категорий */

  //initViewedFromCategories();
  /* Промокарусель на странице категорий и каталога */

  //initPromoSlider();
  /* Оформление заказа */

  //switchDeliveryContent();
  //switchOrderInterval();
  //initOrderCalendar();
  /* Добавление в избранное/к сравнению */

  //initTooltipFavoritesOrCompare($('[data-btn]').not('.carousel [data-btn]'));
  /* Карусели блока аксессуаров */

  //initInnerCarousel();
  //initViewedInnerCarousel();
  /* Кастомизация полей форм */

  //$('input[type="number"], select').styler();
  /* Галерея на детальной странице товара */

  //initGalleryProduct();
  /* Переключение информации на детальной странице товара */

  showDetailInfo();
  /* Карусели в каталоге товаров */

  //initCatalogCarousel();
  /* Просмотренные товары на странице каталога */

  //initViewedSidebarCarousel();
  /* Фильтр */

  //initRangeSlider();
  //initModals('#btn-filter-open', resetToDefaultMobileNav);
  //closingAnInstanceModal(989, '#filter');
  toggleFilterSectionContent();
  /* Переключение кнопок социальных сетей */

  //showSocialButton();
});
/* Функции модальных окон */

function initModals(selector) {
  var afterCloseFunc = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
  var linksToModals = $(selector);
  var bodyElement = $('body');
  var modalInstance = false;
  linksToModals.on('click', function (event) {
    event.preventDefault();
    var idModal = $(this).data('src');
    modalInstance = $.fancybox.getInstance();
    $.fancybox.open({
      src: idModal,
      type: 'inline',
      opts: {
        closeExisting: true,
        smallBtn: false,
        toolbar: false,
        buttons: [],
        animationEffect: 'fade',
        animationDuration: 200,
        transitionEffect: 'fade',
        transitionDuration: 200,
        baseTpl: "<div class=\"fancybox-container\n        fancybox-container_modal\" role=\"dialog\" tabindex=\"-1\">\n          <div class=\"fancybox-bg\"></div>\n          <div class=\"fancybox-inner\">\n            <div class=\"fancybox-stage\"></div>\n          </div>\n        </div>",
        spinnerTpl: '',
        errorTpl: '',
        autoFocus: false,
        backFocus: false,
        touch: false,
        hash: false,
        beforeShow: function beforeShow() {
          setTimeout(function () {
            bodyElement.addClass('opened-modal');
          }, 1);
        },
        beforeClose: function beforeClose() {
          if (!modalInstance) {
            bodyElement.removeClass('opened-modal');
          } else {
            bodyElement.removeClass();
            $('#fancybox-style-noscroll').remove();
            modalInstance = false;
          }
        },
        afterClose: function afterClose() {
          afterCloseFunc();
        }
      }
    });
  });
}

function closingAnInstanceModal(windowSize, selector) {
  $(window).on('resize', function () {
    var modalInstance = $.fancybox.getInstance();
    var objReference = '';

    if (modalInstance.current && modalInstance.current.src !== '') {
      objReference = modalInstance.current.src;
    }

    if (window.innerWidth > windowSize && objReference === selector) {
      modalInstance.close();
    }
  });
}
/* Плавающая шапка */


function floatHeader() {
  var header = $('.js-head');
  var stub = $('.js-stub');
  var panel = $('.js-panel');
  $(window).bind('scroll load resize', function () {
    var panelSize = panel.height();
    var scroll = $(this).scrollTop();
    var stubPos = stub.offset().top;

    if (scroll >= stubPos) {
      header.addClass('_floating');
      stub.height(panelSize);
    } else {
      header.removeClass('_floating');
      stub.height(0);
    }
  });
}
/* Функции поиска */


function showSearch() {
  var searchBtn = $('#search-btn');
  var btnWrapper = searchBtn.parent();
  var formSearch = $('#form-search');
  $(document).on('click', function (event) {
    if (searchBtn.is(event.target) || searchBtn.has(event.target).length !== 0) {
      btnWrapper.toggleClass('active');
    } else if (!formSearch.is(event.target) && formSearch.has(event.target).length === 0) {
      btnWrapper.removeClass('active');
    }
  });
}
/* Функции десктопного меню */


function showNav() {
  var navCatalog = $('#nav-catalog');
  var categoryWrapper = $('.category-wrapper');
  $(document).on('click', function (event) {
    if (navCatalog.is(event.target) || navCatalog.has(event.target).length !== 0) {
      event.preventDefault();
      categoryWrapper.toggleClass('visible');
    } else if (!categoryWrapper.is(event.target) && categoryWrapper.has(event.target).length === 0) {
      categoryWrapper.removeClass('visible');
    }
  });
}

function showNavSubcategories() {
  $(document).on('mouseenter', '.category-item', function () {
    var categoryItem = $(this);
    var idListCategories = categoryItem.data('src');

    if (!categoryItem.hasClass('active')) {
      var activeCategoryItem = categoryItem.siblings('.active');

      if (activeCategoryItem.length) {
        var categorySection = categoryItem.closest('.category-section');
        var siblingCategorySection = categorySection.nextAll();
        var visibleListCategories = siblingCategorySection.find('.visible');
        categoryItem.siblings('.active').removeClass('active');
        visibleListCategories.children('.active').removeClass('active');
        visibleListCategories.removeClass('visible');
      }
    }

    if (!idListCategories) {
      return;
    }

    var listCategories = $(idListCategories);
    listCategories.addClass('visible');
    categoryItem.addClass('active');
  });
}
/* Функции мобильного меню */


function moveElementsMobileNav() {
  /* Prev mobile nav */
  moveMobileNav('.mobile-nav-back-arrow', true);
  /* Next mobile nav */

  moveMobileNav('.mobile-nav-link', false);
}

function moveMobileNav(eventSelector, reverseMove) {
  var mobileNavWrapper = $('#mobile-nav-wrapper');
  $(document).on('click', eventSelector, function (event) {
    var mobileNavLink = $(this);
    showSubElementsMobileNav(event, mobileNavLink, reverseMove);
    scrollingToTopMobileNav(mobileNavWrapper);
  });
}

function showSubElementsMobileNav(event, mobileNavLink) {
  var reverseMove = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
  var mobileNavContainer = $(mobileNavLink.data('src'));

  if (!mobileNavContainer.length) {
    return;
  }

  if ($(event.target).closest('.mobile-nav-link')) {
    event.preventDefault();
  }

  var mobileNavSection = mobileNavContainer.closest('.mobile-nav-section');
  var visibleMobileNavSection = $('.mobile-nav-section.visible');
  var visibleMobileNavContainer = $('.mobile-nav-container.visible');
  visibleMobileNavContainer.removeClass('visible');

  if (reverseMove) {
    visibleMobileNavSection.removeClass('visible');
  } else {
    visibleMobileNavSection.addClass('hidden').removeClass('visible');
  }

  mobileNavContainer.addClass('visible');
  mobileNavSection.addClass('visible').removeClass('hidden');
}

function scrollingToTopMobileNav(mobileNavWrapper) {
  var scrolPosition = mobileNavWrapper.scrollTop();

  if (scrolPosition > 0) {
    mobileNavWrapper.scrollTop(0);
  }
}

function resetToDefaultMobileNav() {
  var firstMobileNavSection = $('#mobile-nav .mobile-nav-section:first');

  if (!firstMobileNavSection.hasClass('visible')) {
    var firstMobileNavContainer = firstMobileNavSection.find('.mobile-nav-container:first');
    var visibleContainersMobileNav = $('#mobile-nav .visible');
    var hiddenContainersMobileNav = $('#mobile-nav .hidden');
    visibleContainersMobileNav.removeClass('visible');
    hiddenContainersMobileNav.removeClass('hidden');
    firstMobileNavSection.addClass('visible');
    firstMobileNavContainer.addClass('visible');
  }
}
/* Функция главного слайдера */


function initSlider() {
  var navContainer = $('#slider-nav');
  var slider = $('#slider');
  slider.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 5000,
    arrows: true,
    appendArrows: navContainer,
    touchThreshold: 100,
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n      <svg class=\"slick-arrow__icon\">\n        <use xlink:href=\"images/icons.svg#slider-arrow\"></use>\n      </svg>\n    </button>",
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n      <svg class=\"slick-arrow__icon\">\n        <use xlink:href=\"images/icons.svg#slider-arrow\"></use>\n      </svg>\n    </button>",
    mobileFirst: true,
    pauseOnFocus: false,
    pauseOnHover: false,
    speed: 300,
    fade: true
  });
}
/* Функция кнопок социальных сетей */


function showSocialButton() {
  var socialContainer = $('#social');
  var socialBtn = $('#social-btn');
  socialBtn.on('click', function () {
    socialContainer.toggleClass('active');
  });
}
/* Функция переключения предложений */


function switchOffers() {
  var tabsContainer = $('#tabs');
  tabsContainer.tabs({
    show: {
      effect: 'fade',
      duration: 500
    },
    activate: function activate(event, ui) {
      var newPanel = ui.newPanel;
      var uninitCarousel = newPanel.find('.tab-carousel-list');
      var createdCarousel = tabsContainer.find('.slick-slider');

      if (createdCarousel.length) {
        createdCarousel.slick('unslick');
      }

      initCarouselOffers(uninitCarousel);
    }
  });
}

function initCarouselOffers() {
  var carousel = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

  if (!carousel) {
    carousel = $('#tabs .tab-carousel-list:visible');
  }

  var controlsContainer = carousel.closest('.tab-carousel');
  var btnArray = carousel.find('[data-btn]');
  carousel.on('init', function (event, slick) {
    initTooltipFavoritesOrCompare(btnArray);
  }).slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: true,
    mobileFirst: true,
    infinite: true,
    variableWidth: true,
    arrows: false,
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    appendArrows: controlsContainer,
    responsive: [{
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 989,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 1359,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        variableWidth: false,
        arrows: true
      }
    }]
  }).on('breakpoint', function (event, slick, breakpoint) {
    initTooltipFavoritesOrCompare(btnArray);
  });
}
/* Функции просмотренных товаров */


function initViewedFromCategories() {
  var controlsContainer = $('#viewed-controls');
  var carousel = $('#viewed-carousel');
  var btnArray = carousel.find('[data-btn]');
  carousel.on('init', function (event, slick) {
    initTooltipFavoritesOrCompare(btnArray);
  }).slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: true,
    mobileFirst: true,
    infinite: true,
    variableWidth: true,
    arrows: false,
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    appendArrows: controlsContainer,
    responsive: [{
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 989,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 1359,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        variableWidth: false,
        arrows: true
      }
    }]
  }).on('breakpoint', function (event, slick, breakpoint) {
    initTooltipFavoritesOrCompare(btnArray);
  });
}
/* Функция промокарусели */


function initPromoSlider() {
  var controlsContainer = $('#promo-controls');
  var slider = $('#promo-slider');
  slider.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: true,
    mobileFirst: true,
    infinite: true,
    fade: false,
    adaptiveHeight: true,
    arrows: true,
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#slider-arrow\"></use>\n          </svg>\n        </button>",
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#slider-arrow\"></use>\n          </svg>\n        </button>",
    appendArrows: controlsContainer,
    responsive: [{
      breakpoint: 989,
      settings: {
        adaptiveHeight: false
      }
    }]
  });
}
/* Функция переключения вида доставки */


function switchDeliveryContent() {
  var orderForm = $('#order-form');
  var myMap;
  orderForm.tabs({
    show: {
      effect: 'fade',
      duration: 500
    },
    activate: function activate(event, ui) {
      var newPanel = ui.newPanel;
      var mapContainer = newPanel.find('#order-map');

      if (!mapContainer.length && myMap) {
        myMap.destroy();
        myMap = null;
      }

      if (mapContainer.length && !myMap) {
        myMap = new ymaps.Map('order-map', {
          center: [55.67103847342826, 37.53423898280332],
          zoom: 17,
          controls: ['zoomControl', 'geolocationControl']
        }, {});
        var myPlacemark = new ymaps.Placemark([55.67103847342826, 37.53423898280332], {}, {
          iconLayout: 'default#image',
          iconImageHref: 'images/placemark-icon.png',
          iconImageSize: [32, 45],
          iconImageOffset: [-16, -45]
        });
        myMap.geoObjects.add(myPlacemark);
        myMap.behaviors.disable('scrollZoom');
      }
    }
  });
}
/* Функция переключения интервала доставки */


function switchOrderInterval() {
  var fields = $('[data-name="interval"]');
  fields.on('focus', function () {
    var fieldContainer = $(this).closest('.field');

    if (!fieldContainer.hasClass('active')) {
      fieldContainer.addClass('active').siblings('.active').removeClass('active');
    }
  });
}
/* Функция вызова календаря */


function initOrderCalendar() {
  var calendarContainer = $('.order__calendar');
  calendarContainer.datepicker({
    firstDay: 1,
    minDate: 0,
    prevText: '',
    nextText: '',
    dateFormat: 'D, d MM',
    onSelect: function onSelect(dateText) {
      var dateWrapper = $(this).closest('.order__date-item');
      var dateTextContainer = dateWrapper.find('.order__date-name');
      $(this).closest('.order__calendar-container').removeClass('active');
      dateTextContainer.text(dateText.toLowerCase());
    }
  });
  $(document).on('mousedown', function (event) {
    var calendar = $(event.target).closest('.order__calendar-container');
    var dateElement = $(event.target).closest('.order__date-item-container');
    var activeCalendar = $('.order__calendar-container.active');

    if (dateElement.length) {
      var wrapper = dateElement.closest('.order__date-item');

      var _calendar = wrapper.find('.order__calendar-container');

      if (_calendar.length) {
        _calendar.toggleClass('active');
      } else {
        activeCalendar.removeClass('active');
      }
    } else if (!calendar.length) {
      activeCalendar.removeClass('active');
    }
  });
}
/* Функция инициализации подсказок на кнопках в избранное/к сравнению */


function initTooltipFavoritesOrCompare(btnArray) {
  btnArray.each(function () {
    var currentBtn = $(this);
    var btnWrapper = currentBtn.parent();
    btnWrapper.tooltipster({
      content: setTextFavoritesOrCompare(currentBtn),
      debug: false,
      updateAnimation: false,
      delay: 0
    });
  });
  btnArray.on('change', function () {
    var btnWrapper = $(this).parent();
    btnWrapper.tooltipster('content', setTextFavoritesOrCompare($(this))).tooltipster('show');
  });
}

function setTextFavoritesOrCompare(currentBtn) {
  var textTooltip = '';

  if (currentBtn.data('btn') === 'compare') {
    if (currentBtn.prop('checked') === true) {
      textTooltip = 'Убрать из сравнения';
    } else {
      textTooltip = 'Добавить к сравнению';
    }
  } else if (currentBtn.data('btn') === 'favorites') {
    if (currentBtn.prop('checked') === true) {
      textTooltip = 'Убрать из избранного';
    } else {
      textTooltip = 'Добавить в избранное';
    }
  }

  return textTooltip;
}
/* Функция вызова каруселей аксессуаров */


function initInnerCarousel() {
  var containerSlides = $('.inner-carousel-list');
  containerSlides.each(function () {
    var btnArray = $(this).find('[data-btn]');
    var controlsContainer = $(this).closest('.inner-carousel');
    $(this).on('init', function (event, slick) {
      initTooltipFavoritesOrCompare(btnArray);
    }).slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      speed: 200,
      accessibility: false,
      swipe: true,
      mobileFirst: true,
      infinite: true,
      variableWidth: true,
      arrows: false,
      nextArrow: "<button type=\"button\" class=\"slick-next\">\n            <svg class=\"slick-arrow__icon\">\n              <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n            </svg>\n          </button>",
      prevArrow: "<button type=\"button\" class=\"slick-prev\">\n            <svg class=\"slick-arrow__icon\">\n              <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n            </svg>\n          </button>",
      appendArrows: controlsContainer,
      responsive: [{
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          variableWidth: false,
          arrows: true
        }
      }, {
        breakpoint: 989,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          variableWidth: false,
          arrows: true
        }
      }, {
        breakpoint: 1359,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
          variableWidth: false,
          arrows: true
        }
      }, {
        breakpoint: 1599,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 5,
          variableWidth: false,
          arrows: true
        }
      }]
    }).on('breakpoint', function (event, slick, breakpoint) {
      initTooltipFavoritesOrCompare(btnArray);
    });
  });
}
/* Функция вызова карусели просмотренных товаров */


function initViewedInnerCarousel() {
  var containerSlides = $('#viewed-inner-list');
  var btnArray = containerSlides.find('[data-btn]');
  var controlsContainer = $('#viewed-inner-carousel');
  containerSlides.on('init', function (event, slick) {
    initTooltipFavoritesOrCompare(btnArray);
  }).slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: true,
    mobileFirst: true,
    infinite: true,
    variableWidth: true,
    arrows: false,
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    appendArrows: controlsContainer,
    responsive: [{
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 989,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 1359,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        variableWidth: false,
        arrows: true
      }
    }]
  }).on('breakpoint', function (event, slick, breakpoint) {
    initTooltipFavoritesOrCompare(btnArray);
  });
}
/* Функция галереи товара */


function initGalleryProduct() {
  var containerSlides = $('#gallery-list-slides');
  var containerPreview = $('#preview-list-slides');
  var controlsContainer = $('#preview-container');
  containerSlides.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: true,
    mobileFirst: true,
    infinite: true,
    variableWidth: false,
    arrows: false,
    asNavFor: containerPreview
  });
  containerPreview.slick({
    slidesToShow: 2,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: false,
    mobileFirst: true,
    infinite: true,
    variableWidth: false,
    focusOnSelect: true,
    arrows: true,
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    appendArrows: controlsContainer,
    asNavFor: containerSlides,
    responsive: [{
      breakpoint: 359,
      settings: {
        slidesToShow: 3
      }
    }, {
      breakpoint: 479,
      settings: {
        slidesToShow: 4
      }
    }, {
      breakpoint: 989,
      settings: {
        slidesToShow: 3
      }
    }, {
      breakpoint: 1199,
      settings: {
        slidesToShow: 4
      }
    }]
  });
}
/* Функция отображения полной информации на детальной странице товара */


function showDetailInfo() {
  var headInfo = $('.full-info__head');
  headInfo.on('click', function () {
    var container = $(this).parent();
    var content = container.find('.full-info__content');

    if (!container.hasClass('active')) {
      var siblingContainer = container.siblings('.active');

      if (siblingContainer.length) {
        siblingContainer.find('.full-info__content').hide(200, function () {
          siblingContainer.removeClass('active');
        });
      }

      content.hide(200, function () {
        container.addClass('active');
      });
    } else {
      content.show(200, function () {
        container.removeClass('active');
      });
    }
  });
}
/* Функция вызова каруселей в каталоге товаров */


function initCatalogCarousel() {
  var containerSlides = $('.catalog__carousel-list');
  containerSlides.each(function () {
    var btnArray = $(this).find('[data-btn]');
    var controlsContainer = $(this).closest('.catalog__carousel');
    $(this).on('init', function (event, slick) {
      initTooltipFavoritesOrCompare(btnArray);
    }).slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      speed: 200,
      accessibility: false,
      swipe: true,
      mobileFirst: true,
      infinite: true,
      variableWidth: true,
      arrows: false,
      nextArrow: "<button type=\"button\" class=\"slick-next\">\n            <svg class=\"slick-arrow__icon\">\n              <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n            </svg>\n          </button>",
      prevArrow: "<button type=\"button\" class=\"slick-prev\">\n            <svg class=\"slick-arrow__icon\">\n              <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n            </svg>\n          </button>",
      appendArrows: controlsContainer,
      responsive: [{
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          variableWidth: false,
          arrows: true
        }
      }, {
        breakpoint: 989,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          variableWidth: false,
          arrows: true
        }
      }, {
        breakpoint: 1359,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
          variableWidth: false,
          arrows: true
        }
      }]
    }).on('breakpoint', function (event, slick, breakpoint) {
      initTooltipFavoritesOrCompare(btnArray);
    });
  });
}
/* Функция вызова карусели просмотренных товаров в каталоге */


function initViewedSidebarCarousel() {
  var containerSlides = $('#viewed-sidebar-list');
  var btnArray = containerSlides.find('[data-btn]');
  var controlsContainer = $('#viewed-sidebar-carousel');
  containerSlides.on('init', function (event, slick) {
    initTooltipFavoritesOrCompare(btnArray);
  }).slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 200,
    accessibility: false,
    swipe: true,
    mobileFirst: true,
    infinite: true,
    variableWidth: true,
    arrows: false,
    nextArrow: "<button type=\"button\" class=\"slick-next\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    prevArrow: "<button type=\"button\" class=\"slick-prev\">\n          <svg class=\"slick-arrow__icon\">\n            <use xlink:href=\"images/icons.svg#carousel-arrow-bold\"></use>\n          </svg>\n        </button>",
    appendArrows: controlsContainer,
    responsive: [{
      breakpoint: 767,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        variableWidth: false,
        arrows: true
      }
    }, {
      breakpoint: 989,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: true,
        arrows: false,
        swipe: false,
        infinite: false
      }
    }, {
      breakpoint: 1359,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        variableWidth: true,
        arrows: false,
        swipe: false,
        infinite: false
      }
    }]
  }).on('breakpoint', function (event, slick, breakpoint) {
    initTooltipFavoritesOrCompare(btnArray);
  });
}
/* Функции фильтра */


function filteringInputNumeringField(fields) {
  if (fields.val().match(/[^0-9]/g)) {
    return fields.val().replace(/[^0-9]/g, '');
  }

  return fields.val();
}

function initRangeSlider() {
  var rangeSlider = $('.range__slider');
  rangeSlider.each(function () {
    var currentSlider = $(this);
    var rangeContainer = currentSlider.closest('.range');
    var fromField = rangeContainer.find('[data-range-name="from"]');
    var beforeField = rangeContainer.find('[data-range-name="before"]');
    var fromValue = parseInt(currentSlider.data('from'));
    var beforeValue = parseInt(currentSlider.data('before'));
    var minValue = parseInt(currentSlider.data('min'));
    var maxValue = parseInt(currentSlider.data('max'));
    currentSlider.ionRangeSlider({
      type: 'double',
      min: minValue,
      max: maxValue,
      from: fromValue,
      to: beforeValue,
      grid: false,
      hide_min_max: true,
      hide_from_to: true,
      grid_num: 3,
      onStart: function onStart(data) {
        updateValuesRangeField(fromField, beforeField, data);
      },
      onChange: function onChange(data) {
        updateValuesRangeField(fromField, beforeField, data);
      },
      onFinish: function onFinish(data) {
        updateValuesRangeField(fromField, beforeField, data);
      },
      onUpdate: function onUpdate(data) {
        updateValuesRangeField(fromField, beforeField, data);
      }
    });
    var rangeSliderInstance = currentSlider.data('ionRangeSlider');
    fromField.on('change', function () {
      changeValuesRangeField($(this), rangeSliderInstance);
    });
    beforeField.on('change', function () {
      changeValuesRangeField($(this), rangeSliderInstance);
    });
  });
}

function updateValuesRangeField(fromField, beforeField, data) {
  fromField.val(data.from);
  beforeField.val(data.to);
}

function changeValuesRangeField(currentField, rangeSliderInstance) {
  var fieldValue = parseInt(filteringInputNumeringField(currentField));
  var minOptionsValue = rangeSliderInstance.options.min;
  var maxOptionsValue = rangeSliderInstance.options.max;
  var fromCurrentValue = rangeSliderInstance.old_from;
  var beforeCurrentValue = rangeSliderInstance.old_to;

  if (currentField.data('range-name') === 'from') {
    if (isNaN(fieldValue) || fieldValue < minOptionsValue) {
      fieldValue = minOptionsValue;
    } else if (fieldValue > beforeCurrentValue) {
      fieldValue = beforeCurrentValue;
    }

    rangeSliderInstance.update({
      from: fieldValue
    });
  }

  if (currentField.data('range-name') === 'before') {
    if (isNaN(fieldValue) || fieldValue < fromCurrentValue) {
      fieldValue = fromCurrentValue;
    } else if (fieldValue > maxOptionsValue) {
      fieldValue = maxOptionsValue;
    }

    rangeSliderInstance.update({
      to: fieldValue
    });
  }

  currentField.val(fieldValue);
}

function toggleFilterSectionContent() {
  var containerNameSection = $('.filter__section-name');
  containerNameSection.on('click', function () {
    var section = $(this).parent();
    var sectionContent = section.find('.filter__section-content');
    sectionContent.stop(true, true).slideToggle(200, function () {
      section.toggleClass('active');
    });
  });
}

let errors = {
  400 : "Вы уже подписаны",
  403 : "Не указан email"
};

    
$("body").on('submit','.b-form-ajax', function(){
  var formData = toJSON($(this).serializeArray());
  $.ajax({
    type: "post",
    url: "/system/ajax/",
    dataType: 'json',
    data: formData,
    success: function (data) {
      console.log(data.action)
    if (!data.errors.length) {
       if (data.action == 'subscribe') {
        $(".subscription__btn").addClass('disabled');
        $(".subscription__btn").attr('disabled', true);
        $(".subscription__policy").hide();
        $(".subscription__ahtung").hide();
        $(".subscription__stock-label.callback").text("Поздравляем, подписка успешно оформлена!");
       }
       if (data.action == 'questions') {
        $(".questions__list-fields, .questions__btn, .questions__policy").hide();
        $(".questions__sub-title").text("Мы ответим на Ваш вопрос, в ближайшее время!");
       } 
    } else {
      $(".subscription__stock-label.callback").text("Ошибка: " + errors[data.errors[0].code] + "!");
    }
    }
  });
  return false;
});


function toJSON(arr){
  let hash = {};
  $.map(arr, function (n, i ){
    hash[n.name] = n.value;
  });
  return JSON.stringify(hash);
}
