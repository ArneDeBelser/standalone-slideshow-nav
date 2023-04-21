class SlideshowNavigationHandler extends elementorModules.frontend.handlers
  .Base {
  updateSliderIdContent() {
    if (!this.contentWrapper) {
      const widgetUniqueSelector = `div[data-id="${this.getID()}"] .slideshow-navigation`;
      this.contentWrapper = document.querySelector(widgetUniqueSelector);
    }

    const amountOfSlides = this.fetchAmountOfSlides();
    let swiperInstance;

    setTimeout(() => {
      const useSectionId = this.getElementSettings("use_section_id");
      let imageCarousel;

      if (useSectionId === undefined || useSectionId == "") {
        console.log("Were using NOT using ID to find slider");
        imageCarousel = jQuery(".e-container").find("swiper-container");
      } else {
        console.log("Were using using ID to find slider");
        imageCarousel = jQuery(
          "#" + this.getElementSettings("slider_id") + " .swiper-container"
        );
      }

      swiperInstance = imageCarousel.data("swiper");

      swiperInstance.on("slideChange", function (e) {
        const bulletIndex = swiperInstance.activeIndex + 1;

        jQuery(
          ".bullet-slide.standalone-swiper-pagination-bullet-active"
        ).removeClass("standalone-swiper-pagination-bullet-active");
        jQuery("span[data-slide_id='" + bulletIndex + "']").addClass(
          "standalone-swiper-pagination-bullet-active"
        );
      });
    }, 1000);

    const bullets = this.constructHTML(amountOfSlides);
    this.contentWrapper.innerHTML = bullets;

    jQuery("body").on("click", ".bullet-slide", function (e) {
      e.preventDefault();
      const slideId = jQuery(e.target).attr("data-slide_id");

      jQuery(
        ".bullet-slide.standalone-swiper-pagination-bullet-active"
      ).removeClass("standalone-swiper-pagination-bullet-active");
      jQuery(this).addClass("standalone-swiper-pagination-bullet-active");

      swiperInstance.slideTo(slideId - 1);
    });
  }

  onElementChange(propertyName) {
    if ("use_section" === propertyName || "slider_id" === propertyName) {
      this.updateSliderIdContent();
    }
  }

  onInit() {
    super.onInit();

    this.updateSliderIdContent();
  }

  fetchAmountOfSlides() {
    const useSectionId = this.getElementSettings("use_section_id");

    let swiperWrapper;

    if (useSectionId === undefined || useSectionId == "") {
      swiperWrapper = jQuery(".e-container").find(".swiper-wrapper");
    } else {
      swiperWrapper = jQuery(
        "#" + this.getElementSettings("slider_id") + " .swiper-wrapper"
      );
    }

    return swiperWrapper.length === 0 ? 0 : swiperWrapper[0].children.length;
  }

  constructHTML(amountOfSlides) {
    let bullets = "";

    for (let i = 1; i < amountOfSlides + 1; i++) {
      bullets += `<span class="standalone-swiper-pagination-bullet bullet-slide ${
        i != 1 || "standalone-swiper-pagination-bullet-active"
      }" tabindex="0" role="button" aria-label="Go to slide ${i}" data-slide_id="${i}"></span>`;
    }

    return bullets;
  }
}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(SlideshowNavigationHandler, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/slideshow-navigation.default",
    addHandler
  );
});
